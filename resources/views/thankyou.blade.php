@extends('layouts.frontend')
@section('title', 'شكراً لك - التكافل الصحي')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-check-circle me-1"></i>
                تم التسجيل بنجاح
            </div>
            
            <h1 class="hero-title">شكراً لك {{ $subscriber->name ?? '' }} على الاشتراك في تكافل الصحي</h1>

            <p class="hero-description">تم إنشاء اشتراكك بنجاح وسيتم مراجعة التحويل البنكي خلال 1-2 يوم عمل</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="thankyou-card card">
                    <div class="card-body text-center p-5">
                        <div class="success-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        
                        <h2 class="fw-bold mb-3">تم إنشاء اشتراكك بنجاح!</h2>

                        <p class="mb-4">شكراً لك {{ $subscriber->name }} على الاشتراك في باقة {{ $subscriber->package->name }}. تم إنشاء حسابك وسيتم تفعيله بعد تأكيد التحويل البنكي.</p>

                        <!-- تفاصيل الاشتراك -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-id-card me-2"></i>
                                    تفاصيل اشتراكك
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">رقم البطاقة:</span>
                                            <span class="fw-bold text-primary">{{ $subscriber->card_number }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">الباقة:</span>
                                            <span class="fw-bold">{{ $subscriber->package->name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">المبلغ المدفوع:</span>
                                            <span class="fw-bold text-success">{{ number_format($subscriber->total_amount, 2) }} ريال</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">تاريخ البداية:</span>
                                            <span class="fw-bold">{{ $subscriber->start_date->format('Y/m/d') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">تاريخ الانتهاء:</span>
                                            <span class="fw-bold">{{ $subscriber->end_date->format('Y/m/d') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">الحالة:</span>
                                            <span class="badge bg-warning">{{ $subscriber->status }}</span>
                                        </div>
                                    </div>
                                    @if($subscriber->dependents_count > 0)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">عدد التابعين:</span>
                                            <span class="fw-bold">{{ $subscriber->dependents_count }}</span>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">المدينة:</span>
                                            <span class="fw-bold">{{ $subscriber->city->name ?? 'غير محدد' }}{{ $subscriber->city && $subscriber->city->region ? ' - ' . $subscriber->city->region->name : '' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">الجنسية:</span>
                                            <span class="fw-bold">{{ $subscriber->nationality ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">رقم الهوية:</span>
                                            <span class="fw-bold">{{ $subscriber->id_number ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- تفاصيل الباقة -->
                                @if($subscriber->package)
                                <hr>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary">
                                            <i class="fas fa-gift me-2"></i>
                                            تفاصيل الباقة
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">اسم الباقة:</span>
                                            <span class="fw-bold">{{ $subscriber->package->name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">مدة الاشتراك:</span>
                                            <span class="fw-bold">{{ $subscriber->package->duration_text ?? $subscriber->package->duration_months . ' شهر' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">سعر الباقة:</span>
                                            <span class="fw-bold text-success">{{ $subscriber->package->formatted_price ?? number_format($subscriber->package->price, 2) . ' ريال' }}</span>
                                        </div>
                                    </div>
                                    @if($subscriber->package->dependent_price > 0)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">سعر التابع:</span>
                                            <span class="fw-bold text-info">{{ $subscriber->package->formatted_dependent_price ?? number_format($subscriber->package->dependent_price, 2) . ' ريال' }}</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <!-- تفاصيل التابعين -->
                                @if($subscriber->dependents && $subscriber->dependents->count() > 0)
                                <hr>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-info">
                                            <i class="fas fa-users me-2"></i>
                                            قائمة التابعين ({{ $subscriber->dependents->count() }})
                                        </h6>
                                    </div>
                                    <div class="col-12">
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

                                @if($latestPayment)
                                <hr>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-info">
                                            <i class="fas fa-credit-card me-2"></i>
                                            معلومات الدفع
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">رقم المعاملة:</span>
                                            <span class="fw-bold">{{ $latestPayment->id }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">طريقة الدفع:</span>
                                            <span class="fw-bold">{{ $latestPayment->payment_method_text }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">حالة الدفع:</span>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'pending_verification' => 'info',
                                                    'completed' => 'success',
                                                    'failed' => 'danger',
                                                    'cancelled' => 'secondary'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$latestPayment->status] ?? 'secondary' }}">{{ $latestPayment->status_text }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">تاريخ الطلب:</span>
                                            <span class="fw-bold">{{ $latestPayment->created_at->format('Y/m/d H:i') }}</span>
                                        </div>
                                    </div>
                                    @if($latestPayment->payment_method === 'bank_transfer')
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">اسم المرسل:</span>
                                            <span class="fw-bold">{{ $latestPayment->sender_name ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">البنك:</span>
                                            <span class="fw-bold">{{ $latestPayment->bank_name ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>
                                    @if($latestPayment->transfer_amount)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">مبلغ التحويل:</span>
                                            <span class="fw-bold text-success">{{ number_format($latestPayment->transfer_amount, 2) }} ريال</span>
                                        </div>
                                    </div>
                                    @endif
                                    @if($latestPayment->transfer_confirmed_at)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">تاريخ التحويل:</span>
                                            <span class="fw-bold">{{ $latestPayment->transfer_confirmed_at->format('Y/m/d H:i') }}</span>
                                        </div>
                                    </div>
                                    @endif
                                    @endif
                                    @if($latestPayment->verified_at)
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold">تاريخ التحقق:</span>
                                            <span class="fw-bold text-success">{{ $latestPayment->verified_at->format('Y/m/d H:i') }}</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- الخطوات التالية -->
                        <div class="card border-success mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-list-check me-2"></i>
                                    الخطوات التالية
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <span class="fw-bold">1</span>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1">مراجعة التحويل</h6>
                                                <p class="mb-0 text-muted">سيتم مراجعة إيصال التحويل البنكي خلال 1-2 يوم عمل</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <div class="step-number bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <span class="fw-bold">2</span>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1">تفعيل الاشتراك</h6>
                                                <p class="mb-0 text-muted">سيتم تفعيل اشتراكك وإرسال رسالة تأكيد</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <div class="step-number bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <span class="fw-bold">3</span>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1">استلام البطاقة</h6>
                                                <p class="mb-0 text-muted">ستحصل على بطاقة التكافل الصحي الرقمية</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <div class="step-number bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <span class="fw-bold">4</span>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1">بداية الاستفادة</h6>
                                                <p class="mb-0 text-muted">يمكنك البدء في الاستفادة من خدمات الشبكة الطبية</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <a href="/" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-home me-2"></i>
                                العودة للرئيسية
                            </a>
                            <a href="{{ route('medicalnetwork') }}" class="btn btn-outline-primary btn-lg px-4">
                                <i class="fas fa-hospital me-2"></i>
                                الشبكة الطبية
                            </a>
                            <a href="/contact" class="btn btn-outline-success btn-lg px-4">
                                <i class="fas fa-phone me-2"></i>
                                تواصل معنا
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Download Section -->
                <div class="download-card">
                    <h4 class="fw-bold mb-3">
                        <i class="fas fa-download me-2"></i>
                        تحميل الوثائق
                    </h4>
                    <p class="mb-4">قم بتحميل بطاقة التكافل الصحي والوثائق المهمة</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ asset('documents/card-template.html') }}" target="_blank" class="btn btn-light btn-lg w-100">
                                <i class="fas fa-id-card me-2"></i>
                                تحميل بطاقة التكافل
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ asset('documents/terms-and-conditions.html') }}" target="_blank" class="btn btn-light btn-lg w-100">
                                <i class="fas fa-file-pdf me-2"></i>
                                تحميل الشروط والأحكام
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3 text-center">
                            <i class="fas fa-info-circle text-success me-2"></i>
                            معلومات مهمة
                        </h4>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-phone text-success me-2 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">خدمة العملاء</h6>
                                        <p class="text-muted mb-0">920031304</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fab fa-whatsapp text-success me-2 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">واتساب</h6>
                                        <p class="text-muted mb-0">966920031304</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-envelope text-success me-2 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">البريد الإلكتروني</h6>
                                        <p class="text-muted mb-0">info@takaful.com</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-clock text-warning me-2 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">ساعات العمل</h6>
                                        <p class="text-muted mb-0">الأحد - الخميس: 8 ص - 4 م</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    function initiateTabbyPayment() {
        // هنا يمكن إضافة كود تابي للدفع
        Swal.fire({
            title: 'تابي - الدفع على 4 أقساط',
            text: 'سيتم توجيهك إلى صفحة الدفع عبر تابي',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'متابعة',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#FF6B35'
        }).then((result) => {
            if (result.isConfirmed) {
                // إضافة كود تابي هنا
                window.open('https://tabby.ai', '_blank');
            }
        });
    }

    function initiateMyFatoorahPayment() {
        // هنا يمكن إضافة كود ماي فاتورة للدفع
        Swal.fire({
            title: 'ماي فاتورة',
            text: 'سيتم توجيهك إلى بوابة الدفع الآمنة',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'متابعة',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                // إضافة كود ماي فاتورة هنا
                window.open('https://myfatoorah.com', '_blank');
            }
        });
    }

    // تحميل PDF
    function downloadPDF(url, filename) {
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // إضافة تأثيرات بصرية للأزرار
    document.addEventListener('DOMContentLoaded', function() {
        const paymentButtons = document.querySelectorAll('.btn-takaful');
        paymentButtons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endpush