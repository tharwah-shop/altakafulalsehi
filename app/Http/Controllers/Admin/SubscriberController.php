<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Models\Package;
use App\Helpers\SaudiCitiesHelper;
use App\Models\Dependent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subscriber::with(['package']);

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('card_number', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%");
            });
        }

        // فلترة حسب الجنسية
        if ($request->filled('nationality')) {
            $query->where('nationality', $request->nationality);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب الباقة
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        // فلترة حسب المدينة
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $subscribers = $query->latest()->paginate(15);

        // إحصائيات للعرض
        $stats = [
            'total' => Subscriber::count(),
            'active' => Subscriber::where('status', 'فعال')->count(),
            'expired' => Subscriber::where('status', 'منتهي')->count(),
            'cancelled' => Subscriber::where('status', 'ملغي')->count(),
        ];

        return view('admin.subscribers.index', compact('subscribers', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packages = Package::active()->ordered()->get();
        $cities = SaudiCitiesHelper::getAllCities();

        return view('admin.subscribers.create', compact('packages', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:subscribers,phone',
            'email' => 'nullable|email|unique:subscribers,email',
            'city' => 'nullable|string|max:255',
            'nationality' => 'required|string|max:255',
            'id_number' => 'required|string|unique:subscribers,id_number',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'package_id' => 'nullable|exists:packages,id',
            'card_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:فعال,منتهي,ملغي,معلق',

            // بيانات إضافية (اختيارية)
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',

            // التابعين
            'dependents' => 'nullable|array',
            'dependents.*.name' => 'required_with:dependents|string|max:255',
            'dependents.*.nationality' => 'required_with:dependents|string|max:255',
            'dependents.*.id_number' => 'nullable|string|max:255',
            'dependents.*.dependent_price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // توليد رقم البطاقة
            $cardNumber = $request->generated_card_number ?:
                         Subscriber::generateCardNumber($request->id_number, $request->phone);

            // التأكد من عدم تكرار رقم البطاقة
            while (Subscriber::where('card_number', $cardNumber)->exists()) {
                $cardNumber = Subscriber::generateCardNumber($request->id_number, $request->phone);
            }

            // إنشاء المشترك
            $subscriber = Subscriber::create(array_merge(
                $request->only([
                    'name', 'phone', 'email', 'city', 'nationality', 'id_number',
                    'start_date', 'end_date', 'package_id', 'card_price', 'status',
                    'discount_percentage', 'discount_amount'
                ]),
                [
                    'card_number' => $cardNumber,
                    'created_by' => auth()->id(),
                ]
            ));

            // إضافة التابعين
            if ($request->has('dependents') && is_array($request->dependents)) {
                foreach ($request->dependents as $dependentData) {
                    if (!empty($dependentData['name'])) {
                        $subscriber->dependents()->create($dependentData);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.subscribers.index')
                ->with('success', 'تم إضافة المشترك بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة المشترك: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscriber $subscriber)
    {
        $subscriber->load(['package', 'city.region', 'dependents', 'creator', 'payments' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('admin.subscribers.show', compact('subscriber'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscriber $subscriber)
    {
        $packages = Package::active()->ordered()->get();
        $cities = SaudiCitiesHelper::getAllCities();

        // Debug: تحقق من وجود المدن
        \Log::info('Cities count in edit subscriber page: ' . $cities->count());

        $subscriber->load(['dependents']);

        return view('admin.subscribers.edit', compact('subscriber', 'packages', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscriber $subscriber)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:subscribers,phone,' . $subscriber->id,
            'email' => 'nullable|email|unique:subscribers,email,' . $subscriber->id,
            'city' => 'nullable|string|max:255',
            'nationality' => 'required|string|max:255',
            'id_number' => 'required|string|unique:subscribers,id_number,' . $subscriber->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'package_id' => 'nullable|exists:packages,id',
            'card_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:فعال,منتهي,ملغي,معلق',

            // بيانات إضافية (اختيارية)
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',

            // التابعين الموجودين
            'existing_dependents' => 'nullable|array',
            'existing_dependents.*.name' => 'required_with:existing_dependents|string|max:255',
            'existing_dependents.*.nationality' => 'required_with:existing_dependents|string|max:255',
            'existing_dependents.*.id_number' => 'nullable|string|max:255',
            'existing_dependents.*.dependent_price' => 'nullable|numeric|min:0',

            // التابعين الجدد
            'new_dependents' => 'nullable|array',
            'new_dependents.*.name' => 'required_with:new_dependents|string|max:255',
            'new_dependents.*.nationality' => 'required_with:new_dependents|string|max:255',
            'new_dependents.*.id_number' => 'nullable|string|max:255',
            'new_dependents.*.dependent_price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // تحديث بيانات المشترك
            $subscriber->update($request->only([
                'name', 'phone', 'email', 'city', 'nationality', 'id_number',
                'start_date', 'end_date', 'package_id', 'card_price', 'status',
                'discount_percentage', 'discount_amount'
            ]));

            // تحديث التابعين الموجودين
            if ($request->has('existing_dependents') && is_array($request->existing_dependents)) {
                $existingDependents = $subscriber->dependents()->get();
                foreach ($request->existing_dependents as $index => $dependentData) {
                    if (isset($existingDependents[$index]) && !empty($dependentData['name'])) {
                        $existingDependents[$index]->update($dependentData);
                    }
                }
            }

            // إضافة التابعين الجدد
            if ($request->has('new_dependents') && is_array($request->new_dependents)) {
                foreach ($request->new_dependents as $dependentData) {
                    if (!empty($dependentData['name'])) {
                        $subscriber->dependents()->create($dependentData);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.subscribers.index')
                ->with('success', 'تم تحديث المشترك بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث المشترك: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscriber $subscriber)
    {
        try {
            $subscriber->delete();
            return redirect()->route('admin.subscribers.index')
                ->with('success', 'تم حذف المشترك بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف المشترك: ' . $e->getMessage());
        }
    }

    /**
     * معاينة البطاقة
     */
    public function cardPreview(Subscriber $subscriber)
    {
        $subscriber->load(['package', 'city.region', 'dependents']);
        return view('admin.subscribers.card-preview', compact('subscriber'));
    }

    /**
     * تحميل البطاقة كـ PDF
     */
    public function cardPdf(Subscriber $subscriber)
    {
        // سيتم تنفيذ هذه الوظيفة لاحقاً
        return response()->json(['message' => 'PDF generation will be implemented']);
    }

    /**
     * توليد بطاقات PDF للمحددين
     */
    public function bulkCards(Request $request)
    {
        $subscriberIds = $request->input('subscriber_ids', []);

        if (empty($subscriberIds)) {
            return redirect()->back()->with('error', 'لم يتم تحديد أي مشتركين');
        }

        // سيتم تنفيذ هذه الوظيفة لاحقاً
        return response()->json(['message' => 'Bulk PDF generation will be implemented']);
    }

    /**
     * تصدير البيانات
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'xlsx');
        $type = $request->input('type', 'subscribers'); // subscribers, dependents, combined

        // جمع الفلاتر
        $filters = $request->only([
            'search', 'nationality', 'status', 'package_id', 'city',
            'start_date', 'end_date', 'subscriber_status'
        ]);

        try {
            $filename = $this->generateExportFilename($type, $format);

            switch ($type) {
                case 'dependents':
                    return Excel::download(new \App\Exports\DependentsExport($filters), $filename);

                case 'combined':
                    return Excel::download(new \App\Exports\SubscribersWithDependentsExport($filters), $filename);

                default: // subscribers
                    return Excel::download(new \App\Exports\SubscribersExport($filters), $filename);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء التصدير: ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة الاستيراد
     */
    public function importForm()
    {
        return view('admin.subscribers.import');
    }

    /**
     * استيراد البيانات
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
            'import_type' => 'required|in:subscribers,dependents',
            'update_existing' => 'boolean',
        ], [
            'import_file.required' => 'يرجى اختيار ملف للاستيراد',
            'import_file.mimes' => 'نوع الملف غير مدعوم. يرجى استخدام Excel أو CSV',
            'import_file.max' => 'حجم الملف كبير جداً. الحد الأقصى 10 ميجابايت',
            'import_type.required' => 'يرجى تحديد نوع الاستيراد',
        ]);

        try {
            $importType = $request->input('import_type');

            if ($importType === 'dependents') {
                $import = new \App\Imports\DependentsImport();
            } else {
                $import = new \App\Imports\SubscribersImport();
            }

            Excel::import($import, $request->file('import_file'));

            $imported = $import->getImportedCount();
            $updated = $import->getUpdatedCount();
            $errors = $import->getErrorCount();

            $message = "تم الاستيراد بنجاح! ";
            $message .= "تم إنشاء {$imported} سجل جديد";
            if ($updated > 0) {
                $message .= " وتحديث {$updated} سجل موجود";
            }
            if ($errors > 0) {
                $message .= " مع وجود {$errors} أخطاء";
            }

            $alertType = $errors > 0 ? 'warning' : 'success';

            return redirect()->route('admin.subscribers.index')
                ->with($alertType, $message)
                ->with('import_errors', $import->getErrors());

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * تحميل نموذج CSV/Excel
     */
    public function downloadTemplate(Request $request)
    {
        $type = $request->input('type', 'subscribers');
        $format = $request->input('format', 'xlsx');

        try {
            $filename = "template_{$type}." . $format;

            if ($type === 'dependents') {
                return $this->generateDependentsTemplate($filename);
            } else {
                return $this->generateSubscribersTemplate($filename);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء النموذج: ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة التصدير
     */
    public function exportForm()
    {
        $packages = Package::active()->get();
        $cities = SaudiCitiesHelper::getAllCities();
        $nationalities = $this->getNationalitiesList();

        return view('admin.subscribers.export', compact('packages', 'cities', 'nationalities'));
    }

    /**
     * توليد رقم بطاقة جديد (AJAX)
     */
    public function generateCardNumber(Request $request)
    {
        $idNumber = $request->input('id_number');
        $phone = $request->input('phone');

        if (!$idNumber || !$phone) {
            return response()->json(['error' => 'رقم الهوية ورقم الجوال مطلوبان'], 400);
        }

        $cardNumber = Subscriber::generateCardNumber($idNumber, $phone);

        // التأكد من عدم تكرار رقم البطاقة
        while (Subscriber::where('card_number', $cardNumber)->exists()) {
            $cardNumber = Subscriber::generateCardNumber($idNumber, $phone);
        }

        return response()->json(['card_number' => $cardNumber]);
    }

    /**
     * توليد اسم ملف التصدير
     */
    private function generateExportFilename($type, $format)
    {
        $typeNames = [
            'subscribers' => 'المشتركين',
            'dependents' => 'التابعين',
            'combined' => 'المشتركين_والتابعين'
        ];

        $typeName = $typeNames[$type] ?? 'البيانات';
        $date = now()->format('Y-m-d_H-i-s');

        return "{$typeName}_{$date}.{$format}";
    }

    /**
     * إنشاء نموذج المشتركين
     */
    private function generateSubscribersTemplate($filename)
    {
        $headers = [
            'الاسم',
            'رقم_الجوال',
            'البريد_الالكتروني',
            'المدينة',
            'الجنسية',
            'رقم_الهوية_الاقامة',
            'تاريخ_البداية',
            'تاريخ_النهاية',
            'الباقة',
            'سعر_البطاقة',
            'المبلغ_الاجمالي',
            'الحالة',
            'نسبة_الخصم',
            'مبلغ_الخصم',
            'الملاحظات',
            'اسماء_التابعين',
            'جنسيات_التابعين',
            'ارقام_هوية_التابعين',
            'اسعار_التابعين'
        ];

        $sampleData = [
            'أحمد محمد علي',
            '0501234567',
            'ahmed@example.com',
            'الرياض',
            'سعودي 🇸🇦',
            '1234567890',
            '2024-01-01',
            '2024-12-31',
            'الباقة الذهبية',
            '500.00',
            '500.00',
            'فعال',
            '0',
            '0',
            'مشترك جديد',
            'فاطمة أحمد, محمد أحمد',
            'سعودي 🇸🇦, سعودي 🇸🇦',
            '1234567891, 1234567892',
            '100.00, 100.00'
        ];

        return $this->generateExcelTemplate($headers, $sampleData, $filename);
    }

    /**
     * إنشاء نموذج التابعين
     */
    private function generateDependentsTemplate($filename)
    {
        $headers = [
            'اسم_التابع',
            'جنسية_التابع',
            'رقم_هوية_التابع',
            'سعر_التابع',
            'ملاحظات_التابع',
            'اسم_المشترك_الاساسي',
            'رقم_جوال_المشترك',
            'رقم_بطاقة_المشترك',
            'رقم_هوية_المشترك'
        ];

        $sampleData = [
            'فاطمة أحمد محمد',
            'سعودي 🇸🇦',
            '1234567891',
            '100.00',
            'تابع للمشترك الأساسي',
            'أحمد محمد علي',
            '0501234567',
            '123456789',
            '1234567890'
        ];

        return $this->generateExcelTemplate($headers, $sampleData, $filename);
    }

    /**
     * إنشاء ملف Excel للنموذج
     */
    private function generateExcelTemplate($headers, $sampleData, $filename)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // إضافة العناوين
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $sheet->getStyle($column . '1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4472C4');
            $sheet->getStyle($column . '1')->getFont()->getColor()->setARGB('FFFFFFFF');
            $column++;
        }

        // إضافة بيانات العينة
        $column = 'A';
        foreach ($sampleData as $data) {
            $sheet->setCellValue($column . '2', $data);
            $column++;
        }

        // تنسيق الأعمدة
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // إنشاء الكاتب
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // إعداد الاستجابة
        $response = response()->stream(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);

        return $response;
    }

    /**
     * الحصول على قائمة الجنسيات
     */
    private function getNationalitiesList()
    {
        return [
            'سعودي 🇸🇦',
            'مصري 🇪🇬',
            'سوري 🇸🇾',
            'لبناني 🇱🇧',
            'أردني 🇯🇴',
            'فلسطيني 🇵🇸',
            'عراقي 🇮🇶',
            'يمني 🇾🇪',
            'كويتي 🇰🇼',
            'إماراتي 🇦🇪',
            'قطري 🇶🇦',
            'بحريني 🇧🇭',
            'عماني 🇴🇲',
            'مغربي 🇲🇦',
            'جزائري 🇩🇿',
            'تونسي 🇹🇳',
            'ليبي 🇱🇾',
            'سوداني 🇸🇩',
            'صومالي 🇸🇴',
            'جيبوتي 🇩🇯',
            'موريتاني 🇲🇷',
            'جزر القمر 🇰🇲',
            'تركي 🇹🇷',
            'إيراني 🇮🇷',
            'أفغاني 🇦🇫',
            'باكستاني 🇵🇰',
            'هندي 🇮🇳',
            'بنغلاديشي 🇧🇩',
            'سريلانكي 🇱🇰',
            'نيبالي 🇳🇵',
            'فلبيني 🇵🇭',
            'إندونيسي 🇮🇩',
            'ماليزي 🇲🇾',
            'تايلاندي 🇹🇭',
            'إثيوبي 🇪🇹',
            'إريتري 🇪🇷',
            'كيني 🇰🇪',
            'أوغندي 🇺🇬',
            'تنزاني 🇹🇿',
            'نيجيري 🇳🇬',
            'غاني 🇬🇭',
            'أمريكي 🇺🇸',
            'كندي 🇨🇦',
            'بريطاني 🇬🇧',
            'فرنسي 🇫🇷',
            'ألماني 🇩🇪',
            'إيطالي 🇮🇹',
            'إسباني 🇪🇸',
            'روسي 🇷🇺',
            'صيني 🇨🇳',
            'ياباني 🇯🇵',
            'كوري جنوبي 🇰🇷',
            'أسترالي 🇦🇺',
            'برازيلي 🇧🇷',
            'أرجنتيني 🇦🇷',
            'جنوب أفريقي 🇿🇦',
            'أخرى'
        ];
    }
}
