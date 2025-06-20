<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'الباقة الأساسية',
                'name_en' => 'Basic Package',
                'description' => 'باقة اقتصادية تشمل الخدمات الأساسية مع خصومات جيدة',
                'description_en' => 'Economical package with basic services and good discounts',
                'price' => 199.00,
                'dependent_price' => 99.00,
                'duration_months' => 6,
                'max_dependents' => 2,
                'features' => [
                    'خصومات حتى 30% على الخدمات الطبية',
                    'تغطية الكشف العام',
                    'خصم على التحاليل المخبرية',
                    'دعم فني 24/7',
                    'صالح لمدة 6 أشهر'
                ],
                'discount_percentage' => 30.00,
                'status' => 'active',
                'is_featured' => false,
                'sort_order' => 1,
                'color' => '#28a745',
                'icon' => 'fas fa-shield-alt',
            ],
            [
                'name' => 'الباقة المميزة',
                'name_en' => 'Premium Package',
                'description' => 'باقة متوسطة تشمل خدمات إضافية وخصومات أكبر',
                'description_en' => 'Medium package with additional services and bigger discounts',
                'price' => 349.00,
                'dependent_price' => 149.00,
                'duration_months' => 9,
                'max_dependents' => 4,
                'features' => [
                    'خصومات حتى 50% على الخدمات الطبية',
                    'تغطية الكشف العام والتخصصي',
                    'خصم على التحاليل والأشعة',
                    'استشارات طبية مجانية',
                    'دعم فني متقدم 24/7',
                    'صالح لمدة 9 أشهر'
                ],
                'discount_percentage' => 50.00,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 2,
                'color' => '#007bff',
                'icon' => 'fas fa-star',
            ],
            [
                'name' => 'الباقة الذهبية',
                'name_en' => 'Gold Package',
                'description' => 'الباقة الأفضل مع أقصى خصومات وخدمات شاملة',
                'description_en' => 'The best package with maximum discounts and comprehensive services',
                'price' => 499.00,
                'dependent_price' => 199.00,
                'duration_months' => 12,
                'max_dependents' => 6,
                'features' => [
                    'خصومات حتى 80% على الخدمات الطبية',
                    'تغطية شاملة لجميع التخصصات',
                    'تحاليل وأشعة مجانية',
                    'استشارات طبية مجانية غير محدودة',
                    'خدمة طوارئ 24/7',
                    'برنامج صحة وقائية',
                    'صالح لمدة سنة كاملة'
                ],
                'discount_percentage' => 80.00,
                'status' => 'active',
                'is_featured' => true,
                'sort_order' => 3,
                'color' => '#ffc107',
                'icon' => 'fas fa-crown',
            ],
            [
                'name' => 'باقة العائلة',
                'name_en' => 'Family Package',
                'description' => 'باقة مخصصة للعائلات الكبيرة بأسعار مخفضة',
                'description_en' => 'Special package for large families with reduced prices',
                'price' => 799.00,
                'dependent_price' => 99.00,
                'duration_months' => 12,
                'max_dependents' => 10,
                'features' => [
                    'خصومات حتى 60% على الخدمات الطبية',
                    'تغطية شاملة لجميع أفراد العائلة',
                    'خصومات خاصة للأطفال وكبار السن',
                    'برامج صحية وقائية للعائلة',
                    'استشارات عائلية مجانية',
                    'دعم فني مخصص للعائلة',
                    'صالح لمدة سنة كاملة'
                ],
                'discount_percentage' => 60.00,
                'status' => 'active',
                'is_featured' => false,
                'sort_order' => 4,
                'color' => '#17a2b8',
                'icon' => 'fas fa-users',
            ],
            [
                'name' => 'باقة الطلاب',
                'name_en' => 'Student Package',
                'description' => 'باقة مخصصة للطلاب والشباب بأسعار مناسبة',
                'description_en' => 'Special package for students and youth with affordable prices',
                'price' => 149.00,
                'dependent_price' => 75.00,
                'duration_months' => 6,
                'max_dependents' => 1,
                'features' => [
                    'خصومات حتى 40% على الخدمات الطبية',
                    'تغطية الخدمات الأساسية',
                    'استشارات طبية مخفضة',
                    'برامج صحية للشباب',
                    'دعم فني في أوقات الدراسة',
                    'صالح لمدة 6 أشهر'
                ],
                'discount_percentage' => 40.00,
                'status' => 'active',
                'is_featured' => false,
                'sort_order' => 5,
                'color' => '#6f42c1',
                'icon' => 'fas fa-graduation-cap',
            ],
        ];

        foreach ($packages as $package) {
            Package::firstOrCreate(
                ['name' => $package['name']],
                $package
            );
        }
    }
}
