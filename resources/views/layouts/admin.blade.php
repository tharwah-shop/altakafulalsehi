<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#00bcd4">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="التكافل الصحي">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - التكافل الصحي</title>

    <!-- Keen Demo 1 RTL Stylesheets -->
    <link href="https://preview.keenthemes.com/keen/demo1/assets/plugins/global/plugins.bundle.rtl.css" rel="stylesheet" type="text/css"/>
    <link href="https://preview.keenthemes.com/keen/demo1/assets/css/style.bundle.rtl.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link href="{{ asset('css/select2-custom.css') }}" rel="stylesheet" />
    <style>
        body, .admin-body { font-family: 'Cairo', Tahoma, Arial, sans-serif !important; background: #f5f6fa; }
        .sidebar-logo { max-width: 120px; }
        .admin-sidebar { background: #181C32; color: #fff; min-width: 250px; max-width: 250px; height: 100vh; position: fixed; right: 0; top: 0; z-index: 100; overflow-y: auto; }
        .admin-main { margin-right: 250px; min-height: 100vh; background: #f5f6fa; }
        .admin-header { background: #fff; border-bottom: 1px solid #e4e6ef; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 99; }
        .sidebar-header { padding: 2rem 1rem 1rem 1rem; text-align: center; border-bottom: 1px solid #23263a; }
        .sidebar-title { font-size: 1.2rem; font-weight: bold; margin-bottom: 0.2rem; }
        .sidebar-subtitle { font-size: 0.9rem; color: #b5b5c3; }
        .sidebar-nav { padding: 1rem 0; }
        .nav-section-title { color: #b5b5c3; font-size: 0.95rem; font-weight: bold; margin: 1.5rem 1rem 0.5rem 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .nav-item { margin: 0.2rem 0; }
        .nav-link { color: #fff; display: flex; align-items: center; gap: 0.7rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem 0 0 0.5rem; text-decoration: none; transition: background 0.2s; }
        .nav-link.active, .nav-link:hover { background: #21244a; color: #00e3a1; }
        .admin-content { padding: 2rem 2.5rem; }
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); border-radius: 0.5rem; }
        .card-header { background: #f8f9fa; border-bottom: 1px solid #e9ecef; }
        .btn { border-radius: 0.375rem; }
        .form-control, .form-select { border-radius: 0.375rem; }
        .table th { border-top: none; font-weight: 600; color: #5a6c7d; }
        .badge { font-size: 0.75rem; }
        .alert { border-radius: 0.5rem; border: none; }
        .nav-link { border-radius: 0.375rem; margin-bottom: 0.25rem; }
        .nav-link:hover { background-color: rgba(255, 255, 255, 0.1); }
        .nav-link.active { background-color: rgba(255, 255, 255, 0.2); font-weight: 600; }
        @media (max-width: 991px) {
            .admin-sidebar { position: fixed; right: -250px; transition: right 0.3s; }
            .admin-sidebar.open { right: 0; }
            .admin-main { margin-right: 0; }
        }

    </style>
    @stack('styles')
    @yield('styles')
</head>
<body class="admin-body">
    <!-- Sidebar -->
    <nav class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo-white.svg') }}" alt="التكافل الصحي" class="sidebar-logo mb-2">
            <h5 class="sidebar-title">التكافل الصحي</h5>
            <p class="sidebar-subtitle">لوحة التحكم الإدارية</p>
        </div>
        <div class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-item">
                    <a href="/admin" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>لوحة التحكم</span>
                    </a>
                </div>
            </div>
            <div class="nav-section">
                <div class="nav-section-title"><i class="fas fa-users"></i>إدارة العملاء</div>
                <div class="nav-item">
                    <a href="{{ route('admin.subscribers.index') }}" class="nav-link {{ request()->is('admin/subscribers*') ? 'active' : '' }}">
                        <i class="fas fa-id-card"></i>
                        <span>المشتركين</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.potential-customers.index') }}" class="nav-link {{ request()->is('admin/potential-customers*') ? 'active' : '' }}">
                        <i class="fas fa-user-clock"></i>
                        <span>العملاء المحتملين</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                        <i class="fas fa-user-cog"></i>
                        <span>المستخدمون</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i>
                        <span>الأدوار والصلاحيات</span>
                    </a>
                </div>
            </div>
            <div class="nav-section">
                <div class="nav-section-title"><i class="fas fa-hospital"></i>الشبكة الطبية</div>
                <div class="nav-item">
                    <a href="{{ route('admin.medical-centers.index') }}" class="nav-link {{ request()->is('admin/medical-centers*') ? 'active' : '' }}">
                        <i class="fas fa-hospital-alt"></i>
                        <span>المراكز الطبية</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.offers.index') }}" class="nav-link {{ request()->is('admin/offers*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>العروض الطبية</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->is('admin/reviews*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        <span>تقييمات المراكز</span>
                    </a>
                </div>
            </div>
            <div class="nav-section">
                <div class="nav-section-title"><i class="fas fa-box"></i>الباقات والاشتراكات</div>
                <div class="nav-item">
                    <a href="{{ route('admin.packages.index') }}" class="nav-link {{ request()->is('admin/packages*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>إدارة الباقات</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->is('admin/payments*') ? 'active' : '' }}">
                        <i class="fas fa-credit-card"></i>
                        <span>إدارة المدفوعات</span>
                    </a>
                </div>
            </div>
            </div>
            <div class="nav-section">
                <div class="nav-section-title"><i class="fas fa-cog"></i>الإعدادات</div>
                <div class="nav-item">
                    {{-- <a href="{{ route('security.index') }}" class="nav-link {{ request()->is('admin/security*') ? 'active' : '' }}"> --}}
                        <i class="fas fa-shield-alt"></i>
                        <span>إعدادات الأمان</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Main Content Area -->
    <div class="admin-main" id="adminMain">
        <!-- Header -->
        <header class="admin-header">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle d-lg-none btn btn-light" type="button" aria-label="فتح القائمة" onclick="document.getElementById('adminSidebar').classList.toggle('open')">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-title">
                    <h1 class="mb-0 fs-3 fw-bold">@yield('content-header', 'لوحة التحكم')</h1>
                    <p class="page-subtitle text-muted mb-0">@yield('content-subtitle', 'إدارة النظام')</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if(isset($notificationsCount) && $notificationsCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $notificationsCount }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3" style="min-width: 350px;">
                        <div class="dropdown-header bg-primary bg-opacity-10 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-primary fw-bold">
                                <i class="fas fa-bell me-2"></i>الإشعارات
                            </h6>
                            <span class="badge bg-primary rounded-pill">{{ $notificationsCount ?? 0 }}</span>
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        @if(isset($recentNotifications) && $recentNotifications->count() > 0)
                            @foreach($recentNotifications as $notification)
                                <a class="dropdown-item py-3 border-bottom" href="#">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold text-dark">{{ $notification->title }}</div>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="dropdown-item text-center py-5">
                                <i class="fas fa-bell-slash text-muted fa-3x mb-3"></i>
                                <div class="text-muted fw-semibold">لا توجد إشعارات جديدة</div>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- User Menu -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-bold text-dark">المدير</div>
                            <small class="text-muted">مدير النظام</small>
                        </div>
                        <i class="fas fa-chevron-down text-muted d-none d-md-inline"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3" style="min-width: 280px;">
                        <div class="dropdown-header bg-primary bg-opacity-10">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-primary">المدير</div>
                                    <small class="text-muted">مدير النظام</small>
                                    <div class="badge bg-success rounded-pill mt-1">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>متصل
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <a class="dropdown-item py-2" href="#">
                            <i class="fas fa-user me-3 text-primary"></i>
                            الملف الشخصي
                        </a>
                        <a class="dropdown-item py-2" href="#">
                            <i class="fas fa-cog me-3 text-primary"></i>
                            الإعدادات
                        </a>
                        <a class="dropdown-item py-2" href="#">
                            <i class="fas fa-key me-3 text-primary"></i>
                            تغيير كلمة المرور
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item py-2 text-danger" href="#">
                            <i class="fas fa-sign-out-alt me-3"></i>
                            تسجيل الخروج
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <!-- Content Area -->
        <div class="admin-content">
            @yield('content')
        </div>
    </div>
    <!-- Keen JS -->
    <script src="https://preview.keenthemes.com/keen/demo1/assets/plugins/global/plugins.bundle.js"></script>
    <script src="https://preview.keenthemes.com/keen/demo1/assets/js/scripts.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ar.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.querySelectorAll('.sidebar-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('adminSidebar').classList.toggle('open');
            });
        });
        // دعم التنبيهات السريعة
        window.showSuccess = function(msg) {
            Swal.fire({icon: 'success', title: 'نجاح', text: msg, confirmButtonText: 'حسنًا'});
        };
        window.showError = function(msg) {
            Swal.fire({icon: 'error', title: 'خطأ', text: msg, confirmButtonText: 'إغلاق'});
        };

        // Load Select2 initialization script
        $.getScript('{{ asset("js/select2-init.js") }}');
    </script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>

