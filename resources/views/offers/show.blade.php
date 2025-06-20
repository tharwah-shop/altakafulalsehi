@extends('layouts.frontend')

@section('title', 'تفاصيل العرض - ' . $offer->name)

@section('content')
    <!-- Hero Section -->
    <section class="bg-primary text-white py-5 mb-4">
        <div class="container">
            <div class="text-center">
                <span class="badge bg-warning text-dark mb-3"><i class="fas fa-gift me-2"></i> تفاصيل العرض</span>
                <h1 class="display-5 fw-bold mb-3">{{ $offer->name }}</h1>
                <p class="lead mb-4">استمتع بخصم يصل إلى {{ $offer->max_discount }}% من {{ $offer->name }}</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="/" class="text-white text-decoration-underline">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('offers') }}" class="text-white text-decoration-underline">العروض</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $offer->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <!-- Offer Details Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-hospital text-white fs-3"></i>
                                    </div>
                                    <div>
                                        <h3 class="fw-bold mb-1">{{ $offer->name }}</h3>
                                        <p class="text-muted mb-0">{{ $offer->city }}, {{ $offer->region }}</p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                        <i class="fas fa-percentage me-1"></i>
                                        خصم {{ $offer->max_discount }}%
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-bold mb-3">وصف العرض</h5>
                                <p class="text-muted">{{ $offer->description ?? 'استمتع بخصومات حصرية على جميع الخدمات الطبية المتاحة في هذا المركز الطبي المعتمد.' }}</p>
                            </div>

                            @if($offer->medical_service_types && is_array($offer->medical_service_types))
                            <div class="mb-4">
                                <h5 class="fw-bold mb-3">الخدمات المشمولة بالعرض</h5>
                                <div class="row g-2">
                                    @foreach($offer->medical_service_types as $service)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>{{ $service }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($offer->medical_discounts && is_array($offer->medical_discounts))
                            <div class="mb-4">
                                <h5 class="fw-bold mb-3">تفاصيل الخصومات</h5>
                                <div class="row g-3">
                                    @foreach($offer->medical_discounts as $discount)
                                    <div class="col-md-6">
                                        <div class="card border-success">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="fw-bold">{{ $discount['service'] ?? 'خدمة طبية' }}</span>
                                                    <span class="badge bg-success">{{ $discount['discount'] ?? $offer->max_discount . '%' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($offer->working_hours && is_array($offer->working_hours))
                            <div class="mb-4">
                                <h5 class="fw-bold mb-3">ساعات العمل</h5>
                                <div class="row g-2">
                                    @foreach($offer->working_hours as $day => $hours)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fw-bold">{{ $day }}</span>
                                            <span class="text-muted">
                                                @if(is_array($hours))
                                                    {{ $hours['start'] ?? '' }} - {{ $hours['end'] ?? '' }}
                                                @else
                                                    {{ $hours }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Contact Card -->
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">معلومات الاتصال</h5>
                            
                            @if($offer->phone)
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-phone text-success me-3"></i>
                                <div>
                                    <small class="text-muted d-block">رقم الهاتف</small>
                                    <a href="tel:{{ $offer->phone }}" class="fw-bold text-decoration-none">{{ $offer->phone }}</a>
                                </div>
                            </div>
                            @endif

                            @if($offer->email)
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-envelope text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">البريد الإلكتروني</small>
                                    <a href="mailto:{{ $offer->email }}" class="fw-bold text-decoration-none">{{ $offer->email }}</a>
                                </div>
                            </div>
                            @endif

                            @if($offer->address)
                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-map-marker-alt text-danger me-3 mt-1"></i>
                                <div>
                                    <small class="text-muted d-block">العنوان</small>
                                    <span class="fw-bold">{{ $offer->address }}</span>
                                </div>
                            </div>
                            @endif

                            @if($offer->website)
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-globe text-info me-3"></i>
                                <div>
                                    <small class="text-muted d-block">الموقع الإلكتروني</small>
                                    <a href="{{ $offer->website }}" target="_blank" class="fw-bold text-decoration-none">زيارة الموقع</a>
                                </div>
                            </div>
                            @endif

                            <div class="d-grid gap-2 mt-4">
                                <a href="tel:{{ $offer->phone }}" class="btn btn-success">
                                    <i class="fas fa-phone me-2"></i>
                                    اتصل الآن
                                </a>
                                <a href="{{ route('medical-center.detail', $offer->slug) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-hospital me-2"></i>
                                    عرض المركز
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Card -->
                    @if($offer->reviews_count > 0)
                    <div class="card border-0 shadow">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">التقييمات</h5>
                            <div class="text-center mb-3">
                                <div class="display-6 fw-bold text-warning">{{ number_format($offer->rating, 1) }}</div>
                                <div class="text-warning mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($offer->rating >= $i)
                                            <i class="fas fa-star"></i>
                                        @elseif($offer->rating >= $i - 0.5)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">{{ $offer->reviews_count }} تقييم</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Related Offers Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h3 class="fw-bold">عروض أخرى قد تهمك</h3>
                <p class="text-muted">اكتشف المزيد من العروض الحصرية</p>
            </div>
            <div class="row g-4">
                @php
                    $relatedOffers = \App\Models\MedicalCenter::where('status', 'active')
                        ->where('max_discount', '>', 0)
                        ->where('id', '!=', $offer->id)
                        ->where('region', $offer->region)
                        ->take(3)
                        ->get();
                @endphp
                
                @forelse($relatedOffers as $relatedOffer)
                <div class="col-lg-4">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-warning text-dark">خصم {{ $relatedOffer->max_discount }}%</span>
                                <small class="text-muted">{{ $relatedOffer->city }}</small>
                            </div>
                            <h6 class="fw-bold mb-2">{{ $relatedOffer->name }}</h6>
                            <p class="text-muted small mb-3">{{ Str::limit($relatedOffer->description, 80) }}</p>
                            <div class="d-grid">
                                <a href="{{ route('offers.show', $relatedOffer->id) }}" class="btn btn-outline-primary btn-sm">
                                    عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">لا توجد عروض أخرى متاحة حالياً</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
