@extends('layouts.frontend')
@section('title', 'طلب بطاقة التكافل الصحية')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">احصل على بطاقة التكافل الصحية</h1>
        <p class="lead mb-5">استفد من خصومات تصل إلى 80% في مئات المراكز الطبية المعتمدة في جميع أنحاء المملكة</p>
        <div class="row g-4 mb-4 justify-content-center">
            <div class="col-md-4">
                <div class="card bg-light text-primary mb-3">
                    <div class="card-body">
                        <span class="display-6 fw-bold">1.6M+</span>
                        <div>مستفيد</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light text-primary mb-3">
                    <div class="card-body">
                        <span class="display-6 fw-bold">4500+</span>
                        <div>مركز طبي</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light text-primary mb-3">
                    <div class="card-body">
                        <span class="display-6 fw-bold">80%</span>
                        <div>خصم يصل إلى</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Form Card (Display Only) -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h2 class="h3 fw-bold mb-2">طلب البطاقة</h2>
                        <p class="mb-0">املأ البيانات التالية للحصول على بطاقتك فوراً</p>
                    </div>
                    <div class="card-body p-5">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>يرجى تصحيح الأخطاء التالية:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('card.request.store') }}" method="POST" id="cardRequestForm">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="أدخل اسمك الكامل" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}"
                                       placeholder="example@email.com">
                                <div class="form-text">سيتم إرسال البطاقة إلى بريدك الإلكتروني</div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone') }}"
                                       placeholder="05xxxxxxxx" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="city" class="form-label">المدينة <span class="text-danger">*</span></label>
                                <select class="form-select @error('city') is-invalid @enderror"
                                        id="city" name="city" required>
                                    <option value="">اختر المدينة</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city['name'] }}"
                                                {{ old('city') == $city['name'] ? 'selected' : '' }}>
                                            {{ $city['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror"
                                       id="terms" name="terms" value="1" required
                                       {{ old('terms') ? 'checked' : '' }}>
                                <label class="form-check-label" for="terms">
                                    أوافق على <a href="#" class="text-decoration-none text-primary">الشروط والأحكام</a> الخاصة بالبطاقة
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                <span class="btn-text">إرسال الطلب</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Info Panel -->
            <div class="col-lg-4">
                <div class="card h-100 shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="h4 fw-bold mb-2">مزايا البطاقة</h3>
                        <p class="mb-0">استفد من مجموعة واسعة من المزايا الطبية</p>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fas fa-percentage text-primary me-2"></i> خصومات تصل إلى 80%</li>
                            <li class="list-group-item"><i class="fas fa-calendar-check text-primary me-2"></i> صلاحية لمدة عام كامل</li>
                            <li class="list-group-item"><i class="fas fa-users text-primary me-2"></i> تغطية لجميع أفراد الأسرة</li>
                            <li class="list-group-item"><i class="fas fa-mobile-alt text-primary me-2"></i> بطاقة رقمية فورية</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="h2 fw-bold mb-3 text-primary">ماذا قال عملاؤنا؟</h2>
            <p class="text-muted">تجارب حقيقية من عملائنا الكرام الذين استفادوا من بطاقة التكافل الصحية</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow mb-3">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="text-warning">★★★★★</span>
                        </div>
                        <blockquote class="blockquote">
                            <p class="mb-0">"بطاقة التكافل الصحي غيرت حياتي! وفرت لي أكثر من 60% من تكاليف العلاج. خدمة ممتازة وسرعة في الاستجابة."</p>
                        </blockquote>
                        <footer class="blockquote-footer mt-3">أحمد الشهري <cite title="Source Title">مهندس - الرياض</cite></footer>
                    </div>
                </div>
                <div class="card border-0 shadow mb-3">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="text-warning">★★★★★</span>
                        </div>
                        <blockquote class="blockquote">
                            <p class="mb-0">"استخدمت البطاقة لعلاج أطفالي وحصلت على خصم 70%. الشبكة الطبية واسعة والخدمة احترافية جداً."</p>
                        </blockquote>
                        <footer class="blockquote-footer mt-3">فاطمة العتيبي <cite title="Source Title">أم لثلاثة أطفال - جدة</cite></footer>
                    </div>
                </div>
                <div class="card border-0 shadow mb-3">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="text-warning">★★★★★</span>
                        </div>
                        <blockquote class="blockquote">
                            <p class="mb-0">"كطبيب أنصح بهذه البطاقة. الخصومات حقيقية والمراكز المشاركة ذات جودة عالية. استثمار ممتاز للصحة."</p>
                        </blockquote>
                        <footer class="blockquote-footer mt-3">د. خالد المطيري <cite title="Source Title">طبيب أسنان - الدمام</cite></footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section with Timer (Static) -->
<section class="py-5 bg-warning text-white text-center">
    <div class="container">
        <h2 class="h2 fw-bold mb-3">عرض خاص محدود الوقت!</h2>
        <p class="lead mb-4">احصل على خصم 25% عند التسجيل خلال:</p>
        <div class="display-4 mb-4">15:00</div>
        <p class="mb-0">
            <i class="fas fa-fire me-2"></i>
            لا تفوت هذه الفرصة الذهبية!
        </p>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="h2 fw-bold mb-3 text-primary">الأسئلة الشائعة</h2>
            <p class="text-muted">إجابات على أهم الأسئلة حول بطاقة التكافل الصحية</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow mb-3">
                    <div class="card-body d-flex align-items-start">
                        <i class="fas fa-users fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="fw-bold mb-2">هل البطاقة تشمل جميع أفراد الأسرة؟</h5>
                            <p class="text-muted mb-0">نعم، يمكنك إضافة جميع أفراد الأسرة والاستفادة من الخصومات المميزة لجميع الأعضاء.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow mb-3">
                    <div class="card-body d-flex align-items-start">
                        <i class="fas fa-clock fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="fw-bold mb-2">متى أستلم البطاقة؟</h5>
                            <p class="text-muted mb-0">خلال دقائق من إتمام الطلب والدفع الإلكتروني، ستصلك البطاقة الرقمية على هاتفك وبريدك الإلكتروني.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow mb-3">
                    <div class="card-body d-flex align-items-start">
                        <i class="fas fa-map-marker-alt fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="fw-bold mb-2">هل البطاقة صالحة في جميع المدن؟</h5>
                            <p class="text-muted mb-0">نعم، البطاقة صالحة في شبكة واسعة من المراكز الطبية المعتمدة في جميع أنحاء المملكة العربية السعودية.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow mb-3">
                    <div class="card-body d-flex align-items-start">
                        <i class="fas fa-percentage fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="fw-bold mb-2">ما هي نسبة الخصومات المتاحة؟</h5>
                            <p class="text-muted mb-0">تتراوح الخصومات من 20% إلى 80% حسب نوع الخدمة والمركز الطبي، مع ضمان الحصول على أفضل الأسعار.</p>
                        </div>
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
    const form = document.getElementById('cardRequestForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');

    // تحسين تجربة الإرسال
    form.addEventListener('submit', function(e) {
        // إظهار حالة التحميل
        submitBtn.disabled = true;
        btnText.textContent = 'جاري الإرسال...';
        spinner.classList.remove('d-none');
    });

    // تحسين حقل رقم الهاتف
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // إزالة كل شيء عدا الأرقام

        // التأكد من أن الرقم يبدأ بـ 05
        if (value.length > 0 && !value.startsWith('05')) {
            if (value.startsWith('5')) {
                value = '0' + value;
            } else if (!value.startsWith('0')) {
                value = '05' + value;
            }
        }

        // تحديد الحد الأقصى للطول
        if (value.length > 10) {
            value = value.substring(0, 10);
        }

        e.target.value = value;
    });

    // تحسين حقل الاسم
    const nameInput = document.getElementById('name');
    nameInput.addEventListener('input', function(e) {
        // السماح بالأحرف العربية والإنجليزية والمسافات فقط
        e.target.value = e.target.value.replace(/[^a-zA-Zأ-ي\s]/g, '');
    });

    // إضافة تأثيرات بصرية للحقول
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});
</script>

<style>
.focused {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.btn:disabled {
    opacity: 0.7;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

.alert {
    border: none;
    border-radius: 10px;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}
</style>
@endpush