@extends('layouts.admin')

@section('title', 'المشتركين')
@section('content-header', 'إدارة المشتركين')
@section('content-subtitle', 'إدارة وعرض جميع المشتركين في النظام')

@section('breadcrumb')
    <li class="breadcrumb-item active">المشتركين</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Total Subscribers -->
    <div class="col-6 col-lg-3">
        <div class="stats-card slide-in-right">
            <div class="card-body">
                <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="stats-number">{{ number_format($stats['total'], 0) }}</h3>
                <p class="stats-label">إجمالي المشتركين</p>
            </div>
        </div>
    </div>

    <!-- Active Subscribers -->
    <div class="col-6 col-lg-3">
        <div class="stats-card slide-in-right">
            <div class="card-body">
                <div class="stats-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="stats-number">{{ number_format($stats['active'], 0) }}</h3>
                <p class="stats-label">المشتركين الفعالين</p>
            </div>
        </div>
    </div>

    <!-- Expired Subscribers -->
    <div class="col-6 col-lg-3">
        <div class="stats-card slide-in-right">
            <div class="card-body">
                <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="stats-number">{{ number_format($stats['expired'], 0) }}</h3>
                <p class="stats-label">منتهي الصلاحية</p>
            </div>
        </div>
    </div>

    <!-- Cancelled Subscribers -->
    <div class="col-6 col-lg-3">
        <div class="stats-card slide-in-right">
            <div class="card-body">
                <div class="stats-icon bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h3 class="stats-number">{{ number_format($stats['cancelled'], 0) }}</h3>
                <p class="stats-label">ملغي</p>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="table-admin fade-in">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                    <i class="fas fa-list"></i>
                </div>
                <div>
                    <h5 class="mb-0">قائمة المشتركين</h5>
                    <small class="text-muted d-none d-md-block">إدارة وعرض جميع المشتركين في النظام</small>
                </div>
            </div>
            <div class="import-export-buttons">
                <!-- Export Dropdown -->
                <div class="dropdown">
                    <button type="button" class="btn-admin btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download me-1"></i>
                        <span class="d-none d-md-inline">تصدير</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">تصدير البيانات</h6></li>
                        <li><a href="{{ route('admin.subscribers.export.form') }}" class="dropdown-item">
                            <i class="fas fa-cog text-primary me-2"></i>تصدير متقدم
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a href="{{ route('admin.subscribers.export', ['type' => 'subscribers', 'format' => 'xlsx']) }}" class="dropdown-item">
                            <i class="fas fa-file-excel text-success me-2"></i>المشتركين (Excel)
                        </a></li>
                        <li><a href="{{ route('admin.subscribers.export', ['type' => 'dependents', 'format' => 'xlsx']) }}" class="dropdown-item">
                            <i class="fas fa-users text-info me-2"></i>التابعين (Excel)
                        </a></li>
                        <li><a href="{{ route('admin.subscribers.export', ['type' => 'combined', 'format' => 'xlsx']) }}" class="dropdown-item">
                            <i class="fas fa-layer-group text-warning me-2"></i>مدمج (Excel)
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">النماذج</h6></li>
                        <li><a href="{{ route('admin.subscribers.download-template', ['type' => 'subscribers']) }}" class="dropdown-item">
                            <i class="fas fa-download text-secondary me-2"></i>نموذج المشتركين
                        </a></li>
                        <li><a href="{{ route('admin.subscribers.download-template', ['type' => 'dependents']) }}" class="dropdown-item">
                            <i class="fas fa-download text-secondary me-2"></i>نموذج التابعين
                        </a></li>
                    </ul>
                </div>

                <!-- Import Dropdown -->
                <div class="dropdown">
                    <button type="button" class="btn-admin btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-upload me-1"></i>
                        <span class="d-none d-md-inline">استيراد</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">استيراد البيانات</h6></li>
                        <li><a href="{{ route('admin.subscribers.import.form') }}" class="dropdown-item">
                            <i class="fas fa-cog text-primary me-2"></i>استيراد متقدم
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#quickImportModal" data-type="subscribers">
                            <i class="fas fa-users text-info me-2"></i>استيراد سريع - مشتركين
                        </a></li>
                        <li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#quickImportModal" data-type="dependents">
                            <i class="fas fa-user-friends text-success me-2"></i>استيراد سريع - تابعين
                        </a></li>
                    </ul>
                </div>

                <!-- Add Subscriber Button -->
                <a href="{{ route('admin.subscribers.create') }}" class="btn-admin btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    <span class="d-none d-sm-inline">إضافة مشترك</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Search and Filters -->
    <div class="search-filter-form">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">
                <i class="fas fa-search me-2"></i>
                البحث والتصفية
            </h6>
        </div>
        <form method="GET" class="form-admin">
            <div class="row g-3">
                <!-- Search Form -->
                <div class="col-12 col-md-6 col-lg-4">
                    <label class="form-label">
                        <i class="fas fa-search text-primary"></i> البحث
                    </label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                               placeholder="ابحث بالاسم، رقم الجوال، رقم البطاقة..."
                               value="{{ request('search') }}">
                        <button class="btn-admin btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Nationality Filter -->
                <div class="col-6 col-md-3 col-lg-3">
                    <label class="form-label">
                        <i class="fas fa-flag text-primary"></i> الجنسية
                    </label>
                    <select name="nationality" class="form-select" onchange="this.form.submit()">
                        <option value="">جميع الجنسيات</option>
                        @foreach(config('nationalities', []) as $nat)
                            <option value="{{ $nat['name'] }}" {{ request('nationality') == $nat['name'] ? 'selected' : '' }}>
                                {{ $nat['emoji'] }} {{ $nat['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                </div>

                <!-- Reset Button -->
                <div class="col-12 col-md-3 col-lg-2">
                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                    @if(request()->hasAny(['search', 'status', 'nationality']))
                        <a href="{{ route('admin.subscribers.index') }}" class="btn-admin btn-outline-primary w-100">
                            <i class="fas fa-refresh me-1"></i>
                            <span class="d-none d-sm-inline">إعادة تعيين</span>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="card-body">
        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions" style="display: none;">
            <form method="POST" id="bulkForm">
                @csrf
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <span class="selected-count">تم تحديد <span id="selectedNumber">0</span> مشترك</span>
                        <button type="button" class="btn-admin btn-outline-primary" id="deselectAllBtn">
                            <i class="fas fa-times me-1"></i>
                            إلغاء التحديد
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-admin btn-primary" id="bulkCardsBtn">
                            <i class="fas fa-file-pdf me-1"></i>
                            توليد بطاقات PDF للمحددين
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button type="button" id="selectAllBtn" class="btn-admin btn-outline-primary">
                <i class="fas fa-check-square me-1"></i> تحديد الكل
            </button>
        </div>
        <!-- Subscribers Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50px;"><input type="checkbox" id="selectAllCheckbox" class="form-check-input"></th>
                        <th style="width: 60px;" class="d-none d-md-table-cell">#</th>
                        <th>الاسم</th>
                        <th>رقم الجوال</th>
                        <th class="d-none d-lg-table-cell">الجنسية</th>
                        <th class="d-none d-xl-table-cell">رقم البطاقة</th>
                        <th class="d-none d-lg-table-cell">الباقة</th>
                        <th class="d-none d-md-table-cell">الحالة</th>
                        <th class="d-none d-xl-table-cell">تاريخ الإصدار</th>
                        <th class="d-none d-xl-table-cell">تاريخ الانتهاء</th>
                        <th style="width: 120px;">الإجراءات</th>
                    </tr>
                </thead>
                            <tbody>
                                @forelse($subscribers ?? [] as $subscriber)
                                <tr>
                                    <td data-label="تحديد">
                                        <input type="checkbox" name="subscriber_ids[]" value="{{ $subscriber->id }}" class="form-check-input subscriber-checkbox">
                                    </td>
                                    <td data-label="#" class="d-none d-md-table-cell">
                                        <span class="badge bg-light text-dark">{{ ($subscribers->firstItem() ?? 0) + $loop->index }}</span>
                                    </td>
                                    <td data-label="الاسم">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="subscriber-avatar">
                                                {{ substr($subscriber->name, 0, 1) }}
                                            </div>
                                            <div class="subscriber-info">
                                                <div class="subscriber-name">{{ $subscriber->name }}</div>
                                                @if($subscriber->email)
                                                    <div class="subscriber-details">{{ $subscriber->email }}</div>
                                                @endif
                                                <!-- Mobile-only info -->
                                                <div class="d-lg-none mt-1">
                                                    <span class="status-badge status-active me-1">{{ $subscriber->nationality ?? '-' }}</span>
                                                    @if($subscriber->package)
                                                        <span class="status-badge status-interested">{{ $subscriber->package->name }}</span>
                                                    @endif
                                                </div>
                                                <!-- Mobile-only status -->
                                                <div class="d-md-none mt-1">
                                                    <span class="badge bg-{{ $subscriber->status_color }} small">
                                                        {{ $subscriber->status }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="رقم الجوال">
                                        <span class="text-nowrap">{{ $subscriber->phone }}</span>
                                        <!-- Mobile-only card number -->
                                        <div class="d-xl-none">
                                            <small class="text-muted">{{ $subscriber->card_number }}</small>
                                        </div>
                                    </td>
                                    <td data-label="الجنسية" class="d-none d-lg-table-cell">
                                        @php
                                            $nat = collect(config('nationalities', []))->firstWhere('name', $subscriber->nationality);
                                        @endphp
                                        <span class="status-badge status-active">
                                            {{ $nat['emoji'] ?? '' }} {{ $subscriber->nationality ?? '-' }}
                                        </span>
                                    </td>
                                    <td data-label="رقم البطاقة" class="d-none d-xl-table-cell">
                                        <code class="small bg-light p-1 rounded">{{ $subscriber->card_number }}</code>
                                    </td>
                                    <td data-label="الباقة" class="d-none d-lg-table-cell">
                                        @if($subscriber->package)
                                            <span class="status-badge status-interested">{{ $subscriber->package->name }}</span>
                                            <br><small class="text-muted">{{ $subscriber->card_price }} ريال</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td data-label="الحالة" class="d-none d-md-table-cell">
                                        <span class="badge bg-{{ $subscriber->status_color }}">
                                            {{ $subscriber->status }}
                                        </span>
                                    </td>
                                    <td data-label="تاريخ الإصدار" class="d-none d-xl-table-cell">
                                        @if($subscriber->start_date)
                                            <span class="badge bg-secondary small">
                                                {{ \Carbon\Carbon::parse($subscriber->start_date)->format('Y-m-d') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td data-label="تاريخ الانتهاء" class="d-none d-xl-table-cell">
                                        @if($subscriber->end_date)
                                            <span class="badge bg-warning text-dark small">
                                                {{ \Carbon\Carbon::parse($subscriber->end_date)->format('Y-m-d') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                        <!-- Mobile-only dates -->
                                        <div class="d-xl-none mt-1">
                                            @if($subscriber->start_date)
                                                <small class="text-muted d-block">
                                                    إصدار: {{ \Carbon\Carbon::parse($subscriber->start_date)->format('Y-m-d') }}
                                                </small>
                                            @endif
                                            @if($subscriber->end_date)
                                                <small class="text-muted d-block">
                                                    انتهاء: {{ \Carbon\Carbon::parse($subscriber->end_date)->format('Y-m-d') }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td data-label="الإجراءات">
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.subscribers.edit', $subscriber->id) }}"
                                               class="action-btn btn-edit"
                                               title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#"
                                               onclick="showCardPreview({{ $subscriber->id }})"
                                               class="action-btn btn-card"
                                               title="معاينة البطاقة"
                                               data-bs-toggle="modal"
                                               data-bs-target="#cardPreviewModal">
                                                <i class="fas fa-id-card"></i>
                                            </a>
                                            <button type="button"
                                                    class="action-btn btn-delete"
                                                    onclick="deleteSubscriber({{ $subscriber->id }})"
                                                    title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3 text-primary opacity-50"></i>
                                            <h5 class="mb-2">لا يوجد مشتركين</h5>
                                            <p class="mb-3">ابدأ بإضافة مشتركين جدد للنظام</p>
                                            <a href="{{ route('admin.subscribers.create') }}" class="btn-admin btn-primary">
                                                <i class="fas fa-plus me-2"></i>
                                                <span>إضافة مشترك</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    @if($subscribers->hasPages())
                        <div class="card-footer p-0 border-0">
                            {{ $subscribers->links('custom.pagination') }}
                        </div>
                    @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Import Modal -->
<div class="modal fade" id="quickImportModal" tabindex="-1" aria-labelledby="quickImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickImportModalLabel">
                    <i class="fas fa-upload me-2"></i>
                    استيراد سريع
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form action="{{ route('admin.subscribers.import') }}" method="POST" enctype="multipart/form-data" id="quickImportForm">
                @csrf
                <input type="hidden" name="import_type" id="quickImportType" value="subscribers">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="importTypeText">استيراد المشتركين</span> - للخيارات المتقدمة استخدم الاستيراد المتقدم
                    </div>

                    <div class="mb-3">
                        <label for="quickImportFile" class="form-label">اختر ملف الاستيراد</label>
                        <input type="file" class="form-control" id="quickImportFile" name="import_file"
                               accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">الصيغ المدعومة: Excel (.xlsx, .xls) أو CSV (.csv)</div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="update_existing" id="quickUpdateExisting" value="1">
                        <label class="form-check-label" for="quickUpdateExisting">
                            تحديث البيانات الموجودة
                        </label>
                    </div>

                    <div id="quickImportProgress" class="progress mt-3" style="display: none;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <a href="#" id="downloadTemplateBtn" class="btn btn-outline-primary me-auto">
                        <i class="fas fa-download me-1"></i>
                        تحميل النموذج
                    </a>
                    <button type="submit" class="btn btn-primary" id="quickImportSubmit">
                        <i class="fas fa-upload me-1"></i>
                        استيراد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                    <p class="mb-0">هل أنت متأكد من حذف هذا المشترك؟</p>
                    <small class="text-muted">لا يمكن التراجع عن هذا الإجراء.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Card Preview Modal -->
<x-card-preview-modal />

@endsection

@push('scripts')
<script>
// Select All Functionality
document.getElementById('selectAllCheckbox').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.subscriber-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
});

document.getElementById('selectAllBtn').addEventListener('click', function() {
    const checkboxes = document.querySelectorAll('.subscriber-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    document.getElementById('selectAllCheckbox').checked = true;
    updateSelectedCount();
    toggleBulkButtons();
});

document.getElementById('deselectAllBtn').addEventListener('click', function() {
    const checkboxes = document.querySelectorAll('.subscriber-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAllCheckbox').checked = false;
    updateSelectedCount();
    toggleBulkButtons();
});

// Individual checkbox change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('subscriber-checkbox')) {
        updateSelectedCount();
        toggleBulkButtons();
    }
});

function updateSelectedCount() {
    const selectedCheckboxes = document.querySelectorAll('.subscriber-checkbox:checked');
    const selectedCount = selectedCheckboxes.length;
    const selectedCountElement = document.getElementById('selectedCount');
    const selectedNumberElement = document.getElementById('selectedNumber');
    
    if (selectedCount > 0) {
        selectedNumberElement.textContent = selectedCount;
        selectedCountElement.style.display = 'inline';
    } else {
        selectedCountElement.style.display = 'none';
    }
}

function toggleBulkButtons() {
    const selectedCheckboxes = document.querySelectorAll('.subscriber-checkbox:checked');
    const bulkCardsBtn = document.getElementById('bulkCardsBtn');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');
    
    if (selectedCheckboxes.length > 0) {
        bulkCardsBtn.style.display = 'inline-block';
        selectAllBtn.style.display = 'none';
        deselectAllBtn.style.display = 'inline-block';
    } else {
        bulkCardsBtn.style.display = 'none';
        selectAllBtn.style.display = 'inline-block';
        deselectAllBtn.style.display = 'none';
    }
}

// Import functionality
document.getElementById('importForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('importFile');
    const progressBar = document.getElementById('importProgress');
    
    if (fileInput.files.length === 0) {
        e.preventDefault();
        alert('يرجى اختيار ملف للاستيراد');
        return;
    }
    
    progressBar.style.display = 'block';
});

// Export functionality
function exportData(format) {
    // Show loading state
    const exportBtn = event.target.closest('a');
    const originalText = exportBtn.textContent;
    exportBtn.innerHTML = '<i class="fas fa-download fa-spin mr-2"></i>جاري التصدير...';

    // Simulate export process (replace with actual export logic)
    setTimeout(() => {
        // Reset button
        exportBtn.innerHTML = `<i class="fas fa-file-csv text-info mr-2"></i>تصدير CSV`;

        // Show success message
        alert(`تم تصدير البيانات بصيغة ${format.toUpperCase()} بنجاح`);
    }, 2000);
}

function downloadTemplate() {
    // Simulate template download
    alert('سيتم تحميل نموذج CSV');
}

// Delete subscriber
function deleteSubscriber(subscriberId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/admin/subscribers/${subscriberId}`;
    $('#deleteModal').modal('show');
}

// Initialize tooltips
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

// Card Preview Functionality
function showCardPreview(subscriberId) {
    // إظهار مؤشر التحميل
    const modalBody = document.querySelector('#cardPreviewModal .modal-body');
    modalBody.innerHTML = `
        <div class="text-center p-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <p class="mt-2">جاري تحميل بيانات البطاقة...</p>
        </div>
    `;
    
    // تحميل بيانات البطاقة
    fetch(`/admin/subscribers/${subscriberId}/card-preview`)
        .then(response => response.text())
        .then(html => {
            // إضافة أزرار التحكم
            const cardPreviewHtml = `
                <div class="card-preview-container">
                    <div class="card-preview-wrapper">
                        ${html}
                    </div>
                    
                    <div class="card-preview-controls">
                        <button class="btn btn-flip" onclick="flipCard()">
                            <i class="fas fa-eye me-2"></i>عرض الوجه الخلفي
                        </button>
                        <button class="btn btn-print" onclick="printCard()">
                            <i class="fas fa-print me-2"></i>طباعة البطاقة
                        </button>
                        <a href="/admin/subscribers/${subscriberId}/card-pdf" class="btn btn-download" target="_blank">
                            <i class="fas fa-download me-2"></i>تحميل PDF
                        </a>
                    </div>
                </div>
            `;
            modalBody.innerHTML = cardPreviewHtml;
        })
        .catch(error => {
            console.error('Error loading card preview:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger m-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    حدث خطأ أثناء تحميل البطاقة
                    <button class="btn btn-sm btn-outline-danger ms-3" onclick="showCardPreview(${subscriberId})">
                        <i class="fas fa-redo me-1"></i>
                        إعادة المحاولة
                    </button>
                </div>
            `;
        });
}

// Quick Import Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const quickImportModal = document.getElementById('quickImportModal');
    const quickImportType = document.getElementById('quickImportType');
    const importTypeText = document.getElementById('importTypeText');
    const downloadTemplateBtn = document.getElementById('downloadTemplateBtn');
    const quickImportForm = document.getElementById('quickImportForm');
    const quickImportSubmit = document.getElementById('quickImportSubmit');

    // Handle modal show event
    quickImportModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const type = button.getAttribute('data-type');

        if (type) {
            quickImportType.value = type;

            if (type === 'dependents') {
                importTypeText.textContent = 'استيراد التابعين';
                downloadTemplateBtn.href = '{{ route("admin.subscribers.download-template", ["type" => "dependents"]) }}';
            } else {
                importTypeText.textContent = 'استيراد المشتركين';
                downloadTemplateBtn.href = '{{ route("admin.subscribers.download-template", ["type" => "subscribers"]) }}';
            }
        }
    });

    // Handle form submission
    quickImportForm.addEventListener('submit', function() {
        quickImportSubmit.disabled = true;
        quickImportSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري الاستيراد...';
        document.getElementById('quickImportProgress').style.display = 'block';
    });
});
</script>
@endpush