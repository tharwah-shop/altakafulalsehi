@extends('layouts.frontend')

@section('title', 'الباقات والأسعار')

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
                    <i class="fas fa-tags me-2"></i>
                    الباقات والأسعار
                </div>

                <h1 class="hero-title">الباقات والأسعار</h1>

                <p class="hero-description">اختر الباقة المناسبة لاحتياجاتك واستمتع بخصومات طبية حصرية</p>

                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-white">الرئيسية</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">الباقات والأسعار</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <!-- Enhanced Packages Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%);">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <div class="position-relative z-2">
                        <span class="d-inline-block bg-gradient-primary text-white px-4 py-2 rounded-pill mb-3">الباقات المتاحة</span>
                        <h2 class="fw-bold mb-3">اختر الباقة المناسبة لاحتياجاتك</h2>
                        <div class="mx-auto mb-4" style="width: 80px; height: 4px; background: linear-gradient(135deg, #00bcd4 0%, #00e676 100%); border-radius: 2px;"></div>
                        <p class="fs-5 text-muted">بطاقة التكافل الصحي متوفرة بباقات متعددة لتناسب جميع الاحتياجات والميزانيات، مع إمكانية إضافة أفراد العائلة بأسعار مخفضة</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center" id="packagesContainer">
                @foreach($packages ?? [] as $package)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-0 shadow-lg rounded-4 h-100">
                            <div class="card-header bg-gradient-primary text-white text-center border-0 rounded-top-4 py-4">
                                <div class="d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; margin: 0 auto;">
                                    <i class="fas fa-shield-alt text-white" style="font-size: 1.5rem;"></i>
                                </div>
                                <h3 class="fw-bold mb-1">{{ $package->name }}</h3>
                                <p class="mb-0 opacity-75">{{ $package->duration_months }} شهور</p>
                            </div>

                            <div class="card-body p-4">
                                <div class="text-center mb-4">
                                    <div class="d-flex align-items-baseline justify-content-center mb-2">
                                        <span class="display-6 fw-bold text-primary">{{ $package->price }}</span>
                                        <span class="fs-5 text-muted me-2">ريال</span>
                                    </div>
                                    <p class="text-muted mb-0">لمدة {{ $package->duration_months }} شهور</p>
                                </div>

                                <div class="mb-4">
                                    <ul class="list-unstyled">
                                        <li class="d-flex align-items-center mb-3">
                                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); border-radius: 50%;">
                                                <i class="fas fa-percentage text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <span class="text-dark">خصومات تصل إلى 80%</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background: linear-gradient(135deg, #198754 0%, #20c997 100%); border-radius: 50%;">
                                                <i class="fas fa-hospital text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <span class="text-dark">أكثر من 4500 مركز طبي</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); border-radius: 50%;">
                                                <i class="fas fa-stethoscope text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <span class="text-dark">جميع التخصصات الطبية</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%); border-radius: 50%;">
                                                <i class="fas fa-infinity text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <span class="text-dark">استخدام غير محدود</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); border-radius: 50%;">
                                                <i class="fas fa-bolt text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <span class="text-dark">بلا فترة انتظار</span>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <div class="d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; background: linear-gradient(135deg, #00bcd4 0%, #00e676 100%); border-radius: 50%;">
                                                <i class="fas fa-users text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <span class="text-dark">سعر التابع: {{ $package->dependent_price }} ريال</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="text-center">
                                    <a href="{{ route('subscribe') }}?package_id={{ $package->id }}" class="btn btn-gradient-primary d-flex align-items-center justify-content-center gap-2 w-100">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span>اشترك الآن</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Enhanced Help Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-lg rounded-4 bg-gradient-success text-white">
                        <div class="card-body p-5">
                            <div class="row align-items-center">
                                <div class="col-lg-8 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 50%;">
                                        <i class="fas fa-question-circle text-white" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <h4 class="fw-bold mb-3">هل تحتاج لمساعدة في اختيار الباقة المناسبة؟</h4>
                                    <p class="mb-0 opacity-75">فريقنا جاهز لمساعدتك في اختيار الباقة الأنسب لاحتياجاتك وظروفك. تواصل معنا الآن واحصل على استشارة مجانية!</p>
                                </div>
                                <div class="col-lg-4 text-lg-end text-center">
                                    <div class="d-flex flex-column gap-3">
                                        <a href="https://wa.me/966920031304" class="btn btn-light d-flex align-items-center justify-content-center gap-2" target="_blank">
                                            <i class="fab fa-whatsapp"></i>
                                            <span>تواصل واتساب</span>
                                        </a>
                                        <a href="{{ route('contact') }}" class="btn btn-outline-light d-flex align-items-center justify-content-center gap-2">
                                            <i class="fas fa-envelope"></i>
                                            <span>راسلنا</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced FAQ Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <div class="position-relative z-2">
                        <span class="d-inline-block bg-gradient-primary text-white px-4 py-2 rounded-pill mb-3">أسئلة شائعة</span>
                        <h3 class="fw-bold mb-3">أسئلة شائعة عن الباقات</h3>
                        <div class="mx-auto mb-4" style="width: 80px; height: 4px; background: linear-gradient(135deg, #00bcd4 0%, #00e676 100%); border-radius: 2px;"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="accordion" id="packagesAccordion">
                        <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button bg-gradient-primary text-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="fas fa-layer-group me-2"></i>
                                    ما الفرق بين الباقات المختلفة؟
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#packagesAccordion">
                                <div class="accordion-body">
                                    الفرق الأساسي بين الباقات هو مدة الاشتراك، حيث تتوفر باقات بمدد مختلفة لتناسب احتياجاتك. جميع الباقات توفر نفس مستوى الخصومات ونفس شبكة المراكز الطبية مع نفس المزايا الشاملة.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed bg-gradient-secondary text-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="fas fa-users me-2"></i>
                                    ما هي تكلفة إضافة أفراد العائلة؟
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#packagesAccordion">
                                <div class="accordion-body">
                                    تكلفة إضافة كل فرد من أفراد العائلة (تابع) تختلف حسب الباقة المختارة. يمكنك رؤية سعر التابع في تفاصيل كل باقة. جميع التابعين يحصلون على نفس مزايا البطاقة الرئيسية ولنفس المدة.
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
@endsection

