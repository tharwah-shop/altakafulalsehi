<?php

use App\Http\Controllers\MedicalCenterController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// معلومات المستخدم المصادق عليه
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API للمراكز الطبية
Route::prefix('medical-centers')->name('api.medical-centers.')->group(function () {
    // عرض جميع المراكز الطبية
    Route::get('/', [MedicalCenterController::class, 'index'])->name('index');
    
    // عرض مركز طبي محدد
    Route::get('/{slug}', [MedicalCenterController::class, 'show'])->name('show');
    
    // البحث في المراكز الطبية
    Route::get('/search/{query}', function ($query) {
        return App\Models\MedicalCenter::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('region', 'like', "%{$query}%")
            ->orWhere('city', 'like', "%{$query}%")
            ->where('status', 'active')
            ->get();
    })->name('search');
    
    // فلترة حسب المنطقة
    Route::get('/region/{region}', function ($region) {
        return App\Models\MedicalCenter::where('region', $region)
            ->where('status', 'active')
            ->get();
    })->name('by-region');
    
    // فلترة حسب المدينة
    Route::get('/city/{city}', function ($city) {
        return App\Models\MedicalCenter::where('city', $city)
            ->where('status', 'active')
            ->get();
    })->name('by-city');
    
    // فلترة حسب النوع
    Route::get('/type/{type}', function ($type) {
        return App\Models\MedicalCenter::where('type', $type)
            ->where('status', 'active')
            ->get();
    })->name('by-type');
    
    // إضافة تقييم
    Route::post('/{medicalCenter}/review', [MedicalCenterController::class, 'review'])->name('review');
});

// API للمنشورات
Route::prefix('posts')->name('api.posts.')->group(function () {
    // عرض جميع المنشورات
    Route::get('/', [PostController::class, 'index'])->name('index');
    
    // عرض منشور محدد
    Route::get('/{slug}', [PostController::class, 'show'])->name('show');
    
    // المنشورات المميزة
    Route::get('/featured/list', function () {
        return App\Models\Post::published()
            ->featured()
            ->with(['category', 'medicalCenter', 'author'])
            ->latest('published_at')
            ->take(10)
            ->get();
    })->name('featured');
    
    // منشورات حسب التصنيف
    Route::get('/category/{categorySlug}', function ($categorySlug) {
        $category = App\Models\PostCategory::where('slug', $categorySlug)->firstOrFail();
        return App\Models\Post::published()
            ->where('category_id', $category->id)
            ->with(['category', 'medicalCenter', 'author'])
            ->latest('published_at')
            ->paginate(12);
    })->name('by-category');
    
    // منشورات حسب المركز الطبي
    Route::get('/medical-center/{centerSlug}', function ($centerSlug) {
        $center = App\Models\MedicalCenter::where('slug', $centerSlug)->firstOrFail();
        return App\Models\Post::published()
            ->where('medical_center_id', $center->id)
            ->with(['category', 'medicalCenter', 'author'])
            ->latest('published_at')
            ->paginate(12);
    })->name('by-medical-center');
});

// API للتصنيفات
Route::get('/categories', function () {
    return App\Models\PostCategory::active()
        ->ordered()
        ->withCount('posts')
        ->get();
})->name('api.categories');

// API للإحصائيات العامة
Route::get('/stats', function () {
    return [
        'medical_centers_count' => App\Models\MedicalCenter::where('status', 'active')->count(),
        'posts_count' => App\Models\Post::published()->count(),
        'reviews_count' => App\Models\MedicalCenterReview::where('status', 'approved')->count(),
        'regions' => App\Models\MedicalCenter::distinct()->pluck('region')->filter()->values(),
        'cities' => App\Models\MedicalCenter::distinct()->pluck('city')->filter()->values(),
        'medical_center_types' => [
            'hospital' => App\Models\MedicalCenter::where('type', 1)->where('status', 'active')->count(),
            'clinic' => App\Models\MedicalCenter::where('type', 2)->where('status', 'active')->count(),
            'medical_center' => App\Models\MedicalCenter::where('type', 3)->where('status', 'active')->count(),
            'lab' => App\Models\MedicalCenter::where('type', 4)->where('status', 'active')->count(),
            'radiology' => App\Models\MedicalCenter::where('type', 5)->where('status', 'active')->count(),
            'dental' => App\Models\MedicalCenter::where('type', 6)->where('status', 'active')->count(),
            'eye_center' => App\Models\MedicalCenter::where('type', 7)->where('status', 'active')->count(),
            'optical' => App\Models\MedicalCenter::where('type', 8)->where('status', 'active')->count(),
            'pharmacy' => App\Models\MedicalCenter::where('type', 9)->where('status', 'active')->count(),
            'cupping' => App\Models\MedicalCenter::where('type', 10)->where('status', 'active')->count(),
            'beauty' => App\Models\MedicalCenter::where('type', 11)->where('status', 'active')->count(),
            'laser' => App\Models\MedicalCenter::where('type', 12)->where('status', 'active')->count(),
        ],
    ];
})->name('api.stats');

// API محمية للمديرين
Route::middleware(['auth:sanctum'])->prefix('admin')->name('api.admin.')->group(function () {
    
    // إدارة المراكز الطبية
    Route::middleware('permission:medical_centers.view')->group(function () {
        Route::apiResource('medical-centers', MedicalCenterController::class);
    });
    
    // إدارة المنشورات
    Route::middleware('permission:posts.view')->group(function () {
        Route::apiResource('posts', PostController::class);
    });
    
    // إدارة التقييمات
    Route::middleware('permission:reviews.view')->group(function () {
        Route::get('/reviews', function () {
            return App\Models\MedicalCenterReview::with(['medicalCenter'])
                ->latest()
                ->paginate(20);
        })->name('reviews.index');
        
        Route::patch('/reviews/{review}/approve', function (App\Models\MedicalCenterReview $review) {
            $review->update(['status' => 'approved']);
            return response()->json(['message' => 'تم الموافقة على التقييم']);
        })->name('reviews.approve')->middleware('permission:reviews.approve');
        
        Route::patch('/reviews/{review}/reject', function (App\Models\MedicalCenterReview $review) {
            $review->update(['status' => 'rejected']);
            return response()->json(['message' => 'تم رفض التقييم']);
        })->name('reviews.reject')->middleware('permission:reviews.approve');
    });
});
