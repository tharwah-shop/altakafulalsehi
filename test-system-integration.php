<?php

/**
 * اختبار تكامل النظام الجديد
 * 
 * هذا الملف يختبر:
 * 1. صفحة طلب البطاقة
 * 2. نظام تتبع الزوار
 * 3. حفظ البيانات في potential_customers
 * 4. التحويل إلى صفحة الاشتراك
 * 5. صفحة العملاء المحتملين في لوحة التحكم
 */

echo "<h1>اختبار تكامل النظام الجديد</h1>";
echo "<hr>";

// 1. اختبار الاتصال بقاعدة البيانات
echo "<h2>1. اختبار قاعدة البيانات</h2>";
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    echo "✅ الاتصال بقاعدة البيانات ناجح<br>";
    
    // فحص جدول potential_customers
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='potential_customers'");
    if ($stmt->fetch()) {
        echo "✅ جدول potential_customers موجود<br>";
        
        // فحص الأعمدة الجديدة
        $stmt = $pdo->query("PRAGMA table_info(potential_customers)");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $requiredColumns = ['city', 'referrer_url', 'landing_page', 'utm_source', 'utm_medium', 'utm_campaign'];
        $existingColumns = array_column($columns, 'name');
        
        foreach ($requiredColumns as $column) {
            if (in_array($column, $existingColumns)) {
                echo "✅ العمود {$column} موجود<br>";
            } else {
                echo "❌ العمود {$column} غير موجود<br>";
            }
        }
    } else {
        echo "❌ جدول potential_customers غير موجود<br>";
    }
} catch (Exception $e) {
    echo "❌ خطأ في قاعدة البيانات: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// 2. اختبار ملفات النظام
echo "<h2>2. اختبار ملفات النظام</h2>";

$files = [
    'app/Http/Controllers/CardRequestController.php' => 'Controller طلب البطاقة',
    'app/Http/Middleware/TrackVisitor.php' => 'Middleware تتبع الزوار',
    'resources/views/card-request.blade.php' => 'صفحة طلب البطاقة',
    'resources/views/admin/potential-customers/index.blade.php' => 'صفحة العملاء المحتملين',
    'app/Helpers/CitiesHelper.php' => 'مساعد المدن',
    'config/cities.php' => 'ملف تكوين المدن'
];

foreach ($files as $file => $description) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ {$description} موجود<br>";
    } else {
        echo "❌ {$description} غير موجود: {$file}<br>";
    }
}

echo "<hr>";

// 3. اختبار المكتبات المطلوبة
echo "<h2>3. اختبار المكتبات المطلوبة</h2>";

$composerFile = __DIR__ . '/composer.json';
if (file_exists($composerFile)) {
    $composer = json_decode(file_get_contents($composerFile), true);
    
    $requiredPackages = [
        'stevebauman/location' => 'مكتبة تحديد الموقع',
        'jenssegers/agent' => 'مكتبة تحديد نوع الجهاز'
    ];
    
    foreach ($requiredPackages as $package => $description) {
        if (isset($composer['require'][$package])) {
            echo "✅ {$description} مثبتة: {$package}<br>";
        } else {
            echo "❌ {$description} غير مثبتة: {$package}<br>";
        }
    }
} else {
    echo "❌ ملف composer.json غير موجود<br>";
}

echo "<hr>";

// 4. اختبار Routes
echo "<h2>4. اختبار المسارات</h2>";

$routesFile = __DIR__ . '/routes/web.php';
if (file_exists($routesFile)) {
    $routes = file_get_contents($routesFile);
    
    $requiredRoutes = [
        'card.request' => 'مسار طلب البطاقة',
        'card.request.store' => 'مسار حفظ طلب البطاقة',
        'potential-customers.show' => 'مسار عرض تفاصيل العميل',
        'potential-customers.update' => 'مسار تحديث بيانات العميل'
    ];
    
    foreach ($requiredRoutes as $route => $description) {
        if (strpos($routes, $route) !== false) {
            echo "✅ {$description} موجود<br>";
        } else {
            echo "❌ {$description} غير موجود<br>";
        }
    }
} else {
    echo "❌ ملف routes/web.php غير موجود<br>";
}

echo "<hr>";

// 5. اختبار التكوين
echo "<h2>5. اختبار التكوين</h2>";

$bootstrapFile = __DIR__ . '/bootstrap/app.php';
if (file_exists($bootstrapFile)) {
    $bootstrap = file_get_contents($bootstrapFile);
    
    if (strpos($bootstrap, 'TrackVisitor') !== false) {
        echo "✅ Middleware تتبع الزوار مسجل<br>";
    } else {
        echo "❌ Middleware تتبع الزوار غير مسجل<br>";
    }
} else {
    echo "❌ ملف bootstrap/app.php غير موجود<br>";
}

echo "<hr>";

// 6. روابط الاختبار
echo "<h2>6. روابط الاختبار</h2>";
echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 5px;'>";
echo "<h3>اختبر النظام:</h3>";
echo "<ul>";
echo "<li><a href='/card-request' target='_blank'>صفحة طلب البطاقة</a></li>";
echo "<li><a href='/subscribe' target='_blank'>صفحة الاشتراك</a></li>";
echo "<li><a href='/admin/potential-customers' target='_blank'>لوحة التحكم - العملاء المحتملين</a></li>";
echo "</ul>";

echo "<h3>تدفق العمل المتوقع:</h3>";
echo "<ol>";
echo "<li>زيارة صفحة طلب البطاقة</li>";
echo "<li>تعبئة النموذج وإرساله</li>";
echo "<li>التحويل التلقائي إلى صفحة الاشتراك مع البيانات المعبأة</li>";
echo "<li>حفظ البيانات في جدول potential_customers</li>";
echo "<li>إمكانية عرض وتعديل البيانات من لوحة التحكم</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<p><strong>تاريخ الاختبار:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>حالة النظام:</strong> جاهز للاختبار</p>";
