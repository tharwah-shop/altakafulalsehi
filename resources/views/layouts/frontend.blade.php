<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'التكافل الصحي')</title>
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <!-- Cairo Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link href="{{ asset('css/select2-custom.css') }}" rel="stylesheet" />
    <!-- Medical Centers CSS -->
    <link href="{{ asset('css/medical-centers.css') }}" rel="stylesheet">
    <!-- Custom User Menu CSS -->
    <style>
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }
        .dropdown-item {
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
            transform: translateX(-2px);
        }
        .dropdown-divider {
            margin: 0.5rem 0;
        }
        @media (max-width: 991.98px) {
            .dropdown-menu {
                margin-bottom: 0.5rem !important;
            }
        }

    </style>
    @stack('styles')
</head>
<body class="font-cairo">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold" href="/">
                <img src="/images/logo-white.svg" alt="التكافل الصحي" class="me-2" height="40">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="/">الرئيسية</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">من نحن</a></li>
                    <li class="nav-item"><a class="nav-link" href="/medicalnetwork">الشبكة الطبية</a></li>
                    <li class="nav-item"><a class="nav-link" href="/offers">العروض</a></li>
                    <li class="nav-item"><a class="nav-link" href="/features">المميزات</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">تواصل معنا</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <!-- قائمة المستخدم المسجل -->
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-2"></i>
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="/dashboard"><i class="bi bi-speedometer2 me-2"></i>حسابي</a></li>
                                <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>الملف الشخصي</a></li>
                                <li><a class="dropdown-item" href="/my-subscriptions"><i class="bi bi-card-list me-2"></i>اشتراكاتي</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>تسجيل الخروج
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <a href="/subscribe" class="btn btn-secondary d-flex align-items-center shadow-sm">اشترك الآن</a>
                    @else
                        <!-- أزرار للمستخدم غير المسجل -->
                        <a href="/login" class="btn btn-outline-light d-flex align-items-center">دخول</a>
                        <a href="/subscribe" class="btn btn-secondary d-flex align-items-center shadow-sm">اشترك الآن</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main class="flex-grow-1 pt-5">
        @yield('content')
    </main>
    <footer class="bg-dark text-white mt-5">
        <div class="container py-5">
            <div class="row gy-5 text-center text-md-start">
                <div class="col-lg-4 col-md-6">
                    <a href="/" class="d-inline-block mb-4">
                        <img src="/images/logo.png" alt="التكافل الصحي" class="img-fluid" style="max-height: 70px; filter: brightness(0) invert(1);">
                    </a>
                    <h4 class="fw-bold mb-3">التكافل الصحي</h4>
                    <p class="text-light mb-4 lh-lg">أقوى بطاقة علاج نقدي في السعودية والخليج تقوم على فكرة الخصم النقدي المباشر بأفضل وأقل الأسعار <span class="text-warning fw-bold">(خصومات من 30% حتى 80%)</span> في جميع التخصصات الطبية</p>
                    <div class="d-flex gap-3 mb-4 flex-wrap justify-content-center justify-content-md-start">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-snapchat"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold mb-4 border-bottom border-primary pb-2">معلومات التواصل</h5>
                    <ul class="list-unstyled">
                        <li class="d-flex align-items-center mb-4 p-3 rounded-3 bg-light">
                            <div class="me-3 d-flex align-items-center justify-content-center rounded-circle bg-primary text-white"><i class="bi bi-envelope"></i></div>
                            <div><div class="fw-bold text-dark">البريد الإلكتروني</div><div class="text-muted">info@altakafulalsehi.com</div></div>
                        </li>
                        <li class="d-flex align-items-center mb-4 p-3 rounded-3 bg-light">
                            <div class="me-3 d-flex align-items-center justify-content-center rounded-circle bg-primary text-white"><i class="bi bi-phone"></i></div>
                            <div><div class="fw-bold text-dark">رقم الهاتف</div><div class="text-muted">920031304</div></div>
                        </li>
                        <li class="d-flex align-items-center mb-4 p-3 rounded-3 bg-light">
                            <div class="me-3 d-flex align-items-center justify-content-center rounded-circle bg-primary text-white"><i class="bi bi-clock"></i></div>
                            <div><div class="fw-bold text-dark">ساعات العمل</div><div class="text-muted">السبت - الخميس: 10:00 - 19:00</div></div>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-secondary d-flex align-items-center justify-content-center mt-auto">تواصل واتساب</a>
                </div>
                <div class="col-lg-4 col-md-12">
                    <h5 class="fw-bold mb-4 border-bottom border-primary pb-2">روابط سريعة</h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <li class="mb-3"><a href="/" class="text-decoration-none d-flex align-items-center p-2 rounded-3">الرئيسية</a></li>
                                <li class="mb-3"><a href="/medicalnetwork" class="text-decoration-none d-flex align-items-center p-2 rounded-3">الشبكة الطبية</a></li>
                                <li class="mb-3"><a href="/how-it-works" class="text-decoration-none d-flex align-items-center p-2 rounded-3">كيف تعمل البطاقة</a></li>
                                <li class="mb-3"><a href="/features" class="text-decoration-none d-flex align-items-center p-2 rounded-3">مميزات البطاقة</a></li>
                                <li class="mb-3"><a href="/card-request" class="text-decoration-none d-flex align-items-center p-2 rounded-3">اطلب بطاقتك</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <li class="mb-3"><a href="/about" class="text-decoration-none d-flex align-items-center p-2 rounded-3">من نحن</a></li>
                                <li class="mb-3"><a href="/offers" class="text-decoration-none d-flex align-items-center p-2 rounded-3">العروض</a></li>
                                <li class="mb-3"><a href="/faq" class="text-decoration-none d-flex align-items-center p-2 rounded-3">الأسئلة الشائعة</a></li>
                                <li class="mb-3"><a href="/testimonials" class="text-decoration-none d-flex align-items-center p-2 rounded-3">آراء العملاء</a></li>
                                <li class="mb-3"><a href="/subscribe" class="text-decoration-none d-flex align-items-center p-2 rounded-3">اشترك الآن</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-top border-secondary pt-4 mt-5">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0 text-light">&copy; 2024 التكافل الصحي. جميع الحقوق محفوظة.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="d-flex justify-content-center justify-content-md-end gap-4">
                            <a href="#" class="text-decoration-none text-light small">سياسة الخصوصية</a>
                            <a href="#" class="text-decoration-none text-light small">شروط الاستخدام</a>
                            <a href="#" class="text-decoration-none text-light small">خريطة الموقع</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <nav class="navbar navbar-dark bg-primary fixed-bottom d-lg-none shadow-lg">
        <div class="container-fluid">
            <div class="row w-100 g-0">
                <div class="col"><a href="/" class="btn btn-link text-white d-flex flex-column align-items-center py-2"><i class="bi bi-house fs-5"></i><small>الرئيسية</small></a></div>
                <div class="col"><a href="/medicalnetwork" class="btn btn-link text-white d-flex flex-column align-items-center py-2"><i class="bi bi-hospital fs-5"></i><small>المراكز</small></a></div>
                <div class="col"><a href="/subscribe" class="btn btn-secondary d-flex flex-column align-items-center py-2 rounded-3 mx-2" style="margin-top: -20px;"><i class="bi bi-plus-lg fs-4"></i><small>اشترك</small></a></div>
                <div class="col"><a href="/offers" class="btn btn-link text-white d-flex flex-column align-items-center py-2"><i class="bi bi-tags fs-5"></i><small>العروض</small></a></div>
                @auth
                    <div class="col">
                        <div class="dropdown dropup">
                            <button class="btn btn-link text-white d-flex flex-column align-items-center py-2 border-0" type="button" id="mobileUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-5"></i><small>حسابي</small>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end mb-2" aria-labelledby="mobileUserDropdown">
                                <li><a class="dropdown-item" href="/dashboard"><i class="bi bi-speedometer2 me-2"></i>لوحة التحكم</a></li>
                                <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>الملف الشخصي</a></li>
                                <li><a class="dropdown-item" href="/my-subscriptions"><i class="bi bi-card-list me-2"></i>اشتراكاتي</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>تسجيل الخروج
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="col"><a href="/login" class="btn btn-link text-white d-flex flex-column align-items-center py-2"><i class="bi bi-box-arrow-in-right fs-5"></i><small>دخول</small></a></div>
                @endauth
            </div>
        </div>
    </nav>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ar.js"></script>

    <!-- Select2 Initialization -->
    <script src="{{ asset('js/select2-init.js') }}"></script>

    @stack('scripts')
</body>
</html>
