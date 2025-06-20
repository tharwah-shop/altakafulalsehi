<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * عرض صفحة التحويل البنكي
     */
    public function bankTransfer(Payment $payment)
    {
        // التحقق من أن الدفع موجود ومن النوع الصحيح
        if ($payment->payment_method !== 'bank_transfer') {
            abort(404, 'طريقة الدفع غير صحيحة');
        }

        // الحصول على البيانات المؤقتة
        $pendingSubscription = null;

        // أولاً: محاولة الحصول على البيانات من notes الدفع (الطريقة الأساسية)
        if ($payment->notes) {
            preg_match('/Pending Subscription ID: (\d+)/', $payment->notes, $matches);
            if (isset($matches[1])) {
                $pendingSubscription = \App\Models\PendingSubscription::with(['package', 'city.region'])
                    ->where('id', $matches[1])
                    ->where('status', 'pending')
                    ->first();

                // تسجيل معلومات للتشخيص
                \Log::info('Found pending subscription from payment notes', [
                    'payment_id' => $payment->id,
                    'pending_subscription_id' => $matches[1],
                    'found' => $pendingSubscription ? 'yes' : 'no'
                ]);
            }
        }

        // ثانياً: محاولة الحصول على البيانات من الجلسة كبديل
        if (!$pendingSubscription) {
            $pendingSubscriptionId = session('pending_subscription_id');
            if ($pendingSubscriptionId) {
                $pendingSubscription = \App\Models\PendingSubscription::with(['package', 'city.region'])
                    ->where('id', $pendingSubscriptionId)
                    ->where('status', 'pending')
                    ->first();

                \Log::info('Found pending subscription from session', [
                    'payment_id' => $payment->id,
                    'pending_subscription_id' => $pendingSubscriptionId,
                    'found' => $pendingSubscription ? 'yes' : 'no'
                ]);
            }
        }

        // التحقق من انتهاء صلاحية البيانات المؤقتة
        if ($pendingSubscription && $pendingSubscription->expires_at < now()) {
            \Log::info('Pending subscription expired', [
                'pending_subscription_id' => $pendingSubscription->id,
                'expired_at' => $pendingSubscription->expires_at,
                'current_time' => now()
            ]);
            $pendingSubscription = null;
        }

        // تسجيل حالة البيانات المؤقتة للتشخيص
        \Log::info('Bank transfer page accessed', [
            'payment_id' => $payment->id,
            'payment_amount' => $payment->amount,
            'payment_status' => $payment->status,
            'payment_notes' => $payment->notes,
            'pending_subscription_found' => $pendingSubscription ? 'yes' : 'no',
            'pending_subscription_id' => $pendingSubscription ? $pendingSubscription->id : null,
            'session_pending_id' => session('pending_subscription_id')
        ]);

        // إعدادات البنك
        $bankConfig = [
            'whatsapp_number' => '966920031304',
            'whatsapp_message' => 'مرحباً، أحتاج مساعدة في عملية التحويل البنكي للاشتراك رقم: ' . $payment->id
        ];

        return view('payment.bank-transfer', compact('payment', 'pendingSubscription', 'bankConfig'));
    }

    /**
     * تأكيد التحويل البنكي وإنشاء المشترك الفعلي
     */
    public function confirmBankTransfer(Request $request, Payment $payment)
    {
        $request->validate([
            'transfer_amount' => 'required|numeric|min:0',
            'sender_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'receipt_file' => 'required|file|mimes:jpeg,png,gif,pdf|max:5120', // 5MB max
            'notes' => 'nullable|string|max:1000'
        ], [
            'transfer_amount.required' => 'يرجى إدخال مبلغ التحويل',
            'transfer_amount.numeric' => 'مبلغ التحويل يجب أن يكون رقماً',
            'transfer_amount.min' => 'مبلغ التحويل يجب أن يكون أكبر من صفر',
            'sender_name.required' => 'يرجى إدخال اسم المرسل',
            'sender_name.max' => 'اسم المرسل لا يجب أن يتجاوز 255 حرف',
            'bank_name.required' => 'يرجى إدخال اسم البنك',
            'receipt_file.required' => 'يرجى رفع إيصال التحويل',
            'receipt_file.file' => 'الملف المرفوع غير صحيح',
            'receipt_file.mimes' => 'نوع الملف غير مدعوم. يرجى رفع صورة أو ملف PDF',
            'receipt_file.max' => 'حجم الملف كبير جداً. الحد الأقصى 5 ميجابايت',
            'notes.max' => 'الملاحظات لا يجب أن تتجاوز 1000 حرف'
        ]);

        try {
            DB::beginTransaction();

            // الحصول على البيانات المؤقتة
            $pendingSubscription = null;

            // أولاً: محاولة الحصول على البيانات من notes الدفع (الطريقة الأساسية)
            if ($payment->notes) {
                preg_match('/Pending Subscription ID: (\d+)/', $payment->notes, $matches);
                if (isset($matches[1])) {
                    $pendingSubscription = \App\Models\PendingSubscription::find($matches[1]);
                }
            }

            // ثانياً: محاولة الحصول على البيانات من الجلسة كبديل
            if (!$pendingSubscription) {
                $pendingSubscriptionId = session('pending_subscription_id');
                if ($pendingSubscriptionId) {
                    $pendingSubscription = \App\Models\PendingSubscription::find($pendingSubscriptionId);
                }
            }

            if (!$pendingSubscription) {
                throw new \Exception('لم يتم العثور على بيانات الاشتراك');
            }

            // رفع ملف الإيصال
            $receiptPath = null;
            if ($request->hasFile('receipt_file')) {
                $receiptPath = $request->file('receipt_file')->store('bank-transfer-receipts', 'public');
            }

            // إنشاء المشترك الفعلي من البيانات المؤقتة
            $subscriber = $pendingSubscription->convertToSubscriber();

            // تحديث بيانات الدفع
            $payment->update([
                'subscriber_id' => $subscriber->id,
                'status' => 'pending_verification',
                'transfer_amount' => $request->transfer_amount,
                'sender_name' => $request->sender_name,
                'bank_name' => $request->bank_name,
                'receipt_file' => $receiptPath,
                'notes' => $request->notes,
                'transfer_confirmed_at' => now()
            ]);

            DB::commit();

            // حفظ معرف المشترك في الجلسة للاستخدام في صفحة الشكر
            session(['completed_subscriber_id' => $subscriber->id]);

            // إزالة البيانات المؤقتة من الجلسة
            session()->forget('pending_subscription_id');

            // إعادة توجيه لصفحة الشكر
            return redirect('/thankyou')
                ->with('success', 'تم تأكيد التحويل وإنشاء الاشتراك بنجاح!');

        } catch (\Exception $e) {
            DB::rollback();

            // تسجيل تفصيلي للخطأ
            \Log::error('Bank Transfer Confirmation Error', [
                'payment_id' => $payment->id,
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'request_data' => $request->except(['receipt_file']),
                'trace' => $e->getTraceAsString()
            ]);

            // رسالة خطأ واضحة للمستخدم
            $errorMessage = 'حدث خطأ أثناء تأكيد التحويل البنكي. ';

            if (str_contains($e->getMessage(), 'pending_subscription')) {
                $errorMessage .= 'لم يتم العثور على بيانات الاشتراك المؤقتة. يرجى إعادة تعبئة نموذج الاشتراك.';
            } elseif (str_contains($e->getMessage(), 'file')) {
                $errorMessage .= 'حدث خطأ في رفع الملف. يرجى التأكد من نوع وحجم الملف المرفوع.';
            } elseif (str_contains($e->getMessage(), 'database') || str_contains($e->getMessage(), 'SQL')) {
                $errorMessage .= 'حدث خطأ في قاعدة البيانات. يرجى المحاولة مرة أخرى لاحقاً.';
            } else {
                $errorMessage .= 'يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني إذا استمرت المشكلة.';
            }

            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
    }
}
