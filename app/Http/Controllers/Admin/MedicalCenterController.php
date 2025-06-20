<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalCenter;
use App\Helpers\ImageHelper;
use App\Helpers\CitiesHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MedicalCentersExport;
use App\Imports\MedicalCentersImport;

class MedicalCenterController extends Controller
{
    /**
     * Display a listing of the medical centers.
     */
    public function index(Request $request)
    {
        $query = MedicalCenter::query();

        // البحث
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // فلترة حسب المنطقة
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $medicalCenters = $query->latest()->paginate(15);

        // جمع المناطق للفلترة
        $regions = MedicalCenter::distinct()->pluck('region')->filter()->sort();

        return view('admin.medical-centers.index', compact('medicalCenters', 'regions'));
    }

    /**
     * Show the form for creating a new medical center.
     */
    public function create()
    {
        $regions = CitiesHelper::getAllRegions();

        // تجميع المدن حسب المناطق
        $citiesByRegion = [];
        foreach ($regions as $region) {
            $citiesByRegion[$region['name']] = $region['cities'];
        }

        return view('admin.medical-centers.create', compact('citiesByRegion'));
    }

    /**
     * Store a newly created medical center in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:medical_centers,slug',
            'description' => 'nullable|string',
            'city' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'type' => 'required|integer|min:1|max:12',
            'medical_service_types' => 'nullable|array',
            'medical_service_types.*' => 'string',
            'discounts' => 'nullable|array',
            'discounts.*.service' => 'nullable|string|max:255',
            'discounts.*.discount' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,pending,suspended',
            'contract_status' => 'nullable|in:active,pending,expired,suspended,terminated',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp|max:5120',
        ]);

        // إنشاء slug إذا لم يتم توفيره
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // التحقق من صحة المدينة واستخراج المنطقة
        if (!CitiesHelper::cityExists($validated['city'])) {
            return back()->withErrors(['city' => 'المدينة المحددة غير صحيحة'])->withInput();
        }

        // استخراج المنطقة من المدينة المختارة
        $regionData = CitiesHelper::getRegionByCity($validated['city']);
        if ($regionData) {
            $validated['region'] = $regionData['name'];
        }

        // معالجة الخصومات
        if (isset($validated['discounts'])) {
            $validated['medical_discounts'] = array_filter($validated['discounts'], function($discount) {
                return !empty($discount['service']) || !empty($discount['discount']);
            });
            unset($validated['discounts']);
        }

        // رفع الصورة
        if ($request->hasFile('image')) {
            // التحقق من صحة الصورة
            $imageErrors = ImageHelper::validateImage($request->file('image'));
            if (!empty($imageErrors)) {
                return back()->withErrors(['image' => $imageErrors])->withInput();
            }

            // رفع وتحسين الصورة
            $validated['image'] = ImageHelper::uploadAndOptimize(
                $request->file('image'),
                'medical-centers',
                [
                    'max_width' => 800,
                    'max_height' => 600,
                    'quality' => 85
                ]
            );
        }

        // إضافة معرف المستخدم الحالي
        $validated['created_by'] = auth()->id();

        MedicalCenter::create($validated);

        return redirect()->route('admin.medical-centers.index')
            ->with('success', 'تم إضافة المركز الطبي بنجاح');
    }

    /**
     * Display the specified medical center.
     */
    public function show(MedicalCenter $medicalCenter)
    {
        return view('admin.medical-centers.show', compact('medicalCenter'));
    }

    /**
     * Show the form for editing the specified medical center.
     */
    public function edit(MedicalCenter $medicalCenter)
    {
        $regions = CitiesHelper::getAllRegions();

        // تجميع المدن حسب المناطق
        $citiesByRegion = [];
        foreach ($regions as $region) {
            $citiesByRegion[$region['name']] = $region['cities'];
        }

        return view('admin.medical-centers.edit', compact('medicalCenter', 'citiesByRegion'));
    }

    /**
     * Update the specified medical center in storage.
     */
    public function update(Request $request, MedicalCenter $medicalCenter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:medical_centers,slug,' . $medicalCenter->id,
            'description' => 'nullable|string',
            'city' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'type' => 'required|integer|min:1|max:12',
            'medical_service_types' => 'nullable|array',
            'medical_service_types.*' => 'string',
            'discounts' => 'nullable|array',
            'discounts.*.service' => 'nullable|string|max:255',
            'discounts.*.discount' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,pending,suspended',
            'contract_status' => 'nullable|in:active,pending,expired,suspended,terminated',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp|max:5120',
            'remove_current_image' => 'nullable|boolean',
        ]);

        // تحديث slug إذا تغير الاسم أو إذا لم يكن موجوداً
        if (empty($validated['slug']) || $medicalCenter->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // التحقق من صحة المدينة واستخراج المنطقة
        if (!CitiesHelper::cityExists($validated['city'])) {
            return back()->withErrors(['city' => 'المدينة المحددة غير صحيحة'])->withInput();
        }

        // استخراج المنطقة من المدينة المختارة
        $regionData = CitiesHelper::getRegionByCity($validated['city']);
        if ($regionData) {
            $validated['region'] = $regionData['name'];
        }

        // معالجة الخصومات
        if (isset($validated['discounts'])) {
            $validated['medical_discounts'] = array_filter($validated['discounts'], function($discount) {
                return !empty($discount['service']) || !empty($discount['discount']);
            });
            unset($validated['discounts']);
        }

        // التعامل مع حذف الصورة الحالية
        if ($request->input('remove_current_image') == '1') {
            if ($medicalCenter->image) {
                ImageHelper::delete($medicalCenter->image);
                $validated['image'] = null;
            }
        }

        // رفع الصورة الجديدة
        if ($request->hasFile('image')) {
            // التحقق من صحة الصورة
            $imageErrors = ImageHelper::validateImage($request->file('image'));
            if (!empty($imageErrors)) {
                return back()->withErrors(['image' => $imageErrors])->withInput();
            }

            // حذف الصورة القديمة إذا كانت موجودة
            if ($medicalCenter->image) {
                ImageHelper::delete($medicalCenter->image);
            }

            // رفع وتحسين الصورة الجديدة
            $validated['image'] = ImageHelper::uploadAndOptimize(
                $request->file('image'),
                'medical-centers',
                [
                    'max_width' => 800,
                    'max_height' => 600,
                    'quality' => 85
                ]
            );
        }

        $medicalCenter->update($validated);

        return redirect()->route('admin.medical-centers.index')
            ->with('success', 'تم تحديث المركز الطبي بنجاح');
    }

    /**
     * Remove the specified medical center from storage.
     */
    public function destroy(MedicalCenter $medicalCenter)
    {
        // حذف الصورة
        if ($medicalCenter->image) {
            ImageHelper::delete($medicalCenter->image);
        }

        $medicalCenter->delete();

        return redirect()->route('admin.medical-centers.index')
            ->with('success', 'تم حذف المركز الطبي بنجاح');
    }

    /**
     * Toggle the status of the medical center.
     */
    public function toggleStatus(MedicalCenter $medicalCenter)
    {
        $newStatus = $medicalCenter->status === 'active' ? 'inactive' : 'active';
        $medicalCenter->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? 'تم تفعيل المركز الطبي' : 'تم إيقاف المركز الطبي';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Export medical centers to Excel
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'xlsx');
        $filename = 'medical_centers_' . date('Y-m-d_H-i-s') . '.' . $format;

        return Excel::download(new MedicalCentersExport, $filename);
    }

    /**
     * Show import form
     */
    public function importForm()
    {
        return view('admin.medical-centers.import');
    }

    /**
     * Import medical centers from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $import = new MedicalCentersImport;
            Excel::import($import, $request->file('file'));

            $imported = $import->getImportedCount();
            $errors = $import->getErrors();

            if ($errors) {
                return redirect()->back()
                    ->with('warning', "تم استيراد {$imported} مركز طبي بنجاح، مع وجود {$errors} أخطاء.")
                    ->with('import_errors', $import->getErrorDetails());
            }

            return redirect()->route('admin.medical-centers.index')
                ->with('success', "تم استيراد {$imported} مركز طبي بنجاح!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }

    /**
     * Download sample import template
     */
    public function downloadTemplate()
    {
        $filename = 'medical_centers_template.xlsx';
        $filePath = storage_path('app/templates/' . $filename);

        // إنشاء ملف القالب إذا لم يكن موجوداً
        if (!file_exists($filePath)) {
            $this->createTemplate($filePath);
        }

        return Response::download($filePath, $filename);
    }

    /**
     * Create sample template file
     */
    private function createTemplate($filePath)
    {
        // إنشاء مجلد القوالب إذا لم يكن موجوداً
        $templateDir = dirname($filePath);
        if (!is_dir($templateDir)) {
            mkdir($templateDir, 0755, true);
        }

        // إنشاء ملف Excel بسيط مع العناوين
        $headers = [
            'name' => 'اسم المركز',
            'description' => 'الوصف',
            'region' => 'المنطقة',
            'city' => 'المدينة',
            'address' => 'العنوان',
            'phone' => 'الهاتف',
            'email' => 'البريد الإلكتروني',
            'website' => 'الموقع الإلكتروني',
            'type' => 'نوع المركز (1-12)',

            'status' => 'الحالة (active/inactive/pending)',
            'contract_status' => 'حالة العقد (active/expired/pending)',
            'contract_start_date' => 'تاريخ بداية العقد (YYYY-MM-DD)',
            'contract_end_date' => 'تاريخ نهاية العقد (YYYY-MM-DD)',
        ];

        // إنشاء محتوى CSV
        $csvContent = implode(',', array_values($headers)) . "\n";

        // إضافة صف مثال
        $exampleRow = [
            'مستشفى الملك فهد',
            'مستشفى متخصص في جميع التخصصات الطبية',
            'الرياض',
            '1',
            'شارع الملك فهد، الرياض',
            '0112345678',
            'info@hospital.com',
            'https://hospital.com',
            '1',
            'LIC123456',
            '2025-12-31',
            '20',
            'active',
            'active',
            '2024-01-01',
            '2025-12-31'
        ];

        $csvContent .= '"' . implode('","', $exampleRow) . '"';

        file_put_contents($filePath, $csvContent);
    }
}
