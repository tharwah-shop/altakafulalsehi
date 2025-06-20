<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    /**
     * Display a listing of the packages.
     */
    public function index(Request $request)
    {
        $query = Package::query();

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب الباقات المميزة
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured === '1');
        }

        // فلترة حسب نطاق السعر
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // ترتيب النتائج
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        if (in_array($sortBy, ['name', 'price', 'duration_months', 'status', 'created_at', 'sort_order'])) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('sort_order')->orderBy('name');
        }

        $packages = $query->paginate(15)->withQueryString();

        // إحصائيات سريعة
        $stats = [
            'total' => Package::count(),
            'active' => Package::where('status', 'active')->count(),
            'featured' => Package::where('is_featured', true)->count(),
            'subscribers_count' => Subscriber::whereNotNull('package_id')->count(),
        ];

        return view('admin.packages.index', compact('packages', 'stats'));
    }

    /**
     * Show the form for creating a new package.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created package in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:packages,name',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:999999.99',
            'dependent_price' => 'nullable|numeric|min:0|max:999999.99',
            'duration_months' => 'required|integer|min:1|max:120',
            'max_dependents' => 'required|integer|min:0|max:50',
            'features' => 'nullable|array',
            'features.*' => 'string|max:500',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,draft',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:100',
        ], [
            'name.required' => 'اسم الباقة مطلوب',
            'name.unique' => 'اسم الباقة موجود مسبقاً',
            'price.required' => 'سعر الباقة مطلوب',
            'price.numeric' => 'سعر الباقة يجب أن يكون رقماً',
            'price.min' => 'سعر الباقة لا يمكن أن يكون أقل من صفر',
            'duration_months.required' => 'مدة الاشتراك مطلوبة',
            'duration_months.integer' => 'مدة الاشتراك يجب أن تكون رقماً صحيحاً',
            'duration_months.min' => 'مدة الاشتراك يجب أن تكون شهر واحد على الأقل',
            'max_dependents.required' => 'عدد التابعين مطلوب',
            'max_dependents.integer' => 'عدد التابعين يجب أن يكون رقماً صحيحاً',
            'status.required' => 'حالة الباقة مطلوبة',
            'status.in' => 'حالة الباقة غير صحيحة',
            'color.regex' => 'لون الباقة يجب أن يكون بصيغة hex صحيحة (مثل: #007bff)',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // تحضير البيانات
            $data = $request->only([
                'name', 'name_en', 'description', 'description_en', 'price', 
                'dependent_price', 'duration_months', 'max_dependents', 
                'discount_percentage', 'status', 'color', 'icon'
            ]);

            $data['is_featured'] = $request->has('is_featured');
            $data['sort_order'] = $request->sort_order ?? 0;
            $data['features'] = $request->features ? array_filter($request->features) : [];

            // إنشاء الباقة
            $package = Package::create($data);

            return redirect()->route('admin.packages.index')
                ->with('success', 'تم إنشاء الباقة بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الباقة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified package.
     */
    public function show(Package $package)
    {
        // إحصائيات الباقة
        $stats = [
            'subscribers_count' => $package->subscribers()->count(),
            'active_subscribers' => $package->subscribers()->where('status', 'فعال')->count(),
            'expired_subscribers' => $package->subscribers()->where('status', 'منتهي')->count(),
            'total_revenue' => $package->subscribers()->sum('card_price'),
            'dependents_count' => $package->subscribers()->withCount('dependents')->get()->sum('dependents_count'),
        ];

        // أحدث المشتركين
        $recentSubscribers = $package->subscribers()
            ->with(['city.region'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.packages.show', compact('package', 'stats', 'recentSubscribers'));
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified package in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('packages')->ignore($package->id)],
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:999999.99',
            'dependent_price' => 'nullable|numeric|min:0|max:999999.99',
            'duration_months' => 'required|integer|min:1|max:120',
            'max_dependents' => 'required|integer|min:0|max:50',
            'features' => 'nullable|array',
            'features.*' => 'string|max:500',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,draft',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:100',
        ], [
            'name.required' => 'اسم الباقة مطلوب',
            'name.unique' => 'اسم الباقة موجود مسبقاً',
            'price.required' => 'سعر الباقة مطلوب',
            'price.numeric' => 'سعر الباقة يجب أن يكون رقماً',
            'duration_months.required' => 'مدة الاشتراك مطلوبة',
            'max_dependents.required' => 'عدد التابعين مطلوب',
            'status.required' => 'حالة الباقة مطلوبة',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // تحضير البيانات
            $data = $request->only([
                'name', 'name_en', 'description', 'description_en', 'price', 
                'dependent_price', 'duration_months', 'max_dependents', 
                'discount_percentage', 'status', 'color', 'icon'
            ]);

            $data['is_featured'] = $request->has('is_featured');
            $data['sort_order'] = $request->sort_order ?? 0;
            $data['features'] = $request->features ? array_filter($request->features) : [];

            // تحديث الباقة
            $package->update($data);

            return redirect()->route('admin.packages.index')
                ->with('success', 'تم تحديث الباقة بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الباقة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified package from storage.
     */
    public function destroy(Package $package)
    {
        try {
            // التحقق من وجود مشتركين مرتبطين بالباقة
            $subscribersCount = $package->subscribers()->count();
            
            if ($subscribersCount > 0) {
                return redirect()->back()
                    ->with('error', "لا يمكن حذف هذه الباقة لأنها مرتبطة بـ {$subscribersCount} مشترك");
            }

            $package->delete();

            return redirect()->route('admin.packages.index')
                ->with('success', 'تم حذف الباقة بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الباقة: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the status of the package.
     */
    public function toggleStatus(Package $package)
    {
        try {
            $newStatus = $package->status === 'active' ? 'inactive' : 'active';
            $package->update(['status' => $newStatus]);

            $message = $newStatus === 'active' ? 'تم تفعيل الباقة' : 'تم إيقاف الباقة';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تغيير حالة الباقة: ' . $e->getMessage());
        }
    }

    /**
     * Toggle featured status of the package.
     */
    public function toggleFeatured(Package $package)
    {
        try {
            $package->update(['is_featured' => !$package->is_featured]);

            $message = $package->is_featured ? 'تم إضافة الباقة للمميزة' : 'تم إزالة الباقة من المميزة';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تغيير حالة الباقة المميزة: ' . $e->getMessage());
        }
    }
}
