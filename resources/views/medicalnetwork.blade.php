<!-- resources/views/frontend/medical-centers.blade.php -->
@extends('layouts.frontend')

@section('title', 'الشبكة الطبية')

@push('styles')
<style>
.pagination {
    direction: ltr;
}
.pagination .page-link {
    border-radius: 8px;
    margin: 0 2px;
    border: 1px solid #dee2e6;
    color: #6c757d;
}
.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}
.pagination .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #0d6efd;
}
.search-results-info {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
}
.medical-center-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.medical-center-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <span class="badge bg-warning text-dark mb-3"><i class="bi bi-hospital me-2"></i>الشبكة الطبية</span>
        <h1 class="display-4 fw-bold mb-3">الشبكة الطبية</h1>
        <p class="lead mb-4">استمتع بتجربة رعاية صحية فريدة من نوعها مع الشبكة الطبية المعتمدة التي تقدم خدمات ذات جودة عالية مع فرص توفير تصل إلى 80%. احصل على بطاقة طبية مناسبة في أماكن قريبة منك، بدون مفاجآت.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent p-0">
                <li class="breadcrumb-item"><a href="/" class="text-white text-decoration-underline">الرئيسية</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">الشبكة الطبية</li>
            </ol>
        </nav>
    </div>
</section>

<!-- بحث وتصفيه -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form class="row g-2 align-items-center justify-content-center" method="GET" action="{{ route('medicalnetwork') }}">
                    <div class="col-md-3">
                        <input type="text" name="q" class="form-control form-control-lg" placeholder="ابحث عن مركز أو مدينة..." value="{{ request('q') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="city" class="form-select form-select-lg" id="citySelect">
                            <option value="">كل المدن</option>
                            @if(isset($citiesByRegion))
                                @foreach($citiesByRegion as $regionName => $cities)
                                    <optgroup label="{{ $regionName }}">
                                        @foreach($cities as $city)
                                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @endif
                        </select>
                    </div>                    <div class="col-md-2">
                        <select name="type" class="form-select form-select-lg">
                            <option value="">كل الأنواع</option>
                            @foreach($centerTypes ?? [] as $typeId => $typeName)
                                <option value="{{ $typeId }}" {{ request('type') == $typeId ? 'selected' : '' }}>{{ $typeName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success btn-lg w-100"><i class="bi bi-search"></i></button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('medicalnetwork') }}" class="btn btn-outline-secondary btn-lg w-100" title="مسح الفلاتر"><i class="bi bi-arrow-clockwise"></i></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- المناطق -->
<section class="py-4">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="fw-bold mb-4 text-center">
                    <span class="badge bg-info bg-gradient fs-6 mb-2">المناطق</span>
                </h3>
            </div>
        </div>
        <div class="row justify-content-center g-3">
            <div class="col-6 col-md-2 text-center">
                <a href="{{ route('region.detail', 'central-region') }}">
                    <img src="/images/central.jpg" alt="المنطقة الوسطى" class="rounded-circle mb-2" style="width:80px; height:80px; object-fit:cover;">
                    <div class="fw-bold small">المنطقة الوسطى</div>
                </a>
            </div>
            <div class="col-6 col-md-2 text-center">
                <a href="{{ route('region.detail', 'northern-region') }}">
                    <img src="/images/north.jpg" alt="المنطقة الشمالية" class="rounded-circle mb-2" style="width:80px; height:80px; object-fit:cover;">
                    <div class="fw-bold small">المنطقة الشمالية</div>
                </a>
            </div>
            <div class="col-6 col-md-2 text-center">
                <a href="{{ route('region.detail', 'southern-region') }}">
                    <img src="/images/south.jpg" alt="المنطقة الجنوبية" class="rounded-circle mb-2" style="width:80px; height:80px; object-fit:cover;">
                    <div class="fw-bold small">المنطقة الجنوبية</div>
                </a>
            </div>
            <div class="col-6 col-md-2 text-center">
                <a href="{{ route('region.detail', 'western-region') }}">
                    <img src="/images/west.jpg" alt="المنطقة الغربية" class="rounded-circle mb-2" style="width:80px; height:80px; object-fit:cover;">
                    <div class="fw-bold small">المنطقة الغربية</div>
                </a>
            </div>
            <div class="col-6 col-md-2 text-center">
                <a href="{{ route('region.detail', 'eastern-region') }}">
                    <img src="/images/east.jpg" alt="المنطقة الشرقية" class="rounded-circle mb-2" style="width:80px; height:80px; object-fit:cover;">
                    <div class="fw-bold small">المنطقة الشرقية</div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section (شبكة طبية متكاملة) -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row text-center mb-4">
            <div class="col-12">
                <span class="badge bg-primary mb-2">إحصائيات الشبكة</span>
                <h5 class="fw-bold mb-2">شبكة طبية متكاملة</h5>
            </div>
        </div>
        <div class="row g-2 justify-content-center">
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm text-center p-2 small">
                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 bg-primary rounded-circle" style="width:36px; height:36px;">
                        <i class="fas fa-hospital text-white" style="font-size:1rem;"></i>
                    </div>
                    <div class="fw-bold text-primary">{{ $stats['total_centers'] ?? 0 }}+</div>
                    <div class="mb-1">مركز طبي</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm text-center p-2 small">
                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 bg-warning rounded-circle" style="width:36px; height:36px;">
                        <i class="fas fa-city text-white" style="font-size:1rem;"></i>
                    </div>
                    <div class="fw-bold text-warning">{{ $stats['total_cities'] ?? 0 }}+</div>
                    <div class="mb-1">مدينة</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm text-center p-2 small">
                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 bg-success rounded-circle" style="width:36px; height:36px;">
                        <i class="fas fa-stethoscope text-white" style="font-size:1rem;"></i>
                    </div>
                    <div class="fw-bold text-success">{{ $stats['centers_with_discounts'] ?? 0 }}+</div>
                    <div class="mb-1">مركز بخصومات</div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm text-center p-2 small">
                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 bg-info rounded-circle" style="width:36px; height:36px;">
                        <i class="fas fa-users text-white" style="font-size:1rem;"></i>
                    </div>
                    <div class="fw-bold text-info">{{ $stats['total_reviews'] ?? 0 }}+</div>
                    <div class="mb-1">تقييم</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Medical Centers Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <span class="badge bg-primary mb-3">المراكز المتاحة</span>
                <h2 class="fw-bold mb-3">
                    @if(request()->has('q') || request()->has('city'))
                        نتائج البحث - المراكز الطبية
                    @else
                        أحدث المراكز الطبية المضافة
                    @endif
                </h2>
                <div class="mx-auto mb-4 bg-success rounded-pill" style="width: 80px; height: 4px;"></div>
                @if(request()->has('q') || request()->has('city') || request()->has('type'))
                    <p class="fs-6 text-muted">
                        تم العثور على {{ $centers->total() }} مركز طبي
                        @if(request('q'))
                            للبحث عن "{{ request('q') }}"
                        @endif
                        @if(request('city'))
                            في مدينة {{ request('city') }}
                        @endif
                    </p>
                @else
                    <p class="fs-6 text-muted">أكثر من {{ $stats['total_centers'] ?? 0 }} مركز طبي معتمد في جميع أنحاء المملكة</p>
                @endif
            </div>
        </div>

        <!-- Search Results Info -->
        @if(request()->has('q') || request()->has('city') || request()->has('type'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="search-results-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">نتائج البحث</h6>
                            <small class="text-muted">
                                تم العثور على {{ $centers->total() }} مركز طبي من أصل {{ $stats['total_centers'] ?? 0 }}
                            </small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">الترتيب: الأحدث أولاً</small>
                            <a href="{{ route('medicalnetwork') }}" class="btn btn-sm btn-outline-secondary mt-1">
                                <i class="bi bi-arrow-clockwise me-1"></i>مسح الفلاتر
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row g-4 justify-content-center">
            @forelse($centers as $center)
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 medical-center-card hover-card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
            <div class="col-md-2 text-center mb-3 mb-md-0">
                @if($center->image)
                    <img src="{{ $center->image_url }}" alt="{{ $center->name }}"
                         class="rounded-circle border border-3 border-white"
                         style="width: 90px; height: 90px; object-fit: cover;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                @else
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center border border-3 border-white"
                         style="width: 90px; height: 90px;">
                        <i class="bi bi-hospital text-primary fs-2"></i>
                    </div>
                @endif
            </div>
                            <div class="ms-3 text-start">
                                <h5 class="fw-bold mb-1">{{ $center->name }}</h5>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-geo-alt-fill text-info me-1"></i>
                                    <span class="small text-muted">{{ $center->city }}, {{ $center->region }}</span>
                                </div>
                                @if($center->type)
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-hospital text-info me-1"></i>
                                    <span class="small text-muted">{{ $center->type_name }}</span>
                                </div>
                                @endif
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-telephone-fill text-success me-1"></i>
                                    <span class="small text-muted">{{ $center->phone ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="text-warning me-2">
                                @for($i=1; $i<=5; $i++)
                                    @if($center->reviews->avg('rating') >= $i)
                                        <i class="bi bi-star-fill"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-dark fw-bold small">{{ number_format($center->reviews->avg('rating'), 1) }}</span>
                            <span class="text-muted small ms-2">({{ $center->reviews->count() }} تقييم)</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">أنواع الخدمات:</small>
                            <div class="d-flex flex-wrap gap-1">
                                @if(is_array($center->medical_service_types) && count($center->medical_service_types) > 0)
                                    @php
                                        $serviceNames = [
                                            'dentistry' => 'الأسنان',
                                            'surgical-procedures' => 'العمليات الجراحية',
                                            'laboratory-tests' => 'التحاليل',
                                            'ophthalmology' => 'العيون',
                                            'check-ups' => 'الكشوفات',
                                            'medications' => 'الادوية',
                                            'emergency' => 'الطوارئ',
                                            'dermatology' => 'الجلدية',
                                            'pharmacy' => 'الصيدلية',
                                            'orthopedics' => 'العظام',
                                            'clinics' => 'العيادات',
                                            'pregnancy-birth' => 'الحمل والولادة',
                                            'lasik' => 'الليزك',
                                            'radiology' => 'الأشعة',
                                            'cosmetics' => 'التجميل',
                                            'laboratory' => 'المختبر',
                                            'hospitalization' => 'التنويم',
                                            'other-services' => 'خدمات اخرى',
                                        ];
                                    @endphp
                                    @foreach($center->medical_service_types as $service)
                                        <span class="badge bg-primary small">{{ $serviceNames[$service] ?? $service }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">لا توجد بيانات خدمات</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">الخصومات:</small>
                            <div class="d-flex flex-wrap gap-1">
                                @if(is_array($center->medical_discounts))
                                    @foreach($center->medical_discounts as $discount)
                                        <span class="badge bg-success small">{{ $discount['service'] ?? '-' }}: {{ $discount['discount'] ?? '-' }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">لا توجد بيانات خصم</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('medical-centers.show', $center->slug) }}" class="btn btn-outline-primary btn-sm flex-fill"><i class="fas fa-eye me-1"></i>تفاصيل</a>                            <a href="tel:{{ $center->phone }}" class="btn btn-outline-success btn-sm flex-fill"><i class="fas fa-phone me-1"></i>اتصل</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-search fs-1 text-muted"></i>
                </div>
                @if(request()->has('q') || request()->has('city') || request()->has('region') || request()->has('type'))
                    <h4 class="text-muted mb-3">لم يتم العثور على نتائج</h4>
                    <p class="text-muted mb-4">لم نجد أي مراكز طبية تطابق معايير البحث الخاصة بك.</p>
                    <a href="{{ route('medicalnetwork') }}" class="btn btn-primary">مشاهدة جميع المراكز</a>
                @else
                    <h4 class="text-muted mb-3">لا توجد مراكز طبية</h4>
                    <p class="text-muted">لا توجد مراكز طبية متاحة حالياً.</p>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($centers->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                {{ $centers->appends(request()->query())->links('custom.pagination') }}
            </div>
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">انضم إلى شبكتنا الطبية أو تواصل معنا</h2>
        <p class="lead mb-4">للاستفسار أو الانضمام للشبكة الطبية، تواصل معنا مباشرة وسنخدمك بأسرع وقت</p>
        <a href="#" class="btn btn-warning btn-lg px-5 fw-bold">تواصل معنا الآن</a>
    </div>
</section>
@endsection

@push('styles')
<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.medical-center-card img {
    transition: transform 0.3s ease;
}

.medical-center-card:hover img {
    transform: scale(1.05);
}

/* تحسين عرض الصور */
.medical-center-card .position-relative {
    overflow: hidden;
}

.medical-center-card .rounded-circle {
    transition: all 0.3s ease;
}

/* تحسين عرض البطاقات على الشاشات الصغيرة */
@media (max-width: 768px) {
    .medical-center-card {
        margin-bottom: 1rem;
    }

    .medical-center-card .d-flex.align-items-center {
        flex-direction: column;
        text-align: center;
    }

    .medical-center-card .ms-3 {
        margin-left: 0 !important;
        margin-top: 1rem;
    }
}

/* تحسين عرض الشارات */
.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

/* تحسين أزرار العمل */
.btn-sm {
    font-size: 0.8rem;
    padding: 0.375rem 0.75rem;
}

/* تأثير التحميل للصور */
.medical-center-card img[loading="lazy"] {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.medical-center-card img[loading="lazy"].loaded {
    opacity: 1;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحسين تحميل الصور
    const images = document.querySelectorAll('img[loading="lazy"]');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.classList.add('loaded');
        });

        // إذا كانت الصورة محملة بالفعل
        if (img.complete) {
            img.classList.add('loaded');
        }
    });

    // Auto-submit form on filter change
    const filterSelects = document.querySelectorAll('select[name="city"], select[name="region"], select[name="type"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Optional: Auto-submit on filter change
            // this.form.submit();
        });
    });

    // Smooth scroll to results after pagination
    if (window.location.search.includes('page=')) {
        setTimeout(() => {
            const resultsSection = document.querySelector('.search-results-info, .row.g-4');
            if (resultsSection) {
                resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 100);
    }

    // Add loading state to search button
    const searchForm = document.querySelector('form');
    const searchButton = document.querySelector('button[type="submit"]');

    if (searchForm && searchButton) {
        searchForm.addEventListener('submit', function() {
            searchButton.innerHTML = '<i class="bi bi-hourglass-split"></i>';
            searchButton.disabled = true;
        });
    }

    // Add tooltips to filter buttons
    const resetButton = document.querySelector('a[title="مسح الفلاتر"]');
    if (resetButton) {
        resetButton.setAttribute('data-bs-toggle', 'tooltip');
        resetButton.setAttribute('data-bs-placement', 'top');
    }

    // Initialize Bootstrap tooltips if available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Add animation to cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all medical center cards
    const cards = document.querySelectorAll('.medical-center-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});
</script>
@endpush

