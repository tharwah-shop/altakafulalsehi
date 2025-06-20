<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\MedicalCenter;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the offers.
     */
    public function index(Request $request)
    {
        $query = Offer::with('medicalCenter');

        // البحث
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('medicalCenter', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب المركز الطبي
        if ($request->filled('medical_center_id')) {
            $query->where('medical_center_id', $request->medical_center_id);
        }

        $offers = $query->latest()->paginate(15);
        $medicalCenters = MedicalCenter::where('status', 'active')->orderBy('name')->get();

        return view('admin.offers.index', compact('offers', 'medicalCenters'));
    }

    /**
     * Show the form for creating a new offer.
     */
    public function create()
    {
        // جمع المراكز الطبية المتاحة
        $medicalCenters = MedicalCenter::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.offers.create', compact('medicalCenters'));
    }

    /**
     * Store a newly created offer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'medical_center_id' => 'required|exists:medical_centers,id',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive,pending',
            'terms_conditions' => 'nullable|string',
            'max_uses' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp|max:5120',
        ]);

        // إنشاء slug
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);

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
                'offers',
                [
                    'max_width' => 800,
                    'max_height' => 600,
                    'quality' => 85
                ]
            );
        }

        // إضافة معرف المستخدم الحالي
        $validated['created_by'] = auth()->id();

        Offer::create($validated);

        return redirect()->route('admin.offers.index')
            ->with('success', 'تم إضافة العرض بنجاح');
    }

    /**
     * Display the specified offer.
     */
    public function show(Offer $offer)
    {
        $offer->load('medicalCenter');
        return view('admin.offers.show', compact('offer'));
    }

    /**
     * Show the form for editing the specified offer.
     */
    public function edit(Offer $offer)
    {
        $medicalCenters = MedicalCenter::where('status', 'active')->orderBy('name')->get();
        return view('admin.offers.edit', compact('offer', 'medicalCenters'));
    }

    /**
     * Update the specified offer in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'medical_center_id' => 'required|exists:medical_centers,id',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive,pending',
            'terms_conditions' => 'nullable|string',
            'max_uses' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp|max:5120',
        ]);

        // تحديث slug إذا تغير العنوان
        if ($offer->title !== $validated['title']) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        }

        // رفع الصورة الجديدة
        if ($request->hasFile('image')) {
            // التحقق من صحة الصورة
            $imageErrors = ImageHelper::validateImage($request->file('image'));
            if (!empty($imageErrors)) {
                return back()->withErrors(['image' => $imageErrors])->withInput();
            }

            // حذف الصورة القديمة
            if ($offer->image) {
                ImageHelper::delete($offer->image);
            }

            // رفع وتحسين الصورة الجديدة
            $validated['image'] = ImageHelper::uploadAndOptimize(
                $request->file('image'),
                'offers',
                [
                    'max_width' => 800,
                    'max_height' => 600,
                    'quality' => 85
                ]
            );
        }

        $offer->update($validated);

        return redirect()->route('admin.offers.index')
            ->with('success', 'تم تحديث العرض بنجاح');
    }

    /**
     * Remove the specified offer from storage.
     */
    public function destroy(Offer $offer)
    {
        // حذف الصورة
        if ($offer->image) {
            ImageHelper::delete($offer->image);
        }

        $offer->delete();

        return redirect()->route('admin.offers.index')
            ->with('success', 'تم حذف العرض بنجاح');
    }

    /**
     * Toggle the offer status.
     */
    public function toggleStatus(Offer $offer)
    {
        $newStatus = $offer->status === 'active' ? 'inactive' : 'active';
        $offer->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? 'تم تفعيل العرض' : 'تم إيقاف العرض';

        return redirect()->back()->with('success', $message);
    }
}
