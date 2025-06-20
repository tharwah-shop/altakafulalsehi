<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            [
                'name' => 'المنطقة الوسطى',
                'name_en' => 'Central Region',
                'description' => 'تشمل الرياض والمناطق المحيطة بها',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'المنطقة الغربية',
                'name_en' => 'Western Region',
                'description' => 'تشمل مكة المكرمة والمدينة المنورة وجدة والطائف',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'المنطقة الشرقية',
                'name_en' => 'Eastern Region',
                'description' => 'تشمل الدمام والخبر والأحساء والجبيل',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'المنطقة الشمالية',
                'name_en' => 'Northern Region',
                'description' => 'تشمل تبوك وحائل وعرعر والجوف',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'المنطقة الجنوبية',
                'name_en' => 'Southern Region',
                'description' => 'تشمل عسير وجازان ونجران والباحة',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($regions as $region) {
            Region::firstOrCreate(
                ['name' => $region['name']],
                $region
            );
        }
    }
}
