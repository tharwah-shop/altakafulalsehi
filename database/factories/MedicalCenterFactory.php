<?php

namespace Database\Factories;

use App\Models\MedicalCenter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalCenter>
 */
class MedicalCenterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MedicalCenter::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = [
            'الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'الظهران',
            'تبوك', 'بريدة', 'خميس مشيط', 'حائل', 'نجران', 'الطائف', 'الجبيل', 'ينبع'
        ];

        $regions = [
            'منطقة الرياض', 'منطقة مكة المكرمة', 'المنطقة الشرقية', 'منطقة المدينة المنورة',
            'منطقة القصيم', 'منطقة تبوك', 'منطقة عسير', 'منطقة حائل', 'منطقة نجران'
        ];

        $types = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
        $statuses = ['active', 'inactive', 'pending', 'suspended'];
        $contractStatuses = ['active', 'pending', 'expired', 'suspended', 'terminated'];

        $medicalServiceTypes = [
            'dentistry', 'surgical-procedures', 'laboratory-tests', 'ophthalmology',
            'check-ups', 'medications', 'emergency', 'dermatology', 'pharmacy',
            'orthopedics', 'clinics', 'pregnancy-birth', 'lasik', 'radiology',
            'cosmetics', 'laboratory', 'hospitalization', 'other-services'
        ];

        $name = $this->faker->randomElement([
            'مستشفى الملك فهد',
            'مركز الرعاية الطبية',
            'عيادات الشفاء',
            'مجمع العيون الطبي',
            'مركز الأسنان المتخصص',
            'مستشفى الحياة',
            'مركز التشخيص الطبي',
            'عيادات النور',
            'مجمع الصحة الشاملة',
            'مركز الطب التخصصي'
        ]) . ' - ' . $this->faker->randomElement($cities);

        return [
            'name' => $name,
            'slug' => \Str::slug($name),
            'description' => $this->faker->paragraph(3),
            'region' => $this->faker->randomElement($regions),
            'city' => $this->faker->randomElement($cities),
            'address' => $this->faker->address(),
            'latitude' => $this->faker->latitude(20, 32),
            'longitude' => $this->faker->longitude(34, 55),
            'phone' => '966' . $this->faker->numerify('#########'),
            'email' => $this->faker->safeEmail(),
            'website' => $this->faker->optional(0.7)->url(),
            'type' => $this->faker->randomElement($types),
            'medical_service_types' => $this->faker->randomElements($medicalServiceTypes, $this->faker->numberBetween(2, 6)),
            'medical_discounts' => $this->generateMedicalDiscounts(),
            'status' => $this->faker->randomElement($statuses),
            'contract_status' => $this->faker->optional(0.8)->randomElement($contractStatuses),
            'contract_start_date' => $this->faker->optional(0.8)->dateTimeBetween('-2 years', 'now'),
            'contract_end_date' => $this->faker->optional(0.8)->dateTimeBetween('now', '+2 years'),
            'image' => null, // سيتم تعيينها في الاختبارات حسب الحاجة
            'location' => $this->faker->optional(0.6)->url(),
            'rating' => $this->faker->randomFloat(2, 1, 5),
            'reviews_count' => $this->faker->numberBetween(0, 100),
            'views_count' => $this->faker->numberBetween(0, 1000),
            'created_by' => User::factory(),
        ];
    }

    /**
     * إنشاء خصومات طبية وهمية
     */
    private function generateMedicalDiscounts(): array
    {
        $services = [
            'كشف عام', 'كشف تخصصي', 'تحاليل دم', 'أشعة سينية', 'أشعة مقطعية',
            'رنين مغناطيسي', 'تخطيط قلب', 'تنظير', 'عمليات صغرى', 'عمليات كبرى',
            'علاج طبيعي', 'أسنان', 'تقويم أسنان', 'زراعة أسنان', 'عيون',
            'جلدية', 'نساء وولادة', 'أطفال', 'باطنة', 'جراحة'
        ];

        $discounts = [];
        $numberOfDiscounts = $this->faker->numberBetween(2, 8);

        for ($i = 0; $i < $numberOfDiscounts; $i++) {
            $discounts[] = [
                'service' => $this->faker->randomElement($services),
                'discount' => $this->faker->randomElement([
                    '10%', '15%', '20%', '25%', '30%', '50%',
                    '100 ريال', '200 ريال', '500 ريال',
                    'خصم 20% على الكشف الأول',
                    'مجاني للأطفال تحت 12 سنة'
                ])
            ];
        }

        return $discounts;
    }

    /**
     * مركز طبي نشط
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * مركز طبي غير نشط
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * مركز طبي مع صورة
     */
    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image' => 'medical-centers/' . $this->faker->uuid() . '.jpg',
        ]);
    }

    /**
     * مركز طبي بدون صورة
     */
    public function withoutImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image' => null,
        ]);
    }

    /**
     * مركز طبي مع تقييم عالي
     */
    public function highRated(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->randomFloat(2, 4.0, 5.0),
            'reviews_count' => $this->faker->numberBetween(50, 200),
        ]);
    }

    /**
     * مركز طبي مع تقييم منخفض
     */
    public function lowRated(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->randomFloat(2, 1.0, 2.5),
            'reviews_count' => $this->faker->numberBetween(5, 30),
        ]);
    }

    /**
     * مركز طبي في الرياض
     */
    public function inRiyadh(): static
    {
        return $this->state(fn (array $attributes) => [
            'city' => 'الرياض',
            'region' => 'منطقة الرياض',
        ]);
    }

    /**
     * مركز طبي في جدة
     */
    public function inJeddah(): static
    {
        return $this->state(fn (array $attributes) => [
            'city' => 'جدة',
            'region' => 'منطقة مكة المكرمة',
        ]);
    }

    /**
     * مستشفى عام
     */
    public function generalHospital(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => '1',
        ]);
    }

    /**
     * عيادة تخصصية
     */
    public function specialtyClinic(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => '2',
        ]);
    }
}
