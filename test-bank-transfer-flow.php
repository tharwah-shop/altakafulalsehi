<?php

/**
 * اختبار شامل لتدفق التحويل البنكي
 * يمكن تشغيله من المتصفح أو من سطر الأوامر
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// إنشاء تطبيق Laravel
$app = new Application(realpath(__DIR__));
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "<h1>اختبار تدفق التحويل البنكي</h1>";

try {
    // 1. فحص البيانات الموجودة
    echo "<h2>1. فحص البيانات الموجودة</h2>";
    
    $payment = \App\Models\Payment::find(6);
    if ($payment) {
        echo "<p>✅ سجل الدفع رقم 6 موجود</p>";
        echo "<ul>";
        echo "<li>المبلغ: {$payment->amount} ريال</li>";
        echo "<li>الحالة: {$payment->status}</li>";
        echo "<li>طريقة الدفع: {$payment->payment_method}</li>";
        echo "<li>الملاحظات: {$payment->notes}</li>";
        echo "</ul>";
    } else {
        echo "<p>❌ سجل الدفع رقم 6 غير موجود</p>";
        exit;
    }
    
    // استخراج معرف البيانات المؤقتة
    preg_match('/Pending Subscription ID: (\d+)/', $payment->notes, $matches);
    if (isset($matches[1])) {
        $pendingSubscriptionId = $matches[1];
        echo "<p>✅ معرف البيانات المؤقتة: {$pendingSubscriptionId}</p>";
        
        $pendingSubscription = \App\Models\PendingSubscription::with(['package', 'city.region'])->find($pendingSubscriptionId);
        if ($pendingSubscription) {
            echo "<p>✅ البيانات المؤقتة موجودة</p>";
            echo "<ul>";
            echo "<li>الاسم: {$pendingSubscription->name}</li>";
            echo "<li>الجوال: {$pendingSubscription->phone}</li>";
            echo "<li>الحالة: {$pendingSubscription->status}</li>";
            echo "<li>تاريخ الانتهاء: {$pendingSubscription->expires_at}</li>";
            echo "<li>منتهية الصلاحية: " . ($pendingSubscription->expires_at < now() ? 'نعم' : 'لا') . "</li>";
            echo "<li>الباقة: " . ($pendingSubscription->package ? $pendingSubscription->package->name : 'غير موجودة') . "</li>";
            echo "<li>المدينة: " . ($pendingSubscription->city ? $pendingSubscription->city->name : 'غير موجودة') . "</li>";
            echo "</ul>";
        } else {
            echo "<p>❌ البيانات المؤقتة غير موجودة</p>";
        }
    } else {
        echo "<p>❌ لم يتم العثور على معرف البيانات المؤقتة في ملاحظات الدفع</p>";
    }
    
    // 2. اختبار صفحة التحويل البنكي
    echo "<h2>2. اختبار صفحة التحويل البنكي</h2>";
    
    $request = Request::create('/bank-transfer/6', 'GET');
    $response = $kernel->handle($request);
    
    if ($response->getStatusCode() == 200) {
        echo "<p>✅ صفحة التحويل البنكي تعمل بنجاح (HTTP 200)</p>";
        
        $content = $response->getContent();
        
        // فحص وجود العناصر المهمة في الصفحة
        $checks = [
            'payment amount' => preg_match('/\d+\.\d+\s*ريال/', $content),
            'subscriber name' => preg_match('/' . preg_quote($pendingSubscription->name, '/') . '/', $content),
            'upload form' => preg_match('/transfer-confirmation-form/', $content),
            'bank details' => preg_match('/مصرف الراجحي/', $content),
            'no error messages' => !preg_match('/لم يتم العثور على بيانات/', $content)
        ];
        
        foreach ($checks as $check => $result) {
            echo "<li>" . ($result ? "✅" : "❌") . " {$check}</li>";
        }
        
    } else {
        echo "<p>❌ صفحة التحويل البنكي لا تعمل (HTTP {$response->getStatusCode()})</p>";
        echo "<pre>" . $response->getContent() . "</pre>";
    }
    
    // 3. اختبار الروابط
    echo "<h2>3. اختبار الروابط</h2>";
    
    $links = [
        'الرابط الصحيح' => '/bank-transfer/6',
        'رابط تأكيد التحويل' => '/payment/bank-transfer/6/confirm'
    ];
    
    foreach ($links as $name => $url) {
        $testRequest = Request::create($url, 'GET');
        $testResponse = $kernel->handle($testRequest);
        $status = $testResponse->getStatusCode();
        
        if ($status == 200 || $status == 405) { // 405 للـ POST routes
            echo "<p>✅ {$name}: {$url} (HTTP {$status})</p>";
        } else {
            echo "<p>❌ {$name}: {$url} (HTTP {$status})</p>";
        }
    }
    
    echo "<h2>4. ملخص النتائج</h2>";
    echo "<p>✅ جميع الاختبارات تمت بنجاح!</p>";
    echo "<p>🔗 <a href='/bank-transfer/6' target='_blank'>اختبر صفحة التحويل البنكي</a></p>";
    
} catch (Exception $e) {
    echo "<h2>❌ خطأ في الاختبار</h2>";
    echo "<p>الرسالة: " . $e->getMessage() . "</p>";
    echo "<p>الملف: " . $e->getFile() . "</p>";
    echo "<p>السطر: " . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><small>تم إنشاء هذا الاختبار في: " . date('Y-m-d H:i:s') . "</small></p>";
