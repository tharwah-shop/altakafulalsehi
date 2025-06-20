@extends('layouts.frontend')
@section('title', 'جاري معالجة الدفع')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg rounded-4 text-center">
                    <div class="card-body p-5">
                        <!-- Processing Icon -->
                        <div class="position-relative mb-4">
                            <div class="mx-auto d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: linear-gradient(135deg, #00bcd4 0%, #00e676 100%); border-radius: 50%; position: relative; z-index: 2;">
                                <i class="bi bi-credit-card-2-front text-white" style="font-size: 2.5rem;"></i>
                            </div>
                            <div class="position-absolute top-50 start-50 translate-middle" style="width: 100px; height: 100px; border-radius: 50%; background: rgba(0, 188, 212, 0.3); animation: pulse 2s infinite; z-index: 1;"></div>
                        </div>

                        <!-- Processing Title -->
                        <h2 class="fw-bold text-dark mb-3">جاري معالجة طلب الدفع</h2>
                        
                        <!-- Processing Message -->
                        <p class="text-muted mb-4 fs-5">
                            يرجى الانتظار... سيتم توجيهك لبوابة الدفع خلال لحظات
                        </p>

                        <!-- Progress Bar -->
                        <div class="progress mb-4" style="height: 8px;">
                            <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: 0%; animation: progress 3s ease-in-out infinite;"></div>
                        </div>

                        <!-- Processing Steps -->
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 50%;">
                                        <i class="bi bi-check-lg text-white"></i>
                                    </div>
                                    <div class="text-start">
                                        <h6 class="fw-bold mb-1">تم التحقق من البيانات</h6>
                                        <small class="text-muted">تم التأكد من صحة جميع المعلومات المدخلة</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #00bcd4 0%, #00e676 100%); border-radius: 50%;">
                                        <i class="bi bi-arrow-repeat text-white"></i>
                                    </div>
                                    <div class="text-start">
                                        <h6 class="fw-bold mb-1">إنشاء الفاتورة</h6>
                                        <small class="text-muted">جاري إنشاء فاتورة الدفع وتجهيز البيانات</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: #6c757d; border-radius: 50%;">
                                        <i class="bi bi-box-arrow-up-right text-white"></i>
                                    </div>
                                    <div class="text-start">
                                        <h6 class="fw-bold mb-1">التوجه لبوابة الدفع</h6>
                                        <small class="text-muted">سيتم توجيهك لإكمال عملية الدفع</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Note -->
                        <div class="bg-light rounded-3 p-3 mt-4 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-shield-check text-success"></i>
                            <span class="text-muted">عملية آمنة ومشفرة بأعلى معايير الأمان</span>
                        </div>

                        <!-- Timeout Warning -->
                        <div class="alert alert-warning mt-4" id="timeout-warning" style="display: none;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>تأخير غير متوقع:</strong> إذا لم يتم توجيهك خلال 30 ثانية، 
                            <a href="{{ route('subscribe') }}" class="alert-link">انقر هنا للعودة وإعادة المحاولة</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto-redirect script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 Payment processing page loaded');
    
    // Simulate processing steps
    setTimeout(() => {
        const step2 = document.querySelector('.col-md-4:nth-child(2) .fa-spinner');
        if (step2) {
            step2.className = 'bi bi-check-lg text-white';
            step2.parentElement.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
        }
        
        const step3 = document.querySelector('.col-md-4:nth-child(3) .bi-box-arrow-up-right');
        if (step3) {
            step3.className = 'bi bi-arrow-repeat text-white';
            step3.parentElement.style.background = 'linear-gradient(135deg, #00bcd4 0%, #00e676 100%)';
        }
    }, 2000);
    
    // Show timeout warning after 30 seconds
    setTimeout(() => {
        const timeoutWarning = document.getElementById('timeout-warning');
        if (timeoutWarning) {
            timeoutWarning.style.display = 'block';
        }
    }, 30000);
    
    // Auto-redirect to subscribe page after 60 seconds as fallback
    setTimeout(() => {
        console.log('⏰ Timeout reached, redirecting to subscribe page');
        window.location.href = '{{ route("subscribe") }}';
    }, 60000);
});
</script>

<style>
@keyframes pulse {
    0% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(2);
        opacity: 0;
    }
}

@keyframes progress {
    0% { width: 0%; }
    50% { width: 70%; }
    100% { width: 100%; }
}
</style>
@endsection
