@extends('layouts.frontend')

@section('title', $city->name . ' - المراكز الطبية')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <span class="badge bg-warning text-dark mb-3"><i class="bi bi-geo-alt-fill me-2"></i>مدينة {{ $city->name }}</span>
        <h1 class="display-4 fw-bold mb-3">المراكز الطبية في {{ $city->name }}</h1>
        <p class="lead mb-4">اكتشف أفضل المراكز الطبية والخدمات الصحية في مدينة {{ $city->name }} مع خصومات حصرية وتجربة رعاية متكاملة.</p>
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb justify-content-center bg-transparent p-0">
                <li class="breadcrumb-item"><a href="/" class="text-white text-decoration-underline">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('region.detail', $city->region->slug) }}" class="text-white text-decoration-underline">{{ $city->region->name }}</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $city->name }}</li>
            </ol>
        </nav>
        <div class="row justify-content-center mb-2">
            <div class="col-auto">
                <div class="bg-white bg-opacity-10 rounded-3 px-4 py-2 d-inline-block">
                    <span class="h4 fw-bold mb-0">{{ $medicalCenters->total() }}</span>
                    <span class="ms-2">مركز طبي</span>
                </div>
            </div>
            <div class="col-auto">
                <div class="bg-white bg-opacity-10 rounded-3 px-4 py-2 d-inline-block">
                    <span class="h4 fw-bold mb-0">{{ $city->region->name }}</span>
                    <span class="ms-2">المنطقة</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- City Info Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="bi bi-buildings fs-2"></i>
                            </div>
                            <div class="text-start">
                                <h2 class="h4 fw-bold mb-1">{{ $city->name }}</h2>
                                <div class="text-muted small">ضمن منطقة <span class="fw-bold">{{ $city->region->name }}</span></div>
                            </div>
                        </div>
                        <p class="lead mb-2">{{ $city->description ?? 'هذه المدينة تضم العديد من المراكز الطبية المتميزة.' }}</p>
                        @if($city->address)
                        <p class="text-muted mb-0">
                            <i class="bi bi-geo-alt-fill"></i> {{ $city->address }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Medical Centers Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <span class="badge bg-primary mb-2">المراكز الطبية</span>
                <h2 class="fw-bold mb-3">المراكز الطبية في {{ $city->name }}</h2>
                <div class="mx-auto mb-4 bg-success rounded-pill" style="width: 80px; height: 4px;"></div>
                <p class="fs-6 text-muted">عدد المراكز: {{ $medicalCenters->total() }}</p>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            @forelse($medicalCenters as $center)
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:56px; height:56px;">
                                <i class="bi bi-hospital text-white fs-4"></i>
                            </div>
                            <div class="ms-3 text-start">
                                <h5 class="fw-bold mb-1">{{ $center->name }}</h5>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-geo-alt-fill text-info me-1"></i>
                                    <span class="small text-muted">
                                        <span class="badge bg-warning text-dark">
                                            {{ $center->city }}
                                            <i class="bi bi-star-fill ms-1"></i>
                                        </span>
                                    </span>
                                </div>
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
                            <small class="text-muted d-block mb-1">نوع المركز:</small>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-success small">{{ $center->type ?? 'مركز طبي' }}</span>
                                @if($center->max_discount > 0)
                                    <span class="badge bg-warning text-dark small">خصم {{ $center->max_discount }}%</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('medical-centers.show', $center->slug) }}" class="btn btn-outline-primary btn-sm flex-fill"><i class="fas fa-eye me-1"></i>تفاصيل</a> 
                            <a href="tel:{{ $center->phone }}" class="btn btn-outline-success btn-sm flex-fill"><i class="bi bi-telephone me-1"></i>اتصل</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted">لا توجد مراكز طبية متاحة حالياً في هذه المدينة.</div>
            @endforelse
        </div>
        @if($medicalCenters->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <nav>
                    <ul class="pagination justify-content-center">
                        @if ($medicalCenters->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $medicalCenters->previousPageUrl() }}">&laquo;</a></li>
                        @endif
                        @for ($i = 1; $i <= $medicalCenters->lastPage(); $i++)
                            <li class="page-item {{ $i == $medicalCenters->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $medicalCenters->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        @if ($medicalCenters->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $medicalCenters->nextPageUrl() }}">&raquo;</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">استفد من خصومات تكافل الصحي في {{ $city->name }}!</h2>
        <p class="lead mb-4">احصل على بطاقة التكافل الصحي الآن واستمتع بخصومات طبية تصل إلى 80% في جميع المراكز الطبية بالمدينة</p>
        <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
            <a href="{{ route('subscribe') }}" class="btn btn-warning btn-lg px-5 fw-bold">اشترك الآن</a>
            <a href="{{ route('card.request') }}" class="btn btn-outline-light btn-lg px-5 fw-bold">اطلب بطاقتك</a>
        </div>
    </div>
</section>
@endsection
