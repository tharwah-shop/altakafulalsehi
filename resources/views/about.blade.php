@extends('layouts.frontend')

@section('title', 'من نحن')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/frontend/hero.css') }}">
<link rel="stylesheet" href="{{ asset('css/frontend/about.css') }}">
<!-- Font Awesome (All Styles, including Brands) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@section('content')
    <!-- مقدمة تعريفية -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <h1 class="fw-bold mb-3">عن التكافل الصحي</h1>
                    <p class="lead text-muted mb-4">
                        نحن في مجال التكافل الصحي نسعى لتقديم حلول رعاية صحية شاملة ومبتكرة. نقدم بطاقات تكافل تلبي احتياجات الأفراد والعائلات، ونوفر خصومات نقدية في جميع المجالات الطبية كالعمليات الجراحية، الكشوفات، الأشعة، التحاليل، متابعة الحمل والولادة، الأسنان، التجميل، وغيرها، بمشاركة العديد من المستشفيات والمراكز الطبية الكبرى في المملكة.<br>
                        <span class="fw-bold">موقع الشركة:</span> جدة، المملكة العربية السعودية.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li><i class="bi bi-check-circle text-success me-2"></i> تخدم الأفراد، الهيئات، الشركات، الجمعيات التعاونية والخيرية</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i> متاحة لجميع الفئات</li>
                    </ul>
                </div>
                <div class="col-lg-5 text-center">
                    <img src="/images/logo-white.svg" alt="بطاقة التكافل الصحي" class="img-fluid" style="max-width: 320px;">
                </div>
            </div>
        </div>
    </section>

    <!-- رؤية ورسالة -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold mb-3">رؤيتنا ورسالتنا</h2>
                    <p class="lead text-muted">نطور استراتيجياتنا في ظل رؤية المملكة 2030 لنحافظ على ريادتنا ونبتكر حلولاً تأمينية لحماية الأفراد والمنشآت.</p>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center">
                        <div class="mb-3"><i class="bi bi-eye fs-1 text-primary"></i></div>
                        <h4 class="fw-bold mb-2">رؤيتنا</h4>
                        <p class="text-muted">أن نكون الخيار الأول في تقديم الخدمات الطبية التأمينية المبتكرة، وتمكين المجتمع من التقدم بأمان نحو المستقبل.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center">
                        <div class="mb-3"><i class="bi bi-bullseye fs-1 text-success"></i></div>
                        <h4 class="fw-bold mb-2">رسالتنا</h4>
                        <p class="text-muted">تقديم حلول رعاية صحية شاملة وخصومات نقدية في جميع التخصصات الطبية، مع شراكات واسعة مع أفضل المراكز والمستشفيات.</p>
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
                        <div class="h1 fw-bold mb-2">+4.2M</div>
                        <div class="fs-5">مليون عميل</div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="bg-dark bg-opacity-50 rounded-4 p-4 text-center">
                        <div class="h1 fw-bold mb-2">+3,500</div>
                        <div class="fs-5">مركز طبي</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-dark bg-opacity-50 rounded-4 p-4 text-center">
                        <div class="h1 fw-bold mb-2">+10.8M</div>
                        <div class="fs-5">مليون عملية خصم</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- شركاؤنا -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">شركاؤنا</h2>
                <p class="lead text-muted">أفضل المراكز الطبية في المملكة هم شركاء بطاقة التكافل الصحي. لدينا أكثر من 3500 مستشفى ومركز طبي شريك.</p>
                <a href="#" class="btn btn-outline-primary btn-lg px-5">شاهد الشبكة الطبية</a>
            </div>
        </div>
    </section>

    <!-- مميزات البطاقة -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">مميزات بطاقة التكافل الصحي</h2>
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

    <!-- دعوة لاتخاذ إجراء -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">ماعندك البطاقة للحين؟</h2>
            <p class="lead mb-4">اطلبها الآن ووفر الكثير من الأموال واحصل على خصومات طبية رهيبة في 3500 مركز طبي في المملكة.</p>
            <a href="#" class="btn btn-warning btn-lg px-5 fw-bold">اطلب بطاقتك الآن</a>
        </div>
    </section>

    <!-- بيانات التواصل -->
    <section class="py-5 bg-white border-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <h4 class="fw-bold mb-3">تواصل معنا</h4>
                    <ul class="list-unstyled mb-3">
                        <li class="mb-2"><i class="bi bi-envelope-at text-primary me-2"></i> البريد الإلكتروني: <a href="mailto:info@altakafulalsehi.com">info@altakafulalsehi.com</a></li>
                        <li class="mb-2"><i class="bi bi-telephone text-primary me-2"></i> 920031304</li>
                        <li class="mb-2"><i class="bi bi-clock text-primary me-2"></i> السبت - الخميس 10:00 - 19:00</li>
                        <li class="mb-2"><i class="bi bi-clock text-primary me-2"></i> الجمعة - مغلق</li>
                    </ul>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="text-primary fs-4"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-primary fs-4"><i class="bi bi-snapchat"></i></a>
                        <a href="#" class="text-primary fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-primary fs-4"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-primary fs-4"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="text-primary fs-4"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


