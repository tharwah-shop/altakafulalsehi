@extends('layouts.admin')

@section('title', 'لوحة التحكم الرئيسية')
@section('content-header', 'لوحة التحكم')
@section('content-subtitle', 'نظرة عامة على النظام')

@section('content')
<div class="row g-4">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-1 fw-bold">{{ $stats['medical_centers'] ?? 0 }}</h3>
                    <p class="mb-0">المراكز الطبية</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3">
                    <i class="fas fa-hospital fa-2x"></i>
                </div>
            </div>
            <div class="card-footer bg-white bg-opacity-10 border-0">
                <a href="{{ route('admin.medical-centers.index') }}" class="text-white text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i>عرض التفاصيل
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-1 fw-bold">{{ $stats['users'] ?? 0 }}</h3>
                    <p class="mb-0">المستخدمين</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3">
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
            <div class="card-footer bg-white bg-opacity-10 border-0">
                <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i>عرض التفاصيل
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-1 fw-bold">{{ $stats['reviews'] ?? 0 }}</h3>
                    <p class="mb-0">التقييمات</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3">
                    <i class="fas fa-star fa-2x"></i>
                </div>
            </div>
            <div class="card-footer bg-white bg-opacity-10 border-0">
                <a href="{{ route('admin.reviews.index') }}" class="text-white text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i>عرض التفاصيل
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-dark h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-1 fw-bold">{{ $stats['offers'] ?? 0 }}</h3>
                    <p class="mb-0">العروض النشطة</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3">
                    <i class="fas fa-tags fa-2x"></i>
                </div>
            </div>
            <div class="card-footer bg-white bg-opacity-10 border-0">
                <a href="{{ route('admin.offers.index') }}" class="text-dark text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i>عرض التفاصيل
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Packages Statistics -->
<div class="row g-4 mt-4">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-info text-white h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-1 fw-bold">{{ $stats['packages'] ?? 0 }}</h3>
                    <p class="mb-0">إجمالي الباقات</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3">
                    <i class="fas fa-box fa-2x"></i>
                </div>
            </div>
            <div class="card-footer bg-white bg-opacity-10 border-0">
                <a href="{{ route('admin.packages.index') }}" class="text-white text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i>عرض التفاصيل
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-1 fw-bold">{{ $stats['active_packages'] ?? 0 }}</h3>
                    <p class="mb-0">الباقات النشطة</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
            <div class="card-footer bg-white bg-opacity-10 border-0">
                <a href="{{ route('admin.packages.index', ['status' => 'active']) }}" class="text-white text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i>عرض التفاصيل
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-1 fw-bold">{{ $stats['subscribers'] ?? 0 }}</h3>
                    <p class="mb-0">إجمالي المشتركين</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3">
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
            <div class="card-footer bg-white bg-opacity-10 border-0">
                <a href="{{ route('admin.subscribers.index') }}" class="text-white text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i>عرض التفاصيل
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-1 fw-bold">{{ $stats['active_subscribers'] ?? 0 }}</h3>
                    <p class="mb-0">المشتركين النشطين</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3">
                    <i class="fas fa-user-check fa-2x"></i>
                </div>
            </div>
            <div class="card-footer bg-white bg-opacity-10 border-0">
                <a href="{{ route('admin.subscribers.index', ['status' => 'فعال']) }}" class="text-white text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i>عرض التفاصيل
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <!-- Recent Medical Centers -->
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-hospital text-primary me-2"></i>
                    أحدث المراكز الطبية
                </h5>
                <a href="{{ route('admin.medical-centers.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                @if(isset($recentCenters) && $recentCenters->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>اسم المركز</th>
                                    <th>المنطقة</th>
                                    <th>النوع</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإضافة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCenters as $center)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                                <i class="fas fa-hospital"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $center->name }}</div>
                                                <small class="text-muted">{{ $center->city }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $center->region }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $center->type }}</span>
                                    </td>
                                    <td>
                                        @if($center->status == 'active')
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>{{ $center->created_at->format('Y-m-d') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-hospital text-muted fa-3x mb-3"></i>
                        <p class="text-muted">لا توجد مراكز طبية مضافة بعد</p>
                        <a href="{{ route('admin.medical-centers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>إضافة مركز طبي
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt text-warning me-2"></i>
                    إجراءات سريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.medical-centers.create') }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="fas fa-plus me-2"></i>
                        إضافة مركز طبي جديد
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-success d-flex align-items-center">
                        <i class="fas fa-user-plus me-2"></i>
                        إدارة المستخدمين
                    </a>
                    
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-warning d-flex align-items-center">
                        <i class="fas fa-star me-2"></i>
                        مراجعة التقييمات
                    </a>

                    <a href="{{ route('admin.packages.create') }}" class="btn btn-outline-info d-flex align-items-center">
                        <i class="fas fa-plus me-2"></i>
                        إضافة باقة جديدة
                    </a>

                    <a href="{{ route('admin.subscribers.create') }}" class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="fas fa-user-plus me-2"></i>
                        إضافة مشترك جديد
                    </a>

                    <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="fas fa-external-link-alt me-2"></i>
                        عرض الموقع
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Packages and Subscribers -->
<div class="row g-4 mt-4">
    <!-- Recent Packages -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-box text-info me-2"></i>
                    أحدث الباقات
                </h5>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-sm btn-outline-info">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                @if(isset($recentPackages) && $recentPackages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>اسم الباقة</th>
                                    <th>السعر</th>
                                    <th>المدة</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPackages as $package)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($package->icon)
                                                <div class="me-3" style="color: {{ $package->color }};">
                                                    <i class="{{ $package->icon }}"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $package->name }}</div>
                                                @if($package->is_featured)
                                                    <small class="badge bg-warning text-dark">مميزة</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ number_format($package->price, 2) }} ريال</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $package->duration_text }}</span>
                                    </td>
                                    <td>
                                        @if($package->status === 'active')
                                            <span class="badge bg-success">نشط</span>
                                        @elseif($package->status === 'inactive')
                                            <span class="badge bg-danger">غير نشط</span>
                                        @else
                                            <span class="badge bg-warning">مسودة</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box text-muted fa-3x mb-3"></i>
                        <p class="text-muted">لا توجد باقات مضافة بعد</p>
                        <a href="{{ route('admin.packages.create') }}" class="btn btn-info">
                            <i class="fas fa-plus me-2"></i>إضافة باقة جديدة
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Subscribers -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users text-primary me-2"></i>
                    أحدث المشتركين
                </h5>
                <a href="{{ route('admin.subscribers.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                @if(isset($recentSubscribers) && $recentSubscribers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>المشترك</th>
                                    <th>الباقة</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الاشتراك</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSubscribers as $subscriber)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                                {{ substr($subscriber->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $subscriber->name }}</div>
                                                @if($subscriber->city)
                                                    <small class="text-muted">{{ $subscriber->city->name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($subscriber->package)
                                            <span class="badge bg-light text-dark">{{ $subscriber->package->name }}</span>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($subscriber->status) {
                                                'فعال' => 'success',
                                                'منتهي' => 'danger',
                                                'ملغي' => 'secondary',
                                                'معلق' => 'warning',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $subscriber->status }}</span>
                                    </td>
                                    <td>{{ $subscriber->created_at->format('Y-m-d') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users text-muted fa-3x mb-3"></i>
                        <p class="text-muted">لا يوجد مشتركين بعد</p>
                        <a href="{{ route('admin.subscribers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>إضافة مشترك جديد
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <!-- System Status -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-server text-success me-2"></i>
                    حالة النظام
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <div class="fw-bold">قاعدة البيانات</div>
                                <small class="text-success">متصلة</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <div class="fw-bold">الخادم</div>
                                <small class="text-success">يعمل بشكل طبيعي</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <div class="fw-bold">التخزين</div>
                                <small class="text-success">متاح</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <div class="fw-bold">النسخ الاحتياطي</div>
                                <small class="text-success">محدث</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock text-info me-2"></i>
                    النشاط الأخير
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <div class="fw-bold">تم إضافة مركز طبي جديد</div>
                            <small class="text-muted">منذ ساعتين</small>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <div class="fw-bold">تم تحديث بيانات مستخدم</div>
                            <small class="text-muted">منذ 4 ساعات</small>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <div class="fw-bold">تقييم جديد في انتظار المراجعة</div>
                            <small class="text-muted">منذ 6 ساعات</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e4e6ea;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .timeline-marker {
        position: absolute;
        left: -1.5rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
    }
    
    .timeline-content {
        padding-left: 1rem;
    }
</style>
@endpush
