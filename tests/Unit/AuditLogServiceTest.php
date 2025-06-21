<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AuditLogService;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\Payment;
use App\Models\MedicalCenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AuditLogServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'email' => 'test@example.com'
        ]);
        
        Auth::login($this->user);
    }

    /** @test */
    public function it_logs_security_events_correctly()
    {
        Log::shouldReceive('channel')
            ->with('security')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('user_login', \Mockery::type('array'));

        AuditLogService::logSecurityEvent('user_login', ['ip' => '127.0.0.1']);
    }

    /** @test */
    public function it_logs_business_events_with_model_data()
    {
        $subscriber = Subscriber::factory()->create([
            'name' => 'أحمد محمد',
            'phone' => '0501234567'
        ]);

        Log::shouldReceive('channel')
            ->with('business')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('subscriber_created', \Mockery::on(function ($data) use ($subscriber) {
                return isset($data['model']) && 
                       $data['model']['id'] === $subscriber->id &&
                       $data['model']['type'] === get_class($subscriber);
            }));

        AuditLogService::logBusinessEvent('subscriber_created', $subscriber, 'create');
    }

    /** @test */
    public function it_logs_payment_events_with_payment_details()
    {
        $payment = Payment::factory()->create([
            'amount' => 500.00,
            'payment_method' => 'bank_transfer',
            'status' => 'pending'
        ]);

        Log::shouldReceive('channel')
            ->with('payments')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('payment_created', \Mockery::on(function ($data) use ($payment) {
                return isset($data['payment']) && 
                       $data['payment']['amount'] === 500.00 &&
                       $data['payment']['method'] === 'bank_transfer';
            }));

        AuditLogService::logPaymentEvent('payment_created', $payment);
    }

    /** @test */
    public function it_logs_subscription_events_with_subscriber_details()
    {
        $subscriber = Subscriber::factory()->create([
            'name' => 'فاطمة أحمد',
            'card_number' => 'CARD123456',
            'status' => 'فعال'
        ]);

        Log::shouldReceive('channel')
            ->with('subscriptions')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('subscription_activated', \Mockery::on(function ($data) use ($subscriber) {
                return isset($data['subscriber']) && 
                       $data['subscriber']['name'] === 'فاطمة أحمد' &&
                       $data['subscriber']['card_number'] === 'CARD123456';
            }));

        AuditLogService::logSubscriptionEvent('subscription_activated', $subscriber);
    }

    /** @test */
    public function it_logs_medical_center_events()
    {
        $medicalCenter = MedicalCenter::factory()->create([
            'name' => 'مستشفى الملك فهد',
            'city' => 'الرياض',
            'type' => 1
        ]);

        Log::shouldReceive('channel')
            ->with('medical_centers')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('medical_center_created', \Mockery::on(function ($data) use ($medicalCenter) {
                return isset($data['medical_center']) && 
                       $data['medical_center']['name'] === 'مستشفى الملك فهد' &&
                       $data['medical_center']['city'] === 'الرياض';
            }));

        AuditLogService::logMedicalCenterEvent('medical_center_created', $medicalCenter);
    }

    /** @test */
    public function it_logs_critical_errors_with_context()
    {
        Log::shouldReceive('channel')
            ->with('critical')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('error')
            ->once()
            ->with('database_connection_failed', \Mockery::on(function ($data) {
                return isset($data['error']) && 
                       isset($data['user_id']) &&
                       isset($data['timestamp']);
            }));

        AuditLogService::logCriticalError('database_connection_failed', [
            'database' => 'mysql',
            'host' => 'localhost'
        ]);
    }

    /** @test */
    public function it_logs_login_attempts_correctly()
    {
        Log::shouldReceive('channel')
            ->with('security')
            ->twice()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('login_success', \Mockery::on(function ($data) {
                return $data['details']['email'] === 'test@example.com' &&
                       $data['details']['success'] === true;
            }));

        Log::shouldReceive('info')
            ->once()
            ->with('login_failed', \Mockery::on(function ($data) {
                return $data['details']['email'] === 'wrong@example.com' &&
                       $data['details']['success'] === false;
            }));

        AuditLogService::logLoginAttempt('test@example.com', true);
        AuditLogService::logLoginAttempt('wrong@example.com', false);
    }

    /** @test */
    public function it_logs_logout_events()
    {
        Log::shouldReceive('channel')
            ->with('security')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('logout', \Mockery::type('array'));

        AuditLogService::logLogout(['reason' => 'user_initiated']);
    }

    /** @test */
    public function it_logs_password_changes()
    {
        Log::shouldReceive('channel')
            ->with('security')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('password_changed', \Mockery::type('array'));

        AuditLogService::logPasswordChange(['method' => 'profile_update']);
    }

    /** @test */
    public function it_logs_unauthorized_access_attempts()
    {
        Log::shouldReceive('channel')
            ->with('security')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('warning')
            ->once()
            ->with('unauthorized_access', \Mockery::on(function ($data) {
                return $data['details']['resource'] === 'admin.users.index';
            }));

        AuditLogService::logUnauthorizedAccess('admin.users.index', ['attempted_action' => 'view']);
    }

    /** @test */
    public function it_logs_model_creation_events()
    {
        $subscriber = Subscriber::factory()->create();

        Log::shouldReceive('channel')
            ->with('business')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('model_created', \Mockery::on(function ($data) {
                return $data['action'] === 'create' &&
                       isset($data['model']);
            }));

        AuditLogService::logCreation($subscriber, ['source' => 'admin_panel']);
    }

    /** @test */
    public function it_logs_model_update_events_with_changes()
    {
        $subscriber = Subscriber::factory()->create(['name' => 'الاسم الأصلي']);
        $originalAttributes = $subscriber->getAttributes();
        
        $subscriber->name = 'الاسم الجديد';
        $subscriber->save();

        Log::shouldReceive('channel')
            ->with('business')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('model_updated', \Mockery::on(function ($data) {
                return $data['action'] === 'update' &&
                       isset($data['details']['changes']) &&
                       isset($data['details']['changes']['name']);
            }));

        AuditLogService::logUpdate($subscriber, $originalAttributes);
    }

    /** @test */
    public function it_logs_model_deletion_events()
    {
        $subscriber = Subscriber::factory()->create();

        Log::shouldReceive('channel')
            ->with('business')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('model_deleted', \Mockery::on(function ($data) {
                return $data['action'] === 'delete' &&
                       isset($data['model']);
            }));

        AuditLogService::logDeletion($subscriber, ['reason' => 'admin_request']);
    }

    /** @test */
    public function it_logs_import_export_operations()
    {
        Log::shouldReceive('channel')
            ->with('business')
            ->twice()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('data_imported', \Mockery::on(function ($data) {
                return $data['details']['type'] === 'subscribers' &&
                       $data['details']['count'] === 100;
            }));

        Log::shouldReceive('info')
            ->once()
            ->with('data_exported', \Mockery::on(function ($data) {
                return $data['details']['type'] === 'subscribers' &&
                       $data['details']['count'] === 50;
            }));

        AuditLogService::logImport('subscribers', 100, ['file' => 'subscribers.csv']);
        AuditLogService::logExport('subscribers', 50, ['format' => 'excel']);
    }

    /** @test */
    public function it_logs_payment_verification_events()
    {
        $payment = Payment::factory()->create();

        Log::shouldReceive('channel')
            ->with('payments')
            ->twice()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('payment_verified', \Mockery::type('array'));

        Log::shouldReceive('info')
            ->once()
            ->with('payment_verification_failed', \Mockery::type('array'));

        AuditLogService::logPaymentVerification($payment, true, ['verified_by' => $this->user->id]);
        AuditLogService::logPaymentVerification($payment, false, ['reason' => 'insufficient_amount']);
    }

    /** @test */
    public function it_logs_subscription_status_changes()
    {
        $subscriber = Subscriber::factory()->create();

        Log::shouldReceive('channel')
            ->with('subscriptions')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('subscription_status_changed', \Mockery::on(function ($data) {
                return $data['details']['old_status'] === 'فعال' &&
                       $data['details']['new_status'] === 'معلق';
            }));

        AuditLogService::logSubscriptionStatusChange($subscriber, 'فعال', 'معلق', ['reason' => 'payment_issue']);
    }

    /** @test */
    public function it_includes_user_context_in_all_logs()
    {
        Log::shouldReceive('channel')
            ->with('security')
            ->once()
            ->andReturnSelf();

        Log::shouldReceive('info')
            ->once()
            ->with('test_event', \Mockery::on(function ($data) {
                return $data['user_id'] === $this->user->id &&
                       $data['user_email'] === 'test@example.com' &&
                       isset($data['ip_address']) &&
                       isset($data['timestamp']);
            }));

        AuditLogService::logSecurityEvent('test_event');
    }
}
