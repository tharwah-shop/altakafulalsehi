<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscriber;
use App\Models\Package;
use App\Models\City;
use App\Models\User;
use Carbon\Carbon;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على البيانات المرجعية
        $packages = Package::all();
        $cities = City::all();
        $adminUser = User::first();

        if ($packages->isEmpty() || $cities->isEmpty()) {
            $this->command->warn('يجب تشغيل PackageSeeder و CitySeeder أولاً');
            return;
        }

        $subscribers = [
            [
                'name' => 'أحمد محمد العلي',
                'phone' => '0501234567',
                'email' => 'ahmed.ali@email.com',
                'city_id' => $cities->where('name', 'الرياض')->first()?->id,
                'nationality' => 'سعودي',
                'id_number' => '1234567890',
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(10),
                'package_id' => $packages->where('name', 'الباقة الذهبية')->first()?->id,
                'card_price' => 499.00,
                'status' => 'فعال',
                'source' => 'موقع إلكتروني',
                'created_by' => $adminUser?->id,
            ],
            [
                'name' => 'فاطمة سعد الغامدي',
                'phone' => '0507654321',
                'email' => 'fatima.ghamdi@email.com',
                'city_id' => $cities->where('name', 'جدة')->first()?->id,
                'nationality' => 'سعودي',
                'id_number' => '2345678901',
                'start_date' => Carbon::now()->subMonth(),
                'end_date' => Carbon::now()->addMonths(8),
                'package_id' => $packages->where('name', 'الباقة المميزة')->first()?->id,
                'card_price' => 349.00,
                'status' => 'فعال',
                'source' => 'إعلان',
                'created_by' => $adminUser?->id,
            ],
            [
                'name' => 'خالد عبدالله الشمري',
                'phone' => '0551234567',
                'email' => 'khalid.shamri@email.com',
                'city_id' => $cities->where('name', 'الدمام')->first()?->id,
                'nationality' => 'سعودي',
                'id_number' => '3456789012',
                'start_date' => Carbon::now()->subWeeks(2),
                'end_date' => Carbon::now()->addMonths(5),
                'package_id' => $packages->where('name', 'الباقة الأساسية')->first()?->id,
                'card_price' => 199.00,
                'status' => 'فعال',
                'source' => 'توصية',
                'created_by' => $adminUser?->id,
            ],
            [
                'name' => 'نورا أحمد القحطاني',
                'phone' => '0567890123',
                'email' => 'nora.qahtani@email.com',
                'city_id' => $cities->where('name', 'أبها')->first()?->id,
                'nationality' => 'سعودي',
                'id_number' => '4567890123',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addYear(),
                'package_id' => $packages->where('name', 'باقة العائلة')->first()?->id,
                'card_price' => 799.00,
                'status' => 'فعال',
                'source' => 'وسائل التواصل الاجتماعي',
                'created_by' => $adminUser?->id,
            ],
            [
                'name' => 'محمد عبدالرحمن الدوسري',
                'phone' => '0512345678',
                'email' => 'mohammed.dosari@email.com',
                'city_id' => $cities->where('name', 'تبوك')->first()?->id,
                'nationality' => 'سعودي',
                'id_number' => '5678901234',
                'start_date' => Carbon::now()->subYear(),
                'end_date' => Carbon::now()->subMonths(2),
                'package_id' => $packages->where('name', 'الباقة الأساسية')->first()?->id,
                'card_price' => 199.00,
                'status' => 'منتهي',
                'source' => 'موقع إلكتروني',
                'created_by' => $adminUser?->id,
            ],
            [
                'name' => 'سارة علي الزهراني',
                'phone' => '0598765432',
                'email' => 'sara.zahrani@email.com',
                'city_id' => $cities->where('name', 'الطائف')->first()?->id,
                'nationality' => 'سعودي',
                'id_number' => '6789012345',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'package_id' => $packages->where('name', 'باقة الطلاب')->first()?->id,
                'card_price' => 149.00,
                'status' => 'فعال',
                'source' => 'موقع إلكتروني',
                'created_by' => $adminUser?->id,
            ],
        ];

        foreach ($subscribers as $subscriberData) {
            if ($subscriberData['city_id'] && $subscriberData['package_id']) {
                // توليد رقم البطاقة
                $cardNumber = Subscriber::generateCardNumber(
                    $subscriberData['id_number'],
                    $subscriberData['phone']
                );

                // التأكد من عدم تكرار رقم البطاقة
                while (Subscriber::where('card_number', $cardNumber)->exists()) {
                    $cardNumber = Subscriber::generateCardNumber(
                        $subscriberData['id_number'],
                        $subscriberData['phone']
                    );
                }

                $subscriberData['card_number'] = $cardNumber;

                $subscriber = Subscriber::firstOrCreate(
                    ['phone' => $subscriberData['phone']],
                    $subscriberData
                );

                // إضافة بعض التابعين للمشتركين
                if ($subscriber->package && $subscriber->package->name === 'باقة العائلة') {
                    $subscriber->dependents()->firstOrCreate([
                        'name' => 'عبدالله نورا القحطاني',
                        'nationality' => 'سعودي',
                        'id_number' => '1111111111',
                        'dependent_price' => 99.00,
                    ]);

                    $subscriber->dependents()->firstOrCreate([
                        'name' => 'مريم نورا القحطاني',
                        'nationality' => 'سعودي',
                        'id_number' => '2222222222',
                        'dependent_price' => 99.00,
                    ]);
                }
            }
        }
    }
}
