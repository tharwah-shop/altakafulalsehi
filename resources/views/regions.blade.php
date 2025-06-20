@extends('layouts.frontend')

@section('title', 'المناطق')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/frontend/hero.css') }}">
<!-- Font Awesome (All Styles, including Brands) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@section('content')
    <!-- Enhanced Hero Section -->
    <section class="hero-section">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>

        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    التغطية الجغرافية الشاملة
                </div>

                <h1 class="hero-title">المناطق والمدن المغطاة</h1>

                <p class="hero-description">استكشف التغطية الشاملة لشبكة التكافل الصحي في جميع مناطق المملكة العربية السعودية</p>

                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-white">الرئيسية</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">المناطق</li>
                    </ol>
                </nav>

                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">13</span>
                        <div class="stat-label">منطقة</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $regions->sum('cities_count') }}+</span>
                        <div class="stat-label">مدينة</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $regions->sum('medical_centers_count') }}+</span>
                        <div class="stat-label">مركز طبي</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Search Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold mb-2">
                                    <i class="fas fa-search me-2 text-primary"></i>
                                    البحث في المناطق والمدن
                                </h3>
                                <p class="text-muted">ابحث عن المنطقة أو المدينة التي تريد استكشاف المراكز الطبية فيها</p>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-gradient-primary text-white border-0">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="regionSearch" class="form-control border-0" placeholder="ابحث عن منطقة أو مدينة...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Regions Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <div class="position-relative z-2">
                        <span class="d-inline-block bg-gradient-primary text-white px-4 py-2 rounded-pill mb-3">المناطق المغطاة</span>
                        <h2 class="fw-bold mb-3">استكشف المناطق والمدن المغطاة</h2>
                        <div class="mx-auto mb-4" style="width: 80px; height: 4px; background: linear-gradient(135deg, #00bcd4 0%, #00e676 100%); border-radius: 2px;"></div>
                        <p class="fs-5 text-muted">تغطي شبكة التكافل الصحي جميع مناطق المملكة مع توفر مراكز طبية متميزة في مختلف المدن</p>
                    </div>
                </div>
            </div>

            <div class="row g-4" id="regionsContainer">
                @foreach($regions as $region)
                    <div class="col-lg-4 col-md-6 region-item" data-region="{{ strtolower($region->name) }}">
                        <div class="card border-0 shadow-lg rounded-4 h-100">
                            @if($region->image)
                                <img src="{{ $region->image_url }}" class="card-img-top" alt="{{ $region->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #00bcd4 0%, #00e676 100%);">
                                    <i class="fas fa-map-marked-alt text-white" style="font-size: 3rem;"></i>
                                </div>
                            @endif

                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="badge bg-gradient-info text-white px-3 py-2">
                                        <i class="fas fa-city me-1"></i>
                                        {{ $region->cities_count }} مدينة
                                    </span>
                                    @if($region->is_featured)
                                        <span class="badge bg-gradient-warning text-dark px-3 py-2">
                                            <i class="fas fa-star me-1"></i>
                                            مميز
                                        </span>
                                    @endif
                                </div>

                                <h5 class="fw-bold mb-3">{{ $region->name }}</h5>

                                @if($region->description)
                                    <p class="text-muted mb-3">{{ Str::limit($region->description, 100) }}</p>
                                @endif

                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px; background: linear-gradient(135deg, #00bcd4 0%, #00e676 100%); border-radius: 50%; margin: 0 auto;">
                                            <i class="fas fa-city text-white" style="font-size: 1rem;"></i>
                                        </div>
                                        <h6 class="fw-bold text-primary mb-1">{{ $region->cities_count }}</h6>
                                        <small class="text-muted">مدينة</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px; background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); border-radius: 50%; margin: 0 auto;">
                                            <i class="fas fa-hospital text-white" style="font-size: 1rem;"></i>
                                        </div>
                                        <h6 class="fw-bold text-warning mb-1">{{ $region->medical_centers_count }}</h6>
                                        <small class="text-muted">مركز طبي</small>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <a href="{{ route('region.detail', \Illuminate\Support\Str::slug($region->name)) }}" class="btn btn-gradient-primary d-flex align-items-center justify-content-center gap-2">
                                        <i class="fas fa-eye"></i>
                                        <span>استعراض المنطقة</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Enhanced Kingdom Map Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%);">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <div class="position-relative z-2">
                        <span class="d-inline-block bg-gradient-primary text-white px-4 py-2 rounded-pill mb-3">خريطة التغطية</span>
                        <h3 class="fw-bold mb-3">خريطة تغطية المملكة</h3>
                        <div class="mx-auto mb-4" style="width: 80px; height: 4px; background: linear-gradient(135deg, #00bcd4 0%, #00e676 100%); border-radius: 2px;"></div>
                        <p class="fs-5 text-muted">المراكز الطبية المتعاقدة مع التكافل الصحي موزعة في مختلف مناطق المملكة لضمان سهولة الوصول</p>
                    </div>
                </div>
                <div class="col-lg-10 mx-auto">
                    <div class="card border-0 shadow-lg rounded-4">
                        <div class="card-body p-0">
                            <div class="ratio ratio-21x9">
                                <iframe src="https://www.google.com/maps/d/embed?mid=1iDYh8g4rKTi41McMuC91i8GFv8k&hl=en&ehbc=2E312F" 
                                        width="640" 
                                        height="480" 
                                        class="rounded-4"
                                        allowfullscreen="" 
                                        loading="lazy">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Coverage Info Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-lg rounded-4 bg-gradient-primary text-white">
                        <div class="card-body p-5">
                            <div class="row align-items-center">
                                <div class="col-lg-8 mb-3 mb-lg-0">
                                    <h4 class="fw-bold mb-3">التغطية الشاملة لجميع مناطق المملكة</h4>
                                    <p class="mb-4 opacity-75">نحرص في التكافل الصحي على توفير تغطية شاملة لجميع مناطق المملكة، مع التركيز على تعاقدات مع أفضل المراكز الطبية والمستشفيات في كل منطقة. هدفنا هو تسهيل وصول جميع المشتركين للخدمات الطبية بأفضل الخصومات بغض النظر عن مكان تواجدهم.</p>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.2); border-radius: 50%;">
                                                    <i class="fas fa-check text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">تغطية شاملة</h6>
                                                    <small class="opacity-75">جميع مناطق المملكة</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.2); border-radius: 50%;">
                                                    <i class="fas fa-check text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">مراكز متميزة</h6>
                                                    <small class="opacity-75">أفضل المراكز الطبية</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.2); border-radius: 50%;">
                                                    <i class="fas fa-check text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">خصومات حصرية</h6>
                                                    <small class="opacity-75">أفضل الأسعار</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.2); border-radius: 50%;">
                                                    <i class="fas fa-check text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">خدمة 24/7</h6>
                                                    <small class="opacity-75">متاحة على مدار الساعة</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-lg-end text-center">
                                    <div class="d-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; margin: 0 auto;">
                                        <i class="fas fa-globe-asia text-white" style="font-size: 2.5rem;"></i>
                                    </div>
                                    <a href="{{ route('subscribe') }}" class="btn btn-light d-flex align-items-center justify-content-center gap-2">
                                        <i class="fas fa-plus"></i>
                                        <span>انضم الآن</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('regionSearch');
        const regionItems = document.querySelectorAll('.region-item');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();

                regionItems.forEach(item => {
                    const regionName = item.getAttribute('data-region');
                    if (regionName.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    });
    </script>
@endsection

