@extends('layouts.admin')

@section('title', 'تفاصيل الباقة: ' . $package->name)

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">{{ $package->name }}</h2>
        <p class="text-muted mb-0">تفاصيل الباقة وإحصائيات المشتركين</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>
            تعديل الباقة
        </a>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<!-- Package Info and Statistics -->
<div class="row g-4 mb-4">
    <!-- Package Details Card -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header text-center" style="background-color: {{ $package->color }}; color: white;">
                @if($package->icon)
                    <div class="mb-2">
                        <i class="{{ $package->icon }} fs-1"></i>
                    </div>
                @endif
                <h4 class="mb-1">{{ $package->name }}</h4>
                @if($package->name_en)
                    <small>{{ $package->name_en }}</small>
                @endif
                @if($package->is_featured)
                    <div class="mt-2">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>
                            باقة مميزة
                        </span>
                    </div>
                @endif
            </div>
            <div class="card-body text-center">
                <div class="display-5 fw-bold text-primary mb-3">{{ $package->formatted_price }}</div>

                @if($package->supportsDependents())
                    <div class="text-muted mb-3">
                        <strong>سعر التابع:</strong> {{ $package->formatted_dependent_price }}
                    </div>
                @endif

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="text-muted small">المدة</div>
                            <div class="fw-bold">{{ $package->duration_text }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="text-muted small">التابعين</div>
                            <div class="fw-bold">{{ $package->dependents_limit_text }}</div>
                        </div>
                    </div>
                </div>

                @if($package->discount_percentage > 0)
                    <div class="alert alert-success">
                        <i class="fas fa-percentage me-2"></i>
                        خصم افتراضي: {{ $package->discount_percentage }}%
                    </div>
                @endif

                <div class="text-center">
                    <span class="badge bg-{{ $package->status === 'active' ? 'success' : ($package->status === 'inactive' ? 'danger' : 'warning') }} fs-6">
                        {{ $package->status === 'active' ? 'نشط' : ($package->status === 'inactive' ? 'غير نشط' : 'مسودة') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-xl-8">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">إجمالي المشتركين</h6>
                                <h3 class="mb-0">{{ $stats['subscribers_count'] }}</h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">المشتركين النشطين</h6>
                                <h3 class="mb-0">{{ $stats['active_subscribers'] }}</h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">المشتركين المنتهين</h6>
                                <h3 class="mb-0">{{ $stats['expired_subscribers'] }}</h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fas fa-user-times"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">إجمالي التابعين</h6>
                                <h3 class="mb-0">{{ $stats['dependents_count'] }}</h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fas fa-user-friends"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">إجمالي الإيرادات من هذه الباقة</h6>
                        <h3 class="text-success mb-0">{{ number_format($stats['total_revenue'], 2) }} ريال</h3>
                    </div>
                    <div class="fs-1 text-success opacity-50">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Package Features -->
@if($package->features && count($package->features) > 0)
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list-check me-2"></i>
            مميزات الباقة
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($package->features as $feature)
                <div class="col-md-6 mb-2">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span>{{ $feature }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Package Description -->
@if($package->description || $package->description_en)
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-info-circle me-2"></i>
            وصف الباقة
        </h5>
    </div>
    <div class="card-body">
        @if($package->description)
            <div class="mb-3">
                <h6 class="text-muted">الوصف العربي:</h6>
                <p class="mb-0">{{ $package->description }}</p>
            </div>
        @endif
        @if($package->description_en)
            <div>
                <h6 class="text-muted">الوصف الإنجليزي:</h6>
                <p class="mb-0">{{ $package->description_en }}</p>
            </div>
        @endif
    </div>
</div>
@endif

<!-- Recent Subscribers -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>
            أحدث المشتركين ({{ $recentSubscribers->count() }})
        </h5>
        <a href="{{ route('admin.subscribers.index', ['package_id' => $package->id]) }}" class="btn btn-sm btn-outline-primary">
            عرض جميع المشتركين
        </a>
    </div>
    <div class="card-body p-0">
        @if($recentSubscribers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>المشترك</th>
                            <th>المنطقة</th>
                            <th>رقم البطاقة</th>
                            <th>تاريخ الاشتراك</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSubscribers as $subscriber)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="subscriber-avatar me-2">
                                        {{ substr($subscriber->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $subscriber->name }}</div>
                                        @if($subscriber->phone)
                                            <small class="text-muted">{{ $subscriber->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($subscriber->city)
                                    <span class="badge bg-light text-dark">{{ $subscriber->city->region->name ?? '' }}</span>
                                    <div class="small text-muted">{{ $subscriber->city->name }}</div>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                <code class="bg-light">{{ $subscriber->card_number }}</code>
                            </td>
                            <td>
                                <div class="small">{{ $subscriber->start_date ? $subscriber->start_date->format('Y/m/d') : 'غير محدد' }}</div>
                            </td>
                            <td>
                                <div class="small">{{ $subscriber->end_date ? $subscriber->end_date->format('Y/m/d') : 'غير محدد' }}</div>
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
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.subscribers.show', $subscriber->id) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.subscribers.edit', $subscriber->id) }}" class="btn btn-sm btn-outline-info" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-users fs-1 text-muted"></i>
                </div>
                <h6 class="text-muted">لا يوجد مشتركين في هذه الباقة بعد</h6>
                <p class="text-muted">عندما يشترك أشخاص في هذه الباقة، ستظهر بياناتهم هنا</p>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.subscriber-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}
</style>
@endpush
@endsection
