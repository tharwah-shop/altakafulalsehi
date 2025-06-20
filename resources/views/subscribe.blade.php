@extends('layouts.frontend')
@section('title', 'الاشتراك في الباقة')
@section('content')
<!-- Progress Steps -->
<div class="container py-4">
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-primary text-white mb-2 mx-auto" style="width: 40px; height: 40px; line-height: 40px; font-size: 1.3rem;">1</div>
                    <div class="small">اختر الباقة</div>
                </div>
                <div class="flex-fill border-top border-3 border-primary mx-2"></div>
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-primary text-white mb-2 mx-auto" style="width: 40px; height: 40px; line-height: 40px; font-size: 1.3rem;">2</div>
                    <div class="small">بيانات المشترك</div>
                </div>
                <div class="flex-fill border-top border-3 border-primary mx-2"></div>
                <div class="text-center flex-fill">
                    <div class="rounded-circle bg-primary text-white mb-2 mx-auto" style="width: 40px; height: 40px; line-height: 40px; font-size: 1.3rem;">3</div>
                    <div class="small">ملخص ودفع</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container pb-5">
    <div class="row g-4 flex-lg-row-reverse">
        <!-- ملخص الباقة -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 90px;">
                <div class="card border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3 text-center"><i class="bi bi-receipt text-primary me-2"></i>ملخص الباقة</h4>
                        <div id="package-summary">
                            @if($selectedPackage)
                                <!-- رأس الباقة -->
                                <div class="text-center mb-3 p-3 rounded" style="background: linear-gradient(135deg, {{ $selectedPackage->color }}15, {{ $selectedPackage->color }}05);">
                                    @if($selectedPackage->icon)
                                        <div class="mb-2" style="color: {{ $selectedPackage->color }};">
                                            <i class="{{ $selectedPackage->icon }} fa-2x"></i>
                                        </div>
                                    @endif
                                    <h5 class="fw-bold mb-1" style="color: {{ $selectedPackage->color }};">{{ $selectedPackage->name }}</h5>
                                    @if($selectedPackage->is_featured)
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-star-fill me-1"></i>باقة مميزة
                                        </span>
                                    @endif
                                </div>

                                <!-- تفاصيل الباقة -->
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="fw-semibold">سعر الباقة:</span>
                                    <span class="fw-bold text-primary">{{ $selectedPackage->formatted_price_with_decimals }}</span>
                                </div>
                                @if($selectedPackage->supportsDependents())
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="fw-semibold">سعر التابع:</span>
                                    <span class="fw-bold text-success">{{ $selectedPackage->formatted_dependent_price_with_decimals }}</span>
                                </div>
                                @endif
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="fw-semibold">مدة الاشتراك:</span>
                                    <span class="fw-bold">{{ $selectedPackage->duration_text }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="fw-semibold">التابعين:</span>
                                    <span class="fw-bold">{{ $selectedPackage->dependents_limit_text }}</span>
                                </div>
                                @if($selectedPackage->discount_percentage > 0)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="fw-semibold">نسبة الخصم:</span>
                                    <span class="fw-bold text-success">{{ $selectedPackage->discount_percentage }}%</span>
                                </div>
                                @endif

                                <!-- ميزات الباقة -->
                                @if($selectedPackage->features && count($selectedPackage->features) > 0)
                                <div class="mt-3">
                                    <h6 class="fw-bold mb-2">
                                        <i class="bi bi-check-circle text-success me-1"></i>
                                        مميزات الباقة:
                                    </h6>
                                    <div class="package-features">
                                        @foreach($selectedPackage->features as $feature)
                                            <div class="d-flex align-items-start mb-1">
                                                <i class="bi bi-check text-success me-2 mt-1"></i>
                                                <span class="small">{{ $feature }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-box-open fa-3x mb-3 d-block"></i>
                                    <h6>يرجى اختيار باقة</h6>
                                    <p class="small mb-0">اختر الباقة المناسبة لك لعرض الملخص والتفاصيل</p>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-semibold">عدد التابعين:</span>
                            <span class="fw-bold" id="dependents-count">0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="fw-semibold">المجموع الفرعي:</span>
                            <span class="fw-bold" id="subtotal">0 ريال</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 mx-n3 mt-3 bg-primary bg-opacity-10 rounded-bottom">
                            <span class="fw-bold">الإجمالي النهائي:</span>
                            <span class="fw-bold text-primary" id="total">0 ريال</span>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow text-center bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2"><i class="bi bi-shield-lock text-success me-1"></i>دفع آمن ومشفر</h6>
                        <div class="d-flex justify-content-center gap-3 mb-2">
                            <i class="bi bi-credit-card-2-front text-primary fs-4"></i>
                            <i class="bi bi-person-badge text-info fs-4"></i>
                            <i class="bi bi-headset text-warning fs-4"></i>
                        </div>
                        <div class="small text-muted">حماية بياناتك، دعم 24/7، ومعايير PCI DSS</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- خطوات الاشتراك -->
        <div class="col-lg-8">
            <form action="{{ route('subscription.store') }}" method="POST" class="needs-validation" novalidate id="subscription-form">
                @csrf

                <!-- عرض رسالة النجاح من طلب البطاقة -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- عرض تنبيه البيانات المعبأة مسبقاً -->
                @if(isset($prefilledData) && $prefilledData)
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>تم تعبئة بياناتك تلقائياً!</strong>
                    لقد قمنا بتعبئة البيانات من طلب البطاقة السابق. يمكنك تعديلها إذا لزم الأمر.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- عرض الأخطاء -->
                @if($errors->any())
                <div class="alert alert-danger">
                    <h6>يرجى تصحيح الأخطاء التالية:</h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- الخطوة 1: اختيار الباقة -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="bi bi-gift fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1">اختر الباقة المناسبة</h4>
                                <div class="text-muted">اختر من الباقات المتاحة أدناه</div>
                            </div>
                        </div>

                        <div class="row g-4">
                            @if(isset($packages) && $packages->count() > 0)
                                @foreach($packages as $package)
                                <div class="col-lg-6">
                                    <div class="card border package-card h-100 {{ $selectedPackage && $selectedPackage->id == $package->id ? 'border-primary bg-light' : '' }}"
                                         style="cursor: pointer; border-color: {{ $package->color }}20 !important;"
                                         data-package-id="{{ $package->id }}"
                                         data-package-price="{{ $package->price }}"
                                         data-dependent-price="{{ $package->dependent_price ?? 0 }}"
                                         data-max-dependents="{{ $package->max_dependents }}"
                                         data-package-name="{{ $package->name }}"
                                         data-duration-text="{{ $package->duration_text }}">

                                        @if($package->is_featured)
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-star-fill me-1"></i>مميزة
                                                </span>
                                            </div>
                                        @endif

                                        <div class="card-header text-center border-0" style="background: linear-gradient(135deg, {{ $package->color }}15, {{ $package->color }}05);">
                                            @if($package->icon)
                                                <div class="mb-2" style="color: {{ $package->color }};">
                                                    <i class="{{ $package->icon }} fa-2x"></i>
                                                </div>
                                            @endif
                                            <h5 class="fw-bold mb-1" style="color: {{ $package->color }};">{{ $package->name }}</h5>
                                            @if($package->name_en)
                                                <small class="text-muted">{{ $package->name_en }}</small>
                                            @endif
                                            <div class="form-check position-absolute top-0 start-0 m-3">
                                                <input class="form-check-input" type="radio" name="package_id"
                                                       value="{{ $package->id }}" id="package{{ $package->id }}"
                                                       {{ $selectedPackage && $selectedPackage->id == $package->id ? 'checked' : '' }}
                                                       {{ old('package_id') == $package->id ? 'checked' : '' }}>
                                            </div>
                                        </div>

                                        <div class="card-body p-4">
                                            @if($package->description)
                                                <p class="text-muted mb-3">{{ Str::limit($package->description, 100) }}</p>
                                            @endif

                                            <!-- الأسعار -->
                                            <div class="row g-3 mb-3">
                                                <div class="col-6">
                                                    <div class="text-center p-3 rounded" style="background-color: {{ $package->color }}10;">
                                                        <div class="h4 fw-bold mb-1" style="color: {{ $package->color }};">
                                                            {{ $package->formatted_price }}
                                                        </div>
                                                        <div class="small text-muted">سعر الباقة</div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center p-3 rounded bg-light">
                                                        @if($package->supportsDependents())
                                                            <div class="h5 fw-bold mb-1 text-success">
                                                                {{ $package->formatted_dependent_price }}
                                                            </div>
                                                            <div class="small text-muted">سعر التابع</div>
                                                        @else
                                                            <div class="h5 fw-bold mb-1 text-muted">-</div>
                                                            <div class="small text-muted">لا يدعم تابعين</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- معلومات الباقة -->
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-calendar-event text-info me-2"></i>
                                                        <span class="small">{{ $package->duration_text }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-people text-primary me-2"></i>
                                                        <span class="small">{{ $package->dependents_limit_text }}</span>
                                                    </div>
                                                </div>
                                                @if($package->discount_percentage > 0)
                                                <div class="col-12">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-percent text-success me-2"></i>
                                                        <span class="small text-success">خصم حتى {{ $package->discount_percentage }}%</span>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>

                                            <!-- مميزات الباقة -->
                                            @if($package->features && count($package->features) > 0)
                                                <div class="mb-3">
                                                    <h6 class="fw-bold mb-2">
                                                        <i class="bi bi-check-circle text-success me-1"></i>
                                                        المميزات:
                                                    </h6>
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach(array_slice($package->features, 0, 3) as $feature)
                                                            <li class="small mb-1">
                                                                <i class="bi bi-check text-success me-1"></i>
                                                                {{ $feature }}
                                                            </li>
                                                        @endforeach
                                                        @if(count($package->features) > 3)
                                                            <li class="small text-muted">
                                                                <i class="bi bi-plus me-1"></i>
                                                                و {{ count($package->features) - 3 }} مميزات أخرى
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="card-footer text-center border-0 bg-transparent">
                                            <button type="button" class="btn btn-outline-primary btn-sm select-package-btn"
                                                    style="border-color: {{ $package->color }}; color: {{ $package->color }};">
                                                <i class="bi bi-check-circle me-1"></i>
                                                اختيار هذه الباقة
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <div class="mb-3">
                                            <i class="bi bi-box-open fa-3x text-muted"></i>
                                        </div>
                                        <h5 class="text-muted">لا توجد باقات متاحة حالياً</h5>
                                        <p class="text-muted">نعمل على إضافة باقات جديدة قريباً</p>
                                        <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                                            <i class="bi bi-envelope me-2"></i>
                                            تواصل معنا
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- الخطوة 2: بيانات المشترك -->
                <div class="card border-0 shadow mb-4" id="personal-data-section">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="bi bi-person-lines-fill fs-3"></i>
                            </div>
                            <h4 class="fw-bold mb-0">بيانات المشترك</h4>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $prefilledData['name'] ?? '') }}" required>
                                    <label for="name"><i class="bi bi-person me-1"></i>الاسم الكامل</label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $prefilledData['phone'] ?? '') }}" required>
                                    <label for="phone"><i class="bi bi-telephone me-1"></i>رقم الجوال</label>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $prefilledData['email'] ?? '') }}">
                                    <label for="email"><i class="bi bi-envelope me-1"></i>البريد الإلكتروني</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('city') is-invalid @enderror"
                                            id="city" name="city" required>
                                        <option value="">اختر المدينة</option>
                                        @if(isset($cities) && $cities->count() > 0)
                                            @foreach($cities as $city)
                                                <option value="{{ $city['name'] }}"
                                                        {{ old('city', $prefilledData['city'] ?? '') == $city['name'] ? 'selected' : '' }}>
                                                    {{ $city['name'] }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>لا توجد مدن متاحة</option>
                                        @endif
                                    </select>
                                    <label for="city"><i class="bi bi-geo-alt me-1"></i>المدينة</label>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                                           id="id_number" name="id_number" value="{{ old('id_number') }}" required>
                                    <label for="id_number"><i class="bi bi-credit-card-2-front me-1"></i>رقم الهوية</label>
                                    @error('id_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('nationality') is-invalid @enderror"
                                            id="nationality" name="nationality" required>
                                        <option value="">اختر الجنسية</option>
                                        @foreach(config('nationalities', []) as $nationality)
                                            <option value="{{ $nationality['name'] }}"
                                                    {{ old('nationality') == $nationality['name'] ? 'selected' : '' }}>
                                                {{ $nationality['emoji'] }} {{ $nationality['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="nationality"><i class="bi bi-flag me-1"></i>الجنسية</label>
                                    @error('nationality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الخطوة 3: التابعون وبوابة الدفع -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="bi bi-people fs-3"></i>
                            </div>
                            <h4 class="fw-bold mb-0">التابعون وبوابة الدفع</h4>
                        </div>

                        <!-- قسم التابعين -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">إضافة التابعين (اختياري)</h6>
                            <div id="dependents-container">
                                <!-- سيتم إضافة التابعين هنا ديناميكياً -->
                            </div>
                            <button type="button" class="btn btn-outline-primary" id="add-dependent-btn">
                                <i class="bi bi-plus-circle me-2"></i>
                                إضافة تابع
                            </button>
                        </div>

                        <!-- بوابات الدفع -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">اختر طريقة الدفع</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card border h-100 p-3 text-center payment-method-card" data-method="myfatoorah" style="cursor: pointer;">
                                        <div class="form-check position-absolute top-0 start-0 m-2">
                                            <input class="form-check-input" type="radio" name="payment_method" value="myfatoorah" id="payment_myfatoorah" checked>
                                        </div>
                                        <div class="mb-3">
                                            <i class="bi bi-credit-card-2-front text-primary fa-2x"></i>
                                        </div>
                                        <div class="fw-bold mb-2">MyFatoorah</div>
                                        <div class="text-muted mb-2">دفع آمن ومضمون</div>
                                        <small class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i>فيزا، ماستركارد، مدى</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border h-100 p-3 text-center payment-method-card" data-method="tabby" style="cursor: pointer;">
                                        <div class="form-check position-absolute top-0 start-0 m-2">
                                            <input class="form-check-input" type="radio" name="payment_method" value="tabby" id="payment_tabby">
                                        </div>
                                        <div class="mb-3">
                                            <i class="bi bi-calendar-event text-warning fa-2x"></i>
                                        </div>
                                        <div class="fw-bold mb-2">تابي</div>
                                        <div class="text-muted mb-2">ادفع بالتقسيط</div>
                                        <small class="text-muted"><i class="bi bi-calendar-event text-warning me-1"></i>قسط على 4 دفعات</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border h-100 p-3 text-center payment-method-card" data-method="bank_transfer" style="cursor: pointer;">
                                        <div class="form-check position-absolute top-0 start-0 m-2">
                                            <input class="form-check-input" type="radio" name="payment_method" value="bank_transfer" id="payment_bank_transfer">
                                        </div>
                                        <div class="mb-3">
                                            <i class="bi bi-bank2 text-info fa-2x"></i>
                                        </div>
                                        <div class="fw-bold mb-2">تحويل بنكي</div>
                                        <div class="text-muted mb-2">تحويل مباشر للبنك</div>
                                        <small class="text-muted"><i class="bi bi-clock text-info me-1"></i>1-2 يوم عمل</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- زر الدفع -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success w-100 py-3" id="submit-btn">
                                <i class="bi bi-credit-card-2-front me-2"></i>
                                <span id="submit-text">متابعة الدفع الإلكتروني</span>
                                <i class="bi bi-arrow-left ms-2"></i>
                            </button>
                            <p class="text-muted mt-3">
                                <i class="bi bi-shield-lock me-1"></i>
                                دفع آمن ومشفر بأعلى معايير الأمان
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // متغيرات عامة
    const form = document.querySelector('#subscription-form');
    const submitBtn = document.querySelector('#submit-btn');
    const submitText = document.querySelector('#submit-text');
    const packageCards = document.querySelectorAll('.package-card');
    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
    const dependentsContainer = document.querySelector('#dependents-container');
    const addDependentBtn = document.querySelector('#add-dependent-btn');

    let selectedPackage = null;
    let selectedPaymentMethod = 'myfatoorah'; // القيمة الافتراضية
    let dependentsCount = 0;

    // تهيئة الصفحة
    initializePage();

    function initializePage() {
        // تفعيل اختيار الباقات
        packageCards.forEach(card => {
            card.addEventListener('click', function() {
                selectPackage(this);
            });
        });

        // تفعيل اختيار طرق الدفع
        paymentMethodCards.forEach(card => {
            card.addEventListener('click', function() {
                selectPaymentMethod(this);
            });
        });

        // تفعيل إضافة التابعين
        if (addDependentBtn) {
            addDependentBtn.addEventListener('click', addDependent);
        }

        // تفعيل التحقق من النموذج
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }

        // تحديث نص زر الإرسال حسب طريقة الدفع
        updateSubmitButtonText();

        // تهيئة الباقة المختارة مسبقاً
        const preSelectedPackage = document.querySelector('.package-card input[type="radio"]:checked');
        if (preSelectedPackage) {
            selectedPackage = preSelectedPackage.value;
            updatePriceCalculation();
        }

        // تهيئة طريقة الدفع المختارة مسبقاً
        const preSelectedPayment = document.querySelector('.payment-method-card input[type="radio"]:checked');
        if (preSelectedPayment) {
            selectedPaymentMethod = preSelectedPayment.value;
            updateSubmitButtonText();
        }
    }

    function selectPackage(packageCard) {
        // إزالة التحديد من جميع الباقات
        packageCards.forEach(card => {
            card.classList.remove('selected', 'border-primary', 'bg-light');
            const radio = card.querySelector('input[type="radio"]');
            if (radio) radio.checked = false;
        });

        // تحديد الباقة المختارة
        packageCard.classList.add('selected', 'border-primary', 'bg-light');
        const radio = packageCard.querySelector('input[type="radio"]');
        if (radio) {
            radio.checked = true;
            selectedPackage = radio.value;
        }

        // تحديث حساب السعر
        updatePriceCalculation();

        // إظهار قسم البيانات الشخصية
        const personalDataSection = document.querySelector('#personal-data-section');
        if (personalDataSection) {
            personalDataSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function selectPaymentMethod(methodCard) {
        // إزالة التحديد من جميع طرق الدفع
        paymentMethodCards.forEach(card => {
            card.classList.remove('selected', 'border-primary', 'bg-light');
            const radio = card.querySelector('input[type="radio"]');
            if (radio) radio.checked = false;
        });

        // تحديد طريقة الدفع المختارة
        methodCard.classList.add('selected', 'border-primary', 'bg-light');
        const radio = methodCard.querySelector('input[type="radio"]');
        if (radio) {
            radio.checked = true;
            selectedPaymentMethod = radio.value;
        }

        // تحديث نص زر الإرسال
        updateSubmitButtonText();
    }

    function updateSubmitButtonText() {
        if (!submitText) return;

        switch(selectedPaymentMethod) {
            case 'bank_transfer':
                submitText.innerHTML = '<i class="bi bi-bank2 me-2"></i>متابعة للتحويل البنكي';
                break;
            case 'tabby':
                submitText.innerHTML = '<i class="bi bi-calendar-event me-2"></i>الدفع بالتقسيط مع تابي';
                break;
            case 'myfatoorah':
            default:
                submitText.innerHTML = '<i class="bi bi-credit-card-2-front me-2"></i>متابعة الدفع الإلكتروني';
                break;
        }
    }

    function addDependent() {
        dependentsCount++;

        const dependentHtml = `
            <div class="dependent-item border rounded p-3 mb-3" data-dependent="${dependentsCount}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-person-plus me-2"></i>
                        التابع رقم ${dependentsCount}
                    </h6>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-dependent" onclick="removeDependent(${dependentsCount})">
                        <i class="bi bi-trash me-1"></i>
                        حذف
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">اسم التابع <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="dependents[${dependentsCount}][name]"
                               placeholder="الاسم الكامل للتابع" required>
                        <div class="invalid-feedback">يرجى إدخال اسم التابع</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الجنسية <span class="text-danger">*</span></label>
                        <select class="form-select" name="dependents[${dependentsCount}][nationality]" required>
                            <option value="">اختر الجنسية</option>
                            <option value="🇸🇦 سعودي">🇸🇦 سعودي</option>
                            <option value="🇪🇬 مصري">🇪🇬 مصري</option>
                            <option value="🇸🇾 سوري">🇸🇾 سوري</option>
                            <option value="🇱🇧 لبناني">🇱🇧 لبناني</option>
                            <option value="🇯🇴 أردني">🇯🇴 أردني</option>
                            <option value="🇵🇸 فلسطيني">🇵🇸 فلسطيني</option>
                            <option value="🇮🇶 عراقي">🇮🇶 عراقي</option>
                            <option value="🇾🇪 يمني">🇾🇪 يمني</option>
                            <option value="🇸🇩 سوداني">🇸🇩 سوداني</option>
                            <option value="🇲🇦 مغربي">🇲🇦 مغربي</option>
                            <option value="🇹🇳 تونسي">🇹🇳 تونسي</option>
                            <option value="🇩🇿 جزائري">🇩🇿 جزائري</option>
                            <option value="🇱🇾 ليبي">🇱🇾 ليبي</option>
                            <option value="🇰🇼 كويتي">🇰🇼 كويتي</option>
                            <option value="🇦🇪 إماراتي">🇦🇪 إماراتي</option>
                            <option value="🇶🇦 قطري">🇶🇦 قطري</option>
                            <option value="🇧🇭 بحريني">🇧🇭 بحريني</option>
                            <option value="🇴🇲 عماني">🇴🇲 عماني</option>
                            <option value="🇵🇰 باكستاني">🇵🇰 باكستاني</option>
                            <option value="🇮🇳 هندي">🇮🇳 هندي</option>
                            <option value="🇧🇩 بنغلاديشي">🇧🇩 بنغلاديشي</option>
                            <option value="🇱🇰 سريلانكي">🇱🇰 سريلانكي</option>
                            <option value="🇳🇵 نيبالي">🇳🇵 نيبالي</option>
                            <option value="🇵🇭 فلبيني">🇵🇭 فلبيني</option>
                            <option value="🇮🇩 إندونيسي">🇮🇩 إندونيسي</option>
                            <option value="🇹🇭 تايلاندي">🇹🇭 تايلاندي</option>
                            <option value="🇪🇹 إثيوبي">🇪🇹 إثيوبي</option>
                            <option value="🇪🇷 إريتري">🇪🇷 إريتري</option>
                            <option value="🇸🇴 صومالي">🇸🇴 صومالي</option>
                            <option value="🇹🇷 تركي">🇹🇷 تركي</option>
                            <option value="🇮🇷 إيراني">🇮🇷 إيراني</option>
                            <option value="🇦🇫 أفغاني">🇦🇫 أفغاني</option>
                            <option value="🇺🇸 أمريكي">🇺🇸 أمريكي</option>
                            <option value="🇬🇧 بريطاني">🇬🇧 بريطاني</option>
                            <option value="🇫🇷 فرنسي">🇫🇷 فرنسي</option>
                            <option value="🇩🇪 ألماني">🇩🇪 ألماني</option>
                            <option value="🇨🇦 كندي">🇨🇦 كندي</option>
                            <option value="🇦🇺 أسترالي">🇦🇺 أسترالي</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                        <div class="invalid-feedback">يرجى اختيار جنسية التابع</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">رقم الهوية/الإقامة</label>
                        <input type="text" class="form-control" name="dependents[${dependentsCount}][id_number]"
                               placeholder="رقم الهوية أو الإقامة (اختياري)">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">العلاقة</label>
                        <select class="form-select" name="dependents[${dependentsCount}][relationship]">
                            <option value="">اختر العلاقة</option>
                            <option value="زوج/زوجة">زوج/زوجة</option>
                            <option value="ابن/ابنة">ابن/ابنة</option>
                            <option value="والد/والدة">والد/والدة</option>
                            <option value="أخ/أخت">أخ/أخت</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                    </div>
                </div>
            </div>
        `;

        if (dependentsContainer) {
            dependentsContainer.insertAdjacentHTML('beforeend', dependentHtml);
            updatePriceCalculation();
        }
    }

    // دالة عامة لحذف التابع
    window.removeDependent = function(dependentNumber) {
        const dependentItem = document.querySelector(`[data-dependent="${dependentNumber}"]`);
        if (dependentItem) {
            dependentItem.remove();
            updatePriceCalculation();
        }
    };

    function updatePriceCalculation() {
        const activeDependents = document.querySelectorAll('.dependent-item').length;
        const dependentsCountElement = document.querySelector('#dependents-count');
        const subtotalElement = document.querySelector('#subtotal');
        const totalElement = document.querySelector('#total');

        if (dependentsCountElement) {
            dependentsCountElement.textContent = activeDependents;
        }

        if (selectedPackage) {
            const selectedCard = document.querySelector(`[data-package-id="${selectedPackage}"]`);
            if (selectedCard) {
                const packagePrice = parseFloat(selectedCard.dataset.packagePrice) || 0;
                const dependentPrice = parseFloat(selectedCard.dataset.dependentPrice) || 0;
                const maxDependents = parseInt(selectedCard.dataset.maxDependents) || 0;

                // التحقق من عدم تجاوز الحد الأقصى للتابعين
                if (maxDependents > 0 && activeDependents > maxDependents) {
                    showAlert(`الحد الأقصى للتابعين في هذه الباقة هو ${maxDependents}`, 'warning');
                    return;
                }

                const subtotal = packagePrice + (dependentPrice * activeDependents);

                if (subtotalElement) {
                    subtotalElement.textContent = `${subtotal.toFixed(2)} ريال`;
                }

                if (totalElement) {
                    totalElement.textContent = `${subtotal.toFixed(2)} ريال`;
                }
            }
        } else {
            if (subtotalElement) subtotalElement.textContent = '0 ريال';
            if (totalElement) totalElement.textContent = '0 ريال';
        }
    }

    function validateForm() {
        let isValid = true;
        const errors = [];

        // التحقق من اختيار الباقة
        if (!selectedPackage) {
            errors.push('يرجى اختيار باقة الاشتراك');
            isValid = false;
        }

        // التحقق من البيانات الشخصية
        const requiredFields = ['name', 'phone', 'city', 'nationality', 'id_number'];
        requiredFields.forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field && !field.value.trim()) {
                field.classList.add('is-invalid');
                errors.push(`يرجى إدخال ${getFieldLabel(fieldName)}`);
                isValid = false;
            } else if (field) {
                field.classList.remove('is-invalid');
            }
        });

        // التحقق من صحة البريد الإلكتروني
        const emailField = form.querySelector('[name="email"]');
        if (emailField && emailField.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField.value)) {
                emailField.classList.add('is-invalid');
                errors.push('يرجى إدخال بريد إلكتروني صحيح');
                isValid = false;
            }
        }

        // التحقق من صحة رقم الجوال
        const phoneField = form.querySelector('[name="phone"]');
        if (phoneField && phoneField.value) {
            const phoneRegex = /^(05|5)[0-9]{8}$/;
            if (!phoneRegex.test(phoneField.value.replace(/\s+/g, ''))) {
                phoneField.classList.add('is-invalid');
                errors.push('يرجى إدخال رقم جوال صحيح (يبدأ بـ 05 ويتكون من 10 أرقام)');
                isValid = false;
            }
        }

        // التحقق من بيانات التابعين
        const dependentItems = document.querySelectorAll('.dependent-item');
        dependentItems.forEach((item, index) => {
            const nameField = item.querySelector('[name*="[name]"]');
            const nationalityField = item.querySelector('[name*="[nationality]"]');

            if (nameField && !nameField.value.trim()) {
                nameField.classList.add('is-invalid');
                errors.push(`يرجى إدخال اسم التابع رقم ${index + 1}`);
                isValid = false;
            }

            if (nationalityField && !nationalityField.value) {
                nationalityField.classList.add('is-invalid');
                errors.push(`يرجى اختيار جنسية التابع رقم ${index + 1}`);
                isValid = false;
            }
        });

        // التحقق من اختيار طريقة الدفع
        if (!selectedPaymentMethod) {
            errors.push('يرجى اختيار طريقة الدفع');
            isValid = false;
        }

        // عرض الأخطاء
        if (errors.length > 0) {
            showAlert(errors.join('<br>'), 'danger');
        }

        return isValid;
    }

    function getFieldLabel(fieldName) {
        const labels = {
            'name': 'الاسم الكامل',
            'phone': 'رقم الجوال',
            'email': 'البريد الإلكتروني',
            'city': 'المدينة',
            'nationality': 'الجنسية',
            'id_number': 'رقم الهوية'
        };
        return labels[fieldName] || fieldName;
    }

    function handleFormSubmit(e) {
        e.preventDefault();

        // التحقق من صحة النموذج
        if (!validateForm()) {
            return false;
        }

        // تحديث action النموذج حسب طريقة الدفع
        if (selectedPaymentMethod === 'bank_transfer') {
            form.action = '{{ route("subscription.bank-transfer") }}';
        } else {
            form.action = '{{ route("subscription.store") }}';
        }

        // إظهار حالة التحميل
        showLoadingState();

        // إرسال النموذج
        form.submit();
    }

    function showLoadingState() {
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>جاري المعالجة...';
        }
    }

    function showAlert(message, type = 'info') {
        // إزالة التنبيهات الموجودة
        const existingAlerts = document.querySelectorAll('.temp-alert');
        existingAlerts.forEach(alert => alert.remove());

        // إنشاء تنبيه جديد
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show temp-alert position-fixed`;
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        alertDiv.style.minWidth = '300px';
        alertDiv.style.maxWidth = '500px';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(alertDiv);

        // إزالة تلقائية بعد 5 ثوان
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // تفعيل التحقق المباشر من الحقول
    if (form) {
        const formInputs = form.querySelectorAll('input, select, textarea');
        formInputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') && this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    }
});
</script>
@endpush

