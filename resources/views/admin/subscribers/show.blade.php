@extends('layouts.admin')

@section('title', 'تفاصيل المشترك #' . $subscriber->id)

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">تفاصيل المشترك #{{ $subscriber->id }}</h2>
        <p class="text-muted mb-0">عرض تفاصيل المشترك والمدفوعات المرتبطة</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.subscribers.edit', $subscriber->id) }}" 
           class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>
            تعديل
        </a>
        <a href="{{ route('admin.subscribers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Subscriber Details -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    معلومات المشترك
                </h5>
                @php
                    $statusColors = [
                        'فعال' => 'success',
                        'منتهي' => 'warning',
                        'ملغي' => 'danger',
                        'معلق' => 'secondary',
                        'بانتظار الدفع' => 'info'
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$subscriber->status] ?? 'secondary' }} fs-6">
                    {{ $subscriber->status }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">الاسم الكامل:</span>
                            <span class="fw-bold">{{ $subscriber->name }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">رقم البطاقة:</span>
                            <span class="fw-bold text-primary">{{ $subscriber->card_number }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">رقم الجوال:</span>
                            <span>{{ $subscriber->phone }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">البريد الإلكتروني:</span>
                            <span>{{ $subscriber->email ?? 'غير محدد' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">الجنسية:</span>
                            <span>{{ $subscriber->nationality ?? 'غير محدد' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">رقم الهوية:</span>
                            <span>{{ $subscriber->id_number ?? 'غير محدد' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">المدينة:</span>
                            <span>{{ $subscriber->city->name ?? 'غير محدد' }}{{ $subscriber->city && $subscriber->city->region ? ' - ' . $subscriber->city->region->name : '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">تاريخ الإنشاء:</span>
                            <span>{{ $subscriber->created_at->format('Y/m/d H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Details -->
        @if($subscriber->package)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-gift me-2"></i>
                    تفاصيل الباقة
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">اسم الباقة:</span>
                            <span class="fw-bold">{{ $subscriber->package->name }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">تاريخ البداية:</span>
                            <span>{{ $subscriber->start_date ? $subscriber->start_date->format('Y/m/d') : 'غير محدد' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">تاريخ الانتهاء:</span>
                            <span>{{ $subscriber->end_date ? $subscriber->end_date->format('Y/m/d') : 'غير محدد' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">المبلغ الإجمالي:</span>
                            <span class="fw-bold text-success">{{ number_format($subscriber->total_amount, 2) }} ريال</span>
                        </div>
                    </div>
                    @if($subscriber->dependents_count > 0)
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">عدد التابعين:</span>
                            <span>{{ $subscriber->dependents_count }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Dependents -->
        @if($subscriber->dependents && $subscriber->dependents->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    قائمة التابعين ({{ $subscriber->dependents->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>الجنسية</th>
                                <th>رقم الهوية</th>
                                <th>العلاقة</th>
                                <th>السعر</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriber->dependents as $index => $dependent)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $dependent->name }}</td>
                                <td>{{ $dependent->nationality ?? 'غير محدد' }}</td>
                                <td>{{ $dependent->id_number ?? 'غير محدد' }}</td>
                                <td>{{ $dependent->notes ? str_replace('العلاقة: ', '', $dependent->notes) : 'غير محدد' }}</td>
                                <td>{{ number_format($dependent->dependent_price, 2) }} ريال</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Payments History -->
        @if($subscriber->payments && $subscriber->payments->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-credit-card me-2"></i>
                    سجل المدفوعات ({{ $subscriber->payments->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>رقم المعاملة</th>
                                <th>المبلغ</th>
                                <th>طريقة الدفع</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriber->payments as $payment)
                            <tr>
                                <td>#{{ $payment->id }}</td>
                                <td>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                                <td>{{ $payment->payment_method_text }}</td>
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
                                <td>{{ $payment->created_at->format('Y/m/d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    الإجراءات السريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.subscribers.edit', $subscriber->id) }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>
                        تعديل المشترك
                    </a>
                    
                    @if($subscriber->status !== 'فعال')
                    <button class="btn btn-outline-success" onclick="activateSubscriber({{ $subscriber->id }})">
                        <i class="fas fa-check me-2"></i>
                        تفعيل الاشتراك
                    </button>
                    @endif
                    
                    @if($subscriber->status === 'فعال')
                    <button class="btn btn-outline-warning" onclick="suspendSubscriber({{ $subscriber->id }})">
                        <i class="fas fa-pause me-2"></i>
                        تعليق الاشتراك
                    </button>
                    @endif
                    
                    <button class="btn btn-outline-danger" onclick="deleteSubscriber({{ $subscriber->id }})">
                        <i class="fas fa-trash me-2"></i>
                        حذف المشترك
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    إحصائيات
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>إجمالي المدفوعات:</span>
                        <span class="fw-bold text-success">{{ number_format($subscriber->payments->sum('amount'), 2) }} ريال</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>عدد المدفوعات:</span>
                        <span class="fw-bold">{{ $subscriber->payments->count() }}</span>
                    </div>
                </div>
                @if($subscriber->end_date)
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>الأيام المتبقية:</span>
                        <span class="fw-bold {{ $subscriber->days_remaining > 30 ? 'text-success' : ($subscriber->days_remaining > 0 ? 'text-warning' : 'text-danger') }}">
                            {{ $subscriber->days_remaining }} يوم
                        </span>
                    </div>
                </div>
                @endif
                @if($subscriber->creator)
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>أنشئ بواسطة:</span>
                        <span class="fw-bold">{{ $subscriber->creator->name }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function activateSubscriber(subscriberId) {
    Swal.fire({
        title: 'تفعيل الاشتراك',
        text: 'هل أنت متأكد من تفعيل هذا الاشتراك؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، تفعيل',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            // يمكن إضافة route لتفعيل الاشتراك
            window.location.reload();
        }
    });
}

function suspendSubscriber(subscriberId) {
    Swal.fire({
        title: 'تعليق الاشتراك',
        text: 'هل أنت متأكد من تعليق هذا الاشتراك؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، تعليق',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            // يمكن إضافة route لتعليق الاشتراك
            window.location.reload();
        }
    });
}

function deleteSubscriber(subscriberId) {
    Swal.fire({
        title: 'حذف المشترك',
        text: 'هل أنت متأكد من حذف هذا المشترك؟ لا يمكن التراجع عن هذا الإجراء.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، حذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/subscribers/${subscriberId}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
@endsection
