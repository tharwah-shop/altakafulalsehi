<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار تأكيد الدفع</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            اختبار تأكيد الدفع
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- معلومات الاختبار -->
                        <div class="alert alert-info">
                            <h6 class="fw-bold">معلومات الاختبار:</h6>
                            <ul class="mb-0">
                                <li>سيتم اختبار تأكيد الدفع رقم 6</li>
                                <li>سيتم إنشاء مشترك جديد إذا لم يكن موجوداً</li>
                                <li>سيتم تحديث حالة الدفع إلى "مكتمل"</li>
                                <li>سيتم تفعيل الاشتراك</li>
                            </ul>
                        </div>

                        <!-- فحص حالة الدفع -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">حالة الدفع الحالية</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $payment = \App\Models\Payment::find(6);
                                @endphp
                                
                                @if($payment)
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <strong>رقم الدفع:</strong> {{ $payment->id }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>المبلغ:</strong> {{ number_format($payment->amount, 2) }} ريال
                                        </div>
                                        <div class="col-md-6">
                                            <strong>الحالة:</strong> 
                                            <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending_verification' ? 'warning' : 'secondary') }}">
                                                {{ $payment->status_text }}
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>طريقة الدفع:</strong> {{ $payment->payment_method_text }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>ملف الإيصال:</strong> 
                                            @if($payment->receipt_file)
                                                <span class="text-success">موجود</span>
                                                <a href="{{ route('admin.payments.download-receipt', $payment->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                                                    <i class="fas fa-download"></i> تحميل
                                                </a>
                                            @else
                                                <span class="text-danger">غير موجود</span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <strong>يمكن التأكيد:</strong> 
                                            <span class="badge bg-{{ $payment->canBeVerified() ? 'success' : 'danger' }}">
                                                {{ $payment->canBeVerified() ? 'نعم' : 'لا' }}
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>المشترك:</strong> 
                                            @if($payment->subscriber_id)
                                                <span class="text-success">موجود (ID: {{ $payment->subscriber_id }})</span>
                                            @else
                                                <span class="text-warning">سيتم إنشاؤه عند التأكيد</span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <strong>تاريخ الإنشاء:</strong> {{ $payment->created_at->format('Y-m-d H:i:s') }}
                                        </div>
                                    </div>

                                    <!-- البيانات المؤقتة -->
                                    @if($payment->notes)
                                        @php
                                            preg_match('/Pending Subscription ID: (\d+)/', $payment->notes, $matches);
                                            $pendingSubscription = null;
                                            if (isset($matches[1])) {
                                                $pendingSubscription = \App\Models\PendingSubscription::with(['package', 'city.region'])->find($matches[1]);
                                            }
                                        @endphp
                                        
                                        @if($pendingSubscription)
                                            <div class="mt-3 pt-3 border-top">
                                                <h6 class="fw-bold text-info">البيانات المؤقتة:</h6>
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <strong>الاسم:</strong> {{ $pendingSubscription->name }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>الجوال:</strong> {{ $pendingSubscription->phone }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>الباقة:</strong> {{ $pendingSubscription->package->name ?? 'غير محدد' }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>المدينة:</strong> {{ $pendingSubscription->city->name ?? 'غير محدد' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <div class="alert alert-danger">
                                        الدفع رقم 6 غير موجود!
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- أزرار الإجراءات -->
                        @if($payment && $payment->canBeVerified())
                            <div class="text-center">
                                <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg me-3" onclick="return confirm('هل أنت متأكد من تأكيد هذا الدفع؟')">
                                        <i class="fas fa-check me-2"></i>
                                        تأكيد الدفع
                                    </button>
                                </form>
                                
                                <button type="button" class="btn btn-danger btn-lg" onclick="showRejectModal()">
                                    <i class="fas fa-times me-2"></i>
                                    رفض الدفع
                                </button>
                            </div>
                        @elseif($payment && $payment->status === 'completed')
                            <div class="alert alert-success text-center">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h5>تم تأكيد هذا الدفع مسبقاً</h5>
                            </div>
                        @endif

                        <!-- روابط مفيدة -->
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="fw-bold">روابط مفيدة:</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="/admin/payments/6" class="btn btn-outline-primary btn-sm">صفحة تفاصيل الدفع</a>
                                <a href="/admin/payments" class="btn btn-outline-secondary btn-sm">قائمة المدفوعات</a>
                                <a href="/bank-transfer/6" class="btn btn-outline-info btn-sm">صفحة التحويل البنكي</a>
                                @if($payment && $payment->subscriber_id)
                                    <a href="/admin/subscribers/{{ $payment->subscriber_id }}" class="btn btn-outline-success btn-sm">صفحة المشترك</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لرفض الدفع -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">رفض الدفع</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.payments.reject', $payment->id ?? 0) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">سبب الرفض:</label>
                            <textarea class="form-control" name="rejection_reason" rows="3" required 
                                      placeholder="أدخل سبب رفض الدفع..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">رفض الدفع</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showRejectModal() {
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }

        // عرض رسائل النجاح/الخطأ
        @if(session('success'))
            alert('✅ {{ session('success') }}');
        @endif

        @if(session('error'))
            alert('❌ {{ session('error') }}');
        @endif
    </script>
</body>
</html>
