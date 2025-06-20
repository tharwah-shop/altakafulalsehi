@extends('layouts.frontend')

@section('title', 'منطقة ' . $region->name)

@push('styles')
<!-- Bootstrap 5 RTL CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
@endpush

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-primary text-white text-center position-relative overflow-hidden">
    <div class="container position-relative z-2">
        <span class="badge bg-warning text-dark mb-3"><i class="bi bi-geo-alt-fill me-2"></i>منطقة {{ $region->name }}</span>
        <h1 class="display-4 fw-bold mb-3">منطقة {{ $region->name }}</h1>
        <p class="lead mb-4">
            @if($region->description)
                {{ $region->description }}
            @else
                استكشف المراكز الطبية المعتمدة والخصومات المتاحة في منطقة {{ $region->name }}
            @endif
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent p-0">
                <li class="breadcrumb-item"><a href="/" class="text-white text-decoration-underline">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('medicalnetwork') }}" class="text-white text-decoration-underline">الشبكة الطبية</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $region->name }}</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-center gap-4 mt-4">
            <div>
                <span class="display-6 fw-bold">{{ $region->cities_count ?? $region->cities->count() }}</span>
                <div>مدينة</div>
            </div>
            <div>
                <span class="display-6 fw-bold">{{ $medicalCenters->total() }}</span>
                <div>مركز طبي</div>
            </div>
        </div>
        @if($region->address)
        <div class="mt-3">
            <span class="badge bg-light text-dark"><i class="bi bi-geo me-2"></i>{{ $region->address }}</span>
        </div>
        @endif
    </div>
</section>

<!-- Cities Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3 class="fw-bold mb-2"><i class="bi bi-buildings me-2"></i>المدن في منطقة {{ $region->name }}</h3>
                <p class="text-muted">اختر المدينة لعرض المراكز الطبية المتاحة فيها</p>
            </div>
        </div>
        <div class="row justify-content-center g-3">
            @forelse($region->cities as $city)
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('city.detail', $city->slug) }}" class="card border-0 shadow-sm text-center h-100 text-decoration-none city-hover">
                        <div class="card-body py-4">
                            <div class="mb-2">
                                <i class="bi bi-building fs-1 text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-1 text-dark">{{ $city->name }}</h5>
                            <div class="text-muted small mb-1"><i class="bi bi-hospital"></i> {{ $city->medical_centers_count ?? 0 }} مركز</div>
                            @if($city->medical_centers_count > 0)
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> متاح</span>
                            @endif
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center text-muted">لا توجد مدن مضافة لهذه المنطقة بعد.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Medical Centers Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <span class="badge bg-primary mb-2">المراكز الطبية</span>
                <h2 class="fw-bold mb-3">المراكز الطبية في منطقة {{ $region->name }}</h2>
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
                            <a href="tel:{{ $center->phone }}" class="btn btn-outline-success btn-sm flex-fill"><i class="fas fa-phone me-1"></i>اتصل</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted">لا توجد مراكز طبية متاحة حالياً.</div>
            @endforelse
        </div>
        @if($medicalCenters->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <nav>
                    <ul class="pagination justify-content-center">
                        {{-- Previous Page Link --}}
                        @if ($medicalCenters->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $medicalCenters->previousPageUrl() }}">&laquo;</a></li>
                        @endif
                        {{-- Pagination Elements --}}
                        @for ($i = 1; $i <= $medicalCenters->lastPage(); $i++)
                            <li class="page-item {{ $i == $medicalCenters->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $medicalCenters->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        {{-- Next Page Link --}}
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
        <h2 class="fw-bold mb-4">استفد من خصومات تكافل الصحي في منطقة {{ $region->name }}!</h2>
        <p class="lead mb-4">احصل على بطاقة التكافل الصحي الآن واستمتع بخصومات طبية تصل إلى 80% في جميع المراكز الطبية بالمنطقة</p>
        <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
            <a href="{{ route('subscribe') }}" class="btn btn-warning btn-lg px-5 fw-bold">اشترك الآن</a>
            <a href="{{ route('card.request') }}" class="btn btn-outline-light btn-lg px-5 fw-bold">اطلب بطاقتك</a>
        </div>
    </div>
</section>



@endsection


