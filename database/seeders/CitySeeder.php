<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على المناطق
        $centralRegion = Region::where('name', 'المنطقة الوسطى')->first();
        $westernRegion = Region::where('name', 'المنطقة الغربية')->first();
        $easternRegion = Region::where('name', 'المنطقة الشرقية')->first();
        $northernRegion = Region::where('name', 'المنطقة الشمالية')->first();
        $southernRegion = Region::where('name', 'المنطقة الجنوبية')->first();

        $cities = [
            // المنطقة الوسطى
            [
                'name' => 'الرياض',
                'name_en' => 'Riyadh',
                'region_id' => $centralRegion?->id,
                'description' => 'العاصمة والمدينة الأكبر في المملكة',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'الخرج',
                'name_en' => 'Al Kharj',
                'region_id' => $centralRegion?->id,
                'description' => 'مدينة زراعية مهمة جنوب الرياض',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'المجمعة',
                'name_en' => 'Al Majmaah',
                'region_id' => $centralRegion?->id,
                'description' => 'مدينة تاريخية شمال الرياض',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'وادي الدواسر',
                'name_en' => 'Wadi Al Dawasir',
                'region_id' => $centralRegion?->id,
                'description' => 'مدينة في جنوب المنطقة الوسطى',
                'is_active' => true,
                'sort_order' => 4,
            ],

            // المنطقة الغربية
            [
                'name' => 'جدة',
                'name_en' => 'Jeddah',
                'region_id' => $westernRegion?->id,
                'description' => 'عروس البحر الأحمر والبوابة الاقتصادية',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'مكة المكرمة',
                'name_en' => 'Makkah',
                'region_id' => $westernRegion?->id,
                'description' => 'المدينة المقدسة وقبلة المسلمين',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'المدينة المنورة',
                'name_en' => 'Madinah',
                'region_id' => $westernRegion?->id,
                'description' => 'المدينة المنورة ومهد الحضارة الإسلامية',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'الطائف',
                'name_en' => 'Taif',
                'region_id' => $westernRegion?->id,
                'description' => 'مدينة الورود ومصيف المملكة',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'ينبع',
                'name_en' => 'Yanbu',
                'region_id' => $westernRegion?->id,
                'description' => 'المدينة الصناعية على البحر الأحمر',
                'is_active' => true,
                'sort_order' => 5,
            ],

            // المنطقة الشرقية
            [
                'name' => 'الدمام',
                'name_en' => 'Dammam',
                'region_id' => $easternRegion?->id,
                'description' => 'عاصمة المنطقة الشرقية',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'الخبر',
                'name_en' => 'Khobar',
                'region_id' => $easternRegion?->id,
                'description' => 'المدينة التجارية والسياحية',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'الأحساء',
                'name_en' => 'Al Ahsa',
                'region_id' => $easternRegion?->id,
                'description' => 'أكبر واحة في العالم',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'الجبيل',
                'name_en' => 'Jubail',
                'region_id' => $easternRegion?->id,
                'description' => 'المدينة الصناعية الكبرى',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'القطيف',
                'name_en' => 'Qatif',
                'region_id' => $easternRegion?->id,
                'description' => 'مدينة تاريخية على الخليج العربي',
                'is_active' => true,
                'sort_order' => 5,
            ],

            // المنطقة الشمالية
            [
                'name' => 'تبوك',
                'name_en' => 'Tabuk',
                'region_id' => $northernRegion?->id,
                'description' => 'بوابة المملكة الشمالية',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'حائل',
                'name_en' => 'Hail',
                'region_id' => $northernRegion?->id,
                'description' => 'مدينة النخيل والتاريخ',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'عرعر',
                'name_en' => 'Arar',
                'region_id' => $northernRegion?->id,
                'description' => 'عاصمة منطقة الحدود الشمالية',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'سكاكا',
                'name_en' => 'Sakaka',
                'region_id' => $northernRegion?->id,
                'description' => 'عاصمة منطقة الجوف',
                'is_active' => true,
                'sort_order' => 4,
            ],

            // المنطقة الجنوبية
            [
                'name' => 'أبها',
                'name_en' => 'Abha',
                'region_id' => $southernRegion?->id,
                'description' => 'عروس الجبل وعاصمة عسير',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'جازان',
                'name_en' => 'Jazan',
                'region_id' => $southernRegion?->id,
                'description' => 'لؤلؤة الجنوب على البحر الأحمر',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'نجران',
                'name_en' => 'Najran',
                'region_id' => $southernRegion?->id,
                'description' => 'مدينة تاريخية في أقصى الجنوب',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'الباحة',
                'name_en' => 'Al Bahah',
                'region_id' => $southernRegion?->id,
                'description' => 'حديقة الحجاز ومصيف الجنوب',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($cities as $city) {
            if ($city['region_id']) {
                City::firstOrCreate(
                    ['name' => $city['name'], 'region_id' => $city['region_id']],
                    $city
                );
            }
        }
    }
}
