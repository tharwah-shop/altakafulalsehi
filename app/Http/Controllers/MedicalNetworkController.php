<?php

namespace App\Http\Controllers;

use App\Models\MedicalCenter;
use App\Helpers\CitiesHelper;
use Illuminate\Http\Request;

class MedicalNetworkController extends Controller
{
    /**
     * Display the medical network page with pagination and search
     */
    public function index(Request $request)
    {
        $query = MedicalCenter::query()->where('status', 'active');

        // البحث العام
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('region', 'like', "%{$searchTerm}%")
                  ->orWhere('city', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%");
            });
        }

        // فلترة حسب المدينة
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // فلترة حسب المنطقة
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        // فلترة حسب نوع المركز
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // فلترة حسب الخصومات
        if ($request->filled('has_discount')) {
            $query->where('max_discount', '>', 0);
        }

        // ترتيب النتائج - أحدث المراكز أولاً
        $query->orderBy('created_at', 'desc');

        // الحصول على النتائج مع الترقيم
        $centers = $query->with(['reviews' => function($reviewQuery) {
            $reviewQuery->where('status', 'approved');
        }])->paginate(12);

        // الحصول على قوائم للفلاتر من النظام القديم
        $regions = CitiesHelper::getAllRegions();

        // تجميع المدن حسب المناطق
        $citiesByRegion = [];
        foreach ($regions as $region) {
            $citiesByRegion[$region['name']] = collect($region['cities'])->pluck('name')->toArray();
        }

        // الحصول على جميع المدن للفلترة
        $allCities = CitiesHelper::getAllCities()->pluck('name')->toArray();

        // أنواع المراكز
        $centerTypes = [
            1 => 'مستشفى عام',
            2 => 'عيادة تخصصية',
            3 => 'مركز طبي',
            4 => 'مختبر طبي',
            5 => 'مركز أشعة',
            6 => 'مجمع أسنان',
            7 => 'مركز عيون',
            8 => 'بصريات',
            9 => 'صيدلية',
            10 => 'مركز حجامة',
            11 => 'مركز تجميل',
            12 => 'مركز ليزر'
        ];

        // إحصائيات الشبكة
        $stats = [
            'total_centers' => MedicalCenter::where('status', 'active')->count(),
            'total_cities' => MedicalCenter::where('status', 'active')->distinct('city')->count(),
            'total_regions' => MedicalCenter::where('status', 'active')->distinct('region')->count(),
            'centers_with_discounts' => MedicalCenter::where('status', 'active')->where('max_discount', '>', 0)->count(),
            'total_reviews' => \App\Models\MedicalCenterReview::where('status', 'approved')->count(),
        ];

        // أحدث المراكز المضافة (للعرض في الصفحة الرئيسية)
        $latestCenters = MedicalCenter::where('status', 'active')
            ->with(['reviews' => function($reviewQuery) {
                $reviewQuery->where('status', 'approved');
            }])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('medicalnetwork', compact(
            'centers',
            'regions',
            'citiesByRegion',
            'allCities',
            'centerTypes',
            'stats',
            'latestCenters'
        ));
    }

    /**
     * Get medical centers by region
     */
    public function byRegion(Request $request, $regionSlug)
    {
        // البحث عن المنطقة بمقارنة الـ slug
        $regions = MedicalCenter::select('region')
            ->distinct()
            ->where('status', 'active')
            ->get();

        $regionName = null;
        foreach ($regions as $region) {
            if (\Illuminate\Support\Str::slug($region->region) === $regionSlug) {
                $regionName = $region->region;
                break;
            }
        }

        if (!$regionName) {
            abort(404, 'المنطقة غير موجودة');
        }

        $query = MedicalCenter::where('region', $regionName)
            ->where('status', 'active');

        // البحث
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('city', 'like', "%{$searchTerm}%");
            });
        }

        // فلترة حسب المدينة
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $medicalCenters = $query->with(['reviews' => function($reviewQuery) {
            $reviewQuery->where('status', 'approved');
        }])->orderBy('created_at', 'desc')->paginate(12);

        // المدن في هذه المنطقة
        $cities = MedicalCenter::where('region', $regionName)
            ->where('status', 'active')
            ->distinct('city')
            ->orderBy('city')
            ->pluck('city');

        return view('region-detail', compact('medicalCenters', 'regionName', 'cities'));
    }

    /**
     * Get medical centers by city
     */
    public function byCity(Request $request, $citySlug)
    {
        // البحث عن المدينة بمقارنة الـ slug
        $cities = MedicalCenter::select('city')
            ->distinct()
            ->where('status', 'active')
            ->get();

        $cityName = null;
        foreach ($cities as $city) {
            if (\Illuminate\Support\Str::slug($city->city) === $citySlug) {
                $cityName = $city->city;
                break;
            }
        }

        if (!$cityName) {
            abort(404, 'المدينة غير موجودة');
        }

        $query = MedicalCenter::where('city', $cityName)
            ->where('status', 'active');

        // البحث
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $medicalCenters = $query->with(['reviews' => function($reviewQuery) {
            $reviewQuery->where('status', 'approved');
        }])->orderBy('created_at', 'desc')->paginate(12);

        // الحصول على المنطقة
        $regionName = MedicalCenter::where('city', $cityName)
            ->where('status', 'active')
            ->value('region');

        return view('city-detail', compact('medicalCenters', 'cityName', 'regionName'));
    }
}
