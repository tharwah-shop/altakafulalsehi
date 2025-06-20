@extends('layouts.frontend')

@section('title', 'تفاصيل العرض - ' . $offer->title)

@section('content')
    <!-- Hero Section -->
    <section class="bg-primary text-white py-5 mb-4">
        <div class="container">
            <div class="text-center">
                <span class="badge bg-warning text-dark mb-3"><i class="bi bi-gift me-2"></i> تفاصيل العرض</span>
                <h1 class="display-5 fw-bold mb-3">{{ $offer->title }}</h1>
                <p class="lead mb-4">استمتع بخصم يصل إلى {{ $offer->discount_percentage }}% من {{ $offer->medicalCenter->name ?? 'المركز الطبي' }}</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="/" class="text-white text-decoration-underline">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('offers') }}" class="text-white text-decoration-underline">العروض</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $offer->title }}</li>
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
                        @if($offer->image)
                        <img src="{{ asset('storage/' . $offer->image) }}" alt="{{ $offer->title }}" class="card-img-top" style="height: 300px; object-fit: cover;" />
                        @endif
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                        <i class="bi bi-percent text-white fs-3"></i>
                                    </div>
                                    <div>
                                        <h3 class="fw-bold mb-1">{{ $offer->title }}</h3>
                                        @if($offer->medicalCenter)
                                        <p class="text-muted mb-0">{{ $offer->medicalCenter->name }}</p>
                                        <small class="text-muted">{{ $offer->medicalCenter->city }}, {{ $offer->medicalCenter->region }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                        <i class="bi bi-percent me-1"></i>
                                        خصم {{ $offer->discount_percentage }}%
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-bold mb-3">وصف العرض</h5>
                                <p class="text-muted">{{ $offer->description }}</p>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar-check text-success me-2"></i>
                                        <span><strong>تاريخ البداية:</strong> {{ $offer->start_date->format('Y/m/d') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar-x text-danger me-2"></i>
                                        <span><strong>تاريخ الانتهاء:</strong> {{ $offer->end_date->format('Y/m/d') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($offer->original_price && $offer->discounted_price)
                            <div class="mb-4">
                                <h5 class="fw-bold mb-3">تفاصيل السعر</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-center p-3 bg-light rounded">
                                            <span class="text-muted text-decoration-line-through">{{ number_format($offer->original_price) }} ريال</span>
                                            <div class="h4 text-success fw-bold">{{ number_format($offer->discounted_price) }} ريال</div>
                                            <small class="text-muted">السعر بعد الخصم</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center p-3 bg-success text-white rounded">
                                            <div class="h4 fw-bold">{{ number_format($offer->original_price - $offer->discounted_price) }} ريال</div>
                                            <small>توفير</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($offer->terms_conditions)
                            <div class="mb-4">
                                <h5 class="fw-bold mb-3">الشروط والأحكام</h5>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0 small">{{ $offer->terms_conditions }}</p>
                                </div>
                            </div>
                            @endif

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
                    <!-- Offer Info Card -->
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">معلومات العرض</h5>

                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-percent text-success me-3"></i>
                                <div>
                                    <small class="text-muted d-block">نسبة الخصم</small>
                                    <span class="fw-bold">{{ $offer->discount_percentage }}%</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-calendar-range text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">فترة العرض</small>
                                    <span class="fw-bold">{{ $offer->start_date->format('Y/m/d') }} - {{ $offer->end_date->format('Y/m/d') }}</span>
                                </div>
                            </div>

                            @if($offer->max_uses > 0)
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-people text-warning me-3"></i>
                                <div>
                                    <small class="text-muted d-block">عدد الاستخدامات المتاحة</small>
                                    <span class="fw-bold">{{ $offer->max_uses - $offer->current_uses }} من {{ $offer->max_uses }}</span>
                                </div>
                            </div>
                            @endif

                            @if($offer->medicalCenter)
                            <div class="d-grid gap-2 mt-4">
                                @if($offer->medicalCenter->phone)
                                <a href="tel:{{ $offer->medicalCenter->phone }}" class="btn btn-success">
                                    <i class="bi bi-telephone me-2"></i>
                                    اتصل بالمركز
                                </a>
                                @endif
                                <a href="{{ route('medical-center.detail', $offer->medicalCenter->slug) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-hospital me-2"></i>
                                    عرض المركز
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($offer->medicalCenter)
                    <!-- Medical Center Info Card -->
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">معلومات المركز الطبي</h5>

                            <div class="text-center mb-3">
                                <h6 class="fw-bold">{{ $offer->medicalCenter->name }}</h6>
                                <p class="text-muted small mb-2">{{ $offer->medicalCenter->city }}, {{ $offer->medicalCenter->region }}</p>
                            </div>

                            @if($offer->medicalCenter->phone)
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-telephone text-success me-3"></i>
                                <div>
                                    <small class="text-muted d-block">رقم الهاتف</small>
                                    <a href="tel:{{ $offer->medicalCenter->phone }}" class="fw-bold text-decoration-none">{{ $offer->medicalCenter->phone }}</a>
                                </div>
                            </div>
                            @endif

                            @if($offer->medicalCenter->email)
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-envelope text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">البريد الإلكتروني</small>
                                    <a href="mailto:{{ $offer->medicalCenter->email }}" class="fw-bold text-decoration-none">{{ $offer->medicalCenter->email }}</a>
                                </div>
                            </div>
                            @endif

                            @if($offer->medicalCenter->address)
                            <div class="d-flex align-items-start mb-3">
                                <i class="bi bi-geo-alt text-danger me-3 mt-1"></i>
                                <div>
                                    <small class="text-muted d-block">العنوان</small>
                                    <span class="fw-bold">{{ $offer->medicalCenter->address }}</span>
                                </div>
                            </div>
                            @endif

                            @if($offer->medicalCenter->website)
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-globe text-info me-3"></i>
                                <div>
                                    <small class="text-muted d-block">الموقع الإلكتروني</small>
                                    <a href="{{ $offer->medicalCenter->website }}" target="_blank" class="fw-bold text-decoration-none">زيارة الموقع</a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Rating Card -->
                    @if($offer->medicalCenter && $offer->medicalCenter->reviews_count > 0)
                    <div class="card border-0 shadow">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">تقييم المركز الطبي</h5>
                            <div class="text-center mb-3">
                                <div class="display-6 fw-bold text-warning">{{ number_format($offer->medicalCenter->rating, 1) }}</div>
                                <div class="text-warning mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($offer->medicalCenter->rating >= $i)
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($offer->medicalCenter->rating >= $i - 0.5)
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">{{ $offer->medicalCenter->reviews_count }} تقييم</small>
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
                    $relatedOffers = \App\Models\Offer::where('status', 'active')
                        ->where('id', '!=', $offer->id)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->with(['medicalCenter'])
                        ->take(3)
                        ->get();
                @endphp

                @forelse($relatedOffers as $relatedOffer)
                <div class="col-lg-4">
                    <div class="card border-0 shadow h-100">
                        @if($relatedOffer->image)
                            <img src="{{ asset('storage/' . $relatedOffer->image) }}" alt="{{ $relatedOffer->title }}" class="card-img-top" style="height: 150px; object-fit: cover;" />
                        @endif
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-warning text-dark">خصم {{ $relatedOffer->discount_percentage }}%</span>
                                @if($relatedOffer->medicalCenter)
                                <small class="text-muted">{{ $relatedOffer->medicalCenter->city }}</small>
                                @endif
                            </div>
                            <h6 class="fw-bold mb-2">{{ $relatedOffer->title }}</h6>
                            <p class="text-muted small mb-3">{{ Str::limit($relatedOffer->description, 80) }}</p>
                            @if($relatedOffer->medicalCenter)
                            <p class="text-muted small mb-3">
                                <i class="bi bi-hospital me-1"></i>{{ $relatedOffer->medicalCenter->name }}
                            </p>
                            @endif
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

            <div class="text-center mt-5">
                <a href="{{ route('offers') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-eye me-2"></i>
                    مشاهدة جميع العروض
                </a>
            </div>
        </div>
    </section>
@endsection
