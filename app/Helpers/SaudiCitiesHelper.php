<?php

namespace App\Helpers;

class SaudiCitiesHelper
{
    /**
     * الحصول على جميع المدن السعودية
     */
    public static function getAllCities()
    {
        $data = config('saudi_cities.cities', []);
        return collect($data);
    }

    /**
     * الحصول على مدينة بالاسم العربي
     */
    public static function getCityByName($name)
    {
        $cities = self::getAllCities();
        return $cities->firstWhere('name', $name);
    }

    /**
     * الحصول على مدينة بالاسم الإنجليزي
     */
    public static function getCityBySlug($slug)
    {
        $cities = self::getAllCities();
        return $cities->firstWhere('name_en', $slug);
    }

    /**
     * الحصول على أسماء المدن فقط (للاستخدام في القوائم المنسدلة)
     */
    public static function getCityNames()
    {
        return self::getAllCities()->pluck('name')->toArray();
    }

    /**
     * الحصول على أسماء المدن الإنجليزية فقط
     */
    public static function getCityEnglishNames()
    {
        return self::getAllCities()->pluck('name_en')->toArray();
    }

    /**
     * التحقق من وجود المدينة
     */
    public static function cityExists($name)
    {
        return self::getAllCities()->contains('name', $name);
    }

    /**
     * البحث في المدن
     */
    public static function searchCities($query)
    {
        $cities = self::getAllCities();
        
        return $cities->filter(function ($city) use ($query) {
            return stripos($city['name'], $query) !== false || 
                   stripos($city['name_en'], $query) !== false;
        });
    }

    /**
     * الحصول على المدن مرتبة أبجدياً
     */
    public static function getCitiesSorted()
    {
        return self::getAllCities()->sortBy('name')->values();
    }

    /**
     * تحويل اسم المدينة إلى slug
     */
    public static function getCitySlug($cityName)
    {
        $city = self::getCityByName($cityName);
        return $city ? $city['name_en'] : null;
    }

    /**
     * تحويل slug إلى اسم المدينة
     */
    public static function getCityNameFromSlug($slug)
    {
        $city = self::getCityBySlug($slug);
        return $city ? $city['name'] : null;
    }

    /**
     * الحصول على المدن الرئيسية (أهم المدن)
     */
    public static function getMajorCities()
    {
        $majorCities = [
            'الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 
            'الخبر', 'تبوك', 'بريدة', 'خميس مشيط', 'حائل', 'أبها', 
            'الطائف', 'الأحساء', 'ينبع', 'نجران', 'جازان', 'عرعر', 
            'سكاكا', 'القطيف', 'الجبيل'
        ];

        return self::getAllCities()->filter(function ($city) use ($majorCities) {
            return in_array($city['name'], $majorCities);
        })->values();
    }

    /**
     * تجميع المدن حسب المنطقة (للعرض المنظم)
     */
    public static function getCitiesByRegion()
    {
        $cities = self::getAllCities();
        
        // تجميع المدن حسب المنطقة الجغرافية
        $regions = [
            'المنطقة الوسطى' => [
                'الرياض', 'الخرج', 'المجمعة', 'المزاحمية', 'وادي الدواسر',
                'الدوادمي', 'عفيف', 'القويعية', 'حوطة بني تميم', 'الأفلاج',
                'السليل', 'ضرما', 'شقراء', 'رماح', 'ثادق', 'حريملاء'
            ],
            'منطقة القصيم' => [
                'بريدة', 'عنيزة', 'الرس', 'المذنب', 'البكيرية', 'البدائع',
                'رياض الخبراء', 'عيون الجواء', 'الأسياح', 'النبهانية', 'ضرية', 'عقلة الصقور'
            ],
            'المنطقة الغربية' => [
                'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الطائف', 'ينبع', 'رابغ',
                'الليث', 'القنفذة', 'عسفان', 'أملج', 'الوجه', 'ضباء', 'تيماء',
                'خيبر', 'العلا', 'بدر', 'المهد', 'الحناكية'
            ],
            'المنطقة الشرقية' => [
                'الدمام', 'الخبر', 'الظهران', 'الأحساء', 'الجبيل', 'القطيف',
                'حفر الباطن', 'سيهات', 'الخفجي', 'الصفوى', 'راس تنورة', 'بقيق',
                'النعيرية', 'تاروت', 'العديد', 'قرية العليا'
            ],
            'المنطقة الشمالية' => [
                'تبوك', 'حائل', 'عرعر', 'سكاكا', 'الجوف', 'طريف', 'طبرجل',
                'القريات', 'رفحاء', 'العيساوية', 'الشنان', 'بقعاء', 'الغزالة',
                'موقق', 'الشملي'
            ],
            'المنطقة الجنوبية' => [
                'أبها', 'خميس مشيط', 'جازان', 'نجران', 'بيشة', 'الباحة', 'بلجرشي',
                'محايل عسير', 'النماص', 'صبيا', 'أبو عريش', 'صامطة', 'الدرب',
                'فرسان', 'الحرث', 'ضمد', 'بيش', 'العارضة', 'أحد رفيدة',
                'ظهران الجنوب', 'سراة عبيدة', 'رجال ألمع', 'تنومة', 'بارق',
                'المندق', 'قلوة', 'العقيق', 'المخواة', 'غامد الزناد', 'شرورة',
                'حبونا', 'ثار', 'يدمة', 'خباش'
            ]
        ];

        $groupedCities = [];
        
        foreach ($regions as $regionName => $regionCities) {
            $groupedCities[$regionName] = $cities->filter(function ($city) use ($regionCities) {
                return in_array($city['name'], $regionCities);
            })->values();
        }

        return $groupedCities;
    }
}
