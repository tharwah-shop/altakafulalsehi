@extends('layouts.admin')

@section('title', 'إدارة المراكز الطبية')
@section('content-header', 'المراكز الطبية')
@section('content-subtitle', 'إدارة شبكة المراكز الطبية والمستشفيات')

@section('content')
<!-- Header Actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">المراكز الطبية</h2>
        <p class="text-muted mb-0">إدارة شبكة المراكز الطبية والمستشفيات</p>
    </div>
    <div class="d-flex gap-2">
        <!-- Import/Export Dropdown -->
        <div class="dropdown">
            <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-file-excel me-2"></i>
                استيراد/تصدير
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.medical-centers.export') }}">
                        <i class="fas fa-download me-2"></i>تصدير إلى Excel
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.medical-centers.import-form') }}">
                        <i class="fas fa-upload me-2"></i>استيراد من Excel
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.medical-centers.download-template') }}">
                        <i class="fas fa-file-download me-2"></i>تحميل قالب Excel
                    </a>
                </li>
            </ul>
        </div>

        <a href="{{ route('admin.medical-centers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            إضافة مركز جديد
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-1 fw-bold">{{ $medicalCenters->total() ?? 0 }}</h3>
                    <p class="mb-0">إجمالي المراكز</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3">
                    <i class="fas fa-hospital fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-1 fw-bold">{{ $medicalCenters->where('status', 'active')->count() ?? 0 }}</h3>
                    <p class="mb-0">المراكز النشطة</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>



    <div class="col-xl-3 col-md-6">
        <div class="card bg-info text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h3 class="mb-1 fw-bold">{{ $medicalCenters->pluck('city')->unique()->count() ?? 0 }}</h3>
                    <p class="mb-0">المدن المغطاة</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-circle p-3">
                    <i class="fas fa-city fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.medical-centers.index') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="البحث في اسم المركز..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="region" class="form-select">
                        <option value="">جميع المناطق</option>
                        @foreach($regions ?? [] as $region)
                            <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                                {{ $region }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-search me-1"></i>
                            بحث
                        </button>
                        <a href="{{ route('admin.medical-centers.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-hospital text-primary me-2"></i>
                قائمة المراكز الطبية
            </h5>
            <span class="badge bg-primary">{{ $medicalCenters->total() ?? 0 }} مركز</span>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>اسم المركز</th>
                        <th>المنطقة</th>
                        <th>المدينة</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicalCenters as $center)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($center->image)
                                        <img src="{{ $center->image_url }}" alt="{{ $center->name }}"
                                             class="rounded-circle me-3"
                                             style="width:40px;height:40px;object-fit:cover;"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    @else
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                                            <i class="fas fa-hospital"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $center->name }}</div>
                                        @if($center->phone)
                                            <small class="text-muted">{{ $center->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $center->region }}</td>
                            <td>{{ $center->city }}</td>
                            <td>
                                <span class="badge bg-info">{{ $center->type_name }}</span>
                            </td>
                            <td>
                                @if($center->status == 'active')
                                    <span class="badge bg-success">نشط</span>
                                @elseif($center->status == 'inactive')
                                    <span class="badge bg-danger">غير نشط</span>
                                @else
                                    <span class="badge bg-warning text-dark">معلق</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('medical-center.detail', $center->slug) }}" class="btn btn-sm btn-outline-primary" title="عرض" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.medical-centers.edit', $center->id) }}" class="btn btn-sm btn-outline-info" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.medical-centers.destroy', $center->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا المركز؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <i class="fa-solid fa-hospital fa-3x text-secondary"></i>
                                    </div>
                                    <h5 class="mb-2">لا توجد مراكز طبية</h5>
                                    <p class="text-muted">ابدأ ببناء شبكتك الطبية بإضافة أول مركز طبي</p>
                                    <a href="{{ route('admin.medical-centers.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i> إضافة مركز طبي جديد
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bulk Actions -->
    @if($medicalCenters->count() > 0)
    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small">تم تحديد <span id="selectedCount">0</span> مركز</span>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" id="bulkActionsBtn" disabled>
                            <i class="fa-solid fa-tasks me-1"></i>
                            إجراءات جماعية
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <button type="button" class="dropdown-item" onclick="bulkAction('activate')">
                                    <i class="fa-solid fa-play me-2"></i> تفعيل
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item" onclick="bulkAction('deactivate')">
                                    <i class="fa-solid fa-pause me-2"></i> إيقاف مؤقت
                                </button>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button type="button" class="dropdown-item text-danger" onclick="bulkAction('delete')">
                                    <i class="fa-solid fa-trash me-2"></i> حذف
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                @if($medicalCenters->hasPages())
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="small text-muted mb-2 mb-md-0">
                            عرض {{ $medicalCenters->firstItem() }} إلى {{ $medicalCenters->lastItem() }} من أصل {{ $medicalCenters->total() }} مركز طبي
                        </div>
                        <div>
                            {{ $medicalCenters->links('custom.pagination') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Bulk Actions Form -->
<form id="bulkActionsForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="action" id="bulkActionType">
    <input type="hidden" name="selected_centers" id="selectedCenters">
</form>
@endsection

