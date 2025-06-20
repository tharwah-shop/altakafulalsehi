@extends('layouts.frontend')

@section('title', 'العروض والخصومات الطبية')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-success text-white text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">العروض والخصومات الطبية</h1>
        <p class="lead mb-4">اكتشف أفضل العروض والخصومات من شبكة المراكز الطبية المتميزة</p>
    </div>
</section>

<!-- Offers Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse($offers as $offer)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-success">
                    @if($offer->image)
                        <img src="{{ asset('storage/' . $offer->image) }}" class="card-img-top" alt="{{ $offer->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-success d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-percent text-white" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold">{{ $offer->name }}</h5>
                            <span class="badge bg-success fs-6">{{ $offer->max_discount }}% خصم</span>
                        </div>
                        
                        <p class="text-muted mb-2">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ $offer->city }}، {{ $offer->region }}
                        </p>
                        
                        @if($offer->description)
                            <p class="card-text text-muted">{{ Str::limit($offer->description, 100) }}</p>
                        @endif
                        
                        <div class="mb-3">
                            <span class="badge bg-info">{{ ucfirst($offer->type) }}</span>
                            @if($offer->is_available_247)
                                <span class="badge bg-warning text-dark">24/7</span>
                            @endif
                        </div>
                        
                        <!-- عرض الخصومات -->
                        @if($offer->medical_discounts && count($offer->medical_discounts) > 0)
                            <div class="mb-3">
                                <h6 class="fw-bold text-success">الخصومات المتاحة:</h6>
                                @foreach(array_slice($offer->medical_discounts, 0, 3) as $discount)
                                    <div class="d-flex justify-content-between small">
                                        <span>{{ $discount['service'] ?? 'خدمة طبية' }}</span>
                                        <span class="text-success fw-bold">{{ $discount['discount'] ?? '0%' }}</span>
                                    </div>
                                @endforeach
                                @if(count($offer->medical_discounts) > 3)
                                    <small class="text-muted">وخصومات أخرى...</small>
                                @endif
                            </div>
                        @endif
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($offer->rating >= $i)
                                        <i class="bi bi-star-fill"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-muted small">({{ $offer->reviews_count }} تقييم)</span>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('medical-center.detail', $offer->slug) }}" class="btn btn-success">
                                <i class="bi bi-eye me-1"></i>
                                عرض التفاصيل والحصول على الخصم
                            </a>
                            @if($offer->phone)
                                <a href="tel:{{ $offer->phone }}" class="btn btn-outline-success">
                                    <i class="bi bi-telephone me-1"></i>
                                    اتصل للاستفسار
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="py-5">
                    <i class="bi bi-percent text-muted" style="font-size: 4rem;"></i>
                    <h4 class="text-muted mt-3">لا توجد عروض متاحة حالياً</h4>
                    <p class="text-muted">سيتم إضافة عروض جديدة قريباً</p>
                    <a href="{{ route('medical-centers.index') }}" class="btn btn-primary">
                        تصفح المراكز الطبية
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Call to Action -->
@if($offers->count() > 0)
<section class="py-5 bg-light">
    <div class="container text-center">
        <h3 class="fw-bold mb-3">هل تريد المزيد من العروض؟</h3>
        <p class="text-muted mb-4">تصفح جميع المراكز الطبية للعثور على المزيد من الخصومات والعروض المميزة</p>
        <a href="{{ route('medical-centers.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-hospital me-2"></i>
            تصفح جميع المراكز الطبية
        </a>
    </div>
</section>
@endif

<!-- Stats Section -->
<section class="py-5 bg-success text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card border-0 bg-transparent text-white">
                    <div class="card-body">
                        <i class="bi bi-percent" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold mt-2">{{ $offers->count() }}</h3>
                        <p>عرض متاح</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 bg-transparent text-white">
                    <div class="card-body">
                        <i class="bi bi-hospital" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold mt-2">{{ $offers->count() }}</h3>
                        <p>مركز طبي يقدم خصومات</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 bg-transparent text-white">
                    <div class="card-body">
                        <i class="bi bi-star" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold mt-2">{{ $offers->max('max_discount') ?? 0 }}%</h3>
                        <p>أعلى خصم متاح</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
