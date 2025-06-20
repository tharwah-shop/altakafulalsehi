@extends('layouts.frontend')

@section('title', 'تم الاشتراك بنجاح')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="text-center mb-5">
                <div class="success-icon mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                </div>
                <h1 class="text-success mb-3">تم إنشاء اشتراكك بنجاح!</h1>
                <p class="lead text-muted">مرحباً بك في عائلة التكافل الصحي</p>
            </div>

            <!-- Subscription Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-id-card me-2"></i>
                        تفاصيل الاشتراك
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="fw-bold text-muted">اسم المشترك:</label>
                                <div class="text-dark">{{ $subscriber->name }}</div>
                            </div>
                            <div class="info-item mb-3">
                                <label class="fw-bold text-muted">رقم الجوال:</label>
                                <div class="text-dark">{{ $subscriber->formatted_phone }}</div>
                            </div>
                            <div class="info-item mb-3">
                                <label class="fw-bold text-muted">البريد الإلكتروني:</label>
                                <div class="text-dark">{{ $subscriber->email ?: 'غير محدد' }}</div>
                            </div>
                            <div class="info-item mb-3">
                                <label class="fw-bold text-muted">المدينة:</label>
                                <div class="text-dark">{{ $subscriber->city->name ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label class="fw-bold text-muted">رقم البطاقة:</label>
                                <div class="text-primary fw-bold fs-5">{{ $subscriber->card_number }}</div>
                            </div>
                            <div class="info-item mb-3">
                                <label class="fw-bold text-muted">الباقة:</label>
                                <div class="text-dark">{{ $subscriber->package->name ?? 'غير محدد' }}</div>
                            </div>
                            <div class="info-item mb-3">
                                <label class="fw-bold text-muted">تاريخ البداية:</label>
                                <div class="text-dark">{{ $subscriber->start_date->format('Y/m/d') }}</div>
                            </div>
                            <div class="info-item mb-3">
                                <label class="fw-bold text-muted">تاريخ الانتهاء:</label>
                                <div class="text-dark">{{ $subscriber->end_date->format('Y/m/d') }}</div>
                            </div>
                        </div>
                    </div>

                    @if($subscriber->dependents->count() > 0)
                    <hr>
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-users me-2"></i>
                        التابعين ({{ $subscriber->dependents->count() }})
                    </h6>
                    <div class="row">
                        @foreach($subscriber->dependents ?? [] as $dependent)
                        <div class="col-md-6 mb-2">
                            <div class="border rounded p-2 bg-light">
                                <div class="fw-bold">{{ $dependent->name }}</div>
                                <small class="text-muted">{{ $dependent->nationality }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Package Features -->
            @if($subscriber->package && $subscriber->package->features)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-star me-2"></i>
                        مميزات الباقة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($subscriber->package->features as $feature)
                        <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check text-success me-2"></i>
                                <span>{{ $feature }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Next Steps -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list-check me-2"></i>
                        الخطوات التالية
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="step-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 14px;">1</div>
                                    <div>
                                        <h6 class="mb-1">احفظ رقم البطاقة</h6>
                                        <p class="text-muted mb-0 small">احتفظ برقم البطاقة في مكان آمن لاستخدامه عند زيارة المراكز الطبية</p>
                                    </div>
                                </div>
                            </div>
                            <div class="step-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 14px;">2</div>
                                    <div>
                                        <h6 class="mb-1">تصفح الشبكة الطبية</h6>
                                        <p class="text-muted mb-0 small">اطلع على المراكز الطبية المشاركة في شبكتنا</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="step-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 14px;">3</div>
                                    <div>
                                        <h6 class="mb-1">احجز موعدك</h6>
                                        <p class="text-muted mb-0 small">تواصل مع المراكز الطبية لحجز مواعيدك والاستفادة من الخصومات</p>
                                    </div>
                                </div>
                            </div>
                            <div class="step-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 14px;">4</div>
                                    <div>
                                        <h6 class="mb-1">تواصل معنا</h6>
                                        <p class="text-muted mb-0 small">لأي استفسارات أو مساعدة، تواصل مع فريق الدعم</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <a href="{{ route('medicalnetwork') }}" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-hospital me-2"></i>
                    تصفح الشبكة الطبية
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-home me-2"></i>
                    العودة للرئيسية
                </a>
            </div>

            <!-- Contact Info -->
            <div class="text-center mt-4 p-3 bg-light rounded">
                <h6 class="text-muted mb-2">هل تحتاج مساعدة؟</h6>
                <p class="mb-0">
                    <i class="fas fa-phone me-2"></i>
                    <span class="fw-bold">920000000</span>
                    <span class="mx-3">|</span>
                    <i class="fas fa-envelope me-2"></i>
                    <span class="fw-bold">support@altakafulalsehi.com</span>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: bounceIn 1s ease-in-out;
}

@keyframes bounceIn {
    0% {
        transform: scale(0.3);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.info-item label {
    font-size: 0.9rem;
}

.step-number {
    flex-shrink: 0;
}
</style>
@endsection
