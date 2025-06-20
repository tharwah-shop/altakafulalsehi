@extends('layouts.frontend')

@section('title', 'اشتراكاتي - التكافل الصحي')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-2">اشتراكاتي</h2>
                    <p class="text-muted mb-0">إدارة جميع اشتراكاتك في التكافل الصحي</p>
                </div>
                <a href="/subscribe" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>اشتراك جديد
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-card-checklist text-primary fs-4"></i>
                            </div>
                            <h4 class="fw-bold text-primary">0</h4>
                            <p class="text-muted mb-0">إجمالي الاشتراكات</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-check-circle text-success fs-4"></i>
                            </div>
                            <h4 class="fw-bold text-success">0</h4>
                            <p class="text-muted mb-0">اشتراكات نشطة</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-clock text-warning fs-4"></i>
                            </div>
                            <h4 class="fw-bold text-warning">0</h4>
                            <p class="text-muted mb-0">في انتظار الدفع</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-calendar-event text-info fs-4"></i>
                            </div>
                            <h4 class="fw-bold text-info">0</h4>
                            <p class="text-muted mb-0">تنتهي قريباً</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscriptions List -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>قائمة الاشتراكات</h5>
                </div>
                <div class="card-body">
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                            <i class="bi bi-inbox text-muted fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3">لا توجد اشتراكات حالياً</h4>
                        <p class="text-muted mb-4">لم تقم بإنشاء أي اشتراك بعد. ابدأ الآن واستمتع بخدماتنا المميزة.</p>
                        <a href="/subscribe" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-lg me-2"></i>إنشاء اشتراك جديد
                        </a>
                    </div>
                    
                    <!-- Future: Subscriptions Table will be here -->
                    <!--
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>رقم البطاقة</th>
                                    <th>نوع الاشتراك</th>
                                    <th>تاريخ البداية</th>
                                    <th>تاريخ الانتهاء</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Subscription rows will be here -->
                            </tbody>
                        </table>
                    </div>
                    -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
