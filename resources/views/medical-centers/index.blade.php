@extends('layouts.frontend')

@section('title', 'المراكز الطبية')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">المراكز الطبية</h1>
        <p class="lead mb-4">اكتشف شبكتنا الواسعة من المراكز الطبية المتميزة</p>
    </div>
</section>

<!-- Search Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="ابحث عن مركز طبي..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="region" class="form-select">
                            <option value="">كل المناطق</option>
                            @foreach($regions ?? [] as $region)
                                <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>{{ $region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="city" class="form-select">
                            <option value="">كل المدن</option>
                            @foreach($cities ?? [] as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">بحث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Medical Centers -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse($medicalCenters as $center)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm">
                    @if($center->image_url)
                        <img src="{{ $center->image_url }}" class="card-img-top" alt="{{ $center->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-hospital text-white" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $center->name }}</h5>
                        <p class="text-muted mb-2">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ $center->city }}، {{ $center->region }}
                        </p>
                        
                        @if($center->description)
                            <p class="card-text text-muted">{{ Str::limit($center->description, 100) }}</p>
                        @endif
                        
                        <div class="mb-2">
                            <span class="badge bg-info">{{ $center->type_name }}</span>
                            @if($center->max_discount > 0)
                                <span class="badge bg-success">خصم حتى {{ $center->max_discount }}%</span>
                            @endif
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($center->rating >= $i)
                                        <i class="bi bi-star-fill"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-muted small">({{ $center->reviews_count }} تقييم)</span>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('medical-center.show', $center->slug) }}" class="btn btn-primary">
                                <i class="bi bi-eye me-1"></i>
                                عرض التفاصيل
                            </a>
                            @if($center->phone)
                                <a href="tel:{{ $center->phone }}" class="btn btn-outline-success">
                                    <i class="bi bi-telephone me-1"></i>
                                    اتصل الآن
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="py-5">
                    <i class="bi bi-hospital text-muted" style="font-size: 4rem;"></i>
                    <h4 class="text-muted mt-3">لا توجد مراكز طبية</h4>
                    <p class="text-muted">لم يتم العثور على مراكز طبية تطابق معايير البحث</p>
                </div>
            </div>
            @endforelse
        </div>
        
        @if($medicalCenters->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $medicalCenters->links() }}
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <i class="bi bi-hospital text-primary" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold mt-2">{{ $medicalCenters->total() }}</h3>
                        <p class="text-muted">مركز طبي</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <i class="bi bi-geo-alt text-success" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold mt-2">{{ count($regions) }}</h3>
                        <p class="text-muted">منطقة</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <i class="bi bi-building text-warning" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold mt-2">{{ count($cities) }}</h3>
                        <p class="text-muted">مدينة</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <i class="bi bi-star text-info" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold mt-2">{{ count($types) }}</h3>
                        <p class="text-muted">نوع خدمة</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
