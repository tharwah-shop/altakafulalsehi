<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * تسجيل عملية أمنية
     */
    public static function logSecurityEvent($event, $details = [], $level = 'info')
    {
        $logData = [
            'event' => $event,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'timestamp' => now()->toISOString(),
            'details' => $details
        ];

        Log::channel('security')->{$level}($event, $logData);
    }

    /**
     * تسجيل عملية تجارية
     */
    public static function logBusinessEvent($event, $model = null, $action = null, $details = [])
    {
        $logData = [
            'event' => $event,
            'action' => $action,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'ip_address' => Request::ip(),
            'timestamp' => now()->toISOString(),
            'details' => $details
        ];

        if ($model) {
            $logData['model'] = [
                'type' => get_class($model),
                'id' => $model->id ?? null,
                'attributes' => $model->getAttributes()
            ];
        }

        Log::channel('business')->info($event, $logData);
    }

    /**
     * تسجيل عملية دفع
     */
    public static function logPaymentEvent($event, $payment = null, $details = [])
    {
        $logData = [
            'event' => $event,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'ip_address' => Request::ip(),
            'timestamp' => now()->toISOString(),
            'details' => $details
        ];

        if ($payment) {
            $logData['payment'] = [
                'id' => $payment->id ?? null,
                'amount' => $payment->amount ?? null,
                'method' => $payment->payment_method ?? null,
                'status' => $payment->status ?? null,
                'subscriber_id' => $payment->subscriber_id ?? null
            ];
        }

        Log::channel('payments')->info($event, $logData);
    }

    /**
     * تسجيل عملية اشتراك
     */
    public static function logSubscriptionEvent($event, $subscriber = null, $details = [])
    {
        $logData = [
            'event' => $event,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'ip_address' => Request::ip(),
            'timestamp' => now()->toISOString(),
            'details' => $details
        ];

        if ($subscriber) {
            $logData['subscriber'] = [
                'id' => $subscriber->id ?? null,
                'name' => $subscriber->name ?? null,
                'phone' => $subscriber->phone ?? null,
                'card_number' => $subscriber->card_number ?? null,
                'status' => $subscriber->status ?? null,
                'package_id' => $subscriber->package_id ?? null
            ];
        }

        Log::channel('subscriptions')->info($event, $logData);
    }

    /**
     * تسجيل عملية مركز طبي
     */
    public static function logMedicalCenterEvent($event, $medicalCenter = null, $details = [])
    {
        $logData = [
            'event' => $event,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'ip_address' => Request::ip(),
            'timestamp' => now()->toISOString(),
            'details' => $details
        ];

        if ($medicalCenter) {
            $logData['medical_center'] = [
                'id' => $medicalCenter->id ?? null,
                'name' => $medicalCenter->name ?? null,
                'slug' => $medicalCenter->slug ?? null,
                'city' => $medicalCenter->city ?? null,
                'type' => $medicalCenter->type ?? null,
                'status' => $medicalCenter->status ?? null
            ];
        }

        Log::channel('medical_centers')->info($event, $logData);
    }

    /**
     * تسجيل خطأ حرج
     */
    public static function logCriticalError($error, $context = [])
    {
        $logData = [
            'error' => $error,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'timestamp' => now()->toISOString(),
            'context' => $context
        ];

        Log::channel('critical')->error($error, $logData);
    }

    /**
     * تسجيل محاولة تسجيل دخول
     */
    public static function logLoginAttempt($email, $success = false, $details = [])
    {
        $event = $success ? 'login_success' : 'login_failed';
        
        self::logSecurityEvent($event, array_merge([
            'email' => $email,
            'success' => $success
        ], $details));
    }

    /**
     * تسجيل تسجيل خروج
     */
    public static function logLogout($details = [])
    {
        self::logSecurityEvent('logout', $details);
    }

    /**
     * تسجيل تغيير كلمة المرور
     */
    public static function logPasswordChange($details = [])
    {
        self::logSecurityEvent('password_changed', $details);
    }

    /**
     * تسجيل محاولة وصول غير مصرح
     */
    public static function logUnauthorizedAccess($resource, $details = [])
    {
        self::logSecurityEvent('unauthorized_access', array_merge([
            'resource' => $resource
        ], $details), 'warning');
    }

    /**
     * تسجيل عملية حذف
     */
    public static function logDeletion($model, $details = [])
    {
        self::logBusinessEvent('model_deleted', $model, 'delete', $details);
    }

    /**
     * تسجيل عملية إنشاء
     */
    public static function logCreation($model, $details = [])
    {
        self::logBusinessEvent('model_created', $model, 'create', $details);
    }

    /**
     * تسجيل عملية تحديث
     */
    public static function logUpdate($model, $originalAttributes = [], $details = [])
    {
        $changes = [];
        foreach ($model->getDirty() as $key => $newValue) {
            $changes[$key] = [
                'old' => $originalAttributes[$key] ?? null,
                'new' => $newValue
            ];
        }

        self::logBusinessEvent('model_updated', $model, 'update', array_merge([
            'changes' => $changes
        ], $details));
    }

    /**
     * تسجيل عملية استيراد
     */
    public static function logImport($type, $count, $details = [])
    {
        self::logBusinessEvent('data_imported', null, 'import', array_merge([
            'type' => $type,
            'count' => $count
        ], $details));
    }

    /**
     * تسجيل عملية تصدير
     */
    public static function logExport($type, $count, $details = [])
    {
        self::logBusinessEvent('data_exported', null, 'export', array_merge([
            'type' => $type,
            'count' => $count
        ], $details));
    }

    /**
     * تسجيل عملية نسخ احتياطي
     */
    public static function logBackup($type, $success = true, $details = [])
    {
        $event = $success ? 'backup_success' : 'backup_failed';
        
        self::logSecurityEvent($event, array_merge([
            'type' => $type,
            'success' => $success
        ], $details));
    }

    /**
     * تسجيل عملية استعادة
     */
    public static function logRestore($type, $success = true, $details = [])
    {
        $event = $success ? 'restore_success' : 'restore_failed';
        
        self::logSecurityEvent($event, array_merge([
            'type' => $type,
            'success' => $success
        ], $details));
    }

    /**
     * تسجيل تغيير إعدادات النظام
     */
    public static function logSystemSettingsChange($setting, $oldValue, $newValue, $details = [])
    {
        self::logSecurityEvent('system_settings_changed', array_merge([
            'setting' => $setting,
            'old_value' => $oldValue,
            'new_value' => $newValue
        ], $details));
    }

    /**
     * تسجيل عملية تحقق من الدفع
     */
    public static function logPaymentVerification($payment, $verified = true, $details = [])
    {
        $event = $verified ? 'payment_verified' : 'payment_verification_failed';
        
        self::logPaymentEvent($event, $payment, array_merge([
            'verified' => $verified
        ], $details));
    }

    /**
     * تسجيل تفعيل/إلغاء تفعيل اشتراك
     */
    public static function logSubscriptionStatusChange($subscriber, $oldStatus, $newStatus, $details = [])
    {
        self::logSubscriptionEvent('subscription_status_changed', $subscriber, array_merge([
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ], $details));
    }
}
