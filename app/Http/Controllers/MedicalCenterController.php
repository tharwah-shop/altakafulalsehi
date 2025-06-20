<?php

namespace App\Http\Controllers;

use App\Models\MedicalCenter;
use App\Models\MedicalCenterReview;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;

class MedicalCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MedicalCenter::query();

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // فلترة حسب المنطقة
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        // فلترة حسب المدينة
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // فلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'active'); // افتراضياً عرض المراكز النشطة فقط
        }

        $medicalCenters = $query->with(['creator', 'reviews'])
                               ->orderBy('created_at', 'desc')
                               ->paginate(12);

        // الحصول على قوائم للفلاتر
        $regions = MedicalCenter::distinct()->pluck('region')->filter();
        $cities = MedicalCenter::distinct()->pluck('city')->filter();
        $types = ['hospital', 'clinic', 'pharmacy', 'lab', 'radiology', 'dental', 'optical', 'physiotherapy', 'other'];

        return view('medical-centers.index', compact('medicalCenters', 'regions', 'cities', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regions = MedicalCenter::distinct()->pluck('region')->filter();
        $cities = MedicalCenter::distinct()->pluck('city')->filter();
        $types = ['hospital', 'clinic', 'pharmacy', 'lab', 'radiology', 'dental', 'optical', 'physiotherapy', 'other'];

        return view('admin.medical-centers.create', compact('regions', 'cities', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:medical_centers,slug',
            'description' => 'nullable|string',
            'region' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'type' => 'required|in:hospital,clinic,pharmacy,lab,radiology,dental,optical,physiotherapy,other',
            'medical_service_types' => 'nullable|array',
            'medical_discounts' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'nullable|url',
        ]);

        // إنشاء slug إذا لم يتم توفيره
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // رفع الصورة
        if ($request->hasFile('image')) {
            // التحقق من صحة الصورة
            $imageErrors = ImageHelper::validateImage($request->file('image'));
            if (!empty($imageErrors)) {
                return back()->withErrors(['image' => $imageErrors])->withInput();
            }

            // رفع وتحسين الصورة
            $validated['image'] = ImageHelper::uploadAndOptimize(
                $request->file('image'),
                'medical-centers',
                [
                    'max_width' => 800,
                    'max_height' => 600,
                    'quality' => 85
                ]
            );
        }

        // إضافة معرف المنشئ
        $validated['created_by'] = auth()->id();

        $medicalCenter = MedicalCenter::create($validated);

        return redirect()->route('medical-centers.show', $medicalCenter)
                        ->with('success', 'تم إنشاء المركز الطبي بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $center = MedicalCenter::where('slug', $slug)
                              ->with(['reviews' => function($query) {
                                  $query->where('status', 'approved')->latest();
                              }])
                              ->firstOrFail();

        // زيادة عدد المشاهدات
        $center->incrementViews();

        return view('medical-center-detail', compact('center'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalCenter $medicalCenter)
    {
        $regions = MedicalCenter::distinct()->pluck('region')->filter();
        $cities = MedicalCenter::distinct()->pluck('city')->filter();
        $types = ['hospital', 'clinic', 'pharmacy', 'lab', 'radiology', 'dental', 'optical', 'physiotherapy', 'other'];

        return view('admin.medical-centers.edit', compact('medicalCenter', 'regions', 'cities', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalCenter $medicalCenter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:medical_centers,slug,' . $medicalCenter->id,
            'description' => 'nullable|string',
            'region' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'type' => 'required|in:hospital,clinic,pharmacy,lab,radiology,dental,optical,physiotherapy,other',
            'medical_service_types' => 'nullable|array',
            'medical_discounts' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'nullable|url',
            'status' => 'required|in:active,inactive,pending,suspended',
        ]);

        // إنشاء slug إذا لم يتم توفيره
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // رفع الصورة الجديدة
        if ($request->hasFile('image')) {
            // التحقق من صحة الصورة
            $imageErrors = ImageHelper::validateImage($request->file('image'));
            if (!empty($imageErrors)) {
                return back()->withErrors(['image' => $imageErrors])->withInput();
            }

            // حذف الصورة القديمة
            if ($medicalCenter->image) {
                ImageHelper::delete($medicalCenter->image);
            }

            // رفع وتحسين الصورة الجديدة
            $validated['image'] = ImageHelper::uploadAndOptimize(
                $request->file('image'),
                'medical-centers',
                [
                    'max_width' => 800,
                    'max_height' => 600,
                    'quality' => 85
                ]
            );
        }

        $medicalCenter->update($validated);

        return redirect()->route('medical-centers.show', $medicalCenter)
                        ->with('success', 'تم تحديث المركز الطبي بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalCenter $medicalCenter)
    {
        // حذف الصورة
        if ($medicalCenter->image) {
            ImageHelper::delete($medicalCenter->image);
        }

        $medicalCenter->delete();

        return redirect()->route('medical-centers.index')
                        ->with('success', 'تم حذف المركز الطبي بنجاح');
    }

    /**
     * إضافة تقييم للمركز الطبي
     */
    public function review(Request $request, MedicalCenter $medicalCenter)
    {
        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:255',
            'reviewer_email' => 'nullable|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $validated['medical_center_id'] = $medicalCenter->id;
        $validated['ip_address'] = $request->ip();

        MedicalCenterReview::create($validated);

        return redirect()->route('medical-center.detail', $medicalCenter->slug)
                        ->with('success', 'تم إرسال تقييمك بنجاح وسيتم مراجعته قريباً');
    }
}
