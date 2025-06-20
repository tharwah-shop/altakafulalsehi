@extends('layouts.frontend')
@section('title', 'التحويل البنكي - التكافل الصحي')

@push('styles')
<style>
    .cursor-pointer {
        cursor: pointer;
    }

    #upload-area:hover {
        border-color: #0d6efd !important;
        background-color: #e7f1ff !important;
    }

    #upload-area.dragover {
        border-color: #0d6efd !important;
        background-color: #e7f1ff !important;
    }

    .font-monospace {
        font-family: 'Courier New', monospace;
    }

    @media (max-width: 768px) {
        .display-5 {
            font-size: 2rem;
        }

        .d-flex.justify-content-center.align-items-center.flex-wrap.gap-3 {
            flex-direction: column;
            gap: 1rem !important;
        }

        .d-flex.justify-content-center.align-items-center.flex-wrap.gap-3 > div:nth-child(2),
        .d-flex.justify-content-center.align-items-center.flex-wrap.gap-3 > div:nth-child(4) {
            width: 30px;
            height: 3px;
            transform: rotate(90deg);
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="text-center">
            <span class="badge bg-light text-primary px-3 py-2 mb-3">
                <i class="fas fa-university me-1"></i>
                التحويل البنكي
            </span>

            <h1 class="display-5 fw-bold mb-3">أكمل عملية الدفع بالتحويل البنكي</h1>

            <p class="lead mb-4">قم بتحويل المبلغ المطلوب إلى أحد حساباتنا البنكية وأرسل إيصال التحويل</p>

            <!-- Progress Steps -->
            <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mt-4">
                <div class="text-center">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-check"></i>
                    </div>
                    <small>اختر الباقة</small>
                </div>
                <div class="bg-success" style="width: 40px; height: 3px; border-radius: 2px;"></div>
                <div class="text-center">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-check"></i>
                    </div>
                    <small>املأ البيانات</small>
                </div>
                <div class="bg-success" style="width: 40px; height: 3px; border-radius: 2px;"></div>
                <div class="text-center">
                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <strong>3</strong>
                    </div>
                    <small>التحويل البنكي</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <!-- Payment Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>
                            ملخص الدفع
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($payment))
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-semibold">المبلغ المطلوب:</span>
                            <span class="fw-bold text-primary h5 mb-0">{{ number_format($payment->amount, 2) }} ريال</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-semibold">رقم الطلب:</span>
                            <span class="fw-bold">{{ $payment->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-semibold">تاريخ الطلب:</span>
                            <span class="fw-bold">{{ $payment->created_at->format('Y/m/d') }}</span>
                        </div>
                        @endif

                        @if(isset($pendingSubscription) && $pendingSubscription)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-semibold">اسم المشترك:</span>
                            <span class="fw-bold">{{ $pendingSubscription->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-semibold">رقم الجوال:</span>
                            <span class="fw-bold">{{ $pendingSubscription->phone }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-semibold">الباقة المختارة:</span>
                            <span class="fw-bold">{{ $pendingSubscription->package->name ?? 'غير محدد' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-semibold">المدينة:</span>
                            <span class="fw-bold">{{ $pendingSubscription->city->name ?? 'غير محدد' }}{{ $pendingSubscription->city && $pendingSubscription->city->region ? ' - ' . $pendingSubscription->city->region->name : '' }}</span>
                        </div>
                        @if($pendingSubscription->dependents_count > 0)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-semibold">عدد التابعين:</span>
                            <span class="fw-bold">{{ $pendingSubscription->dependents_count }}</span>
                        </div>
                        @endif
                        @if($pendingSubscription->dependents && is_array($pendingSubscription->dependents) && count($pendingSubscription->dependents) > 0)
                        <div class="mt-3">
                            <h6 class="fw-bold text-info mb-2">
                                <i class="fas fa-users me-2"></i>
                                قائمة التابعين:
                            </h6>
                            @foreach($pendingSubscription->dependents as $index => $dependent)
                                @if(!empty($dependent['name']))
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light">
                                    <span class="small">{{ $index + 1 }}. {{ $dependent['name'] }}</span>
                                    <span class="small text-muted">{{ $dependent['nationality'] ?? 'غير محدد' }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @endif
                        @elseif(isset($payment) && $payment->subscriber)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-semibold">اسم المشترك:</span>
                            <span class="fw-bold">{{ $payment->subscriber->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-semibold">رقم البطاقة:</span>
                            <span class="fw-bold">{{ $payment->subscriber->card_number }}</span>
                        </div>
                        @endif

                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>مهم:</strong> تأكد من تحويل المبلغ الصحيح بالضبط
                        </div>
                    </div>
                </div>

                <!-- WhatsApp Contact -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fab fa-whatsapp me-2"></i>
                            تواصل معنا عبر واتساب
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-muted mb-3">للمساعدة أو الاستفسار عن عملية الدفع</p>
                        @if(isset($bankConfig) && isset($payment))
                        <a href="https://wa.me/{{ $bankConfig['whatsapp_number'] ?? '966920031304' }}?text={{ urlencode(($bankConfig['whatsapp_message'] ?? 'مرحباً، أحتاج مساعدة في عملية التحويل البنكي للاشتراك.') . ' رقم الطلب: ' . $payment->id) }}"
                           class="btn btn-success btn-lg" target="_blank">
                            <i class="fab fa-whatsapp me-2"></i>
                            تواصل الآن
                        </a>
                        @else
                        <a href="https://wa.me/966920031304?text={{ urlencode('مرحباً، أحتاج مساعدة في عملية التحويل البنكي للاشتراك.') }}"
                           class="btn btn-success btn-lg" target="_blank">
                            <i class="fab fa-whatsapp me-2"></i>
                            تواصل الآن
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bank Transfer Details -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-university me-2"></i>
                            حسابات البنوك المتاحة
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Al Rajhi Bank -->
                        <div class="card bg-gradient border-0 mb-4" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="me-3">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f1/Al_Rajhi_Bank_Logo.svg/2560px-Al_Rajhi_Bank_Logo.svg.png"
                                             alt="مصرف الراجحي" class="img-fluid" style="height: 40px; filter: brightness(0) invert(1);">
                                    </div>
                                    <div>
                                        <h4 class="mb-1 fw-bold">مصرف الراجحي</h4>
                                        <small class="opacity-75">Al Rajhi Bank</small>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="fw-bold mb-2 d-block">
                                                <i class="fas fa-building me-2"></i>
                                                اسم الحساب:
                                            </label>
                                            <div class="bg-white bg-opacity-10 p-3 rounded cursor-pointer" onclick="copyBankNumber('شركة بطاقة التكافل الصحي')">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">شركة بطاقة التكافل الصحي</span>
                                                    <button class="btn btn-sm btn-outline-light" type="button">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="fw-bold mb-2 d-block">
                                                <i class="fas fa-credit-card me-2"></i>
                                                رقم الحساب:
                                            </label>
                                            <div class="bg-white bg-opacity-10 p-3 rounded cursor-pointer" onclick="copyBankNumber('577608010023961')">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold font-monospace">577608010023961</span>
                                                    <button class="btn btn-sm btn-outline-light" type="button">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="fw-bold mb-2 d-block">
                                                <i class="fas fa-globe me-2"></i>
                                                رقم الآيبان:
                                            </label>
                                            <div class="bg-white bg-opacity-10 p-3 rounded cursor-pointer" onclick="copyBankNumber('SA0680000577608010023961')">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold font-monospace">SA0680000577608010023961</span>
                                                    <button class="btn btn-sm btn-outline-light" type="button">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="fw-bold mb-2 d-block">
                                                <i class="fas fa-code me-2"></i>
                                                رمز البنك:
                                            </label>
                                            <div class="bg-white bg-opacity-10 p-3 rounded cursor-pointer" onclick="copyBankNumber('RJHI')">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold font-monospace">RJHI</span>
                                                    <button class="btn btn-sm btn-outline-light" type="button">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <div class="badge bg-success bg-opacity-25 text-white px-3 py-2">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        حساب آمن ومعتمد
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Subscription Summary -->
                        @if(isset($pendingSubscription) && $pendingSubscription)
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-receipt me-2"></i>
                                    ملخص الاشتراك
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">المشترك:</span>
                                            <span>{{ $pendingSubscription->name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">الجوال:</span>
                                            <span>{{ $pendingSubscription->phone }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">البريد الإلكتروني:</span>
                                            <span>{{ $pendingSubscription->email ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">الجنسية:</span>
                                            <span>{{ $pendingSubscription->nationality ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">الباقة:</span>
                                            <span>{{ $pendingSubscription->package->name ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">المدينة:</span>
                                            <span>{{ $pendingSubscription->city->name ?? 'غير محدد' }}{{ $pendingSubscription->city && $pendingSubscription->city->region ? ' - ' . $pendingSubscription->city->region->name : '' }}</span>
                                        </div>
                                    </div>
                                    @if($pendingSubscription->dependents_count > 0)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">عدد التابعين:</span>
                                            <span>{{ $pendingSubscription->dependents_count }}</span>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- قائمة التابعين التفصيلية -->
                                    @if($pendingSubscription->dependents && is_array($pendingSubscription->dependents) && count($pendingSubscription->dependents) > 0)
                                    <div class="col-12">
                                        <hr class="my-2">
                                        <h6 class="fw-bold text-info mb-2">
                                            <i class="fas fa-users me-2"></i>
                                            تفاصيل التابعين:
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>الاسم</th>
                                                        <th>الجنسية</th>
                                                        <th>رقم الهوية</th>
                                                        <th>العلاقة</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pendingSubscription->dependents as $index => $dependent)
                                                        @if(!empty($dependent['name']))
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $dependent['name'] }}</td>
                                                            <td>{{ $dependent['nationality'] ?? 'غير محدد' }}</td>
                                                            <td>{{ $dependent['id_number'] ?? 'غير محدد' }}</td>
                                                            <td>{{ $dependent['relationship'] ?? 'غير محدد' }}</td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-12">
                                        <hr class="my-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-primary">المبلغ الإجمالي:</span>
                                            <span class="fw-bold text-primary fs-5">{{ number_format($pendingSubscription->total_amount, 2) }} ريال</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- في حالة عدم وجود بيانات مؤقتة -->
                        <div class="alert alert-warning">
                            <h6 class="fw-bold">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                تنبيه: لم يتم العثور على بيانات الاشتراك
                            </h6>
                            <p class="mb-3">قد تكون بيانات الاشتراك المؤقتة منتهية الصلاحية أو غير موجودة.</p>
                            <div class="d-grid gap-2 d-md-flex">
                                <a href="{{ route('subscribe') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    إنشاء اشتراك جديد
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-home me-2"></i>
                                    العودة للرئيسية
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Transfer Instructions -->
                        <div class="alert alert-warning">
                            <h5 class="fw-bold">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                تعليمات مهمة
                            </h5>
                            <ul class="mb-0">
                                {{-- <li>قم بتحويل المبلغ بالضبط: <strong>{{ number_format($payment->amount, 2) }} ريال</strong></li> --}}
                                <li>احتفظ بإيصال التحويل لرفعه بالاسفل</li>
                                <li>سيتم مراجعة التحويل خلال 1-2 يوم عمل</li>
                                <li>ستصلك رسالة تأكيد على جوالك وبريدك الإلكتروني</li>
                            </ul>
                        </div>

                        <!-- Upload Receipt Form -->
                        @if(isset($payment))
                        <form id="transfer-confirmation-form" action="{{ route('payment.bank-transfer.confirm', $payment->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                            <input type="hidden" name="bank_name" value="مصرف الراجحي">

                            <!-- عرض رسائل الخطأ -->
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <h6 class="fw-bold mb-2">يرجى تصحيح الأخطاء التالية:</h6>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- عرض رسائل النجاح -->
                            @if(session('success'))
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- عرض رسائل الخطأ -->
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <h4 class="fw-bold mb-3">
                                <i class="fas fa-upload text-success me-2"></i>
                                رفع إيصال التحويل
                            </h4>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">المبلغ المحول</label>
                                    <input type="number" step="0.01" class="form-control @error('transfer_amount') is-invalid @enderror"
                                           name="transfer_amount" value="{{ old('transfer_amount', $payment->amount) }}" required>
                                    @error('transfer_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">اسم المرسل</label>
                                    <input type="text" class="form-control @error('sender_name') is-invalid @enderror"
                                           name="sender_name"
                                           value="{{ old('sender_name', (isset($pendingSubscription) && $pendingSubscription) ? $pendingSubscription->name : '') }}"
                                           placeholder="اسم صاحب الحساب المحول منه" required>
                                    @error('sender_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-info">
                                        <i class="fas fa-info-circle me-1"></i>
                                        @if(isset($pendingSubscription) && $pendingSubscription)
                                            تم ملء الاسم تلقائياً من بيانات الاشتراك. يمكنك تغييره إذا كان مختلفاً.
                                        @else
                                            أدخل اسم صاحب الحساب المحول منه
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">ملاحظات إضافية (اختياري)</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              name="notes" rows="3"
                                              placeholder="أي معلومات إضافية تريد إضافتها...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">إيصال التحويل <span class="text-danger">*</span></label>
                                    <div class="border border-2 border-dashed rounded p-4 text-center bg-light @error('receipt_file') border-danger @enderror"
                                         id="upload-area" style="cursor: pointer; transition: all 0.3s ease;">
                                        <input type="file" id="receipt_file" name="receipt_file"
                                               accept="image/*,.pdf" style="display: none;" required>
                                        <div id="upload-content">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <h5 class="fw-bold text-dark">اسحب وأفلت الملف هنا</h5>
                                            <p class="text-muted mb-2">أو اضغط لاختيار ملف (صورة أو PDF)</p>
                                            <small class="text-muted">الحد الأقصى: 5 ميجابايت</small>
                                        </div>
                                        <div id="file-preview" style="display: none;">
                                            <img id="preview-image" class="img-fluid rounded mb-3" style="max-width: 200px; max-height: 200px;">
                                            <div id="file-info" class="mb-3"></div>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeFile()">
                                                <i class="fas fa-trash me-1"></i>
                                                إزالة الملف
                                            </button>
                                        </div>
                                    </div>
                                    @error('receipt_file')
                                        <div class="text-danger mt-2">
                                            <small><i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</small>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-5" id="submit-btn">
                                    <span id="submit-text">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        إرسال تأكيد التحويل
                                    </span>
                                    <span id="loading-text" style="display: none;">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        جاري الإرسال...
                                    </span>
                                </button>
                            </div>
                        </form>
                        @else
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>خطأ:</strong> لم يتم العثور على بيانات الدفع. يرجى المحاولة مرة أخرى.
                        </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Security Section -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <span class="badge bg-primary px-4 py-2 mb-3">الأمان والثقة</span>
                <h3 class="fw-bold">تحويل آمن ومضمون</h3>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 bg-dark bg-opacity-50 text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" >
                            <i class="fas fa-shield-alt fa-lg"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">حسابات رسمية</h5>
                        <p class="text-muted mb-0">حسابات بنكية معتمدة ومرخصة</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 bg-dark bg-opacity-50 text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" >
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">مراجعة سريعة</h5>
                        <p class="text-muted mb-0">مراجعة خلال 1-2 يوم عمل</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 bg-dark bg-opacity-50 text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" >
                            <i class="fas fa-mobile-alt fa-lg"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">تأكيد فوري</h5>
                        <p class="text-muted mb-0">رسالة تأكيد على الجوال والبريد</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 bg-dark bg-opacity-50 text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                            <i class="fas fa-headset fa-lg"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">دعم 24/7</h5>
                        <p class="text-muted mb-0">خدمة عملاء على مدار الساعة</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload functionality
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('receipt_file');
    const uploadContent = document.getElementById('upload-content');
    const filePreview = document.getElementById('file-preview');
    const previewImage = document.getElementById('preview-image');
    const fileInfo = document.getElementById('file-info');

    if (!uploadArea || !fileInput) {
        console.error('Upload elements not found');
        return;
    }

    // Click to upload
    uploadArea.addEventListener('click', (e) => {
        if (e.target.type !== 'button') {
            fileInput.click();
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', (e) => {
        if (!uploadArea.contains(e.relatedTarget)) {
            uploadArea.classList.remove('dragover');
        }
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFile(e.target.files[0]);
        }
    });

    function handleFile(file) {
        try {
            // Validate file exists
            if (!file) {
                showAlert('لم يتم اختيار أي ملف. يرجى المحاولة مرة أخرى.', 'warning');
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'];
            const allowedExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.pdf'];
            const fileExtension = file.name.toLowerCase().substring(file.name.lastIndexOf('.'));

            if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                showAlert('نوع الملف غير مدعوم. يرجى اختيار صورة (JPG, PNG, GIF) أو ملف PDF فقط.', 'danger');
                fileInput.value = '';
                return;
            }

            // Validate file size (5MB max, 1KB min)
            const maxSize = 5 * 1024 * 1024; // 5MB
            const minSize = 1024; // 1KB

            if (file.size > maxSize) {
                showAlert(`حجم الملف كبير جداً (${formatFileSize(file.size)}). الحد الأقصى ${formatFileSize(maxSize)}.`, 'danger');
                fileInput.value = '';
                return;
            }

            if (file.size < minSize) {
                showAlert('حجم الملف صغير جداً. يرجى التأكد من صحة الملف.', 'warning');
                fileInput.value = '';
                return;
            }

            // Validate file name
            if (file.name.length > 255) {
                showAlert('اسم الملف طويل جداً. يرجى إعادة تسمية الملف.', 'warning');
                fileInput.value = '';
                return;
            }
        } catch (error) {
            console.error('File validation error:', error);
            showAlert('حدث خطأ أثناء فحص الملف. يرجى المحاولة مرة أخرى.', 'danger');
            fileInput.value = '';
            return;
        }

        // Show preview
        if (uploadContent) uploadContent.style.display = 'none';
        if (filePreview) filePreview.style.display = 'block';

        if (file.type.startsWith('image/') && previewImage) {
            const reader = new FileReader();
            reader.onload = (e) => {
                try {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    previewImage.onerror = function() {
                        showAlert('فشل في تحميل معاينة الصورة. الملف قد يكون تالفاً.', 'warning');
                        previewImage.style.display = 'none';
                    };
                } catch (error) {
                    console.error('Error displaying image preview:', error);
                    showAlert('حدث خطأ في معاينة الصورة.', 'warning');
                    previewImage.style.display = 'none';
                }
            };
            reader.onerror = function() {
                showAlert('فشل في قراءة الملف. يرجى التأكد من سلامة الملف والمحاولة مرة أخرى.', 'danger');
                fileInput.value = '';
            };
            reader.readAsDataURL(file);
        } else if (previewImage) {
            previewImage.style.display = 'none';
        }

        if (fileInfo) {
            fileInfo.innerHTML = `
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <i class="fas fa-file-${file.type.startsWith('image/') ? 'image' : 'pdf'} fa-2x text-primary me-2"></i>
                    <div>
                        <div class="fw-bold">${file.name}</div>
                        <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} ميجابايت</small>
                    </div>
                </div>
            `;
        }

        // Remove error styling
        uploadArea.classList.remove('border-danger');
    }

    // Global function for removing file
    window.removeFile = function() {
        fileInput.value = '';
        if (uploadContent) uploadContent.style.display = 'block';
        if (filePreview) filePreview.style.display = 'none';
        if (previewImage) previewImage.src = '';
        uploadArea.classList.remove('border-danger');
    };

    // Copy bank account number
    window.copyBankNumber = function(number) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(number).then(() => {
                showAlert('تم نسخ رقم الحساب بنجاح!', 'success');
            }).catch(() => {
                fallbackCopyTextToClipboard(number);
            });
        } else {
            fallbackCopyTextToClipboard(number);
        }
    };

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            document.execCommand('copy');
            showAlert('تم نسخ رقم الحساب بنجاح!', 'success');
        } catch (err) {
            showAlert('فشل في نسخ الرقم. يرجى نسخه يدوياً.', 'warning');
        }

        document.body.removeChild(textArea);
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 بايت';
        const k = 1024;
        const sizes = ['بايت', 'كيلوبايت', 'ميجابايت', 'جيجابايت'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showAlert(message, type = 'info') {
        try {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.temp-alert');
            existingAlerts.forEach(alert => alert.remove());

            // Create new alert using Bootstrap classes
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show temp-alert position-fixed`;
            alertDiv.style.top = '20px';
            alertDiv.style.right = '20px';
            alertDiv.style.zIndex = '9999';
            alertDiv.style.minWidth = '300px';
            alertDiv.style.maxWidth = '500px';
            alertDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    <div>${message}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        } catch (error) {
            console.error('Error showing alert:', error);
            // Fallback to browser alert
            alert(message);
        }
    }

    // Form submission with enhanced validation
    const form = document.getElementById('transfer-confirmation-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            try {
                // Validate file upload
                if (!fileInput.files.length) {
                    e.preventDefault();
                    showAlert('يرجى رفع إيصال التحويل أولاً.', 'danger');
                    uploadArea.classList.add('border-danger');
                    uploadArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                // Validate required fields
                const requiredFields = ['transfer_amount', 'sender_name', 'bank_name'];
                let hasErrors = false;

                requiredFields.forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    if (field && !field.value.trim()) {
                        field.classList.add('is-invalid');
                        hasErrors = true;
                    } else if (field) {
                        field.classList.remove('is-invalid');
                    }
                });

                if (hasErrors) {
                    e.preventDefault();
                    showAlert('يرجى تعبئة جميع الحقول المطلوبة.', 'danger');
                    return;
                }

                // Validate transfer amount
                const amountField = form.querySelector('[name="transfer_amount"]');
                if (amountField) {
                    const amount = parseFloat(amountField.value);
                    if (isNaN(amount) || amount <= 0) {
                        e.preventDefault();
                        amountField.classList.add('is-invalid');
                        showAlert('يرجى إدخال مبلغ تحويل صحيح.', 'danger');
                        return;
                    }
                }

                // Show loading state
                const submitBtn = document.getElementById('submit-btn');
                const submitText = document.getElementById('submit-text');
                const loadingText = document.getElementById('loading-text');

                if (submitBtn) {
                    submitBtn.disabled = true;
                    if (submitText) submitText.style.display = 'none';
                    if (loadingText) loadingText.style.display = 'inline';
                }

                // Show success message
                showAlert('جاري إرسال تأكيد التحويل...', 'info');

            } catch (error) {
                e.preventDefault();
                console.error('Form submission error:', error);
                showAlert('حدث خطأ أثناء إرسال النموذج. يرجى المحاولة مرة أخرى.', 'danger');
            }
        });
    }
});
</script>
@endpush

