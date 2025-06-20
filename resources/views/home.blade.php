@extends('layouts.frontend')

@section('title', 'الرئيسية - التكافل الصحي')

@section('content')
    <!-- Feature Bar (Top) -->
    <section class="bg-dark text-white py-2 border-bottom">
        <div class="container d-flex flex-wrap justify-content-center gap-4 small fw-bold">
            <div><i class="bi bi-hospital me-1 text-warning"></i> {{ number_format($stats['medical_centers_count']) }}+ مركز طبي</div>
            <div><i class="bi bi-percent me-1 text-warning"></i> خصومات حتى 80%</div>
            <div><i class="bi bi-infinity me-1 text-warning"></i> استخدام غير محدود</div>
            <div><i class="bi bi-lightning-charge me-1 text-warning"></i> تفعيل فوري</div>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="py-5 bg-primary text-white position-relative overflow-hidden">
        <div class="container position-relative z-2">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <span class="badge bg-warning text-dark mb-2"><i class="bi bi-crown me-1"></i> الأفضل في المملكة</span>
                    <h1 class="display-4 fw-bold mb-3">أفضل بطاقة تأمين طبي في المملكة</h1>
                    <p class="lead mb-4">أقوى بطاقة علاج نقدي في السعودية والخليج تقوم على فكرة الخصم النقدي المباشر بأفضل الأسعار مع خصومات من 30% حتى 80% في جميع التخصصات الطبية</p>
                    <div class="d-flex gap-4 mb-4">
                        <div class="text-center">
                            <span class="h2 d-block">80%</span>
                            <small>خصومات تصل إلى</small>
                        </div>
                        <div class="text-center">
                            <span class="h2 d-block">{{ number_format($stats['medical_centers_count']) }}+</span>
                            <small>مركز طبي</small>
                        </div>
                        <div class="text-center">
                            <span class="h2 d-block">∞</span>
                            <small>استخدام غير محدود</small>
                        </div>
                    </div>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('subscribe') }}" class="btn btn-warning btn-lg px-4"><i class="bi bi-rocket me-1"></i> اشترك الآن</a>
                        <a href="{{ route('card.request') }}" class="btn btn-outline-light btn-lg px-4"><i class="bi bi-credit-card me-1"></i> اطلب بطاقتك</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="card bg-light border-0 rounded-4 p-4 shadow-lg mx-auto" style="max-width:350px;">
                        <img src="/images/logo-white.svg" alt="بطاقة التكافل الصحي" class="img-fluid mb-3 w-100" />
                        <div class="text-dark mb-2 fs-5">**** **** **** 1234</div>
                        <div class="text-muted">حامل البطاقة</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 opacity-25 z-1" style="width: 400px; height: 400px; background: radial-gradient(circle, #fff 0%, transparent 70%);"></div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-2">كيف تعمل البطاقة؟</h2>
                <p class="lead text-muted">بطاقة التكافل الصحي هي نظام خصومات طبية مباشرة يمكنك من الحصول على خدمات طبية بأسعار مخفضة في شبكة واسعة من المراكز الطبية</p>
            </div>
            <div class="row g-4 justify-content-center">
                @php $steps = [
                    ['اشترك في الباقة', 'اختر الباقة المناسبة لك ولعائلتك'],
                    ['أتمم الدفع', 'ادفع بكل سهولة وأمان'],
                    ['استلم بطاقتك', 'فوراً بعد الدفع'],
                    ['استخدم البطاقة', 'في شبكتنا الواسعة'],
                    ['احصل على الخصم', 'خصومات حتى 80%'],
                ]; @endphp
                @foreach($steps as $i => $step)
                <div class="col-6 col-md-2">
                    <div class="text-center">
                        <div class="step-number bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                            <span class="fw-bold fs-4">{{ $i+1 }}</span>
                        </div>
                        <h6 class="fw-bold">{{ $step[0] }}</h6>
                        <p class="small text-muted">{{ $step[1] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('subscribe') }}" class="btn btn-primary btn-lg px-5"><i class="bi bi-rocket me-2"></i> اشترك الآن</a>
            </div>
        </div>
    </section>

    <!-- ماذا يمكننا أن نقدم لك؟ (مميزات البطاقة) -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3" style="font-size:2.2rem;">
                    <span>ماذا يمكننا أن نقدم أن نقدم <span class="text-primary">لك؟</span></span>
                </h2>
                <h3 class="h4 mb-4">
                    <span>مجموعة واسعة من <span class="bg-success text-white px-2 rounded">الخصومات الطبية</span></span>
                </h3>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="mb-3"><i class="bi bi-hospital fs-1"></i></div>
                        <h5 class="fw-bold mb-2">أكثر من 3500 مركز طبي</h5>
                        <p class="text-muted small">تغطي 3500 مركز طبي ومستشفى في جميع أنحاء المملكة وفي زيادة مستمرة تصل نسبة الخصومات حتى 80%</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="mb-3"><i class="bi bi-people fs-1"></i></div>
                        <h5 class="fw-bold mb-2">تغطي جميع الأعمار</h5>
                        <p class="text-muted small">البطاقة يمكن استخراجها للأطفال والشيوخ وكبار السن وصغار السن وللرجال والنساء بدون قيود عمرية</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="mb-3"><i class="bi bi-scissors fs-1"></i></div>
                        <h5 class="fw-bold mb-2">خصومات طبية على العمليات</h5>
                        <p class="text-muted small">تغطي البطاقة معظم الخدمات الطبية وتشمل الحمل والولادة والأسنان وعمليات التجميل والليزر وغيرها</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="mb-3"><i class="bi bi-stars fs-1"></i></div>
                        <h5 class="fw-bold mb-2">متاحة لجميع فئات المجتمع</h5>
                        <p class="text-muted small">البطاقة متاحة للاستخدام لكل من المواطنين السعوديين والمقيمين والزائرين والمعتمرين وحجاج بيت الله الحرام</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="mb-3"><i class="bi bi-clock-history fs-1"></i></div>
                        <h5 class="fw-bold mb-2">بدون انتظار أو موافقة مسبقة</h5>
                        <p class="text-muted small">يمكن استخدام البطاقة فورًا بدون انتظار أو موافقة مسبقة، تقبل جميع التخصصات وتفعل فورًا</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="mb-3"><i class="bi bi-pencil-ruler fs-1"></i></div>
                        <h5 class="fw-bold mb-2">يمكن استخدام البطاقة بدون حد تأميني</h5>
                        <p class="text-muted small">البطاقة صالحة للاستخدام طوال العام بدون حد تأميني</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Card Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-2">بطاقة التكافل الصحي</h2>
                <h4 class="h5 text-primary mb-3">لماذا تختار بطاقة التكافل الصحي؟</h4>
                <p class="lead text-muted mb-0">اكتشف المزايا الفريدة التي تجعل بطاقة التكافل الصحي الخيار الأمثل لك ولعائلتك</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="feature-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-percent fs-2"></i>
                        </div>
                        <h5 class="fw-bold">خصومات قوية</h5>
                        <p class="text-muted small">خصومات طبية تصل إلى 80% في جميع التخصصات</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="feature-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-hospital fs-2"></i>
                        </div>
                        <h5 class="fw-bold">تغطية واسعة</h5>
                        <p class="text-muted small">أكثر من 4500 مركز طبي ومستشفى في المملكة</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="feature-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-infinity fs-2"></i>
                        </div>
                        <h5 class="fw-bold">استخدام غير محدود</h5>
                        <p class="text-muted small">استخدم بطاقتك بلا حدود طوال فترة صلاحيتها</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="feature-icon bg-info text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-bolt fs-2"></i>
                        </div>
                        <h5 class="fw-bold">تفعيل فوري</h5>
                        <p class="text-muted small">تعمل البطاقة فورًا بعد الاشتراك بدون انتظار</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Offers Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-2">العروض الحالية</h2>
                <p class="lead text-muted">استفد من عروضنا الحصرية واحصل على أفضل الخصومات على الخدمات الطبية</p>
            </div>
            <div class="row g-4 justify-content-center">
                @forelse($featuredOffers as $offer)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($offer->image)
                            <img src="{{ asset('storage/' . $offer->image) }}" alt="{{ $offer->title }}" class="card-img-top object-fit-cover" style="height: 180px;" />
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="bi bi-percent text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">{{ $offer->title }}</h5>
                            <p class="text-muted small mb-2">{{ Str::limit($offer->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-success"><i class="bi bi-percent me-1"></i> خصم {{ $offer->discount_percentage }}%</span>
                                <span class="text-muted small"><i class="bi bi-calendar me-1"></i> {{ $offer->start_date->format('Y/m/d') }} - {{ $offer->end_date->format('Y/m/d') }}</span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted"><i class="bi bi-hospital me-1"></i> {{ $offer->medicalCenter->name ?? 'غير محدد' }}</small>
                            </div>
                            <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-outline-primary w-100">التفاصيل <i class="bi bi-arrow-left ms-2"></i></a>
                        </div>
                    </div>
                </div>
                @empty
                <!-- عروض افتراضية في حالة عدم وجود عروض -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="bi bi-percent text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">خصومات حصرية</h5>
                            <p class="text-muted small mb-2">استفد من خصومات كبيرة على الخدمات الطبية</p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-success"><i class="bi bi-percent me-1"></i> خصم حتى 80%</span>
                            </div>
                            <a href="{{ route('offers') }}" class="btn btn-outline-primary w-100">اكتشف العروض <i class="bi bi-arrow-left ms-2"></i></a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('offers') }}" class="btn btn-outline-primary btn-lg px-5">مشاهدة المزيد من العروض <i class="bi bi-arrow-left ms-2"></i></a>
            </div>
        </div>
    </section>

    <!-- Medical Centers Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-2">المراكز الطبية</h2>
                <p class="lead text-muted">اكتشف شبكتنا الواسعة من المراكز الطبية المتميزة في جميع أنحاء المملكة</p>
            </div>
            <div class="row g-4 justify-content-center">
                @forelse($featuredMedicalCenters->take(3) as $center)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($center->image)
                            <img src="{{ asset('storage/' . $center->image) }}" alt="{{ $center->name }}" class="card-img-top object-fit-cover" style="height: 180px;" />
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="bi bi-hospital text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">{{ $center->name }}</h5>
                            <div class="d-flex align-items-center mb-2">
                                <div class="text-warning me-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= ($center->rating ?? 4))
                                            <i class="bi bi-star-fill"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-muted small">{{ number_format($center->rating ?? 4, 1) }} ({{ $center->reviews_count ?? 0 }})</span>
                            </div>
                            @if($center->medical_service_types)
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">التخصصات:</small>
                                @foreach(array_slice($center->medical_service_types, 0, 2) as $service)
                                    <span class="badge bg-success me-1">{{ $service }}</span>
                                @endforeach
                                @if(count($center->medical_service_types) > 2)
                                    <span class="badge bg-secondary">+{{ count($center->medical_service_types) - 2 }}</span>
                                @endif
                            </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small"><i class="bi bi-geo-alt me-1"></i> {{ $center->city }}</span>
                                <a href="{{ route('medical-center.detail', $center->slug) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye me-1"></i> عرض التفاصيل</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- مركز افتراضي في حالة عدم وجود مراكز -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="bi bi-hospital text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2">شبكة واسعة من المراكز</h5>
                            <p class="text-muted small mb-2">اكتشف شبكتنا الواسعة من المراكز الطبية المتميزة</p>
                            <a href="{{ route('medical-centers.index') }}" class="btn btn-outline-primary w-100">اكتشف المراكز <i class="bi bi-arrow-left ms-2"></i></a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('medical-centers.index') }}" class="btn btn-outline-primary btn-lg px-5">مشاهدة جميع المراكز الطبية <i class="bi bi-arrow-left ms-2"></i></a>
            </div>
        </div>
    </section>

    <!-- Unique Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-2">المزايا الفريدة</h2>
                <p class="lead text-muted">تتميز بطاقة التكافل الصحي بالعديد من المزايا الفريدة التي تجعلها الخيار الأمثل لك</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="feature-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-percent fs-3"></i>
                        </div>
                        <h5 class="fw-bold">خصومات كبيرة</h5>
                        <p class="text-muted small">خصومات بين 30% إلى 80% على جميع الخدمات الطبية</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="feature-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-infinity fs-3"></i>
                        </div>
                        <h5 class="fw-bold">استخدام غير محدود</h5>
                        <p class="text-muted small">استخدم بطاقتك لعدد غير محدود من المرات خلال فترة صلاحيتها</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="feature-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-bolt fs-3"></i>
                        </div>
                        <h5 class="fw-bold">بدون فترات انتظار</h5>
                        <p class="text-muted small">بطاقتك تعمل فورًا بعد الدفع، بدون فترات انتظار لتفعيل الخدمة</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 text-center p-4">
                        <div class="feature-icon bg-info text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                        <h5 class="fw-bold">لجميع الفئات</h5>
                        <p class="text-muted small">متاحة للمواطنين والمقيمين والزائرين والمعتمرين وحجاج بيت الله الحرام</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Specialties & Services Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold mb-2"><i class="bi bi-stethoscope text-primary ms-2"></i> التخصصات والخدمات المشمولة</h2>
                <p class="lead text-muted">بطاقة التكافل الصحي تشمل جميع التخصصات الطبية والعلاجية، وتغطي العمليات، الأسنان، الحمل والولادة، التجميل، الليزر، العيون، الجلدية، الأطفال، النساء، الرجال، كبار السن، وأكثر من ذلك.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="mb-3"><i class="bi bi-tooth fs-2 text-info"></i></div>
                        <h6 class="fw-bold">طب الأسنان</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="mb-3"><i class="bi bi-heart-pulse fs-2 text-danger"></i></div>
                        <h6 class="fw-bold">العمليات الجراحية</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="mb-3"><i class="bi bi-baby fs-2 text-warning"></i></div>
                        <h6 class="fw-bold">الحمل والولادة</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="mb-3"><i class="bi bi-eye fs-2 text-primary"></i></div>
                        <h6 class="fw-bold">العيون والليزر</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="mb-3"><i class="bi bi-person-badge fs-2 text-success"></i></div>
                        <h6 class="fw-bold">جميع التخصصات الطبية</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <div class="mb-3"><i class="bi bi-spa fs-2 text-secondary"></i></div>
                        <h6 class="fw-bold">الجلدية والتجميل</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- البطاقة بالأرقام -->
    <section class="py-5 position-relative" style="background: url('/images/saudi-map-bg.jpg') center/cover no-repeat;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background:rgba(0,30,60,0.65);"></div>
        <div class="container position-relative z-2">
            <div class="text-center mb-5 text-white">
                <h2 class="fw-bold mb-4" style="font-size:2.2rem;">البطاقة بالأرقام</h2>
            </div>
            <div class="row justify-content-center text-white">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="bg-dark bg-opacity-50 rounded-4 p-4 text-center">
                        <div class="h1 fw-bold mb-2">{{ number_format($stats['subscribers_count']) }}+</div>
                        <div class="fs-5">عميل مشترك</div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="bg-dark bg-opacity-50 rounded-4 p-4 text-center">
                        <div class="h1 fw-bold mb-2">{{ number_format($stats['medical_centers_count']) }}+</div>
                        <div class="fs-5">مركز طبي</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-dark bg-opacity-50 rounded-4 p-4 text-center">
                        <div class="h1 fw-bold mb-2">{{ $stats['regions_count'] }}+</div>
                        <div class="fs-5">منطقة مغطاة</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- آراء العملاء -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">آراء العملاء</h2>
                <p class="lead text-muted">تمنح هذه القصص نظرة فريدة عن كيفية تأثير بطاقاتنا في تحسين الرعاية الصحية وتوفير التكاليف، مما يساعدك في اتخاذ قرار مستنير بخصوص رعايتك الصحية.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-4 mb-4">
                    <div class="bg-light rounded-4 p-4 h-100 shadow-sm">
                        <div class="mb-3"><span class="text-success fs-1">“</span></div>
                        <div class="mb-3 text-muted">استفدت كثيرًا من بطاقة التكافل الصحي، حيث تم توفير خدمات طبية ممتازة، سهلة الاستخدام وتوفير تكاليف معقولة.</div>
                        <div class="fw-bold">محمد صالح</div>
                        <div class="text-muted small">الرياض</div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="bg-light rounded-4 p-4 h-100 shadow-sm">
                        <div class="mb-3"><span class="text-success fs-1">“</span></div>
                        <div class="mb-3 text-muted">تجربتي مع التكافل الصحي كانت إيجابية جدًا. استفدت من تخفيضات كبيرة على الأدوية والفحوصات.</div>
                        <div class="fw-bold">نورة محمد</div>
                        <div class="text-muted small">جدة</div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="bg-light rounded-4 p-4 h-100 shadow-sm">
                        <div class="mb-3"><span class="text-success fs-1">“</span></div>
                        <div class="mb-3 text-muted">الخدمات الشاملة التي قدمتها البطاقة منحتني راحة البال والراحة. كنت قادراً على الحصول على العلاج اللازم دون عناء.</div>
                        <div class="fw-bold">خالد بندر</div>
                        <div class="text-muted small">الرياض</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light" id="faq">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold mb-2"><i class="bi bi-question-circle text-success ms-2"></i>أسئلة متكررة</h2>
                <p class="lead text-muted">سؤالك غير موجود؟ <a href="https://wa.me/966920031304" target="_blank" class="text-success fw-bold">تواصل معنا مباشرة</a> وسنجيبك بكل سرور</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    <i class="bi bi-person-plus text-primary ms-2"></i> كيف يمكنني الاشتراك؟
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    يمكنك الاشتراك عبر الموقع من خلال تعبئة نموذج الاشتراك واختيار الباقة المناسبة، أو التواصل معنا عبر الواتساب للحصول على مساعدة شخصية.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                    <i class="bi bi-heart text-danger ms-2"></i> ماهي فائدة البطاقة؟
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    البطاقة تمنحك خصومات طبية مباشرة في أكثر من 4500 مركز طبي ومستشفى، وتوفر عليك الكثير من التكاليف الطبية مع خصومات تصل إلى 80%.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                    <i class="bi bi-cash-coin text-warning ms-2"></i> ماهو سعر الاشتراك في البطاقة؟
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    تختلف الأسعار حسب الباقة المختارة وعدد الأفراد، يمكنك الاطلاع على الأسعار في صفحة الباقات أو التواصل معنا للحصول على عرض مخصص.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="faq4">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                    <i class="bi bi-x-lg text-secondary ms-2"></i> هل أستطيع إلغاء الاشتراك؟
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نعم، يمكنك إلغاء الاشتراك في أي وقت عبر التواصل مع خدمة العملاء. نحن نقدر اختيارك ونسعى لتقديم أفضل خدمة ممكنة.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="https://wa.me/966920031304" target="_blank" class="btn btn-success btn-lg px-4"><i class="bi bi-whatsapp me-2"></i>تواصل معنا عبر الواتساب</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Final CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="cta-content" data-aos="zoom-in">
                        <h2 class="display-5 fw-bold mb-4">احصل على بطاقتك الآن واستمتع بخصومات حصرية!</h2>
                        <p class="lead mb-4">انضم إلى الآلاف من المشتركين واستفد من خصومات كبيرة على الخدمات الطبية</p>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('subscribe') }}" class="btn btn-warning btn-lg px-5"><i class="bi bi-rocket me-2"></i> اشترك الآن</a>
                            <a href="{{ route('card.request') }}" class="btn btn-outline-light btn-lg px-5"><i class="bi bi-credit-card me-2"></i> اطلب بطاقتك</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection



