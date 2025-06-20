<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Subscriber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SaudiCitiesHelper;

class SubscriptionController extends Controller
{
    /**
     * عرض صفحة الاشتراك
     */
    public function index(Request $request)
    {
        // الحصول على جميع الباقات النشطة مرتبة
        $packages = Package::active()->ordered()->get();

        // إعطاء أولوية للباقات المميزة
        $packages = $packages->sortByDesc('is_featured')->values();

        $cities = SaudiCitiesHelper::getAllCities();

        // Debug: تحقق من وجود المدن
        \Log::info('Cities count in subscription page: ' . $cities->count());

        // الحصول على الباقة المحددة إن وجدت
        $selectedPackage = null;
        if ($request->has('package')) {
            $selectedPackage = Package::where('id', $request->package)
                                    ->orWhere('name', $request->package)
                                    ->active()
                                    ->first();
        }

        // الحصول على بيانات طلب البطاقة من الـ session
        $cardRequestData = session('card_request_data');

        // إذا كانت هناك بيانات من طلب البطاقة، قم بتعبئتها مسبقاً
        $prefilledData = null;
        if ($cardRequestData) {
            $prefilledData = [
                'name' => $cardRequestData['name'],
                'email' => $cardRequestData['email'],
                'phone' => $cardRequestData['phone'],
                'city' => $cardRequestData['city'],
            ];

            // تحديث حالة العميل المحتمل
            if (isset($cardRequestData['potential_customer_id'])) {
                \App\Models\PotentialCustomer::where('id', $cardRequestData['potential_customer_id'])
                    ->update(['status' => 'تم التواصل']);
            }

            // مسح البيانات من الـ session بعد استخدامها
            session()->forget('card_request_data');
        }

        return view('subscribe', compact('packages', 'cities', 'selectedPackage', 'prefilledData'));
    }

    /**
     * معالجة طلب الاشتراك
     */
    public function store(Request $request)
    {
        \Log::info('Regular Store Request:', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:subscribers,phone',
            'email' => 'nullable|email|unique:subscribers,email',
            'city' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'id_number' => 'required|string|unique:subscribers,id_number',
            'package_id' => 'required|exists:packages,id',

            // بيانات التابعين
            'dependents' => 'nullable|array',
            'dependents.*.name' => 'required_with:dependents|string|max:255',
            'dependents.*.nationality' => 'required_with:dependents|string|max:255',
            'dependents.*.id_number' => 'nullable|string|max:255',
            'dependents.*.relationship' => 'required_with:dependents|string|max:255',
        ], [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الجوال مطلوب',
            'phone.unique' => 'رقم الجوال مسجل مسبقاً',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
            'city.required' => 'المدينة مطلوبة',
            'nationality.required' => 'الجنسية مطلوبة',
            'id_number.required' => 'رقم الهوية مطلوب',
            'id_number.unique' => 'رقم الهوية مسجل مسبقاً',
            'package_id.required' => 'الباقة مطلوبة',
            'package_id.exists' => 'الباقة المحددة غير صحيحة',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // الحصول على الباقة لحساب السعر والتواريخ
            $package = Package::findOrFail($request->package_id);

            // التحقق من حدود التابعين
            $dependentsCount = $request->has('dependents') ? count($request->dependents) : 0;
            if ($package->max_dependents > 0 && $dependentsCount > $package->max_dependents) {
                return redirect()->back()
                    ->with('error', "لا يمكن إضافة أكثر من {$package->max_dependents} تابعين لهذه الباقة")
                    ->withInput();
            }

            // التحقق من دعم الباقة للتابعين
            if ($dependentsCount > 0 && !$package->dependent_price) {
                return redirect()->back()
                    ->with('error', 'هذه الباقة لا تدعم إضافة تابعين')
                    ->withInput();
            }

            // حساب تواريخ الاشتراك
            $startDate = now();
            $endDate = now()->addMonths($package->duration_months);

            // توليد رقم البطاقة
            $cardNumber = Subscriber::generateCardNumber($request->id_number, $request->phone);

            // التأكد من عدم تكرار رقم البطاقة
            while (Subscriber::where('card_number', $cardNumber)->exists()) {
                $cardNumber = Subscriber::generateCardNumber($request->id_number, $request->phone);
            }

            // حساب السعر الإجمالي
            $totalPrice = $package->price;
            if ($dependentsCount > 0 && $package->dependent_price) {
                $totalPrice += ($dependentsCount * $package->dependent_price);
            }

            // إنشاء المشترك
            $subscriber = Subscriber::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'city' => $request->city,
                'nationality' => $request->nationality,
                'id_number' => $request->id_number,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'card_number' => $cardNumber,
                'package_id' => $request->package_id,
                'card_price' => $totalPrice,
                'status' => 'فعال',
            ]);

            // إضافة التابعين
            if ($request->has('dependents') && is_array($request->dependents)) {
                foreach ($request->dependents as $dependentData) {
                    if (!empty($dependentData['name'])) {
                        $subscriber->dependents()->create([
                            'name' => $dependentData['name'],
                            'nationality' => $dependentData['nationality'],
                            'id_number' => $dependentData['id_number'] ?? null,
                            'dependent_price' => $package->dependent_price,
                            'notes' => 'العلاقة: ' . ($dependentData['relationship'] ?? ''),
                        ]);
                    }
                }
            }

            DB::commit();

            // إعادة توجيه لصفحة الدفع أو صفحة النجاح
            return redirect()->route('subscription.success', ['subscriber' => $subscriber->id])
                ->with('success', 'تم إنشاء الاشتراك بنجاح! رقم البطاقة: ' . $cardNumber);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الاشتراك. يرجى المحاولة مرة أخرى.')
                ->withInput();
        }
    }

    /**
     * صفحة نجاح الاشتراك
     */
    public function success(Subscriber $subscriber)
    {
        $subscriber->load(['package', 'dependents']);
        return view('subscription-success', compact('subscriber'));
    }

    /**
     * الحصول على معلومات الباقة (AJAX)
     */
    public function getPackageInfo(Package $package)
    {
        return response()->json([
            'id' => $package->id,
            'name' => $package->name,
            'price' => $package->price,
            'dependent_price' => $package->dependent_price,
            'duration_months' => $package->duration_months,
            'duration_text' => $package->duration_text,
            'features' => $package->features,
            'formatted_price' => $package->formatted_price,
            'formatted_dependent_price' => $package->formatted_dependent_price,
        ]);
    }

    /**
     * الحصول على مدن المنطقة (AJAX)
     */
    public function getCitiesByRegion(Request $request)
    {
        $regionName = $request->input('region_name');
        $cities = SaudiCitiesHelper::getAllCities();

        return response()->json($cities);
    }

    /**
     * معالجة طلب الاشتراك بالتحويل البنكي - حفظ البيانات مؤقتاً
     */
    public function bankTransfer(Request $request)
    {
        \Log::info('Bank Transfer Request:', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'city' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'id_number' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'payment_method' => 'required|in:bank_transfer'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $package = Package::findOrFail($request->package_id);

            // حساب السعر الإجمالي
            $dependentsCount = 0;
            $dependentsData = [];
            if ($request->has('dependents') && is_array($request->dependents)) {
                $dependentsData = array_filter($request->dependents, function($dependent) {
                    return !empty($dependent['name']);
                });
                $dependentsCount = count($dependentsData);
            }

            $totalPrice = $package->calculateTotalPrice($dependentsCount);

            // إنشاء معرف جلسة فريد
            $sessionId = session()->getId() . '_' . time();

            // حفظ البيانات مؤقتاً
            $pendingSubscription = \App\Models\PendingSubscription::create([
                'session_id' => $sessionId,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'city' => $request->city,
                'nationality' => $request->nationality,
                'id_number' => $request->id_number,
                'package_id' => $request->package_id,
                'payment_method' => $request->payment_method,
                'dependents' => $dependentsData,
                'total_amount' => $totalPrice,
                'dependents_count' => $dependentsCount,
                'expires_at' => now()->addHours(24) // انتهاء الصلاحية خلال 24 ساعة
            ]);

            // إنشاء سجل دفع مؤقت
            $payment = \App\Models\Payment::create([
                'subscriber_id' => null, // لا يوجد مشترك بعد
                'amount' => $totalPrice,
                'payment_method' => 'bank_transfer',
                'status' => 'pending',
                'currency' => 'SAR'
            ]);

            // ربط الدفع بالبيانات المؤقتة
            $payment->update(['notes' => 'Pending Subscription ID: ' . $pendingSubscription->id]);

            // حفظ معرف البيانات المؤقتة في الجلسة
            session(['pending_subscription_id' => $pendingSubscription->id]);

            DB::commit();

            // إعادة توجيه لصفحة التحويل البنكي
            return redirect()->route('payment.bank-transfer', ['payment' => $payment->id])
                ->with('success', 'تم حفظ بيانات الاشتراك! يرجى إكمال عملية التحويل البنكي.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.')
                ->withInput();
        }
    }

    /**
     * صفحة الشكر الديناميكية
     */
    public function thankyou()
    {
        // الحصول على معرف المشترك المكتمل من الجلسة
        $subscriberId = session('completed_subscriber_id');

        if (!$subscriberId) {
            // إذا لم يوجد معرف، نعيد توجيه للصفحة الرئيسية
            return redirect()->route('subscribe')
                ->with('error', 'لم يتم العثور على بيانات الاشتراك.');
        }

        // الحصول على بيانات المشترك مع العلاقات
        $subscriber = Subscriber::with(['package', 'dependents', 'payments'])
            ->find($subscriberId);

        if (!$subscriber) {
            return redirect()->route('subscribe')
                ->with('error', 'لم يتم العثور على بيانات المشترك.');
        }

        // الحصول على آخر دفعة
        $latestPayment = $subscriber->payments()->latest()->first();

        // مسح معرف المشترك من الجلسة
        session()->forget('completed_subscriber_id');
        session()->forget('pending_subscription_id');

        return view('thankyou', compact('subscriber', 'latestPayment'));
    }
}
