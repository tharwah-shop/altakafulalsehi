<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalCenterReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = MedicalCenterReview::with(['user', 'medicalCenter'])
            ->latest()
            ->paginate(15);
            
        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(MedicalCenterReview $review)
    {
        return view('admin.reviews.show', compact('review'));
    }

    public function destroy(MedicalCenterReview $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'تم حذف التقييم بنجاح');
    }
}
