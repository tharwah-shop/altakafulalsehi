<?php

namespace Database\Seeders;

use App\Models\MedicalCenter;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@altakaful.com')->first();

        $medicalCenters = [
            [
                'name' => 'مستشفى الملك فيصل التخصصي',
                'slug' => 'king-faisal-specialist-hospital',
                'description' => 'مستشفى متخصص في الطب التخصصي والبحوث الطبية',
                'region' => 'الرياض',
                'city' => 'الرياض',
                'address' => 'شارع الملك فيصل، حي النخيل، الرياض',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'phone' => '+966-11-464-7272',
                'email' => 'info@kfshrc.edu.sa',
                'website' => 'https://www.kfshrc.edu.sa',
                'type' => 'hospital',
                'medical_service_types' => ['جراحة القلب', 'زراعة الأعضاء', 'الأورام', 'طب الأطفال'],
                'medical_discounts' => [
                    ['service' => 'كشف عام', 'discount' => '20%'],
                    ['service' => 'تحاليل مخبرية', 'discount' => '15%'],
                    ['service' => 'أشعة تشخيصية', 'discount' => '25%'],
                ],
                'status' => 'active',
                'max_discount' => 30,
                'created_by' => $adminUser?->id,
            ],
            [
                'name' => 'مجمع عيادات النور الطبي',
                'slug' => 'al-noor-medical-complex',
                'description' => 'مجمع طبي متكامل يقدم خدمات طبية شاملة',
                'region' => 'جدة',
                'city' => 'جدة',
                'address' => 'شارع الأمير سلطان، حي الزهراء، جدة',
                'latitude' => 21.5433,
                'longitude' => 39.1728,
                'phone' => '+966-12-123-4567',
                'email' => 'info@alnoor-medical.com',
                'type' => 'clinic',
                'medical_service_types' => ['طب عام', 'أسنان', 'نساء وولادة', 'أطفال', 'جلدية'],
                'medical_discounts' => [
                    ['service' => 'كشف طبي عام', 'discount' => '25%'],
                    ['service' => 'تنظيف أسنان', 'discount' => '30%'],
                    ['service' => 'فحص نساء وولادة', 'discount' => '20%'],
                ],
                'status' => 'active',
                'max_discount' => 35,
                'created_by' => $adminUser?->id,
            ],
            [
                'name' => 'صيدلية الشفاء',
                'slug' => 'al-shifa-pharmacy',
                'description' => 'صيدلية متكاملة تقدم جميع الأدوية والمستلزمات الطبية',
                'region' => 'الدمام',
                'city' => 'الدمام',
                'address' => 'شارع الملك عبدالعزيز، حي الفيصلية، الدمام',
                'latitude' => 26.4207,
                'longitude' => 50.0888,
                'phone' => '+966-13-987-6543',
                'email' => 'info@alshifa-pharmacy.com',
                'type' => 'pharmacy',
                'medical_service_types' => ['أدوية عامة', 'أدوية مزمنة', 'مستلزمات طبية', 'فيتامينات'],
                'medical_discounts' => [
                    ['service' => 'أدوية عامة', 'discount' => '15%'],
                    ['service' => 'أدوية مزمنة', 'discount' => '20%'],
                    ['service' => 'مستلزمات طبية', 'discount' => '10%'],
                ],
                'status' => 'active',
                'created_by' => $adminUser?->id,
            ],
        ];

        foreach ($medicalCenters as $center) {
            MedicalCenter::firstOrCreate(['slug' => $center['slug']], $center);
        }
    }
}
