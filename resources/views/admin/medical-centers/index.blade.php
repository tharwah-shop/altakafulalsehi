@extends('layouts.admin')

@section('title', 'إدارة المراكز الطبية')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none">لوحة التحكم</a></li>
                            <li class="breadcrumb-item active">المراكز الطبية</li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-0 fw-bold">
                        <i class="fas fa-hospital text-primary me-2"></i>
                        إدارة المراكز الطبية
                    </h1>
                    <p class="text-muted mb-0">إدارة شبكة المراكز الطبية والمستشفيات</p>
                </div>
                <div class="d-flex gap-2">
                    <!-- Import/Export Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-file-csv me-2"></i>
                            استيراد/تصدير CSV
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.medical-centers.export-csv') }}">
                                    <i class="fas fa-download me-2"></i>تصدير إلى CSV
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
                                    <i class="fas fa-upload me-2"></i>استيراد من CSV
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.medical-centers.download-csv-template') }}">
                                    <i class="fas fa-file-download me-2"></i>تحميل قالب CSV
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
            <div class="card bg-warning text-dark h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="mb-1 fw-bold">{{ $medicalCenters->where('status', 'pending')->count() ?? 0 }}</h3>
                        <p class="mb-0">في انتظار المراجعة</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle p-3">
                        <i class="fas fa-clock fa-2x"></i>
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

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter text-primary me-2"></i>
                البحث والتصفية
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.medical-centers.index') }}" id="searchForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">البحث</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" id="search" class="form-control"
                                   placeholder="البحث في اسم المركز..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="city" class="form-label">المدينة</label>
                        <select name="city" id="city" class="form-select">
                            <option value="">جميع المدن</option>
                            @foreach($cities ?? [] as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في انتظار المراجعة</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>معلق</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search me-1"></i>
                                بحث
                            </button>
                            <a href="{{ route('admin.medical-centers.index') }}" class="btn btn-outline-secondary" title="إعادة تعيين">
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
                    <i class="fas fa-list text-primary me-2"></i>
                    قائمة المراكز الطبية
                </h5>
                <div class="d-flex align-items-center gap-3">
                    @if($medicalCenters->count() > 0)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">
                                تحديد الكل
                            </label>
                        </div>
                    @endif
                    <span class="badge bg-primary">{{ $medicalCenters->total() ?? 0 }} مركز</span>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            @if($medicalCenters->count() > 0)
                                <th width="50">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllHeader">
                                    </div>
                                </th>
                            @endif
                            <th width="60">#</th>
                            <th>اسم المركز</th>
                            <th>المدينة</th>
                            <th>النوع</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                            <th width="150">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicalCenters as $center)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input center-checkbox" type="checkbox"
                                               value="{{ $center->id }}" id="center_{{ $center->id }}">
                                    </div>
                                </td>
                                <td>{{ $loop->iteration + ($medicalCenters->currentPage() - 1) * $medicalCenters->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($center->image)
                                            <img src="{{ $center->image_url }}" alt="{{ $center->name }}"
                                                 class="rounded-circle me-3"
                                                 style="width:40px;height:40px;object-fit:cover;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $center->name }}</div>
                                            @if($center->phone)
                                                <small class="text-muted">
                                                    <i class="fas fa-phone me-1"></i>{{ $center->phone }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $center->city }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $center->type_name }}</span>
                                </td>
                                <td>
                                    @switch($center->status)
                                        @case('active')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>نشط
                                            </span>
                                            @break
                                        @case('inactive')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>غير نشط
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>في انتظار المراجعة
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-pause-circle me-1"></i>معلق
                                            </span>
                                    @endswitch
                                </td>
                                <td>
                                    <small class="text-muted">{{ $center->created_at->format('Y-m-d') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('medical-center.detail', $center->slug) }}"
                                           class="btn btn-sm btn-outline-primary" title="عرض" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.medical-centers.edit', $center->id) }}"
                                           class="btn btn-sm btn-outline-info" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                title="حذف" onclick="deleteCenter({{ $center->id }}, '{{ $center->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-hospital fa-3x text-muted"></i>
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

        <!-- Footer with Bulk Actions and Pagination -->
        @if($medicalCenters->count() > 0)
        <div class="card-footer">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted small">تم تحديد <span id="selectedCount">0</span> مركز</span>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" id="bulkActionsBtn" disabled>
                                <i class="fas fa-tasks me-1"></i>
                                إجراءات جماعية
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button type="button" class="dropdown-item" onclick="bulkAction('activate')">
                                        <i class="fas fa-check-circle me-2 text-success"></i> تفعيل
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item" onclick="bulkAction('deactivate')">
                                        <i class="fas fa-pause-circle me-2 text-warning"></i> إيقاف
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button type="button" class="dropdown-item text-danger" onclick="bulkAction('delete')">
                                        <i class="fas fa-trash me-2"></i> حذف
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    @if($medicalCenters->hasPages())
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="small text-muted me-3">
                                عرض {{ $medicalCenters->firstItem() }} إلى {{ $medicalCenters->lastItem() }}
                                من أصل {{ $medicalCenters->total() }} مركز طبي
                            </div>
                            {{ $medicalCenters->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- CSV Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-upload me-2"></i>
                        استيراد المراكز الطبية من CSV
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.medical-centers.import-csv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">ملف CSV</label>
                            <input type="file" class="form-control" id="csv_file" name="csv_file"
                                   accept=".csv" required>
                            <div class="form-text">
                                يجب أن يكون الملف بصيغة CSV ويحتوي على الأعمدة المطلوبة
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>تعليمات الاستيراد:</h6>
                            <ul class="mb-0">
                                <li>تأكد من أن الملف بصيغة CSV</li>
                                <li>يجب أن يحتوي الصف الأول على أسماء الأعمدة</li>
                                <li>يمكنك تحميل قالب CSV للمساعدة</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>
                            استيراد
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Bulk Actions Form -->
    <form id="bulkActionsForm" method="POST" action="{{ route('admin.medical-centers.bulk-action') }}" style="display: none;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="action" id="bulkActionType">
        <input type="hidden" name="selected_centers" id="selectedCenters">
    </form>
</div>

@endsection

@push('styles')
<style>
.table-hover tbody tr:hover {
    background-color: var(--bs-light);
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: var(--bs-border-radius);
    border-bottom-left-radius: var(--bs-border-radius);
}

.btn-group .btn:last-child {
    border-top-right-radius: var(--bs-border-radius);
    border-bottom-right-radius: var(--bs-border-radius);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Checkbox functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllHeaderCheckbox = document.getElementById('selectAllHeader');
    const centerCheckboxes = document.querySelectorAll('.center-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkActionsBtn = document.getElementById('bulkActionsBtn');

    // Sync header checkboxes
    if (selectAllCheckbox && selectAllHeaderCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            selectAllHeaderCheckbox.checked = this.checked;
            toggleAllCenters(this.checked);
        });

        selectAllHeaderCheckbox.addEventListener('change', function() {
            selectAllCheckbox.checked = this.checked;
            toggleAllCenters(this.checked);
        });
    }

    // Individual checkbox change
    centerCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    function toggleAllCenters(checked) {
        centerCheckboxes.forEach(checkbox => {
            checkbox.checked = checked;
        });
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const selectedCheckboxes = document.querySelectorAll('.center-checkbox:checked');
        const count = selectedCheckboxes.length;

        selectedCountSpan.textContent = count;
        bulkActionsBtn.disabled = count === 0;

        // Update select all checkboxes
        const allChecked = count === centerCheckboxes.length && count > 0;
        const someChecked = count > 0 && count < centerCheckboxes.length;

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked;
        }

        if (selectAllHeaderCheckbox) {
            selectAllHeaderCheckbox.checked = allChecked;
            selectAllHeaderCheckbox.indeterminate = someChecked;
        }
    }

    // Initial count update
    updateSelectedCount();

    // Search form auto-submit
    const searchForm = document.getElementById('searchForm');
    const searchInputs = searchForm.querySelectorAll('input, select');

    searchInputs.forEach(input => {
        if (input.type !== 'submit') {
            input.addEventListener('change', function() {
                if (this.name !== 'search') {
                    searchForm.submit();
                }
            });
        }
    });

    // Search input with delay
    const searchInput = document.getElementById('search');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    searchForm.submit();
                }
            }, 500);
        });
    }
});

// Delete center function
function deleteCenter(centerId, centerName) {
    if (confirm(`هل أنت متأكد من حذف المركز الطبي "${centerName}"؟\n\nهذا الإجراء لا يمكن التراجع عنه.`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/medical-centers/${centerId}`;
        form.submit();
    }
}

// Bulk actions function
function bulkAction(action) {
    const selectedCheckboxes = document.querySelectorAll('.center-checkbox:checked');
    const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);

    if (selectedIds.length === 0) {
        alert('يرجى تحديد مركز واحد على الأقل');
        return;
    }

    let confirmMessage = '';
    switch (action) {
        case 'activate':
            confirmMessage = `هل أنت متأكد من تفعيل ${selectedIds.length} مركز طبي؟`;
            break;
        case 'deactivate':
            confirmMessage = `هل أنت متأكد من إيقاف ${selectedIds.length} مركز طبي؟`;
            break;
        case 'delete':
            confirmMessage = `هل أنت متأكد من حذف ${selectedIds.length} مركز طبي؟\n\nهذا الإجراء لا يمكن التراجع عنه.`;
            break;
    }

    if (confirm(confirmMessage)) {
        document.getElementById('bulkActionType').value = action;
        document.getElementById('selectedCenters').value = selectedIds.join(',');
        document.getElementById('bulkActionsForm').submit();
    }
}

// Show success/error messages
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showAlert('success', '{{ session('success') }}');
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        showAlert('error', '{{ session('error') }}');
    });
@endif

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);

    // Auto dismiss after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}
</script>
@endpush

