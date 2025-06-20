@extends('layouts.admin')

@section('title', 'إدارة المدفوعات')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">إدارة المدفوعات</h2>
        <p class="text-muted mb-0">عرض وإدارة جميع المدفوعات والتحويلات البنكية</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-success" onclick="exportPayments()">
            <i class="fas fa-download me-2"></i>
            تصدير
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card border-0 bg-primary text-white">
            <div class="card-body text-center p-3">
                <div class="fs-4 fw-bold">{{ $stats['total'] }}</div>
                <small>إجمالي المدفوعات</small>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card border-0 bg-warning text-white">
            <div class="card-body text-center p-3">
                <div class="fs-4 fw-bold">{{ $stats['pending'] }}</div>
                <small>في الانتظار</small>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card border-0 bg-info text-white">
            <div class="card-body text-center p-3">
                <div class="fs-4 fw-bold">{{ $stats['pending_verification'] }}</div>
                <small>بانتظار التحقق</small>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card border-0 bg-success text-white">
            <div class="card-body text-center p-3">
                <div class="fs-4 fw-bold">{{ $stats['completed'] }}</div>
                <small>مكتملة</small>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card border-0 bg-danger text-white">
            <div class="card-body text-center p-3">
                <div class="fs-4 fw-bold">{{ $stats['failed'] }}</div>
                <small>مرفوضة</small>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card border-0 bg-dark text-white">
            <div class="card-body text-center p-3">
                <div class="fs-6 fw-bold">{{ number_format($stats['total_amount'], 2) }}</div>
                <small>إجمالي المبالغ (ريال)</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">البحث</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" 
                       placeholder="رقم المعاملة، اسم المرسل، رقم الجوال...">
            </div>
            <div class="col-md-2">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                    <option value="pending_verification" {{ request('status') === 'pending_verification' ? 'selected' : '' }}>بانتظار التحقق</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتملة</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>مرفوضة</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">طريقة الدفع</label>
                <select name="payment_method" class="form-select">
                    <option value="">جميع الطرق</option>
                    <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                    <option value="myfatoorah" {{ request('payment_method') === 'myfatoorah' ? 'selected' : '' }}>MyFatoorah</option>
                    <option value="tabby" {{ request('payment_method') === 'tabby' ? 'selected' : '' }}>تابي</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">من تاريخ</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">إلى تاريخ</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Payments Table -->
<div class="card">
    <div class="card-body p-0">
        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>رقم المعاملة</th>
                            <th>المشترك</th>
                            <th>المبلغ</th>
                            <th>طريقة الدفع</th>
                            <th>الحالة</th>
                            <th>تاريخ الطلب</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>
                                <span class="fw-bold">#{{ $payment->id }}</span>
                            </td>
                            <td>
                                @if($payment->subscriber)
                                    <div>
                                        <div class="fw-semibold">{{ $payment->subscriber->name }}</div>
                                        <small class="text-muted">{{ $payment->subscriber->phone }}</small>
                                        <br>
                                        <small class="text-muted">{{ $payment->subscriber->card_number }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">{{ $payment->sender_name ?? 'غير محدد' }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-success">{{ number_format($payment->amount, 2) }} ريال</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $payment->payment_method_text }}</span>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'pending_verification' => 'info',
                                        'completed' => 'success',
                                        'failed' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$payment->status] ?? 'secondary' }}">
                                    {{ $payment->status_text }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $payment->created_at->format('Y/m/d') }}</div>
                                <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                       class="btn btn-outline-primary" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($payment->receipt_file)
                                        <a href="{{ route('admin.payments.download-receipt', $payment->id) }}" 
                                           class="btn btn-outline-success" title="تحميل المرفق">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                    @if($payment->canBeVerified())
                                        <button class="btn btn-outline-success" 
                                                onclick="verifyPayment({{ $payment->id }})" title="تأكيد الدفع">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    @if($payment->status === 'pending_verification')
                                        <button class="btn btn-outline-danger" 
                                                onclick="rejectPayment({{ $payment->id }})" title="رفض الدفع">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد مدفوعات</h5>
                <p class="text-muted">لم يتم العثور على أي مدفوعات تطابق معايير البحث</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function verifyPayment(paymentId) {
    Swal.fire({
        title: 'تأكيد الدفع',
        text: 'هل أنت متأكد من تأكيد هذا الدفع؟ سيتم تفعيل الاشتراك تلقائياً.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، تأكيد',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/payments/${paymentId}/verify`;
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function rejectPayment(paymentId) {
    Swal.fire({
        title: 'رفض الدفع',
        input: 'textarea',
        inputLabel: 'سبب الرفض',
        inputPlaceholder: 'أدخل سبب رفض الدفع...',
        inputAttributes: {
            'aria-label': 'سبب الرفض'
        },
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'رفض الدفع',
        cancelButtonText: 'إلغاء',
        inputValidator: (value) => {
            if (!value) {
                return 'يجب إدخال سبب الرفض'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/payments/${paymentId}/reject`;
            form.innerHTML = `
                @csrf
                <input type="hidden" name="rejection_reason" value="${result.value}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function exportPayments() {
    // يمكن إضافة وظيفة التصدير هنا
    Swal.fire('قريباً', 'ميزة التصدير ستكون متاحة قريباً', 'info');
}
</script>
@endpush
@endsection
