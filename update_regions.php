<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// المناطق المعتمدة
$validRegions = [
    'المنطقة الوسطى',
    'المنطقة الشمالية',
    'المنطقة الجنوبية',
    'المنطقة الغربية',
    'المنطقة الشرقية'
];

// الحصول على جميع المناطق الحالية
$currentRegions = DB::table('medical_centers')
    ->select('region')
    ->distinct()
    ->pluck('region')
    ->toArray();

echo "المناطق الحالية:\n";
print_r($currentRegions);

// تحديد المناطق غير المعتمدة
$nonValidRegions = array_diff($currentRegions, $validRegions);

echo "\nالمناطق غير المعتمدة التي سيتم تحديثها:\n";
print_r($nonValidRegions);

// تحديث المناطق غير المعتمدة إلى "المنطقة الوسطى" (افتراضي)
foreach ($nonValidRegions as $region) {
    $count = DB::table('medical_centers')
        ->where('region', $region)
        ->update(['region' => 'المنطقة الوسطى']);
    
    echo "تم تحديث {$count} مركز من {$region} إلى المنطقة الوسطى\n";
}

// التحقق من المناطق بعد التحديث
$updatedRegions = DB::table('medical_centers')
    ->select('region')
    ->distinct()
    ->pluck('region')
    ->toArray();

echo "\nالمناطق بعد التحديث:\n";
print_r($updatedRegions);

echo "\nتم الانتهاء من تحديث المناطق بنجاح!\n";