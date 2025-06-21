<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Subscriber;
use App\Models\Package;
use App\Models\Dependent;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class SubscriberTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // إنشاء باقة للاختبار
        $this->package = Package::factory()->create([
            'name' => 'باقة تجريبية',
            'price' => 500.00,
            'dependent_price' => 100.00,
            'duration_months' => 12
        ]);
    }

    /** @test */
    public function it_can_create_subscriber_with_valid_data()
    {
        $subscriber = Subscriber::factory()->create([
            'name' => 'أحمد محمد علي',
            'phone' => '0501234567',
            'email' => 'ahmed@example.com',
            'city' => 'الرياض',
            'nationality' => 'سعودي',
            'id_number' => '1234567890',
            'package_id' => $this->package->id
        ]);

        $this->assertInstanceOf(Subscriber::class, $subscriber);
        $this->assertEquals('أحمد محمد علي', $subscriber->name);
        $this->assertEquals('0501234567', $subscriber->phone);
        $this->assertEquals('الرياض', $subscriber->city);
    }

    /** @test */
    public function it_generates_unique_card_number()
    {
        $subscriber1 = Subscriber::factory()->create(['id_number' => '1234567890', 'phone' => '0501234567']);
        $subscriber2 = Subscriber::factory()->create(['id_number' => '1234567891', 'phone' => '0501234568']);

        $this->assertNotEquals($subscriber1->card_number, $subscriber2->card_number);
        $this->assertNotEmpty($subscriber1->card_number);
        $this->assertNotEmpty($subscriber2->card_number);
    }

    /** @test */
    public function it_belongs_to_package()
    {
        $subscriber = Subscriber::factory()->create(['package_id' => $this->package->id]);

        $this->assertInstanceOf(Package::class, $subscriber->package);
        $this->assertEquals($this->package->id, $subscriber->package->id);
    }

    /** @test */
    public function it_has_many_dependents()
    {
        $subscriber = Subscriber::factory()->create();
        
        $dependent1 = Dependent::factory()->create(['subscriber_id' => $subscriber->id]);
        $dependent2 = Dependent::factory()->create(['subscriber_id' => $subscriber->id]);

        $this->assertCount(2, $subscriber->dependents);
        $this->assertInstanceOf(Dependent::class, $subscriber->dependents->first());
    }

    /** @test */
    public function it_has_many_payments()
    {
        $subscriber = Subscriber::factory()->create();
        
        $payment1 = Payment::factory()->create(['subscriber_id' => $subscriber->id]);
        $payment2 = Payment::factory()->create(['subscriber_id' => $subscriber->id]);

        $this->assertCount(2, $subscriber->payments);
        $this->assertInstanceOf(Payment::class, $subscriber->payments->first());
    }

    /** @test */
    public function it_can_scope_active_subscribers()
    {
        $activeSubscriber = Subscriber::factory()->create(['status' => 'فعال']);
        $inactiveSubscriber = Subscriber::factory()->create(['status' => 'منتهي']);

        $activeSubscribers = Subscriber::active()->get();

        $this->assertCount(1, $activeSubscribers);
        $this->assertEquals($activeSubscriber->id, $activeSubscribers->first()->id);
    }

    /** @test */
    public function it_can_scope_expired_subscribers()
    {
        $expiredSubscriber = Subscriber::factory()->create([
            'status' => 'منتهي',
            'end_date' => Carbon::yesterday()
        ]);
        
        $activeSubscriber = Subscriber::factory()->create([
            'status' => 'فعال',
            'end_date' => Carbon::tomorrow()
        ]);

        $expiredSubscribers = Subscriber::expired()->get();

        $this->assertCount(1, $expiredSubscribers);
        $this->assertEquals($expiredSubscriber->id, $expiredSubscribers->first()->id);
    }

    /** @test */
    public function it_can_scope_by_nationality()
    {
        $saudiSubscriber = Subscriber::factory()->create(['nationality' => 'سعودي']);
        $egyptianSubscriber = Subscriber::factory()->create(['nationality' => 'مصري']);

        $saudiSubscribers = Subscriber::byNationality('سعودي')->get();

        $this->assertCount(1, $saudiSubscribers);
        $this->assertEquals($saudiSubscriber->id, $saudiSubscribers->first()->id);
    }

    /** @test */
    public function it_can_scope_by_package()
    {
        $package2 = Package::factory()->create();
        
        $subscriber1 = Subscriber::factory()->create(['package_id' => $this->package->id]);
        $subscriber2 = Subscriber::factory()->create(['package_id' => $package2->id]);

        $packageSubscribers = Subscriber::byPackage($this->package->id)->get();

        $this->assertCount(1, $packageSubscribers);
        $this->assertEquals($subscriber1->id, $packageSubscribers->first()->id);
    }

    /** @test */
    public function it_can_scope_by_city()
    {
        $riyadhSubscriber = Subscriber::factory()->create(['city' => 'الرياض']);
        $jeddahSubscriber = Subscriber::factory()->create(['city' => 'جدة']);

        $riyadhSubscribers = Subscriber::byCity('الرياض')->get();

        $this->assertCount(1, $riyadhSubscribers);
        $this->assertEquals($riyadhSubscriber->id, $riyadhSubscribers->first()->id);
    }

    /** @test */
    public function it_calculates_total_amount_correctly()
    {
        $subscriber = Subscriber::factory()->create([
            'package_id' => $this->package->id,
            'dependents_count' => 2
        ]);

        $expectedTotal = $this->package->price + (2 * $this->package->dependent_price);
        $this->assertEquals($expectedTotal, $subscriber->calculateTotalAmount());
    }

    /** @test */
    public function it_applies_discount_correctly()
    {
        $subscriber = Subscriber::factory()->create([
            'package_id' => $this->package->id,
            'dependents_count' => 1,
            'discount_percentage' => 10
        ]);

        $baseAmount = $this->package->price + $this->package->dependent_price;
        $expectedTotal = $baseAmount - ($baseAmount * 0.10);
        
        $this->assertEquals($expectedTotal, $subscriber->calculateTotalAmountWithDiscount());
    }

    /** @test */
    public function it_checks_if_subscription_is_expired()
    {
        $expiredSubscriber = Subscriber::factory()->create([
            'end_date' => Carbon::yesterday()
        ]);
        
        $activeSubscriber = Subscriber::factory()->create([
            'end_date' => Carbon::tomorrow()
        ]);

        $this->assertTrue($expiredSubscriber->is_expired);
        $this->assertFalse($activeSubscriber->is_expired);
    }

    /** @test */
    public function it_calculates_days_remaining_correctly()
    {
        $subscriber = Subscriber::factory()->create([
            'end_date' => Carbon::now()->addDays(30)
        ]);

        $this->assertEquals(30, $subscriber->days_remaining);
    }

    /** @test */
    public function it_formats_phone_number_correctly()
    {
        $subscriber = Subscriber::factory()->create(['phone' => '501234567']);

        $this->assertEquals('0501234567', $subscriber->formatted_phone);
    }

    /** @test */
    public function it_validates_saudi_phone_number()
    {
        $validPhones = ['0501234567', '0551234567', '0561234567'];
        $invalidPhones = ['0401234567', '1234567890', '05012345'];

        foreach ($validPhones as $phone) {
            $subscriber = Subscriber::factory()->make(['phone' => $phone]);
            $this->assertTrue($subscriber->isValidSaudiPhone());
        }

        foreach ($invalidPhones as $phone) {
            $subscriber = Subscriber::factory()->make(['phone' => $phone]);
            $this->assertFalse($subscriber->isValidSaudiPhone());
        }
    }

    /** @test */
    public function it_validates_national_id_number()
    {
        $validIds = ['1234567890', '2234567890'];
        $invalidIds = ['3234567890', '123456789', '12345678901'];

        foreach ($validIds as $id) {
            $subscriber = Subscriber::factory()->make(['id_number' => $id]);
            $this->assertTrue($subscriber->isValidIdNumber());
        }

        foreach ($invalidIds as $id) {
            $subscriber = Subscriber::factory()->make(['id_number' => $id]);
            $this->assertFalse($subscriber->isValidIdNumber());
        }
    }

    /** @test */
    public function it_can_renew_subscription()
    {
        $subscriber = Subscriber::factory()->create([
            'end_date' => Carbon::yesterday(),
            'status' => 'منتهي'
        ]);

        $newEndDate = Carbon::now()->addYear();
        $subscriber->renewSubscription($newEndDate);

        $this->assertEquals('فعال', $subscriber->status);
        $this->assertEquals($newEndDate->toDateString(), $subscriber->end_date->toDateString());
    }

    /** @test */
    public function it_can_suspend_subscription()
    {
        $subscriber = Subscriber::factory()->create(['status' => 'فعال']);

        $subscriber->suspendSubscription('مخالفة الشروط');

        $this->assertEquals('معلق', $subscriber->status);
        $this->assertStringContains('مخالفة الشروط', $subscriber->notes);
    }

    /** @test */
    public function it_can_cancel_subscription()
    {
        $subscriber = Subscriber::factory()->create(['status' => 'فعال']);

        $subscriber->cancelSubscription('طلب العميل');

        $this->assertEquals('ملغي', $subscriber->status);
        $this->assertStringContains('طلب العميل', $subscriber->notes);
    }
}
