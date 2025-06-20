<?php

namespace Database\Seeders;

use App\Models\PostCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name_ar' => 'أخبار طبية',
                'name_en' => 'Medical News',
                'slug' => 'medical-news',
                'description_ar' => 'آخر الأخبار والتطورات في المجال الطبي',
                'description_en' => 'Latest news and developments in the medical field',
                'color' => '#007bff',
                'icon' => 'fa-newspaper',
                'sort_order' => 1,
            ],
            [
                'name_ar' => 'نصائح صحية',
                'name_en' => 'Health Tips',
                'slug' => 'health-tips',
                'description_ar' => 'نصائح وإرشادات للحفاظ على الصحة',
                'description_en' => 'Tips and guidelines for maintaining health',
                'color' => '#28a745',
                'icon' => 'fa-heart',
                'sort_order' => 2,
            ],
            [
                'name_ar' => 'خدمات طبية',
                'name_en' => 'Medical Services',
                'slug' => 'medical-services',
                'description_ar' => 'معلومات عن الخدمات الطبية المتاحة',
                'description_en' => 'Information about available medical services',
                'color' => '#17a2b8',
                'icon' => 'fa-stethoscope',
                'sort_order' => 3,
            ],
            [
                'name_ar' => 'عروض وخصومات',
                'name_en' => 'Offers & Discounts',
                'slug' => 'offers-discounts',
                'description_ar' => 'العروض والخصومات الطبية المتاحة',
                'description_en' => 'Available medical offers and discounts',
                'color' => '#ffc107',
                'icon' => 'fa-percent',
                'sort_order' => 4,
            ],
            [
                'name_ar' => 'توعية صحية',
                'name_en' => 'Health Awareness',
                'slug' => 'health-awareness',
                'description_ar' => 'مقالات التوعية الصحية والوقاية',
                'description_en' => 'Health awareness and prevention articles',
                'color' => '#6f42c1',
                'icon' => 'fa-lightbulb',
                'sort_order' => 5,
            ],
            [
                'name_ar' => 'أحداث ومؤتمرات',
                'name_en' => 'Events & Conferences',
                'slug' => 'events-conferences',
                'description_ar' => 'الأحداث والمؤتمرات الطبية',
                'description_en' => 'Medical events and conferences',
                'color' => '#dc3545',
                'icon' => 'fa-calendar',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            PostCategory::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
