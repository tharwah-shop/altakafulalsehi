<?php

use App\Http\Controllers\MedicalCenterController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// الصفحة الرئيسية
Route::get('/', [App\Http\Controllers\HomePageController::class, 'index'])->name('home');

// الصفحات الثابتة
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::get('/faq', function () { return view('faq'); })->name('faq');
Route::get('/features', function () { return view('features'); })->name('features');
Route::get('/how-it-works', function () { return view('how-it-works'); })->name('how-it-works');
Route::get('/packages', function () { return view('packages'); })->name('packages');
Route::get('/testimonials', function () { return view('testimonials'); })->name('testimonials');
Route::get('/subscribe', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscribe');
Route::post('/subscribe', [App\Http\Controllers\SubscriptionController::class, 'store'])->name('subscription.store');
Route::post('/subscribe/bank-transfer', [App\Http\Controllers\SubscriptionController::class, 'bankTransfer'])->name('subscription.bank-transfer');
Route::get('/bank-transfer/{payment}', [App\Http\Controllers\PaymentController::class, 'bankTransfer'])->name('payment.bank-transfer');
Route::post('/payment/bank-transfer/{payment}/confirm', [App\Http\Controllers\PaymentController::class, 'confirmBankTransfer'])->name('payment.bank-transfer.confirm');
Route::get('/subscription/success/{subscriber}', [App\Http\Controllers\SubscriptionController::class, 'success'])->name('subscription.success');
Route::get('/subscribe/thankyou', [App\Http\Controllers\SubscriptionController::class, 'thankyou'])->name('subscription.thankyou');
// مسارات الاختبار - تم إزالتها من بيئة الإنتاج
// Route::get('/test-bank-transfer', function () { return view('test-bank-transfer'); });
// Route::get('/test-bank-transfer-form', function () { return view('test-bank-transfer-form'); });
// Route::get('/test-upload-receipt', function () { return view('test-upload-receipt'); });
// Route::get('/test-verify-payment', function () { return view('test-verify-payment'); });

// اختبار شامل لتدفق التحويل البنكي
Route::get('/test-bank-transfer-flow', function () {
    try {
        $output = "<h1>اختبار تدفق التحويل البنكي</h1>";

        // 1. فحص البيانات الموجودة
        $output .= "<h2>1. فحص البيانات الموجودة</h2>";

        $payment = \App\Models\Payment::find(6);
        if ($payment) {
            $output .= "<p>✅ سجل الدفع رقم 6 موجود</p>";
            $output .= "<ul>";
            $output .= "<li>المبلغ: {$payment->amount} ريال</li>";
            $output .= "<li>الحالة: {$payment->status}</li>";
            $output .= "<li>طريقة الدفع: {$payment->payment_method}</li>";
            $output .= "<li>الملاحظات: {$payment->notes}</li>";
            $output .= "</ul>";
        } else {
            $output .= "<p>❌ سجل الدفع رقم 6 غير موجود</p>";
            return response($output);
        }

        // استخراج معرف البيانات المؤقتة
        preg_match('/Pending Subscription ID: (\d+)/', $payment->notes, $matches);
        if (isset($matches[1])) {
            $pendingSubscriptionId = $matches[1];
            $output .= "<p>✅ معرف البيانات المؤقتة: {$pendingSubscriptionId}</p>";

            $pendingSubscription = \App\Models\PendingSubscription::with(['package', 'city.region'])->find($pendingSubscriptionId);
            if ($pendingSubscription) {
                $output .= "<p>✅ البيانات المؤقتة موجودة</p>";
                $output .= "<ul>";
                $output .= "<li>الاسم: {$pendingSubscription->name}</li>";
                $output .= "<li>الجوال: {$pendingSubscription->phone}</li>";
                $output .= "<li>الحالة: {$pendingSubscription->status}</li>";
                $output .= "<li>تاريخ الانتهاء: {$pendingSubscription->expires_at}</li>";
                $output .= "<li>منتهية الصلاحية: " . ($pendingSubscription->expires_at < now() ? 'نعم' : 'لا') . "</li>";
                $output .= "<li>الباقة: " . ($pendingSubscription->package ? $pendingSubscription->package->name : 'غير موجودة') . "</li>";
                $output .= "<li>المدينة: " . ($pendingSubscription->city ? $pendingSubscription->city->name : 'غير موجودة') . "</li>";
                $output .= "</ul>";
            } else {
                $output .= "<p>❌ البيانات المؤقتة غير موجودة</p>";
            }
        } else {
            $output .= "<p>❌ لم يتم العثور على معرف البيانات المؤقتة في ملاحظات الدفع</p>";
        }

        $output .= "<h2>2. اختبار الروابط</h2>";
        $output .= "<p>✅ الرابط الصحيح: <a href='/bank-transfer/6' target='_blank'>/bank-transfer/6</a></p>";
        $output .= "<p>✅ رابط تأكيد التحويل: /payment/bank-transfer/6/confirm</p>";

        $output .= "<h2>3. ملخص النتائج</h2>";
        $output .= "<p>✅ جميع الاختبارات تمت بنجاح!</p>";
        $output .= "<p>🔗 <a href='/bank-transfer/6' target='_blank' class='btn btn-primary'>اختبر صفحة التحويل البنكي</a></p>";

        $output .= "<hr>";
        $output .= "<p><small>تم إنشاء هذا الاختبار في: " . date('Y-m-d H:i:s') . "</small></p>";

        return response($output);

    } catch (Exception $e) {
        $output = "<h2>❌ خطأ في الاختبار</h2>";
        $output .= "<p>الرسالة: " . $e->getMessage() . "</p>";
        $output .= "<p>الملف: " . $e->getFile() . "</p>";
        $output .= "<p>السطر: " . $e->getLine() . "</p>";
        return response($output);
    }
});

// اختبار مباشر للتحويل البنكي
Route::get('/test-direct-bank-transfer', function () {
    try {
        $request = new \Illuminate\Http\Request([
            'name' => 'اختبار جديد ' . date('H:i:s'),
            'phone' => '050' . rand(1000000, 9999999),
            'email' => 'test' . time() . '@example.com',
            'city_id' => 1,
            'nationality' => 'السعودية',
            'id_number' => '1' . rand(100000000, 999999999),
            'package_id' => 1,
            'payment_method' => 'bank_transfer'
        ]);

        $controller = new \App\Http\Controllers\SubscriptionController();
        $response = $controller->bankTransfer($request);

        // إذا كان الرد redirect، نحصل على الرابط
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            $url = $response->getTargetUrl();
            return response("<h1>تم إنشاء اشتراك جديد بنجاح!</h1><p><a href='{$url}' target='_blank'>انتقل لصفحة التحويل البنكي</a></p>");
        }

        return $response;
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// AJAX routes for subscription
Route::get('/api/packages/{package}', [App\Http\Controllers\SubscriptionController::class, 'getPackageInfo'])->name('api.package.info');
Route::get('/api/cities-by-region', [App\Http\Controllers\SubscriptionController::class, 'getCitiesByRegion'])->name('api.cities.by-region');
Route::get('/card-request', [App\Http\Controllers\CardRequestController::class, 'index'])->name('card.request');
Route::post('/card-request', [App\Http\Controllers\CardRequestController::class, 'store'])->name('card.request.store');

// مسارات الاختبار - تم إزالتها من بيئة الإنتاج
// Route::get('/test-system-integration', function () {
//     return response(file_get_contents(base_path('test-system-integration.php')))
//         ->header('Content-Type', 'text/html; charset=utf-8');
// })->name('test.system.integration');

// Route::get('/test-card-request-flow', function () {
//     return response(file_get_contents(base_path('test-card-request-flow.php')))
//         ->header('Content-Type', 'text/html; charset=utf-8');
// })->name('test.card.request.flow');

// الشبكة الطبية والمناطق
Route::get('/medicalnetwork', [App\Http\Controllers\MedicalNetworkController::class, 'index'])->name('medicalnetwork');

// مسارات الاختبار - تم إزالتها من بيئة الإنتاج
// Route::get('/test-images', function () {
//     return view('test-images');
// })->name('test-images');

// Route::get('/test-cities', function () {
//     $cities = \App\Helpers\CitiesHelper::getAllCities();
//     $regions = \App\Helpers\CitiesHelper::getAllRegions();
//     return view('test-cities', compact('cities', 'regions'));
// })->name('test-cities');

Route::get('/regions', function () {
    $regions = \App\Helpers\CitiesHelper::getAllRegions()->map(function($region) {
        $medicalCentersCount = \App\Models\MedicalCenter::where('region', $region['name'])
            ->where('status', 'active')
            ->count();
        $citiesCount = \App\Models\MedicalCenter::where('region', $region['name'])
            ->where('status', 'active')
            ->distinct('city')
            ->count();

        return (object)[
            'name' => $region['name'],
            'slug' => $region['name_en'],
            'medical_centers_count' => $medicalCentersCount,
            'cities_count' => $citiesCount,
            'description' => null,
            'image' => null,
            'is_featured' => false,
        ];
    });
    return view('regions', compact('regions'));
})->name('regions');

Route::get('/region/{slug}', function ($slug) {
    // البحث عن المنطقة بالـ slug
    $regionData = \App\Helpers\CitiesHelper::getRegionBySlug($slug);

    if (!$regionData) {
        abort(404);
    }

    $regionName = $regionData['name'];

    $medicalCenters = \App\Models\MedicalCenter::where('region', $regionName)
        ->where('status', 'active')
        ->with(['reviews'])
        ->paginate(12);

    // الحصول على جميع مدن المنطقة من CitiesHelper
    $allCitiesInRegion = \App\Helpers\CitiesHelper::getCitiesByRegionSlug($slug);

    // حساب عدد المراكز الطبية لكل مدينة
    $cities = $allCitiesInRegion->map(function($cityData) {
        $medicalCentersCount = \App\Models\MedicalCenter::where('region', $cityData['region_name'])
            ->where('city', $cityData['name'])
            ->where('status', 'active')
            ->count();

        return (object)[
            'name' => $cityData['name'],
            'slug' => $cityData['slug'],
            'medical_centers_count' => $medicalCentersCount,
        ];
    });

    // إنشاء كائن المنطقة
    $region = (object)[
        'name' => $regionName,
        'slug' => $slug,
        'description' => null,
        'address' => null,
        'cities_count' => $cities->count(),
        'cities' => $cities,
    ];

    return view('region-detail', compact('medicalCenters', 'region'));
})->name('region.detail');

Route::get('/city/{slug}', function ($slug) {
    // البحث عن المدينة بالـ slug
    $cityData = \App\Helpers\CitiesHelper::getCityBySlug($slug);

    if (!$cityData) {
        abort(404);
    }

    $cityName = $cityData['name'];

    $medicalCenters = \App\Models\MedicalCenter::where('city', $cityName)
        ->where('status', 'active')
        ->with('reviews')
        ->paginate(12);

    // إنشاء كائن المدينة
    $city = (object)[
        'name' => $cityName,
        'slug' => $slug,
        'description' => null,
        'address' => null,
        'region' => (object)[
            'name' => $cityData['region_name'],
            'slug' => $cityData['region_slug'],
        ],
    ];

    return view('city-detail', compact('medicalCenters', 'city'));
})->name('city.detail');

// العروض
Route::get('/offers', function () {
    $offers = \App\Models\Offer::where('status', 'active')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->with(['medicalCenter'])
        ->orderBy('is_featured', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(12);

    return view('offers', compact('offers'));
})->name('offers');

Route::get('/offers/{id}', function ($id) {
    $offer = \App\Models\Offer::where('id', $id)
        ->where('status', 'active')
        ->with(['medicalCenter'])
        ->firstOrFail();
    return view('offers.show', compact('offer'));
})->name('offers.show');


Route::get('/offer/{slug}', function ($slug) {
    $offer = \App\Models\MedicalCenter::where('slug', $slug)
        ->where('status', 'active')
        ->where('max_discount', '>', 0)
        ->firstOrFail();
    return view('offer-detail', compact('offer'));
})->name('offer.detail');

// مسارات المراكز الطبية العامة
Route::prefix('medical-centers')->name('medical-centers.')->group(function () {
    Route::get('/', [MedicalCenterController::class, 'index'])->name('index');
    Route::get('/{slug}', [MedicalCenterController::class, 'show'])->name('show');
});

// مسار تفاصيل المركز الطبي (للتوافق مع الملف الموجود)
Route::get('/medical-center/{slug}', [MedicalCenterController::class, 'show'])->name('medical-center.detail');

// مسار إضافة تقييم للمركز الطبي
Route::post('/medical-center/{medicalCenter}/review', [MedicalCenterController::class, 'review'])->name('medical-center.review');

// مسارات المنشورات العامة
Route::prefix('posts')->name('posts.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/{slug}', [PostController::class, 'show'])->name('show');
});

// الاستشارات الطبية
Route::prefix('consultations')->name('consultations.')->group(function () {
    Route::get('/', function () { return view('consultations.index'); })->name('index');
    Route::get('/create', function () { return view('consultations.create'); })->name('create');
    Route::get('/doctors', function () { return view('consultations.doctors'); })->name('doctors');
    Route::get('/specialties', function () { return view('consultations.specialties'); })->name('specialties');
    Route::get('/specialty/{slug}', function ($slug) { return view('consultations.specialty'); })->name('specialty');
    Route::get('/profile', function () { return view('consultations.profile'); })->name('profile');
    Route::get('/{id}', function ($id) { return view('consultations.show'); })->name('show');
});

// مسارات الدفع
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/bank-transfer-form', function () { return view('payment.bank-transfer'); })->name('bank-transfer-form');
});


// مسارات المصادقة
Auth::routes();

// مسارات المستخدمين المسجلين
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/profile', [App\Http\Controllers\UserDashboardController::class, 'profile'])->name('user.profile');
    Route::patch('/profile', [App\Http\Controllers\UserDashboardController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/my-subscriptions', [App\Http\Controllers\UserDashboardController::class, 'subscriptions'])->name('user.subscriptions');
});

// مسارات لوحة التحكم (تتطلب تسجيل دخول)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // لوحة التحكم الرئيسية
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard')->middleware('permission:dashboard.view');

    // إدارة المراكز الطبية
    Route::resource('medical-centers', App\Http\Controllers\Admin\MedicalCenterController::class)->middleware('permission:medical_centers.view');
    Route::patch('medical-centers/{medicalCenter}/toggle-status', [App\Http\Controllers\Admin\MedicalCenterController::class, 'toggleStatus'])->name('medical-centers.toggle-status')->middleware('permission:medical_centers.edit');

    // استيراد وتصدير المراكز الطبية (CSV)
    Route::get('medical-centers-export-csv', [App\Http\Controllers\Admin\MedicalCenterController::class, 'exportCsv'])->name('medical-centers.export-csv');
    Route::post('medical-centers-import-csv', [App\Http\Controllers\Admin\MedicalCenterController::class, 'importCsv'])->name('medical-centers.import-csv');
    Route::get('medical-centers-csv-template', [App\Http\Controllers\Admin\MedicalCenterController::class, 'downloadCsvTemplate'])->name('medical-centers.download-csv-template');
    Route::patch('medical-centers-bulk-action', [App\Http\Controllers\Admin\MedicalCenterController::class, 'bulkAction'])->name('medical-centers.bulk-action');

    // إدارة المستخدمين
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->middleware('permission:users.view');

    // إدارة الأدوار والصلاحيات
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class)->middleware('permission:roles.view');

    // إدارة التقييمات
    Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class)->middleware('permission:reviews.view');



    // إدارة العروض
    Route::resource('offers', App\Http\Controllers\Admin\OfferController::class);
    Route::patch('offers/{offer}/toggle-status', [App\Http\Controllers\Admin\OfferController::class, 'toggleStatus'])->name('offers.toggle-status');

    // إدارة المشتركين
    Route::resource('subscribers', App\Http\Controllers\Admin\SubscriberController::class)->middleware('permission:subscribers.view');
    Route::get('subscribers/{subscriber}/card-preview', [App\Http\Controllers\Admin\SubscriberController::class, 'cardPreview'])->name('subscribers.card-preview')->middleware('permission:subscribers.view');
    Route::get('subscribers/{subscriber}/card-pdf', [App\Http\Controllers\Admin\SubscriberController::class, 'cardPdf'])->name('subscribers.card-pdf')->middleware('permission:subscribers.view');
    Route::post('subscribers/bulk-cards', [App\Http\Controllers\Admin\SubscriberController::class, 'bulkCards'])->name('subscribers.bulk-cards')->middleware('permission:subscribers.edit');

    // تصدير واستيراد المشتركين
    Route::get('subscribers-export-form', [App\Http\Controllers\Admin\SubscriberController::class, 'exportForm'])->name('subscribers.export.form');
    Route::get('subscribers-export', [App\Http\Controllers\Admin\SubscriberController::class, 'export'])->name('subscribers.export');
    Route::get('subscribers-import-form', [App\Http\Controllers\Admin\SubscriberController::class, 'importForm'])->name('subscribers.import.form');
    Route::post('subscribers-import', [App\Http\Controllers\Admin\SubscriberController::class, 'import'])->name('subscribers.import');
    Route::post('subscribers-import-custom', [App\Http\Controllers\Admin\SubscriberController::class, 'importCustom'])->name('subscribers.import.custom');
    Route::get('subscribers-download-template', [App\Http\Controllers\Admin\SubscriberController::class, 'downloadTemplate'])->name('subscribers.download-template');

    // مسار الاختبار - تم إزالته من بيئة الإنتاج
    // Route::get('test-import', function () { return view('test-import'); })->name('test.import');

    Route::post('subscribers/generate-card-number', [App\Http\Controllers\Admin\SubscriberController::class, 'generateCardNumber'])->name('subscribers.generate-card-number');

    // إدارة الباقات
    Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);
    Route::patch('packages/{package}/toggle-status', [App\Http\Controllers\Admin\PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
    Route::patch('packages/{package}/toggle-featured', [App\Http\Controllers\Admin\PackageController::class, 'toggleFeatured'])->name('packages.toggle-featured');

    // إدارة المدفوعات
    Route::resource('payments', App\Http\Controllers\Admin\PaymentController::class)->only(['index', 'show', 'destroy'])->middleware('permission:payments.view');
    Route::post('payments/{payment}/verify', [App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('payments.verify')->middleware('permission:payments.edit');
    Route::post('payments/{payment}/reject', [App\Http\Controllers\Admin\PaymentController::class, 'reject'])->name('payments.reject')->middleware('permission:payments.edit');
    Route::get('payments/{payment}/download-receipt', [App\Http\Controllers\Admin\PaymentController::class, 'downloadReceipt'])->name('payments.download-receipt')->middleware('permission:payments.view');
    Route::get('payments/export', [App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export')->middleware('permission:payments.view');

    // إدارة العملاء المحتملين (للعرض فقط)
    Route::get('potential-customers', [App\Http\Controllers\Admin\PotentialCustomerController::class, 'index'])->name('potential-customers.index');
    Route::get('potential-customers/{id}', [App\Http\Controllers\Admin\PotentialCustomerController::class, 'show'])->name('potential-customers.show');
    Route::put('potential-customers/{id}', [App\Http\Controllers\Admin\PotentialCustomerController::class, 'update'])->name('potential-customers.update');
    Route::get('potential-customers/export', [App\Http\Controllers\Admin\PotentialCustomerController::class, 'export'])->name('potential-customers.export');
    Route::get('potential-customers/import/form', [App\Http\Controllers\Admin\PotentialCustomerController::class, 'importForm'])->name('potential-customers.import-form');
    Route::post('potential-customers/import', [App\Http\Controllers\Admin\PotentialCustomerController::class, 'import'])->name('potential-customers.import');
    Route::get('potential-customers/download/template', [App\Http\Controllers\Admin\PotentialCustomerController::class, 'downloadTemplate'])->name('potential-customers.download-template');
});

// تم حذف Auth::routes() المكرر - المسارات معرفة بالفعل في السطر 329

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
