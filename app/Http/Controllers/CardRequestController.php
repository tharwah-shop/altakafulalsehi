<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PotentialCustomer;
use App\Helpers\SaudiCitiesHelper;

class CardRequestController extends Controller
{
    /**
     * Display the card request form
     */
    public function index()
    {
        // الحصول على جميع المدن
        $cities = SaudiCitiesHelper::getAllCities();

        return view('card-request', compact('cities'));
    }

    /**
     * Store the card request and redirect to subscribe
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
        ]);

        // الحصول على معلومات تتبع الزائر من الـ session
        $visitorTracking = session('visitor_tracking', []);

        // إنشاء عميل محتمل جديد
        $potentialCustomer = PotentialCustomer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'status' => 'لم يتم التواصل',
            'source' => $visitorTracking['source'] ?? 'card_request',
            'device_type' => $visitorTracking['device_type'] ?? null,
            'ip_address' => $visitorTracking['ip_address'] ?? $request->ip(),
            'user_agent' => $visitorTracking['user_agent'] ?? $request->userAgent(),
            'referrer_url' => $visitorTracking['referrer_url'] ?? null,
            'landing_page' => $visitorTracking['landing_page'] ?? $request->fullUrl(),
            'utm_source' => $visitorTracking['utm_source'] ?? $request->get('utm_source'),
            'utm_medium' => $visitorTracking['utm_medium'] ?? $request->get('utm_medium'),
            'utm_campaign' => $visitorTracking['utm_campaign'] ?? $request->get('utm_campaign'),
            'utm_term' => $visitorTracking['utm_term'] ?? $request->get('utm_term'),
            'utm_content' => $visitorTracking['utm_content'] ?? $request->get('utm_content'),
        ]);

        // حفظ البيانات في الـ session للاستخدام في صفحة الاشتراك
        session([
            'card_request_data' => [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'city' => $request->city,
                'potential_customer_id' => $potentialCustomer->id,
            ]
        ]);

        // إعادة التوجيه إلى صفحة الاشتراك مع البيانات المعبأة مسبقاً
        return redirect()->route('subscribe')->with('success', 'تم إرسال طلبك بنجاح! يرجى إكمال عملية الاشتراك.');
    }
}
