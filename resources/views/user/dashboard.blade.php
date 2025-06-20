@extends('layouts.frontend')

@section('title', 'لوحة التحكم - التكافل الصحي')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Welcome Header -->
            <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                <div class="card-body text-white py-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-3">مرحباً بك، {{ Auth::user()->name }}!</h2>
                            <p class="mb-4 opacity-75">نحن سعداء لرؤيتك مرة أخرى. إليك نظرة سريعة على حسابك وأنشطتك الأخيرة.</p>
                            <div class="d-flex gap-3">
                                <a href="/subscribe" class="btn btn-light btn-lg">
                                    <i class="bi bi-plus-lg me-2"></i>اشتراك جديد
                                </a>
                                <a href="/medicalnetwork" class="btn btn-outline-light btn-lg">
                                    <i class="bi bi-hospital me-2"></i>الشبكة الطبية
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="bg-white bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i class="bi bi-person-fill text-white" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-card-checklist text-primary fs-4"></i>
                            </div>
                            <h4 class="fw-bold text-primary">0</h4>
                            <p class="text-muted mb-0">اشتراكاتي</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-hospital text-success fs-4"></i>
                            </div>
                            <h4 class="fw-bold text-success">{{ \App\Models\MedicalCenter::where('status', 'active')->count() }}</h4>
                            <p class="text-muted mb-0">المراكز المتاحة</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-tags text-warning fs-4"></i>
                            </div>
                            <h4 class="fw-bold text-warning">{{ \App\Models\Offer::where('status', 'active')->count() }}</h4>
                            <p class="text-muted mb-0">العروض النشطة</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-percent text-info fs-4"></i>
                            </div>
                            <h4 class="fw-bold text-info">30-80%</h4>
                            <p class="text-muted mb-0">نسبة الخصم</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-lightning-fill me-2"></i>الإجراءات السريعة</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="/subscribe" class="btn btn-outline-primary w-100 py-3">
                                        <i class="bi bi-plus-circle-fill fs-4 d-block mb-2"></i>
                                        <strong>اشتراك جديد</strong>
                                        <small class="d-block text-muted">احصل على بطاقة التكافل الصحي</small>
                                    </a>
                                </div>
                                
                                <div class="col-md-6">
                                    <a href="/my-subscriptions" class="btn btn-outline-success w-100 py-3">
                                        <i class="bi bi-card-list fs-4 d-block mb-2"></i>
                                        <strong>اشتراكاتي</strong>
                                        <small class="d-block text-muted">إدارة اشتراكاتك الحالية</small>
                                    </a>
                                </div>
                                
                                <div class="col-md-6">
                                    <a href="/medicalnetwork" class="btn btn-outline-info w-100 py-3">
                                        <i class="bi bi-hospital fs-4 d-block mb-2"></i>
                                        <strong>الشبكة الطبية</strong>
                                        <small class="d-block text-muted">استكشف المراكز المتاحة</small>
                                    </a>
                                </div>
                                
                                <div class="col-md-6">
                                    <a href="/offers" class="btn btn-outline-warning w-100 py-3">
                                        <i class="bi bi-gift fs-4 d-block mb-2"></i>
                                        <strong>العروض الخاصة</strong>
                                        <small class="d-block text-muted">اكتشف أحدث العروض</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-person-gear me-2"></i>إعدادات الحساب</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/profile" class="btn btn-outline-secondary">
                                    <i class="bi bi-person me-2"></i>الملف الشخصي
                                </a>
                                <a href="/contact" class="btn btn-outline-secondary">
                                    <i class="bi bi-headset me-2"></i>الدعم الفني
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="bi bi-box-arrow-right me-2"></i>تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
