<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Models\PotentialCustomer;
use App\Imports\PotentialCustomersImport;
use App\Exports\PotentialCustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class PotentialCustomerController extends Controller
{
    /**
     * Display a listing of the potential customers.
     */
    public function index(Request $request)
    {
        // بناء الاستعلام
        $query = PotentialCustomer::query();

        // تطبيق الفلاتر
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city_id')) {
            $query->where('city', $request->city_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // ترتيب النتائج
        $query->orderBy('created_at', 'desc');

        // التصفح
        $perPage = $request->get('per_page', 15);
        $potentialCustomers = $query->paginate($perPage);

        // إضافة خصائص العرض للعملاء
        $potentialCustomers->getCollection()->transform(function ($customer) {
            $customer->device_type_display = $this->getDeviceTypeDisplay($customer->device_type);
            $customer->source_display = $this->getSourceDisplay($customer->source, $customer->referrer_url);
            return $customer;
        });

        // حساب الإحصائيات
        $allCustomers = PotentialCustomer::all();
        $statistics = $this->calculateStatistics($allCustomers);

        // الحصول على المدن من النظام الجديد
        $cities = \App\Helpers\SaudiCitiesHelper::getAllCities();

        return view('admin.potential-customers.index', compact(
            'potentialCustomers',
            'statistics',
            'cities'
        ));
    }

    /**
     * Export potential customers data with filters.
     */
    public function export(Request $request)
    {
        // بناء الاستعلام مع الفلاتر
        $query = PotentialCustomer::query();

        // تطبيق نفس الفلاتر المستخدمة في الفهرس
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city_id')) {
            $query->where('city', $request->city_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // ترتيب النتائج
        $query->orderBy('created_at', 'desc');

        $customers = $query->get();

        // تحديد نوع الملف
        $format = $request->get('format', 'csv');
        $timestamp = date('Y-m-d_H-i-s');

        if ($format === 'excel') {
            $filename = "potential_customers_{$timestamp}.xlsx";
            return Excel::download(new PotentialCustomersExport($customers), $filename);
        }

        // تصدير CSV (الافتراضي)
        $filename = "potential_customers_{$timestamp}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($customers) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Add headers - جميع الحقول
            fputcsv($file, [
                'الرقم',
                'الاسم',
                'البريد الإلكتروني',
                'رقم الجوال',
                'المدينة',
                'الحالة',
                'المصدر',
                'نوع الجهاز',
                'عنوان IP',
                'User Agent',
                'رابط الإحالة',
                'الصفحة المقصودة',
                'UTM Source',
                'UTM Medium',
                'UTM Campaign',
                'UTM Term',
                'UTM Content',
                'ملخص المكالمة',
                'تاريخ الطلب',
                'تاريخ الإنشاء',
                'تاريخ التحديث'
            ]);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->email ?? '',
                    $customer->phone,
                    $customer->city ?? '',
                    $customer->status ?? 'لم يتم التواصل',
                    $this->getSourceDisplay($customer->source, $customer->referrer_url),
                    $this->getDeviceTypeDisplay($customer->device_type),
                    $customer->ip_address ?? '',
                    $customer->user_agent ?? '',
                    $customer->referrer_url ?? '',
                    $customer->landing_page ?? '',
                    $customer->utm_source ?? '',
                    $customer->utm_medium ?? '',
                    $customer->utm_campaign ?? '',
                    $customer->utm_term ?? '',
                    $customer->utm_content ?? '',
                    $customer->call_summary ?? '',
                    $customer->created_at->format('Y-m-d H:i:s'), // تاريخ الطلب
                    $customer->created_at->format('Y-m-d H:i:s'), // تاريخ الإنشاء
                    $customer->updated_at->format('Y-m-d H:i:s')  // تاريخ التحديث
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get customer details for modal
     */
    public function show($id)
    {
        $customer = PotentialCustomer::findOrFail($id);

        // إضافة خصائص العرض
        $customer->device_type_display = $this->getDeviceTypeDisplay($customer->device_type);
        $customer->source_display = $this->getSourceDisplay($customer->source, $customer->referrer_url);

        return response()->json($customer);
    }

    /**
     * Update customer information
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'status' => 'required|string',
            'call_summary' => 'nullable|string',
            'ip_address' => 'nullable|ip',
            'device_type' => 'nullable|string',
            'source' => 'nullable|string',
        ]);

        $customer = PotentialCustomer::findOrFail($id);

        // تحديد نوع الجهاز تلقائياً إذا لم يكن محدداً
        $deviceType = $request->device_type;
        if (empty($deviceType) && !empty($customer->user_agent)) {
            $deviceType = $this->detectDeviceType($customer->user_agent);
        }

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'status' => $request->status,
            'call_summary' => $request->call_summary,
            'ip_address' => $request->ip_address,
            'device_type' => $deviceType,
            'source' => $request->source,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات العميل بنجاح'
        ]);
    }

    /**
     * Get device type display name with automatic detection
     */
    private function getDeviceTypeDisplay($deviceType)
    {
        $types = [
            'mobile' => 'جوال',
            'desktop' => 'كمبيوتر',
            'tablet' => 'تابلت'
        ];

        return $types[$deviceType] ?? 'غير محدد';
    }

    /**
     * Detect device type from user agent if not already set
     */
    private function detectDeviceType($userAgent)
    {
        if (empty($userAgent)) {
            return 'desktop';
        }

        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent($userAgent);

        if ($agent->isMobile()) {
            return 'mobile';
        } elseif ($agent->isTablet()) {
            return 'tablet';
        }

        return 'desktop';
    }

    /**
     * Get source display name with referrer URL
     */
    private function getSourceDisplay($source, $referrerUrl = null)
    {
        // إذا كان هناك رابط إحالة، اعرضه
        if (!empty($referrerUrl)) {
            $domain = parse_url($referrerUrl, PHP_URL_HOST);
            if ($domain) {
                // إزالة www. من بداية النطاق
                $domain = preg_replace('/^www\./', '', $domain);
                return $domain;
            }
        }

        // إذا لم يكن هناك رابط إحالة، استخدم التصنيف التقليدي
        $sources = [
            'google_ads' => 'إعلانات جوجل',
            'facebook_ads' => 'إعلانات فيسبوك',
            'direct' => 'دخول مباشر',
            'organic' => 'بحث طبيعي',
            'referral' => 'إحالة',
            'social' => 'وسائل التواصل',
            'card_request' => 'طلب بطاقة'
        ];

        return $sources[$source] ?? 'غير محدد';
    }

    /**
     * Show import form
     */
    public function importForm()
    {
        return view('admin.potential-customers.import');
    }

    /**
     * Import potential customers from Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $import = new PotentialCustomersImport();
            Excel::import($import, $request->file('file'));

            $successCount = $import->getSuccessCount();
            $skipCount = $import->getSkipCount();
            $errors = $import->getErrors();

            $message = "تم استيراد {$successCount} عميل بنجاح";
            if ($skipCount > 0) {
                $message .= "، تم تجاهل {$skipCount} سجل";
            }

            if (!empty($errors)) {
                $message .= ". الأخطاء: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " و " . (count($errors) - 3) . " أخطاء أخرى";
                }
            }

            return redirect()->route('admin.potential-customers.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء استيراد البيانات: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="potential_customers_template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Add headers
            fputcsv($file, [
                'name',
                'email',
                'phone',
                'city',
                'status',
                'source',
                'device_type',
                'ip_address',
                'user_agent',
                'referrer_url',
                'call_summary',
                'request_date'
            ]);

            // Add sample data
            fputcsv($file, [
                'أحمد محمد',
                'ahmed@example.com',
                '0501234567',
                'الرياض',
                'لم يتم التواصل',
                'google_ads',
                'mobile',
                '192.168.1.1',
                'Mozilla/5.0...',
                'https://google.com',
                'عميل مهتم بالخدمة',
                '2024-01-15 10:30:00'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Calculate statistics for potential customers.
     */
    private function calculateStatistics($data)
    {
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        return [
            'total' => $data->count(),
            'today' => $data->where('created_at', '>=', $today)->count(),
            'this_week' => $data->where('created_at', '>=', $thisWeek)->count(),
            'this_month' => $data->where('created_at', '>=', $thisMonth)->count(),
            'pending' => $data->where('status', 'لم يتم التواصل')->count(),
            'contacted' => $data->whereIn('status', ['لم يرد', 'تأجيل', 'تم التواصل'])->count(),
            'issued' => $data->where('status', 'تم الاصدار')->count(),
            'rejected' => $data->where('status', 'رفض')->count(),
            'converted' => $data->where('status', 'تم الاصدار')->count(),
            'by_source' => $data->groupBy('source')->map->count()->toArray(),
            'by_device' => $data->groupBy('device_type')->map->count()->toArray(),
            'by_city' => $data->groupBy('city')->map->count()->toArray(),
        ];
    }
}
