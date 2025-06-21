<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Package;
use App\Models\Subscriber;
use App\Models\PendingSubscription;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SubscriptionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $package;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->package = Package::factory()->create([
            'name' => 'باقة أساسية',
            'price' => 500.00,
            'dependent_price' => 100.00,
            'status' => 'active'
        ]);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    /** @test */
    public function complete_subscription_workflow_with_bank_transfer()
    {
        // الخطوة 1: عرض صفحة الاشتراك
        $response = $this->get(route('subscribe'));
        $response->assertStatus(200);
        $response->assertSee('اختر باقتك');

        // الخطوة 2: إرسال بيانات الاشتراك
        $subscriptionData = [
            'name' => 'أحمد محمد علي',
            'phone' => '0501234567',
            'email' => 'ahmed@example.com',
            'city' => 'الرياض',
            'nationality' => 'سعودي',
            'id_number' => '1234567890',
            'package_id' => $this->package->id,
            'payment_method' => 'bank_transfer',
            'dependents' => [
                [
                    'name' => 'فاطمة أحمد',
                    'nationality' => 'سعودي',
                    'id_number' => '2234567890'
                ]
            ]
        ];

        $response = $this->post(route('subscription.store'), $subscriptionData);
        $response->assertRedirect();
        $response->assertSessionHas('subscription_data');

        // التحقق من إنشاء اشتراك معلق
        $this->assertDatabaseHas('pending_subscriptions', [
            'phone' => '0501234567',
            'payment_method' => 'bank_transfer'
        ]);

        // الخطوة 3: صفحة التحويل البنكي
        $response = $this->get(route('bank-transfer'));
        $response->assertStatus(200);
        $response->assertSee('تفاصيل التحويل البنكي');

        // الخطوة 4: رفع إيصال التحويل
        Storage::fake('public');
        $receiptFile = UploadedFile::fake()->image('receipt.jpg');

        $transferData = [
            'transfer_amount' => 600.00,
            'sender_name' => 'أحمد محمد علي',
            'bank_name' => 'البنك الأهلي',
            'receipt_file' => $receiptFile,
            'notes' => 'تحويل اشتراك جديد'
        ];

        $response = $this->post(route('bank-transfer.store'), $transferData);
        $response->assertRedirect(route('thankyou'));

        // التحقق من إنشاء سجل الدفع
        $this->assertDatabaseHas('payments', [
            'amount' => 600.00,
            'payment_method' => 'bank_transfer',
            'status' => 'pending_verification',
            'sender_name' => 'أحمد محمد علي'
        ]);

        // الخطوة 5: تأكيد الدفع من الإدارة
        $payment = Payment::where('sender_name', 'أحمد محمد علي')->first();
        
        $response = $this->actingAs($this->admin)
            ->post(route('admin.payments.verify', $payment));

        $response->assertRedirect();

        // التحقق من إنشاء المشترك
        $this->assertDatabaseHas('subscribers', [
            'name' => 'أحمد محمد علي',
            'phone' => '0501234567',
            'status' => 'فعال'
        ]);

        // التحقق من إنشاء التابع
        $subscriber = Subscriber::where('phone', '0501234567')->first();
        $this->assertDatabaseHas('dependents', [
            'subscriber_id' => $subscriber->id,
            'name' => 'فاطمة أحمد'
        ]);

        // التحقق من تحديث حالة الدفع
        $payment->refresh();
        $this->assertEquals('completed', $payment->status);
        $this->assertNotNull($payment->verified_at);
    }

    /** @test */
    public function subscription_validation_prevents_duplicate_phone()
    {
        // إنشاء مشترك موجود
        Subscriber::factory()->create(['phone' => '0501234567']);

        $subscriptionData = [
            'name' => 'محمد أحمد',
            'phone' => '0501234567', // رقم مكرر
            'email' => 'mohammed@example.com',
            'city' => 'جدة',
            'nationality' => 'سعودي',
            'id_number' => '1234567891',
            'package_id' => $this->package->id,
            'payment_method' => 'bank_transfer'
        ];

        $response = $this->post(route('subscription.store'), $subscriptionData);
        $response->assertSessionHasErrors('phone');
    }

    /** @test */
    public function subscription_validation_prevents_duplicate_id_number()
    {
        // إنشاء مشترك موجود
        Subscriber::factory()->create(['id_number' => '1234567890']);

        $subscriptionData = [
            'name' => 'محمد أحمد',
            'phone' => '0501234568',
            'email' => 'mohammed@example.com',
            'city' => 'جدة',
            'nationality' => 'سعودي',
            'id_number' => '1234567890', // رقم هوية مكرر
            'package_id' => $this->package->id,
            'payment_method' => 'bank_transfer'
        ];

        $response = $this->post(route('subscription.store'), $subscriptionData);
        $response->assertSessionHasErrors('id_number');
    }

    /** @test */
    public function bank_transfer_requires_valid_receipt_file()
    {
        // إنشاء اشتراك معلق
        $pendingSubscription = PendingSubscription::factory()->create([
            'session_id' => session()->getId()
        ]);

        session(['subscription_data' => $pendingSubscription->toArray()]);

        $transferData = [
            'transfer_amount' => 500.00,
            'sender_name' => 'أحمد محمد',
            'bank_name' => 'البنك الأهلي',
            'receipt_file' => 'invalid-file', // ملف غير صحيح
        ];

        $response = $this->post(route('bank-transfer.store'), $transferData);
        $response->assertSessionHasErrors('receipt_file');
    }

    /** @test */
    public function payment_verification_creates_subscriber_correctly()
    {
        // إنشاء اشتراك معلق
        $pendingSubscription = PendingSubscription::factory()->create([
            'name' => 'سارة أحمد',
            'phone' => '0551234567',
            'package_id' => $this->package->id,
            'total_amount' => 500.00,
            'dependents' => json_encode([
                ['name' => 'علي سارة', 'nationality' => 'سعودي']
            ])
        ]);

        // إنشاء دفعة معلقة
        $payment = Payment::factory()->create([
            'amount' => 500.00,
            'payment_method' => 'bank_transfer',
            'status' => 'pending_verification'
        ]);

        // ربط الدفعة بالاشتراك المعلق
        session(['pending_subscription_id' => $pendingSubscription->id]);

        // تأكيد الدفع
        $response = $this->actingAs($this->admin)
            ->post(route('admin.payments.verify', $payment));

        $response->assertRedirect();

        // التحقق من إنشاء المشترك
        $subscriber = Subscriber::where('phone', '0551234567')->first();
        $this->assertNotNull($subscriber);
        $this->assertEquals('سارة أحمد', $subscriber->name);
        $this->assertEquals('فعال', $subscriber->status);

        // التحقق من إنشاء التابع
        $this->assertDatabaseHas('dependents', [
            'subscriber_id' => $subscriber->id,
            'name' => 'علي سارة'
        ]);

        // التحقق من ربط الدفعة بالمشترك
        $payment->refresh();
        $this->assertEquals($subscriber->id, $payment->subscriber_id);
        $this->assertEquals('completed', $payment->status);
    }

    /** @test */
    public function subscription_calculates_total_amount_correctly()
    {
        $subscriptionData = [
            'name' => 'خالد محمد',
            'phone' => '0561234567',
            'email' => 'khalid@example.com',
            'city' => 'الدمام',
            'nationality' => 'سعودي',
            'id_number' => '1234567892',
            'package_id' => $this->package->id,
            'payment_method' => 'bank_transfer',
            'dependents' => [
                ['name' => 'أحمد خالد', 'nationality' => 'سعودي'],
                ['name' => 'فاطمة خالد', 'nationality' => 'سعودي']
            ]
        ];

        $response = $this->post(route('subscription.store'), $subscriptionData);

        // التحقق من حساب المبلغ الإجمالي
        $expectedTotal = $this->package->price + (2 * $this->package->dependent_price); // 500 + (2 * 100) = 700

        $pendingSubscription = PendingSubscription::where('phone', '0561234567')->first();
        $this->assertEquals($expectedTotal, $pendingSubscription->total_amount);
    }

    /** @test */
    public function expired_pending_subscriptions_are_cleaned_up()
    {
        // إنشاء اشتراك معلق قديم
        $oldPendingSubscription = PendingSubscription::factory()->create([
            'created_at' => now()->subDays(2)
        ]);

        // إنشاء اشتراك معلق حديث
        $newPendingSubscription = PendingSubscription::factory()->create([
            'created_at' => now()->subHours(1)
        ]);

        // تشغيل أمر التنظيف
        $this->artisan('subscription:cleanup-pending');

        // التحقق من حذف الاشتراك القديم وبقاء الحديث
        $this->assertDatabaseMissing('pending_subscriptions', ['id' => $oldPendingSubscription->id]);
        $this->assertDatabaseHas('pending_subscriptions', ['id' => $newPendingSubscription->id]);
    }

    /** @test */
    public function card_request_redirects_to_subscription_with_prefilled_data()
    {
        $cardRequestData = [
            'name' => 'نورا سالم',
            'phone' => '0571234567',
            'email' => 'nora@example.com',
            'city' => 'مكة'
        ];

        $response = $this->post(route('card-request.store'), $cardRequestData);
        $response->assertRedirect(route('subscribe'));

        // التحقق من حفظ البيانات في الجلسة
        $this->assertEquals('نورا سالم', session('prefilled_data.name'));
        $this->assertEquals('0571234567', session('prefilled_data.phone'));

        // التحقق من إنشاء عميل محتمل
        $this->assertDatabaseHas('potential_customers', [
            'name' => 'نورا سالم',
            'phone' => '0571234567',
            'source' => 'card_request'
        ]);
    }
}
