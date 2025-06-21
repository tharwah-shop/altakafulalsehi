@extends('layouts.admin')

@section('title', 'المشتركين')
@section('content-header', 'إدارة المشتركين')
@section('content-subtitle', 'إدارة وعرض جميع المشتركين في النظام')

@section('breadcrumb')
    <li class="breadcrumb-item active">المشتركين</li>
@endsection

@section('content')
<!-- Import Report -->
@if(session('import_report'))
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-check-circle me-2"></i>
                    تقرير استيراد ملف بطاقات التأمين
                </h5>
            </div>
            <div class="card-body">
                @php $report = session('import_report'); @endphp
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-3">
                            <h4 class="fw-bold mb-1">{{ $report['total_processed'] }}</h4>
                            <small>إجمالي السجلات المعالجة</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-success bg-opacity-10 text-success rounded p-3">
                            <h4 class="fw-bold mb-1">{{ $report['imported'] }}</h4>
                            <small>مشتركين جدد</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-info bg-opacity-10 text-info rounded p-3">
                            <h4 class="fw-bold mb-1">{{ $report['updated'] }}</h4>
                            <small>مشتركين محدثين</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-danger bg-opacity-10 text-danger rounded p-3">
                            <h4 class="fw-bold mb-1">{{ $report['errors'] }}</h4>
                            <small>أخطاء</small>
                        </div>
                    </div>
                </div>

                @if($report['errors'] > 0 && !empty($report['error_details']))
                <hr>
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>تفاصيل الأخطاء:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>رقم الصف</th>
                                    <th>الأخطاء</th>
                                    <th>البيانات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report['error_details'] as $error)
                                <tr>
                                    <td>{{ $error['row'] }}</td>
                                    <td>
                                        @foreach($error['errors'] as $err)
                                            <span class="badge bg-danger me-1">{{ $err }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ json_encode($error['data'], JSON_UNESCAPED_UNICODE) }}
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Total Subscribers -->
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-users fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($stats['total'], 0) }}</h3>
                <p class="text-muted mb-0">إجمالي المشتركين</p>
            </div>
        </div>
    </div>

    <!-- Active Subscribers -->
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($stats['active'], 0) }}</h3>
                <p class="text-muted mb-0">المشتركين الفعالين</p>
            </div>
        </div>
    </div>

    <!-- Expired Subscribers -->
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-clock fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($stats['expired'], 0) }}</h3>
                <p class="text-muted mb-0">منتهي الصلاحية</p>
            </div>
        </div>
    </div>

    <!-- Cancelled Subscribers -->
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-times-circle fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($stats['cancelled'], 0) }}</h3>
                <p class="text-muted mb-0">ملغي</p>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                    <i class="fas fa-list"></i>
                </div>
                <div>
                    <h5 class="mb-0">قائمة المشتركين</h5>
                    <small class="text-muted">إدارة وعرض جميع المشتركين في النظام</small>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <!-- Export Dropdown -->
                <div class="dropdown">
                    <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download me-1"></i>
                        تصدير
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
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-upload me-1"></i>
                        استيراد
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
                <a href="{{ route('admin.subscribers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    إضافة مشترك
                </a>
            </div>
        </div>
    </div>
    <!-- Search and Filters -->
    <div class="card-body border-bottom">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">
                <i class="fas fa-search me-2"></i>
                البحث والتصفية
            </h6>
        </div>
        <form method="GET">
            <div class="row g-3">
                <!-- Search Form -->
                <div class="col-lg-4">
                    <label class="form-label">
                        <i class="fas fa-search text-primary"></i> البحث
                    </label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                               placeholder="ابحث بالاسم، رقم الجوال، رقم البطاقة..."
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Nationality Filter -->
                <div class="col-lg-3">
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
                <div class="col-lg-2">
                    <label class="form-label">&nbsp;</label>
                    @if(request()->hasAny(['search', 'status', 'nationality']))
                        <a href="{{ route('admin.subscribers.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-refresh me-1"></i>
                            إعادة تعيين
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

        <!-- Subscribers Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>الاسم</th>
                        <th>رقم الجوال</th>
                        <th>الجنسية</th>
                        <th>رقم البطاقة</th>
                        <th>الباقة</th>
                        <th>الحالة</th>
                        <th>تاريخ الإصدار</th>
                        <th>تاريخ الانتهاء</th>
                        <th style="width: 120px;">الإجراءات</th>
                    </tr>
                </thead>
                            <tbody>
                                @forelse($subscribers ?? [] as $subscriber)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="subscriber_ids[]" value="{{ $subscriber->id }}" class="form-check-input subscriber-checkbox">
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ ($subscribers->firstItem() ?? 0) + $loop->index }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                                {{ substr($subscriber->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $subscriber->name }}</div>
                                                @if($subscriber->email)
                                                    <small class="text-muted">{{ $subscriber->email }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-nowrap">{{ $subscriber->phone }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $nat = collect(config('nationalities', []))->firstWhere('name', $subscriber->nationality);
                                        @endphp
                                        <span class="badge bg-light text-dark">
                                            {{ $nat['emoji'] ?? '' }} {{ $subscriber->nationality ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <code class="small bg-light p-1 rounded">{{ $subscriber->card_number }}</code>
                                    </td>
                                    <td>
                                        @if($subscriber->package)
                                            <span class="badge bg-info">{{ $subscriber->package->name }}</span>
                                            <br><small class="text-muted">{{ $subscriber->card_price }} ريال</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $subscriber->status_color }}">
                                            {{ $subscriber->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($subscriber->start_date)
                                            <span class="badge bg-secondary">
                                                {{ \Carbon\Carbon::parse($subscriber->start_date)->format('Y-m-d') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subscriber->end_date)
                                            <span class="badge bg-warning text-dark">
                                                {{ \Carbon\Carbon::parse($subscriber->end_date)->format('Y-m-d') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.subscribers.show', $subscriber->id) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.subscribers.edit', $subscriber->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteSubscriber({{ $subscriber->id }})"
                                                    title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3 text-primary opacity-50"></i>
                                            <h5 class="mb-2">لا يوجد مشتركين</h5>
                                            <p class="mb-3">ابدأ بإضافة مشتركين جدد للنظام</p>
                                            <a href="{{ route('admin.subscribers.create') }}" class="btn btn-primary">
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



@endsection

@push('scripts')
<script>
    // Quick Import Modal functionality
    const quickImportModal = document.getElementById('quickImportModal');
    if (quickImportModal) {
        quickImportModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const type = button.getAttribute('data-type');
            const quickImportType = document.getElementById('quickImportType');
            const importTypeText = document.getElementById('importTypeText');
            const downloadTemplateBtn = document.getElementById('downloadTemplateBtn');

            if (type && quickImportType) {
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
        const quickImportForm = document.getElementById('quickImportForm');
        if (quickImportForm) {
            quickImportForm.addEventListener('submit', function() {
                const submitBtn = document.getElementById('quickImportSubmit');
                const progressBar = document.getElementById('quickImportProgress');

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري الاستيراد...';
                }
                if (progressBar) {
                    progressBar.style.display = 'block';
                }
            });
        }
    }
});

// Delete subscriber function
function deleteSubscriber(subscriberId) {
    const deleteForm = document.getElementById('deleteForm');
    if (deleteForm) {
        deleteForm.action = `/admin/subscribers/${subscriberId}`;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
}
</script>
@endpush