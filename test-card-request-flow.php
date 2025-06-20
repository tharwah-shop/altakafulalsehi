<?php

/**
 * اختبار تدفق طلب البطاقة
 * 
 * هذا الملف يختبر:
 * 1. إرسال طلب بطاقة
 * 2. حفظ البيانات في potential_customers
 * 3. التحويل إلى صفحة الاشتراك مع البيانات المعبأة
 */

echo "<h1>اختبار تدفق طلب البطاقة</h1>";
echo "<hr>";

// بيانات اختبار
$testData = [
    'name' => 'أحمد محمد علي',
    'email' => 'ahmed@example.com',
    'phone' => '0501234567',
    'city' => 'الرياض',
    'terms' => '1'
];

echo "<h2>بيانات الاختبار:</h2>";
echo "<ul>";
foreach ($testData as $key => $value) {
    echo "<li><strong>{$key}:</strong> {$value}</li>";
}
echo "</ul>";

echo "<hr>";

// محاكاة إرسال الطلب
echo "<h2>محاكاة إرسال طلب البطاقة:</h2>";

try {
    // إنشاء اتصال بقاعدة البيانات
    $pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    
    // التحقق من وجود الجدول
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='potential_customers'");
    if (!$stmt->fetch()) {
        echo "❌ جدول potential_customers غير موجود<br>";
        exit;
    }
    
    echo "✅ جدول potential_customers موجود<br>";
    
    // إدراج بيانات اختبار
    $sql = "INSERT INTO potential_customers (name, email, phone, city, status, source, device_type, ip_address, user_agent, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        $testData['name'],
        $testData['email'],
        $testData['phone'],
        $testData['city'],
        'لم يتم التواصل',
        'card_request',
        'desktop',
        '127.0.0.1',
        'Test User Agent',
        date('Y-m-d H:i:s'),
        date('Y-m-d H:i:s')
    ]);
    
    if ($result) {
        $customerId = $pdo->lastInsertId();
        echo "✅ تم حفظ البيانات بنجاح - ID: {$customerId}<br>";
        
        // التحقق من البيانات المحفوظة
        $stmt = $pdo->prepare("SELECT * FROM potential_customers WHERE id = ?");
        $stmt->execute([$customerId]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($customer) {
            echo "✅ تم التحقق من البيانات المحفوظة:<br>";
            echo "<ul>";
            echo "<li>الاسم: {$customer['name']}</li>";
            echo "<li>البريد: {$customer['email']}</li>";
            echo "<li>الجوال: {$customer['phone']}</li>";
            echo "<li>المدينة: {$customer['city']}</li>";
            echo "<li>الحالة: {$customer['status']}</li>";
            echo "<li>المصدر: {$customer['source']}</li>";
            echo "</ul>";
        }
        
    } else {
        echo "❌ فشل في حفظ البيانات<br>";
    }
    
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// اختبار session data
echo "<h2>اختبار بيانات الـ Session:</h2>";

// محاكاة بيانات session
$sessionData = [
    'card_request_data' => [
        'name' => $testData['name'],
        'email' => $testData['email'],
        'phone' => $testData['phone'],
        'city' => $testData['city'],
        'potential_customer_id' => $customerId ?? 1
    ]
];

echo "✅ بيانات Session محاكاة:<br>";
echo "<pre>" . json_encode($sessionData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

echo "<hr>";

// روابط الاختبار
echo "<h2>روابط الاختبار:</h2>";
echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 5px;'>";
echo "<h3>اختبر التدفق:</h3>";
echo "<ol>";
echo "<li><a href='/card-request' target='_blank'>صفحة طلب البطاقة</a> - املأ النموذج واضغط إرسال</li>";
echo "<li><a href='/subscribe' target='_blank'>صفحة الاشتراك</a> - تحقق من تعبئة البيانات تلقائياً</li>";
echo "<li><a href='/admin/potential-customers' target='_blank'>لوحة التحكم</a> - تحقق من ظهور العميل الجديد</li>";
echo "</ol>";

echo "<h3>خطوات الاختبار اليدوي:</h3>";
echo "<ol>";
echo "<li>اذهب إلى صفحة طلب البطاقة</li>";
echo "<li>املأ النموذج بالبيانات التالية:</li>";
echo "<ul>";
echo "<li>الاسم: أحمد محمد علي</li>";
echo "<li>البريد: ahmed@example.com</li>";
echo "<li>الجوال: 0501234567</li>";
echo "<li>المدينة: الرياض</li>";
echo "</ul>";
echo "<li>اضغط 'إرسال الطلب'</li>";
echo "<li>تحقق من التحويل إلى صفحة الاشتراك</li>";
echo "<li>تحقق من تعبئة البيانات تلقائياً</li>";
echo "<li>اذهب إلى لوحة التحكم وتحقق من ظهور العميل</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<p><strong>تاريخ الاختبار:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>حالة الاختبار:</strong> جاهز للتنفيذ</p>";
