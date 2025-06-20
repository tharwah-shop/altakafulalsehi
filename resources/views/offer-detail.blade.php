@extends('layouts.frontend')

@section('title', $offer->title)


@section('content')
    <!-- Enhanced Hero Section -->
    <section class="bg-primary text-white py-5 position-relative overflow-hidden">
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25">
            <div class="position-absolute top-25 start-25 bg-white rounded-circle" style="width: 100px; height: 100px;"></div>
            <div class="position-absolute top-75 start-75 bg-white rounded-circle" style="width: 150px; height: 150px;"></div>
            <div class="position-absolute top-50 start-50 bg-white rounded-circle" style="width: 80px; height: 80px;"></div>
        </div>

        <div class="container position-relative">
            <div class="text-center">
                <div class="badge bg-warning text-dark mb-3 px-3 py-2">
                    <i class="bi bi-gift me-2"></i>
                    عرض حصري
                </div>

                <h1 class="display-4 fw-bold mb-3">{{ $offer->title }}</h1>

                <p class="lead mb-4">
                    @if($offer->description)
                        {{ Str::limit(strip_tags($offer->description), 150) }}
                    @else
                        استمتع بهذا العرض الحصري من {{ $offer->medicalCenter->name }}
                    @endif
                </p>

                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-white">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('offers') }}" class="text-decoration-none text-white">العروض</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ Str::limit($offer->title, 50) }}</li>
                    </ol>
                </nav>

                <div class="row justify-content-center mb-4">
                    <div class="col-md-4 text-center">
                        <div class="bg-white bg-opacity-25 rounded p-3">
                            <div class="h3 fw-bold text-warning">{{ $offer->discount_percentage }}%</div>
                            <div class="small">خصم</div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="bg-white bg-opacity-25 rounded p-3">
                            <div class="h5 fw-bold">{{ $offer->medicalCenter->name }}</div>
                            <div class="small">المركز الطبي</div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="bg-white bg-opacity-25 rounded p-3">
                            <div class="h5 fw-bold">{{ $offer->medicalCenter->primaryCity->name ?? 'غير محدد' }}</div>
                            <div class="small">المدينة</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="tel:{{ $offer->medicalCenter->phone }}" class="btn btn-light btn-lg">
                        <i class="bi bi-telephone-fill me-2"></i>
                        <span>اتصل الآن</span>
                    </a>
                    <button class="btn btn-outline-light btn-lg" onclick="shareOffer()">
                        <i class="bi bi-share me-2"></i>
                        <span>مشاركة</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Header Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <a href="{{ route('hospitals.show', $offer->medicalCenter) }}"
                           class="text-decoration-none">
                            <div class="mb-3">
                                @if($offer->medicalCenter->image_url)
                                    <img src="{{ $offer->medicalCenter->image_url }}"
                                         alt="{{ $offer->medicalCenter->name }}"
                                         class="img-fluid rounded-circle"
                                         style="width: 80px; height: 80px; object-fit: cover;"
                                         onerror="this.src='{{ asset('images/no-image-hospital.png') }}'">
                                @else
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                                         style="width: 80px; height: 80px;">
                                        <i class="bi bi-hospital fa-2x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <h4 class="text-dark">{{ $offer->medicalCenter->name }}</h4>
                        </a>
                        <span class="badge bg-info">
                            <i class="bi bi-heart-pulse me-1"></i>
                            {{ $offer->medicalCenter->type->name }}
                        </span>
                    </div>
                    <div class="col-md-9">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-check text-primary me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-muted d-block">تاريخ البداية</small>
                                        <span class="fw-bold">{{ \Carbon\Carbon::parse($offer->start_date)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-x text-warning me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-muted d-block">تاريخ الانتهاء</small>
                                        <span class="fw-bold">{{ $offer->end_date ? \Carbon\Carbon::parse($offer->end_date)->format('d/m/Y') : 'مفتوح' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt-fill text-danger me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-muted d-block">الموقع</small>
                                        <span class="fw-bold">{{ $offer->medicalCenter->primaryCity->name ?? 'غير محدد' }}, {{ $offer->medicalCenter->primaryCity->region->name ?? 'غير محدد' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Offer Image -->
                @if($offer->image)
                <div class="card shadow-sm mb-4">
                    <div class="position-relative">
                        <img src="{{ $offer->image_url }}"
                             class="card-img-top"
                             alt="{{ $offer->title }}"
                             style="height: 400px; object-fit: cover;"
                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                        <!-- Overlay with Offer Details -->
                        <div class="position-absolute top-0 end-0 m-3">
                            <div class="bg-dark bg-opacity-75 text-white rounded p-2">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-calendar me-2"></i>
                                    <span class="small">{{ \Carbon\Carbon::parse($offer->start_date)->format('Y-m-d') }}</span>
                                </div>
                                @if($offer->end_date)
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock me-2"></i>
                                    <span class="small">{{ \Carbon\Carbon::parse($offer->end_date)->format('Y-m-d') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Offer Description -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-info-circle text-primary me-2"></i>تفاصيل العرض
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="lead">
                            {!! nl2br(e($offer->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Location Map -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-pin-map-fill text-primary me-2"></i>
                            موقع المركز الطبي
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div style="height: 400px;">
                            <div id="map" class="w-100 h-100"></div>
                        </div>
                    </div>
                </div>

                <!-- Offer Gallery -->
                @if($offer->gallery && count($offer->gallery) > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-images text-primary me-2"></i>معرض الصور
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            @foreach($offer->gallery as $image)
                            <div class="col-6 col-md-4">
                                <div class="position-relative">
                                    <a href="{{ asset('storage/' . $image) }}"
                                       data-fancybox="gallery"
                                       class="d-block">
                                        <img src="{{ asset('storage/' . $image) }}"
                                             alt="صورة العرض"
                                             class="img-fluid rounded"
                                             style="height: 200px; width: 100%; object-fit: cover;"
                                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center rounded opacity-0 transition-opacity">
                                            <i class="bi bi-zoom-in text-white fa-2x"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Medical Center Info -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-hospital text-primary me-2"></i>معلومات المركز الطبي
                        </h4>
                        <a href="{{ route('hospitals.show', $offer->medicalCenter) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <i class="bi bi-geo-alt-fill text-danger me-3 mt-1"></i>
                                <span class="small">{{ $offer->medicalCenter->address }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-telephone-fill text-success me-3"></i>
                                <span class="small">{{ $offer->medicalCenter->phone }}</span>
                            </div>
                            @if($offer->medicalCenter->email)
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-envelope-fill text-primary me-3"></i>
                                <span class="small">{{ $offer->medicalCenter->email }}</span>
                            </div>
                            @endif
                            <div class="d-flex align-items-center">
                                <i class="bi bi-building text-info me-3"></i>
                                <span class="small">{{ $offer->medicalCenter->primaryCity->name ?? 'غير محدد' }}, {{ $offer->medicalCenter->primaryCity->region->name ?? 'غير محدد' }}</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="tel:{{ $offer->medicalCenter->phone }}" class="btn btn-primary">
                                <i class="bi bi-telephone-fill me-2"></i>اتصل الآن
                            </a>
                            <button class="btn btn-outline-primary" onclick="showDirections()">
                                <i class="bi bi-signpost-2 me-2"></i>احصل على الاتجاهات
                            </button>
                            <a href="{{ route('hospitals.show', $offer->medicalCenter) }}"
                               class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-hospital"></i>
                                <span>عرض المركز الطبي</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Other Offers -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-gift text-primary me-2"></i>عروض أخرى من نفس المركز
                        </h4>
                    </div>
                    <div class="card-body">
                        @php
                            $otherOffers = $offer->medicalCenter->offers()
                                ->where('id', '!=', $offer->id)
                                ->where('is_active', true)
                                ->latest()
                                ->take(3)
                                ->get();
                        @endphp

                        @if($otherOffers->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($otherOffers as $otherOffer)
                                    <a href="{{ route('offers.show', $otherOffer) }}"
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">{{ $otherOffer->title }}</span>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($otherOffer->start_date)->format('Y-m-d') }}</small>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                لا توجد عروض أخرى متاحة حالياً
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

