@extends('layouts.frontend')

@section('title', 'العروض والخصومات')

@section('content')
    <!-- Hero Section -->
    <section class="bg-primary text-white py-5 mb-4">
        <div class="container">
            <div class="text-center">
                <span class="badge bg-warning text-dark mb-3"><i class="fas fa-gift me-2"></i> العروض والخصومات</span>
                <h1 class="display-5 fw-bold mb-3">العروض والخصومات الحصرية</h1>
                <p class="lead mb-4">اكتشف أحدث العروض والخصومات الحصرية من المراكز الطبية المعتمدة</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="/" class="text-white text-decoration-underline">الرئيسية</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">العروض والخصومات</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <!-- Offers Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <span class="badge bg-primary mb-3">العروض المتاحة</span>
                    <h2 class="fw-bold mb-3">أحدث العروض والخصومات</h2>
                    <div class="mx-auto mb-4 bg-success rounded-pill" style="width: 80px; height: 4px;"></div>
                    <p class="fs-5 text-muted">استمتع بخصومات حصرية من أفضل المراكز الطبية في المملكة</p>
                </div>
            </div>
            <div class="row g-4">
                @forelse($offers as $offer)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow h-100 position-relative">
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-warning text-dark">{{ $loop->iteration }}</span>
                        </div>
                        <img src="{{ $offer->image_url ?? asset('images/placeholder.jpg') }}" class="card-img-top" alt="{{ $offer->title }}" height="200" style="object-fit:cover">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-warning text-dark"><i class="fas fa-percentage me-1"></i> خصم {{ $offer->discount_percentage }}%</span>
                                <span class="badge bg-success text-white"><i class="fas fa-star me-1"></i> عرض حصري</span>
                            </div>
                            <h5 class="fw-bold mb-3">{{ $offer->title }}</h5>
                            <p class="text-muted mb-3">{{ Str::limit(strip_tags($offer->description), 80) }}</p>
                            <div class="d-flex align-items-center mb-3">
                                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient rounded-circle me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-hospital text-white"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $offer->medicalCenter->name ?? 'مركز طبي' }}</h6>
                                    <small class="text-muted">{{ $offer->medicalCenter->primaryCity->name ?? 'غير محدد' }}</small>
                                </div>
                            </div>
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <small class="text-muted d-block">تاريخ البداية</small>
                                    <span class="fw-bold text-primary">{{ $offer->start_date }}</span>
                                </div>
                                <div class="col">
                                    <small class="text-muted d-block">تاريخ الانتهاء</small>
                                    <span class="fw-bold text-danger">{{ $offer->end_date ?? 'مفتوح' }}</span>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas fa-eye"></i>
                                    <span>عرض التفاصيل</span>
                                </a>
                                <a href="#" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas fa-hospital"></i>
                                    <span>عرض المركز</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">لا توجد عروض متاحة حالياً.</div>
                </div>
                @endforelse
            </div>
            @if(method_exists($offers, 'links'))
            <div class="mt-4 d-flex justify-content-center">
                {{ $offers->links() }}
            </div>
            @endif
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <span class="badge bg-primary mb-3">فئات العروض</span>
                    <h3 class="fw-bold mb-3">تصفح العروض حسب الفئة</h3>
                    <div class="mx-auto mb-4 bg-success rounded-pill" style="width: 80px; height: 4px;"></div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow text-center h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center mb-3 bg-warning rounded-circle mx-auto" style="width: 80px; height: 80px;">
                                <i class="fas fa-stethoscope text-white fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">طب عام</h5>
                            <p class="text-muted mb-3">عروض وخصومات في الطب العام والاستشارات الطبية</p>
                            <a href="#" class="btn btn-outline-primary">تصفح العروض</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow text-center h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center mb-3 bg-success rounded-circle mx-auto" style="width: 80px; height: 80px;">
                                <i class="fas fa-tooth text-white fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">طب الأسنان</h5>
                            <p class="text-muted mb-3">خصومات حصرية في طب الأسنان والعلاجات التجميلية</p>
                            <a href="#" class="btn btn-outline-primary">تصفح العروض</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow text-center h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center mb-3 bg-info rounded-circle mx-auto" style="width: 80px; height: 80px;">
                                <i class="fas fa-eye text-white fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">طب العيون</h5>
                            <p class="text-muted mb-3">عروض في طب العيون والفحوصات البصرية</p>
                            <a href="#" class="btn btn-outline-primary">تصفح العروض</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow text-center h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center mb-3 bg-primary rounded-circle mx-auto" style="width: 80px; height: 80px;">
                                <i class="fas fa-heartbeat text-white fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">طب القلب</h5>
                            <p class="text-muted mb-3">خصومات في طب القلب والأوعية الدموية</p>
                            <a href="#" class="btn btn-outline-primary">تصفح العروض</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow bg-primary text-white">
                        <div class="card-body p-5">
                            <div class="row align-items-center">
                                <div class="col-lg-8 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center justify-content-center mb-4 bg-white bg-opacity-25 rounded-circle mx-auto" style="width: 60px; height: 60px;">
                                        <i class="fas fa-bell text-white fs-2"></i>
                                    </div>
                                    <h4 class="fw-bold mb-3">احصل على إشعارات بأحدث العروض</h4>
                                    <p class="mb-0 opacity-75">اشترك في النشرة الإخبارية واحصل على إشعارات فورية بأحدث العروض والخصومات الحصرية</p>
                                </div>
                                <div class="col-lg-4 text-lg-end text-center">
                                    <a href="#" class="btn btn-light d-flex align-items-center justify-content-center gap-2">
                                        <i class="fas fa-plus"></i>
                                        <span>اشترك الآن</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
