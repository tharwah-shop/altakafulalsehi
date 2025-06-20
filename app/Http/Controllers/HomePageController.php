<?php

namespace App\Http\Controllers;

use App\Models\MedicalCenter;
use App\Models\Offer;
use App\Models\Package;
use App\Models\Post;
use App\Models\Subscriber;
use App\Models\MedicalCenterReview;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomePageController extends Controller
{
    /**
     * عرض الصفحة الرئيسية مع البيانات الديناميكية
     */
    public function index()
    {
        // الإحصائيات الرئيسية
        $stats = [
            'medical_centers_count' => MedicalCenter::where('status', 'active')->count(),
            'subscribers_count' => Subscriber::where('status', 'فعال')->count(),
            'total_discounts' => MedicalCenter::where('status', 'active')
                ->where('max_discount', '>', 0)
                ->sum('max_discount'),
            'regions_count' => MedicalCenter::where('status', 'active')
                ->distinct('region')
                ->count(),
        ];

        // العروض المميزة (أحدث 3 عروض نشطة)
        $featuredOffers = Offer::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('is_featured', true)
            ->with(['medicalCenter'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // إذا لم توجد عروض مميزة، نأخذ أحدث العروض النشطة
        if ($featuredOffers->isEmpty()) {
            $featuredOffers = Offer::where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->with(['medicalCenter'])
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        }

        // المراكز الطبية المميزة (أحدث 6 مراكز)
        $featuredMedicalCenters = MedicalCenter::where('status', 'active')
            ->with(['reviews' => function($query) {
                $query->where('status', 'approved');
            }])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // الباقات المميزة
        $featuredPackages = Package::where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        // إذا لم توجد باقات مميزة، نأخذ أحدث الباقات
        if ($featuredPackages->isEmpty()) {
            $featuredPackages = Package::where('status', 'active')
                ->orderBy('sort_order')
                ->take(3)
                ->get();
        }

        // آراء العملاء (تقييمات عشوائية مع تقييم عالي)
        $testimonials = MedicalCenterReview::where('status', 'approved')
            ->where('rating', '>=', 4)
            ->with(['medicalCenter'])
            ->inRandomOrder()
            ->take(3)
            ->get();

        // أحدث المنشورات
        $latestPosts = Post::published()
            ->with(['category', 'medicalCenter'])
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('home', compact(
            'stats',
            'featuredOffers',
            'featuredMedicalCenters',
            'featuredPackages',
            'testimonials',
            'latestPosts'
        ));
    }
}
