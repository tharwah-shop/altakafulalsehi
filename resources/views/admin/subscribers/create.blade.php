@extends('layouts.admin')

@section('title', 'إضافة مشترك جديد')
@section('content-header', 'إضافة مشترك جديد')
@section('content-subtitle', 'أدخل معلومات المشترك الجديد')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!-- Page title -->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">إضافة مشترك جديد</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="/admin" class="text-muted text-hover-primary">لوحة التحكم</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('admin.subscribers.index') }}" class="text-muted text-hover-primary">المشتركين</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">إضافة مشترك جديد</li>
                </ul>
            </div>
            <!-- Actions -->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.subscribers.index') }}" class="btn btn-sm fw-bold btn-secondary">العودة للقائمة</a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

<div class="row g-5 g-xl-10">
    <!-- Main Form -->
    <div class="col-xl-8">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">معلومات المشترك</span>
                    <span class="text-gray-400 mt-1 fw-semibold fs-6">أدخل البيانات الأساسية للمشترك</span>
                </h3>
            </div>
            <div class="card-body pt-6">
                <form id="subscriberForm" action="{{ route('admin.subscribers.store') }}" method="POST" class="form">
                    @csrf
                    <input type="hidden" id="generated_card_number" name="card_number" value="{{ old('card_number') }}">
                    <input type="hidden" name="potential_customer_id" value="{{ request('potential_customer_id') }}">
                    <input type="hidden" id="generated_card_number" name="generated_card_number" value="">

                    <!-- البيانات الأساسية -->
                    <div class="row g-9 mb-8">
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40px me-3">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-user fs-2 text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h3 class="text-gray-800 fw-bold mb-1">البيانات الأساسية</h3>
                                    <span class="text-gray-400 fw-semibold fs-6">معلومات المشترك الشخصية</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">الاسم الكامل</label>
                                <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', request('name')) }}" placeholder="أدخل الاسم الكامل" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">رقم الجوال</label>
                                <input type="text" id="phone" name="phone" class="form-control form-control-solid @error('phone') is-invalid @enderror" value="{{ old('phone', request('phone')) }}" placeholder="05xxxxxxxx" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">البريد الإلكتروني</label>
                                <input type="email" class="form-control form-control-solid @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', request('email')) }}" placeholder="example@domain.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">الجنسية</label>
                                <select class="form-select form-select-solid @error('nationality') is-invalid @enderror" id="nationality" name="nationality" data-control="select2" data-placeholder="اختر الجنسية" required>
                                    <option value="">اختر الجنسية</option>
                                    @foreach(config('nationalities', []) as $nat)
                                        <option value="{{ $nat['name'] }}" {{ old('nationality') == $nat['name'] ? 'selected' : '' }}>
                                            {{ $nat['emoji'] }} {{ $nat['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">رقم الهوية/الإقامة</label>
                                <input type="text" id="id_number" name="id_number" class="form-control form-control-solid @error('id_number') is-invalid @enderror" value="{{ old('id_number', request('id_number')) }}" placeholder="1xxxxxxxxx" required>
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">المدينة</label>
                                <select class="form-select form-select-solid @error('city') is-invalid @enderror" id="city" name="city" data-control="select2" data-placeholder="اختر المدينة" required>
                                    <option value="">اختر المدينة</option>
                                    @if(isset($cities) && $cities->count() > 0)
                                        @foreach($cities as $city)
                                            <option value="{{ $city['name'] }}" {{ old('city', request('city')) == $city['name'] ? 'selected' : '' }}>{{ $city['name'] }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>لا توجد مدن متاحة</option>
                                    @endif
                                </select>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Card Number Display -->
                        <div class="col-12" id="cardNumberDisplay" style="display: none;">
                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                                <i class="ki-duotone ki-credit-cart fs-2tx text-primary me-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <div class="d-flex flex-stack flex-grow-1">
                                    <div class="fw-semibold">
                                        <h4 class="text-gray-900 fw-bold">رقم البطاقة المُولد</h4>
                                        <div class="fs-6 text-gray-700"><span id="displayCardNumber"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- بيانات الاشتراك -->
                    <div class="row g-9 mb-8">
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40px me-3">
                                    <div class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-package fs-2 text-success">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h3 class="text-gray-800 fw-bold mb-1">بيانات الاشتراك</h3>
                                    <span class="text-gray-400 fw-semibold fs-6">معلومات الباقة والتواريخ</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">الباقة</label>
                                <select class="form-select form-select-solid @error('package_id') is-invalid @enderror" id="package_id" name="package_id" data-control="select2" data-placeholder="اختر الباقة" required>
                                    <option value="">اختر الباقة</option>
                                    @foreach($packages ?? [] as $package)
                                        <option value="{{ $package->id }}"
                                                data-price="{{ $package->price }}"
                                                data-dependent-price="{{ $package->dependent_price }}"
                                                data-duration="{{ $package->duration_months }}">
                                            {{ $package->name }} - {{ $package->price }} ريال / {{ $package->duration_months }} شهر
                                            @if($package->dependent_price)
                                                (سعر التابع: {{ $package->dependent_price }} ريال)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('package_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">سعر البطاقة</label>
                                <input type="number" step="0.01" id="card_price" name="card_price" class="form-control form-control-solid @error('card_price') is-invalid @enderror" placeholder="سيتم تحديده تلقائياً" readonly>
                                @error('card_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">الحالة</label>
                                <select class="form-select form-select-solid @error('status') is-invalid @enderror" id="status" name="status" data-control="select2" data-placeholder="اختر الحالة" required>
                                    <option value="فعال" selected>فعال</option>
                                    <option value="منتهي">منتهي</option>
                                    <option value="ملغي">ملغي</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">تاريخ بدء الاشتراك</label>
                                <input type="date" id="start_date" name="start_date" class="form-control form-control-solid @error('start_date') is-invalid @enderror" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">تاريخ انتهاء الاشتراك</label>
                                <input type="date" id="end_date" name="end_date" class="form-control form-control-solid @error('end_date') is-invalid @enderror" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- بيانات الخصم والتتبع - تظهر فقط للعملاء المحتملين -->
                    @if(request('potential_customer_id'))
                    <div class="row g-3 mt-4" id="trackingFields">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fa-solid fa-chart-line me-2"></i>بيانات التتبع والخصم
                            </h6>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fa-solid fa-percent text-primary"></i> نسبة الخصم (%)
                            </label>
                            <input type="number" step="0.01" min="0" max="100" id="discount_percentage" name="discount_percentage" class="form-control @error('discount_percentage') is-invalid @enderror" value="{{ old('discount_percentage', 0) }}" readonly>
                            @error('discount_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fa-solid fa-money-bill text-primary"></i> مبلغ الخصم (ريال)
                            </label>
                            <input type="number" step="0.01" min="0" id="discount_amount" name="discount_amount" class="form-control @error('discount_amount') is-invalid @enderror" value="{{ old('discount_amount', 0) }}" readonly>
                            @error('discount_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>
                    @endif

                    <!-- التابعين -->
                    <div class="row g-3 mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-primary border-bottom pb-2 mb-0">
                                    <i class="fa-solid fa-users me-2"></i>التابعين
                                </h6>
                                <button type="button" class="btn-admin btn-outline-primary btn-sm" id="addDependentBtn">
                                    <i class="fa-solid fa-plus"></i> إضافة تابع جديد
                                </button>
                            </div>
                            <div id="dependentsContainer"></div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end">
                        <div class="d-flex">
                            <button type="reset" class="btn btn-light me-3">إعادة تعيين</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">حفظ المشترك</span>
                                <span class="indicator-progress">جاري الحفظ...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-xl-4">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">معلومات إضافية</span>
                    <span class="text-gray-400 mt-1 fw-semibold fs-6">إرشادات وتوجيهات</span>
                </h3>
            </div>
            <div class="card-body pt-6">
                <!-- Card Number Info -->
                <div class="notice d-flex bg-light-info rounded border-info border border-dashed mb-9 p-6">
                    <i class="ki-duotone ki-information fs-2tx text-info me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-semibold">
                            <h4 class="text-gray-900 fw-bold">رقم البطاقة</h4>
                            <div class="fs-6 text-gray-700">سيتم توليد رقم البطاقة تلقائياً بناءً على رقم الهوية والجوال</div>
                        </div>
                    </div>
                </div>

                <!-- Package Info -->
                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                    <i class="ki-duotone ki-package fs-2tx text-warning me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-semibold">
                            <h4 class="text-gray-900 fw-bold">الباقات</h4>
                            <div class="fs-6 text-gray-700">سيتم تحديد السعر والتواريخ تلقائياً عند اختيار الباقة</div>
                        </div>
                    </div>
                </div>

                <!-- Dependents Info -->
                <div class="notice d-flex bg-light-success rounded border-success border border-dashed mb-9 p-6">
                    <i class="ki-duotone ki-people fs-2tx text-success me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                    </i>
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-semibold">
                            <h4 class="text-gray-900 fw-bold">التابعين</h4>
                            <div class="fs-6 text-gray-700">يمكن إضافة التابعين وسيتم تحديد أسعارهم حسب الباقة المختارة</div>
                        </div>
                    </div>
                </div>

                <!-- Validation Status -->
                <div id="validationStatus"></div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2-field').select2({
        width: '100%',
        placeholder: function(){
            return $(this).attr('placeholder') || 'اختر';
        },
        allowClear: true,
        dir: 'rtl',
        language: { noResults: function() { return "لا توجد نتائج"; } }
    });

    // تواريخ الاشتراك الافتراضية (اليوم + سنة)
    function setDefaultDates() {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const startStr = `${yyyy}-${mm}-${dd}`;
        const end = new Date(today);
        end.setFullYear(end.getFullYear() + 1);
        const endStr = `${end.getFullYear()}-${String(end.getMonth() + 1).padStart(2, '0')}-${String(end.getDate()).padStart(2, '0')}`;
        
        // تعيين القيم فقط إذا كانت الحقول فارغة
        if (!$('#start_date').val()) {
            $('#start_date').val(startStr);
        }
        if (!$('#end_date').val()) {
            $('#end_date').val(endStr);
        }
    }
    setDefaultDates();

    // توليد رقم البطاقة تلقائياً
    function generateCardNumber() {
        const idNumber = $('#id_number').val().replace(/\D/g, '');
        const phone = $('#phone').val().replace(/\D/g, '');
        
        if (idNumber.length >= 3 && phone.length >= 3) {
            // أول 3 أرقام من رقم الهوية
            const idPart = idNumber.substring(0, 3).padStart(3, '0');
            // آخر 3 أرقام من رقم الجوال
            const phonePart = phone.slice(-3).padStart(3, '0');
            // 4 أرقام عشوائية
            const randomPart = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
            
            return idPart + phonePart + randomPart;
        }
        return '';
    }

    // تحديث رقم البطاقة عند تغيير رقم الهوية أو الجوال
    function updateCardNumber() {
        const cardNumber = generateCardNumber();
        if (cardNumber) {
            // تحديث الحقل المخفي
            $('#generated_card_number').val(cardNumber);
            // عرض رقم البطاقة
            $('#displayCardNumber').text(cardNumber);
            $('#cardNumberDisplay').show();
        } else {
            $('#cardNumberDisplay').hide();
        }
    }

    $('#id_number, #phone').on('input', function() {
        updateCardNumber();
    });

    // تحديث التواريخ والأسعار عند اختيار الباقة
    $('#package_id').on('change', function() {
        const selected = this.options[this.selectedIndex];
        const price = selected.getAttribute('data-price');
        const duration = selected.getAttribute('data-duration');
        const dependentPrice = selected.getAttribute('data-dependent-price') || 0;
        
        // تحديث السعر
        if (price && price !== '') {
            $('#card_price').val(price);
            $('#card_price').prop('readonly', true).css('background-color', '#f8f9fa');
        } else {
            $('#card_price').prop('readonly', false).css('background-color', '');
            $('#card_price').val('');
        }
        
        // تحديث التواريخ
        if (duration && duration !== '') {
            const start = new Date();
            const end = new Date();
            end.setMonth(end.getMonth() + parseInt(duration));
            const yyyy = start.getFullYear();
            const mm = String(start.getMonth() + 1).padStart(2, '0');
            const dd = String(start.getDate()).padStart(2, '0');
            const startStr = `${yyyy}-${mm}-${dd}`;
            const endStr = `${end.getFullYear()}-${String(end.getMonth() + 1).padStart(2, '0')}-${String(end.getDate()).padStart(2, '0')}`;
            $('#start_date').val(startStr);
            $('#end_date').val(endStr);
        } else {
            setDefaultDates();
        }
        
        // تحديث أسعار التابعين الحاليين
        $('.dependent-price').each(function() {
            if (dependentPrice && dependentPrice !== '') {
                $(this).val(dependentPrice).prop('readonly', true).css('background-color', '#f8f9fa');
            } else {
                $(this).prop('readonly', false).css('background-color', '');
                $(this).val('');
            }
        });
    });

    // إضافة تابع ديناميكي
    let dependentCount = 0;
    $('#addDependentBtn').on('click', function() {
        dependentCount++;
        const selectedNationality = $('#nationality').val() || '';
        const selectedPackage = $('#package_id option:selected');
        const dependentPrice = selectedPackage.attr('data-dependent-price') || 0;
        const dependentHtml = `
            <div class="row g-2 align-items-end mb-3 dependent-row" data-dependent="${dependentCount}">
                <div class="col-md-3">
                    <label class="form-label">اسم التابع: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="dependents[${dependentCount}][name]" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">الجنسية <span class="text-danger">*</span></label>
                    <select class="form-select select2-field nationality-select" name="dependents[${dependentCount}][nationality]" required>
                        <option value="">اختر الجنسية</option>
                        @foreach(config('nationalities', []) as $nat)
                            <option value="{{ $nat['name'] }}" ${selectedNationality === '{{ $nat['name'] }}' ? 'selected' : ''}>{{ $nat['emoji'] }} {{ $nat['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">رقم الهوية / الإقامة</label>
                    <input type="text" class="form-control" name="dependents[${dependentCount}][id_number]">
                </div>
                <div class="col-md-2">
                    <label class="form-label">سعر التابع (ريال)</label>
                    <input type="number" step="0.01" class="form-control dependent-price" name="dependents[${dependentCount}][dependent_price]" value="${dependentPrice}" ${dependentPrice ? 'readonly style=\'background-color:#f8f9fa;\'' : ''}>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-danger btn-sm remove-dependent" title="حذف التابع">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#dependentsContainer').append(dependentHtml);
        
        // إعادة تهيئة Select2 للحقول الجديدة
        $('#dependentsContainer .dependent-row:last .select2-field').select2({
            width: '100%',
            placeholder: function(){
                return $(this).attr('placeholder') || 'اختر';
            },
            allowClear: true,
            dir: 'rtl',
            language: { noResults: function() { return "لا توجد نتائج"; } }
        });
    });
    
    // حذف التابع
    $(document).on('click', '.remove-dependent', function() {
        $(this).closest('.dependent-row').remove();
    });
    
    // تحديث جنسية التابعين عند تغيير جنسية المشترك
    $('#nationality').on('change', function() {
        const selectedNationality = $(this).val();
        if (selectedNationality) {
            $('.nationality-select').each(function() {
                if (!$(this).val()) {
                    $(this).val(selectedNationality).trigger('change');
                }
            });
        }
    });
    
    // تنسيق رقم الهوية (أرقام فقط)
    $(document).on('input', 'input[name*="[id_number]"]', function() {
        $(this).val($(this).val().replace(/\D/g, ''));
    });
    
    // تنسيق رقم الجوال
    $('#phone').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.startsWith('966')) {
            value = value.substring(3);
        }
        if (value.startsWith('0')) {
            value = value.substring(1);
        }
        if (value.length > 0 && !value.startsWith('5')) {
            value = '5' + value;
        }
        if (value.length > 0) {
            value = '0' + value;
        }
        $(this).val(value);
    });
    
    // إعادة تهيئة Select2 عند إضافة أي عنصر ديناميكي
    $(document).on('DOMNodeInserted', function(e) {
        $(e.target).find('.select2-field').select2({
            width: '100%',
            placeholder: function(){
                return $(this).attr('placeholder') || 'اختر';
            },
            allowClear: true,
            dir: 'rtl',
            language: { noResults: function() { return "لا توجد نتائج"; } }
        });
    });

    // التحقق من النموذج قبل الإرسال
    $('#subscriberForm').on('submit', function(e) {
        // التأكد من وجود التواريخ
        if (!$('#start_date').val()) {
            setDefaultDates();
        }
        if (!$('#end_date').val()) {
            setDefaultDates();
        }
        
        // التحقق من أن التواريخ موجودة
        if (!$('#start_date').val() || !$('#end_date').val()) {
            e.preventDefault();
            alert('يرجى التأكد من تعيين تواريخ بدء وانتهاء الاشتراك');
            return false;
        }
        
        // التحقق من أن تاريخ الانتهاء بعد تاريخ البداية
        const startDate = new Date($('#start_date').val());
        const endDate = new Date($('#end_date').val());
        if (endDate <= startDate) {
            e.preventDefault();
            alert('يجب أن يكون تاريخ انتهاء الاشتراك بعد تاريخ بدء الاشتراك');
            return false;
        }
        
        // التحقق من التابعين
        let hasError = false;
        $('.dependent-row').each(function() {
            const name = $(this).find('input[name*="[name]"]').val();
            const nationality = $(this).find('select[name*="[nationality]"]').val();
            
            if (name && !nationality) {
                hasError = true;
                $(this).find('select[name*="[nationality]"]').addClass('is-invalid');
            } else {
                $(this).find('select[name*="[nationality]"]').removeClass('is-invalid');
            }
        });
        
        if (hasError) {
            e.preventDefault();
            alert('يرجى التأكد من اختيار الجنسية لجميع التابعين');
            return false;
        }
    });

    // Form validation
    const form = document.getElementById('subscriberForm');
    const validationStatus = document.getElementById('validationStatus');

    form.addEventListener('input', function() {
        validateForm();
    });

    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const nationality = document.getElementById('nationality').value;
        const idNumber = document.getElementById('id_number').value.trim();

        const isValid = name.length > 0 && phone.length > 0 && nationality && idNumber.length > 0;

        if (isValid) {
            validationStatus.innerHTML = `
                <div class="text-success">
                    <i class="fa-solid fa-check-circle fa-2x mb-2"></i>
                    <p class="mb-0">النموذج صحيح</p>
                </div>
            `;
        } else {
            validationStatus.innerHTML = `
                <div class="text-warning">
                    <i class="fa-solid fa-exclamation-triangle fa-2x mb-2"></i>
                    <p class="mb-0">يرجى إكمال الحقول المطلوبة</p>
                </div>
            `;
        }
    }

    // Initial validation
    validateForm();

    // إخفاء حقول التتبع إذا لم يكن هناك عميل محتمل
    if (!document.getElementById('trackingFields')) {
        const trackingFields = ['discount_percentage', 'discount_amount'];
        trackingFields.forEach(field => {
            const element = document.querySelector(`[name="${field}"]`);
            if (element) {
                element.closest('.col-md-3, .col-md-4, .col-md-6')?.style.setProperty('display', 'none');
            }
        });
    }
});
</script>
@endpush