@extends('layouts.admin')

@section('title', 'تفاصيل المدفوعة #' . $payment->id)

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">تفاصيل المدفوعة #{{ $payment->id }}</h2>
        <p class="text-muted mb-0">عرض تفاصيل المدفوعة والمرفقات</p>
    </div>
    <div class="d-flex gap-2">
        @if($payment->receipt_file)
            <a href="{{ route('admin.payments.download-receipt', $payment->id) }}" 
               class="btn btn-outline-success">
                <i class="fas fa-download me-2"></i>
                تحميل المرفق
            </a>
        @endif
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Payment Details -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-credit-card me-2"></i>
                    معلومات المدفوعة
                </h5>
                @php
                    $statusColors = [
                        'pending' => 'warning',
                        'pending_verification' => 'info',
                        'completed' => 'success',
                        'failed' => 'danger'
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$payment->status] ?? 'secondary' }} fs-6">
                    {{ $payment->status_text }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">رقم المعاملة:</span>
                            <span class="fw-bold">#{{ $payment->id }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">المبلغ:</span>
                            <span class="fw-bold text-success">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">طريقة الدفع:</span>
                            <span class="badge bg-info">{{ $payment->payment_method_text }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">تاريخ الطلب:</span>
                            <span>{{ $payment->created_at->format('Y/m/d H:i') }}</span>
                        </div>
                    </div>
                    
                    @if($payment->payment_method === 'bank_transfer')
                        <div class="col-12"><hr></div>
                        <div class="col-12">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-university me-2"></i>
                                تفاصيل التحويل البنكي
                            </h6>
                        </div>
                        @if($payment->sender_name)
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">اسم المرسل:</span>
                                <span>{{ $payment->sender_name }}</span>
                            </div>
                        </div>
                        @endif
                        @if($payment->bank_name)
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">البنك:</span>
                                <span>{{ $payment->bank_name }}</span>
                            </div>
                        </div>
                        @endif
                        @if($payment->transfer_amount)
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">المبلغ المحول:</span>
                                <span class="fw-bold">{{ number_format($payment->transfer_amount, 2) }} ريال</span>
                            </div>
                        </div>
                        @endif
                        @if($payment->transfer_confirmed_at)
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">تاريخ التأكيد:</span>
                                <span>{{ $payment->transfer_confirmed_at->format('Y/m/d H:i') }}</span>
                            </div>
                        </div>
                        @endif
                    @endif
                    
                    @if($payment->verified_at)
                        <div class="col-12"><hr></div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">تاريخ التحقق:</span>
                                <span>{{ $payment->verified_at->format('Y/m/d H:i') }}</span>
                            </div>
                        </div>
                        @if($payment->verifiedBy)
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">تم التحقق بواسطة:</span>
                                <span>{{ $payment->verifiedBy->name }}</span>
                            </div>
                        </div>
                        @endif
                    @endif
                    
                    @if($payment->notes)
                        <div class="col-12"><hr></div>
                        <div class="col-12">
                            <h6 class="fw-semibold">ملاحظات:</h6>
                            <div class="bg-light p-3 rounded">
                                {!! nl2br(e($payment->notes)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Receipt File -->
        @if($payment->receipt_file)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-image me-2"></i>
                    إيصال التحويل
                </h5>
            </div>
            <div class="card-body text-center">
                @php
                    $fileExtension = pathinfo($payment->receipt_file, PATHINFO_EXTENSION);
                    $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']);
                @endphp
                
                @if($isImage)
                    <img src="{{ Storage::url($payment->receipt_file) }}" 
                         class="img-fluid rounded shadow" 
                         style="max-height: 500px; cursor: pointer;"
                         onclick="showImageModal(this.src)">
                @else
                    <div class="py-5">
                        <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                        <h5>ملف PDF</h5>
                        <p class="text-muted">{{ basename($payment->receipt_file) }}</p>
                        <a href="{{ route('admin.payments.download-receipt', $payment->id) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-download me-2"></i>
                            تحميل الملف
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Subscriber Details -->
    <div class="col-lg-4">
        @if($payment->subscriber)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    بيانات المشترك
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                    <h5 class="mt-2 mb-1">{{ $payment->subscriber->name }}</h5>
                    <span class="badge bg-{{ $payment->subscriber->status === 'فعال' ? 'success' : 'warning' }}">
                        {{ $payment->subscriber->status }}
                    </span>
                </div>

                <div class="mb-2">
                    <small class="text-muted">رقم البطاقة</small>
                    <div class="fw-bold">{{ $payment->subscriber->card_number }}</div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">رقم الجوال</small>
                    <div>{{ $payment->subscriber->phone }}</div>
                </div>
                @if($payment->subscriber->email)
                <div class="mb-2">
                    <small class="text-muted">البريد الإلكتروني</small>
                    <div>{{ $payment->subscriber->email }}</div>
                </div>
                @endif
                <div class="mb-2">
                    <small class="text-muted">المدينة</small>
                    <div>{{ $payment->subscriber->city->name ?? 'غير محدد' }}{{ $payment->subscriber->city && $payment->subscriber->city->region ? ' - ' . $payment->subscriber->city->region->name : '' }}</div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">الباقة</small>
                    <div class="fw-semibold">{{ $payment->subscriber->package->name ?? 'غير محدد' }}</div>
                </div>
                @if($payment->subscriber->dependents_count > 0)
                <div class="mb-2">
                    <small class="text-muted">عدد التابعين</small>
                    <div>{{ $payment->subscriber->dependents_count }}</div>
                </div>
                @endif

                <!-- عرض التابعين -->
                @if($payment->subscriber->dependents && $payment->subscriber->dependents->count() > 0)
                <div class="mb-3">
                    <small class="text-muted">قائمة التابعين</small>
                    <div class="mt-1">
                        @foreach($payment->subscriber->dependents as $index => $dependent)
                        <div class="small border-bottom py-1">
                            <strong>{{ $index + 1 }}.</strong> {{ $dependent->name }}
                            <br><small class="text-muted">{{ $dependent->nationality ?? 'غير محدد' }}</small>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('admin.subscribers.show', $payment->subscriber->id) }}"
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>
                        عرض تفاصيل المشترك
                    </a>
                </div>
            </div>
        </div>
        @else
        <!-- عرض البيانات المؤقتة إذا لم يكن هناك مشترك بعد -->
        @php
            $pendingSubscription = null;
            if ($payment->notes) {
                preg_match('/Pending Subscription ID: (\d+)/', $payment->notes, $matches);
                if (isset($matches[1])) {
                    $pendingSubscription = \App\Models\PendingSubscription::with(['package', 'city.region'])
                        ->find($matches[1]);
                }
            }
        @endphp

        @if($pendingSubscription)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    بيانات الاشتراك المؤقتة
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>ملاحظة:</strong> هذه البيانات مؤقتة وسيتم إنشاء المشترك الفعلي بعد تأكيد الدفع.
                </div>

                <div class="text-center mb-3">
                    <div class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-user-clock fa-2x"></i>
                    </div>
                    <h5 class="mt-2 mb-1">{{ $pendingSubscription->name }}</h5>
                    <span class="badge bg-warning">في انتظار التأكيد</span>
                </div>

                <div class="mb-2">
                    <small class="text-muted">رقم الجوال</small>
                    <div>{{ $pendingSubscription->phone }}</div>
                </div>
                @if($pendingSubscription->email)
                <div class="mb-2">
                    <small class="text-muted">البريد الإلكتروني</small>
                    <div>{{ $pendingSubscription->email }}</div>
                </div>
                @endif
                <div class="mb-2">
                    <small class="text-muted">الجنسية</small>
                    <div>{{ $pendingSubscription->nationality ?? 'غير محدد' }}</div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">رقم الهوية</small>
                    <div>{{ $pendingSubscription->id_number ?? 'غير محدد' }}</div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">المدينة</small>
                    <div>{{ $pendingSubscription->city->name ?? 'غير محدد' }}{{ $pendingSubscription->city && $pendingSubscription->city->region ? ' - ' . $pendingSubscription->city->region->name : '' }}</div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">الباقة</small>
                    <div class="fw-semibold">{{ $pendingSubscription->package->name ?? 'غير محدد' }}</div>
                </div>
                @if($pendingSubscription->dependents_count > 0)
                <div class="mb-2">
                    <small class="text-muted">عدد التابعين</small>
                    <div>{{ $pendingSubscription->dependents_count }}</div>
                </div>
                @endif

                <!-- عرض التابعين المؤقتين -->
                @if($pendingSubscription->dependents && is_array($pendingSubscription->dependents) && count($pendingSubscription->dependents) > 0)
                <div class="mb-3">
                    <small class="text-muted">قائمة التابعين</small>
                    <div class="mt-1">
                        @foreach($pendingSubscription->dependents as $index => $dependent)
                            @if(!empty($dependent['name']))
                            <div class="small border-bottom py-1">
                                <strong>{{ $index + 1 }}.</strong> {{ $dependent['name'] }}
                                <br><small class="text-muted">{{ $dependent['nationality'] ?? 'غير محدد' }}</small>
                                @if(!empty($dependent['relationship']))
                                <br><small class="text-muted">العلاقة: {{ $dependent['relationship'] }}</small>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mb-2">
                    <small class="text-muted">المبلغ الإجمالي</small>
                    <div class="fw-bold text-success">{{ number_format($pendingSubscription->total_amount, 2) }} ريال</div>
                </div>

                <div class="mb-2">
                    <small class="text-muted">تاريخ انتهاء الصلاحية</small>
                    <div class="text-warning">{{ $pendingSubscription->expires_at->format('Y/m/d H:i') }}</div>
                </div>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">لا توجد بيانات مشترك</h6>
                <p class="text-muted small mb-0">لم يتم ربط هذا الدفع بأي مشترك أو بيانات مؤقتة</p>
            </div>
        </div>
        @endif
        @endif

        <!-- Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    الإجراءات
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($payment->canBeVerified())
                        <button class="btn btn-success" onclick="verifyPayment({{ $payment->id }})">
                            <i class="fas fa-check me-2"></i>
                            تأكيد الدفع
                        </button>
                    @endif
                    
                    @if($payment->status === 'pending_verification')
                        <button class="btn btn-danger" onclick="rejectPayment({{ $payment->id }})">
                            <i class="fas fa-times me-2"></i>
                            رفض الدفع
                        </button>
                    @endif
                    
                    @if($payment->receipt_file)
                        <a href="{{ route('admin.payments.download-receipt', $payment->id) }}" 
                           class="btn btn-outline-success">
                            <i class="fas fa-download me-2"></i>
                            تحميل المرفق
                        </a>
                    @endif
                    
                    <button class="btn btn-outline-danger" onclick="deletePayment({{ $payment->id }})">
                        <i class="fas fa-trash me-2"></i>
                        حذف المدفوعة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إيصال التحويل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" class="img-fluid">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showImageModal(src) {
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

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

function deletePayment(paymentId) {
    Swal.fire({
        title: 'حذف المدفوعة',
        text: 'هل أنت متأكد من حذف هذه المدفوعة؟ لا يمكن التراجع عن هذا الإجراء.',
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
            form.action = `/admin/payments/${paymentId}`;
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
