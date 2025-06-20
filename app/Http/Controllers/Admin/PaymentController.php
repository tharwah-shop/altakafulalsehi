<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * عرض قائمة المدفوعات
     */
    public function index(Request $request)
    {
        $query = Payment::with(['subscriber.package', 'subscriber.city.region']);

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('sender_name', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%")
                  ->orWhereHas('subscriber', function($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('phone', 'like', "%{$search}%")
                               ->orWhere('card_number', 'like', "%{$search}%");
                  });
            });
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب طريقة الدفع
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // ترتيب النتائج
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        if (in_array($sortBy, ['id', 'amount', 'status', 'payment_method', 'created_at'])) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $payments = $query->paginate(15)->withQueryString();

        // إحصائيات سريعة
        $stats = [
            'total' => Payment::count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'pending_verification' => Payment::where('status', 'pending_verification')->count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'total_amount' => Payment::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * عرض تفاصيل المدفوعة
     */
    public function show(Payment $payment)
    {
        $payment->load(['subscriber.package', 'subscriber.city.region', 'subscriber.dependents', 'verifiedBy']);
        
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * تأكيد الدفع
     */
    public function verify(Request $request, Payment $payment)
    {
        if (!$payment->canBeVerified()) {
            return redirect()->back()
                ->with('error', 'لا يمكن تأكيد هذا الدفع في الوضع الحالي');
        }

        try {
            DB::beginTransaction();

            // إذا لم يكن هناك مشترك مرتبط، نحاول إنشاؤه من البيانات المؤقتة
            if (!$payment->subscriber_id) {
                $pendingSubscription = null;

                // البحث عن البيانات المؤقتة
                if ($payment->notes) {
                    preg_match('/Pending Subscription ID: (\d+)/', $payment->notes, $matches);
                    if (isset($matches[1])) {
                        $pendingSubscription = \App\Models\PendingSubscription::find($matches[1]);
                    }
                }

                if ($pendingSubscription) {
                    // إنشاء المشترك من البيانات المؤقتة
                    $subscriber = $pendingSubscription->convertToSubscriber();

                    // ربط الدفع بالمشترك
                    $payment->update(['subscriber_id' => $subscriber->id]);
                } else {
                    throw new \Exception('لم يتم العثور على بيانات الاشتراك المؤقتة');
                }
            }

            // تأكيد الدفع وتفعيل الاشتراك
            $payment->markAsCompleted(auth()->id());

            DB::commit();

            return redirect()->back()
                ->with('success', 'تم تأكيد الدفع بنجاح وتفعيل الاشتراك');

        } catch (\Exception $e) {
            DB::rollback();

            // تسجيل تفصيلي للخطأ
            \Log::error('Payment Verification Error', [
                'payment_id' => $payment->id,
                'admin_user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // رسالة خطأ واضحة للمستخدم
            $errorMessage = 'حدث خطأ أثناء تأكيد الدفع. ';

            if (str_contains($e->getMessage(), 'pending_subscription')) {
                $errorMessage .= 'لم يتم العثور على بيانات الاشتراك المؤقتة المرتبطة بهذا الدفع.';
            } elseif (str_contains($e->getMessage(), 'subscriber')) {
                $errorMessage .= 'حدث خطأ أثناء إنشاء المشترك من البيانات المؤقتة.';
            } elseif (str_contains($e->getMessage(), 'database') || str_contains($e->getMessage(), 'SQL')) {
                $errorMessage .= 'حدث خطأ في قاعدة البيانات. يرجى المحاولة مرة أخرى لاحقاً.';
            } else {
                $errorMessage .= 'يرجى المحاولة مرة أخرى أو التواصل مع المطور إذا استمرت المشكلة.';
            }

            return redirect()->back()
                ->with('error', $errorMessage);
        }
    }

    /**
     * رفض الدفع
     */
    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $payment->update([
                'status' => 'failed',
                'notes' => ($payment->notes ? $payment->notes . "\n\n" : '') . 
                          'سبب الرفض: ' . $request->rejection_reason . ' - ' . now()->format('Y-m-d H:i:s')
            ]);

            // تحديث حالة المشترك
            if ($payment->subscriber) {
                $payment->subscriber->update([
                    'status' => 'ملغي'
                ]);
            }

            return redirect()->back()
                ->with('success', 'تم رفض الدفع بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء رفض الدفع: ' . $e->getMessage());
        }
    }

    /**
     * تحميل المرفق
     */
    public function downloadReceipt(Payment $payment)
    {
        if (!$payment->receipt_file) {
            abort(404, 'لا يوجد مرفق لهذا الدفع');
        }

        $filePath = storage_path('app/public/' . $payment->receipt_file);
        
        if (!file_exists($filePath)) {
            abort(404, 'الملف غير موجود');
        }

        $fileName = 'receipt_' . $payment->id . '_' . basename($payment->receipt_file);
        
        return response()->download($filePath, $fileName);
    }

    /**
     * حذف المدفوعة
     */
    public function destroy(Payment $payment)
    {
        try {
            // حذف المرفق إن وجد
            if ($payment->receipt_file) {
                Storage::disk('public')->delete($payment->receipt_file);
            }

            $payment->delete();

            return redirect()->route('admin.payments.index')
                ->with('success', 'تم حذف المدفوعة بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف المدفوعة: ' . $e->getMessage());
        }
    }

    /**
     * تصدير المدفوعات
     */
    public function export(Request $request)
    {
        // يمكن إضافة تصدير Excel هنا لاحقاً
        return redirect()->back()
            ->with('info', 'ميزة التصدير ستكون متاحة قريباً');
    }
}
