@extends('layouts.admin')

@section('title', 'إدارة الباقات')

@section('content')
<!-- Header Actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">إدارة الباقات</h2>
        <p class="text-muted mb-0">إدارة باقات الاشتراك والخدمات المتاحة</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            إضافة باقة جديدة
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">إجمالي الباقات</h6>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">الباقات النشطة</h6>
                        <h3 class="mb-0">{{ $stats['active'] }}</h3>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">الباقات المميزة</h6>
                        <h3 class="mb-0">{{ $stats['featured'] }}</h3>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-info text-white">
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
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-search me-2"></i>
            البحث والتصفية
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <!-- Search -->
            <div class="col-md-4">
                <label class="form-label">البحث</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="البحث في اسم الباقة أو الوصف..." 
                       value="{{ request('search') }}">
            </div>

            <!-- Status Filter -->
            <div class="col-md-2">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                </select>
            </div>

            <!-- Featured Filter -->
            <div class="col-md-2">
                <label class="form-label">مميزة</label>
                <select name="is_featured" class="form-select">
                    <option value="">الكل</option>
                    <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>مميزة</option>
                    <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>عادية</option>
                </select>
            </div>

            <!-- Price Range -->
            <div class="col-md-2">
                <label class="form-label">السعر من</label>
                <input type="number" name="price_min" class="form-control" 
                       placeholder="0" value="{{ request('price_min') }}" min="0" step="0.01">
            </div>

            <div class="col-md-2">
                <label class="form-label">السعر إلى</label>
                <input type="number" name="price_max" class="form-control" 
                       placeholder="1000" value="{{ request('price_max') }}" min="0" step="0.01">
            </div>

            <!-- Action Buttons -->
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i>
                    بحث
                </button>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i>
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Packages Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">قائمة الباقات</h5>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-sort me-1"></i>
                    ترتيب حسب
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'sort_order', 'sort_direction' => 'asc']) }}">الترتيب الافتراضي</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_direction' => 'asc']) }}">الاسم (أ-ي)</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_direction' => 'desc']) }}">الاسم (ي-أ)</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'price', 'sort_direction' => 'asc']) }}">السعر (الأقل أولاً)</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'price', 'sort_direction' => 'desc']) }}">السعر (الأعلى أولاً)</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_direction' => 'desc']) }}">الأحدث أولاً</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($packages->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>اسم الباقة</th>
                            <th style="width: 120px;">السعر</th>
                            <th style="width: 100px;">المدة</th>
                            <th style="width: 100px;">التابعين</th>
                            <th style="width: 100px;">المشتركين</th>
                            <th style="width: 100px;">الحالة</th>
                            <th style="width: 80px;">مميزة</th>
                            <th style="width: 150px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark">{{ ($packages->firstItem() ?? 0) + $loop->index }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($package->icon)
                                        <div class="me-3" style="color: {{ $package->color }};">
                                            <i class="{{ $package->icon }} fs-4"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $package->name }}</div>
                                        @if($package->name_en)
                                            <small class="text-muted">{{ $package->name_en }}</small>
                                        @endif
                                        @if($package->description)
                                            <div class="text-muted small mt-1" style="max-width: 300px;">
                                                {{ Str::limit($package->description, 100) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $package->formatted_price }}</div>
                                @if($package->supportsDependents())
                                    <small class="text-muted">التابع: {{ $package->formatted_dependent_price }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $package->duration_text }}</span>
                            </td>
                            <td>
                                @if($package->supportsDependents())
                                    @if($package->max_dependents == 0)
                                        <span class="badge bg-success">غير محدود</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $package->max_dependents }}</span>
                                    @endif
                                @else
                                    <span class="badge bg-light text-muted">لا يدعم</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $package->subscribers()->count() }}</span>
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
                            <td>
                                @if($package->is_featured)
                                    <i class="fas fa-star text-warning" title="باقة مميزة"></i>
                                @else
                                    <i class="far fa-star text-muted" title="باقة عادية"></i>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.packages.show', $package->id) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-sm btn-outline-info" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Toggle Status -->
                                    <form action="{{ route('admin.packages.toggle-status', $package->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $package->status === 'active' ? 'warning' : 'success' }}" 
                                                title="{{ $package->status === 'active' ? 'إيقاف' : 'تفعيل' }}">
                                            <i class="fas fa-{{ $package->status === 'active' ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- Toggle Featured -->
                                    <form action="{{ route('admin.packages.toggle-featured', $package->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $package->is_featured ? 'warning' : 'secondary' }}" 
                                                title="{{ $package->is_featured ? 'إزالة من المميزة' : 'إضافة للمميزة' }}">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذه الباقة؟\n\nملاحظة: لا يمكن حذف الباقة إذا كان هناك مشتركين مرتبطين بها.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
                    <i class="fas fa-box fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted">لا توجد باقات</h5>
                <p class="text-muted">لم يتم العثور على أي باقات تطابق معايير البحث</p>
                <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    إضافة باقة جديدة
                </a>
            </div>
        @endif
    </div>
    
    @if($packages->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    عرض {{ $packages->firstItem() }} إلى {{ $packages->lastItem() }} من أصل {{ $packages->total() }} باقة
                </div>
                <div>
                    {{ $packages->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
