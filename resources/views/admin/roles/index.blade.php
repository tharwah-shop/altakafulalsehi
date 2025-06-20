@extends('layouts.admin')

@section('title', 'إدارة الأدوار والصلاحيات')
@section('content-header', 'الأدوار والصلاحيات')
@section('content-subtitle', 'إدارة أدوار المستخدمين وصلاحياتهم')

@section('content')
<!-- Header Actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">الأدوار والصلاحيات</h2>
        <p class="text-muted mb-0">إدارة أدوار المستخدمين وصلاحياتهم في النظام</p>
    </div>
    <div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            إضافة دور جديد
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-user-shield text-primary me-2"></i>
            قائمة الأدوار
        </h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Default Roles -->
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-crown me-2"></i>
                            مدير عام
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">صلاحيات كاملة لإدارة النظام</p>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge bg-success">إدارة المستخدمين</span>
                            <span class="badge bg-success">إدارة المراكز الطبية</span>
                            <span class="badge bg-success">إدارة التقييمات</span>
                            <span class="badge bg-success">إدارة النظام</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-user-cog me-2"></i>
                            مدير
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">صلاحيات إدارية محدودة</p>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge bg-info">إدارة المراكز الطبية</span>
                            <span class="badge bg-info">إدارة التقييمات</span>
                            <span class="badge bg-info">عرض التقارير</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-hospital-user me-2"></i>
                            مدير مركز طبي
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">إدارة مركز طبي محدد</p>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge bg-warning text-dark">إدارة مركزه الطبي</span>
                            <span class="badge bg-warning text-dark">إدارة تقييمات مركزه</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-secondary">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            مستخدم عادي
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">صلاحيات أساسية للمستخدمين</p>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge bg-secondary">عرض المراكز الطبية</span>
                            <span class="badge bg-secondary">إضافة تقييمات</span>
                            <span class="badge bg-secondary">عرض الملف الشخصي</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <h6><i class="fas fa-info-circle me-2"></i>معلومات مهمة:</h6>
            <ul class="mb-0">
                <li>يتم تعيين الأدوار للمستخدمين عند إنشاء حساباتهم أو تعديلها</li>
                <li>كل دور له صلاحيات محددة في النظام</li>
                <li>المدير العام له صلاحيات كاملة لإدارة جميع أجزاء النظام</li>
                <li>يمكن للمديرين إدارة المراكز الطبية والتقييمات فقط</li>
            </ul>
        </div>
    </div>
</div>
@endsection