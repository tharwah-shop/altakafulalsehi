<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalCenter;
use App\Models\User;
use App\Models\MedicalCenterReview;
use App\Models\Package;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // جمع الإحصائيات
        $stats = [
            'medical_centers' => MedicalCenter::count(),
            'users' => User::count(),
            'reviews' => 0, // MedicalCenterReview::count(),
            'offers' => MedicalCenter::where('max_discount', '>', 0)->count(),
            'packages' => Package::count(),
            'active_packages' => Package::where('status', 'active')->count(),
            'subscribers' => Subscriber::count(),
            'active_subscribers' => Subscriber::where('status', 'فعال')->count(),
        ];

        // أحدث المراكز الطبية
        $recentCenters = MedicalCenter::latest()
            ->take(5)
            ->get();

        // أحدث الباقات
        $recentPackages = Package::latest()
            ->take(5)
            ->get();

        // أحدث المشتركين
        $recentSubscribers = Subscriber::with(['package', 'city.region'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentCenters', 'recentPackages', 'recentSubscribers'));
    }
}
