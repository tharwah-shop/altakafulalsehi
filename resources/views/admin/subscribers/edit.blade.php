@extends('layouts.admin')

@section('title', 'تعديل مشترك')

@section('content')
<!-- Hero Section -->
<div class="admin-card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="/admin" class="text-muted text-decoration-none">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.subscribers.index') }}" class="text-muted text-decoration-none">المشتركين</a></li>
                        <li class="breadcrumb-item active" aria-current="page">تعديل: {{ $subscriber->name }}</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-2 text-primary">تعديل مشترك</h1>
                <p class="text-muted mb-0">تعديل بيانات المشترك: {{ $subscriber->name }}</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" onclick="showCardPreview({{ $subscriber->id }})" class="btn-admin btn-outline-success" data-bs-toggle="modal" data-bs-target="#cardPreviewModal">
                        <i class="fa-solid fa-id-card"></i>
                        <span>معاينة البطاقة</span>
                    </button>
                    <a href="{{ route('admin.subscribers.index') }}" class="btn-admin btn-outline-primary">
                        <i class="fa-solid fa-arrow-right"></i>
                        <span>العودة للقائمة</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Form -->
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                        <i class="fa-solid fa-user-edit"></i>
                    </div>
                    <h5 class="card-title mb-0">تعديل معلومات المشترك</h5>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.subscribers.update', $subscriber->id) }}" method="POST" id="subscriberForm" class="form-admin">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Payment Method & Card Number -->
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fa-solid fa-credit-card me-2"></i>معلومات البطاقة
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fa-solid fa-payment text-primary"></i> طريقة الإضافة
                            </label>
                            <select class="form-select select2-field @error('payment_method') is-invalid @enderror" name="payment_method">
                                <option value="يدوي" {{ old('payment_method', $subscriber->payment_method) == 'يدوي' ? 'selected' : '' }}>يدوي</option>
                                <option value="myfatoorah" {{ old('payment_method', $subscriber->payment_method) == 'myfatoorah' ? 'selected' : '' }}>ماي فاتورة</option>
                                <option value="tabby" {{ old('payment_method', $subscriber->payment_method) == 'tabby' ? 'selected' : '' }}>تابي</option>
                                <option value="bank_transfer" {{ old('payment_method', $subscriber->payment_method) == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fa-solid fa-id-card text-primary"></i> رقم البطاقة
                            </label>
                            <input type="text" class="form-control" value="{{ $subscriber->card_number }}" readonly style="background-color: #f8f9fa;">
                            <div class="form-text">رقم البطاقة يتم إنشاؤه تلقائياً ولا يمكن تعديله</div>
                        </div>
                        <!-- Personal Information -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fa-solid fa-user me-2"></i>البيانات الشخصية
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fa-solid fa-user text-primary"></i> الاسم الكامل <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $subscriber->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fa-solid fa-phone text-primary"></i> رقم الجوال <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $subscriber->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fa-solid fa-envelope text-primary"></i> البريد الإلكتروني
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $subscriber->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fa-solid fa-city text-primary"></i> المدينة
                            </label>
                            <select class="form-select select2-field @error('city') is-invalid @enderror" name="city">
                                <option value="">اختر المدينة</option>
                                @if(isset($cities) && $cities->count() > 0)
                                    @foreach($cities as $city)
                                        <option value="{{ $city['name'] }}" {{ old('city', $subscriber->city) == $city['name'] ? 'selected' : '' }}>{{ $city['name'] }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>لا توجد مدن متاحة</option>
                                @endif
                            </select>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fa-solid fa-id-card text-primary"></i> رقم الهوية / الإقامة
                            </label>
                            <input type="text" name="id_number" class="form-control @error('id_number') is-invalid @enderror" value="{{ old('id_number', $subscriber->id_number) }}">
                            @error('id_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fa-solid fa-flag text-primary"></i> الجنسية <span class="text-danger">*</span>
                            </label>
                            <select class="form-select select2-field nationality-select @error('nationality') is-invalid @enderror" name="nationality" required>
                                <option value="">اختر الجنسية</option>
                                @foreach(config('nationalities', []) as $nat)
                                    <option value="{{ $nat['name'] }}" {{ old('nationality', $subscriber->nationality ?? '') == $nat['name'] ? 'selected' : '' }}>
                                        {{ $nat['emoji'] }} {{ $nat['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nationality')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subscription Information -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fa-solid fa-calendar me-2"></i>بيانات الاشتراك
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fa-solid fa-calendar-plus text-primary"></i> تاريخ بدء الاشتراك <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', optional($subscriber->start_date)->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fa-solid fa-calendar-times text-primary"></i> تاريخ انتهاء الاشتراك <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', optional($subscriber->end_date)->format('Y-m-d')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fa-solid fa-box text-primary"></i> الباقة
                            </label>
                            <select class="form-select select2-field @error('package_id') is-invalid @enderror" id="package_id" name="package_id">
                                <option value="">اختر الباقة</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}"
                                            data-price="{{ $package->price }}"
                                            data-dependent-price="{{ $package->dependent_price }}"
                                            data-duration="{{ $package->duration_months }}"
                                            {{ old('package_id', $subscriber->package_id) == $package->id ? 'selected' : '' }}>
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

                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fa-solid fa-money-bill text-primary"></i> سعر البطاقة
                            </label>
                            <input type="number" step="0.01" id="card_price" name="card_price" class="form-control @error('card_price') is-invalid @enderror" value="{{ old('card_price', $subscriber->card_price) }}">
                            @error('card_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fa-solid fa-toggle-on text-primary"></i> الحالة <span class="text-danger">*</span>
                            </label>
                            <select class="form-select select2-field @error('status') is-invalid @enderror" name="status" required>
                                @foreach(\App\Models\Subscriber::getStatusOptions() as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $subscriber->status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Dependents Section -->
                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-primary border-bottom pb-2 mb-0">
                                    <i class="fa-solid fa-users me-2"></i>التابعين
                                </h6>
                                <button type="button" class="btn-admin btn-outline-primary btn-sm" id="addDependentBtn">
                                    <i class="fa-solid fa-plus"></i> إضافة تابع جديد
                                </button>
                            </div>
                            <div id="dependentsContainer">
                                @foreach($subscriber->dependents as $i => $dependent)
                                    <div class="dependent-row mb-3 p-3 border rounded">
                                        <input type="hidden" name="existing_dependents[{{ $i }}][id]" value="{{ $dependent->id }}">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label">
                                                    <i class="fa-solid fa-user text-primary"></i> اسم التابع <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" name="existing_dependents[{{ $i }}][name]" value="{{ $dependent->name }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">
                                                    <i class="fa-solid fa-flag text-primary"></i> الجنسية <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select select2-field nationality-select" name="existing_dependents[{{ $i }}][nationality]" required>
                                                    <option value="">اختر الجنسية</option>
                                                    @foreach(config('nationalities', []) as $nat)
                                                        <option value="{{ $nat['name'] }}" {{ $dependent->nationality == $nat['name'] ? 'selected' : '' }}>
                                                            {{ $nat['emoji'] }} {{ $nat['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">
                                                    <i class="fa-solid fa-id-card text-primary"></i> رقم الهوية / الإقامة
                                                </label>
                                                <input type="text" class="form-control" name="existing_dependents[{{ $i }}][id_number]" value="{{ $dependent->id_number }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">
                                                    <i class="fa-solid fa-money-bill text-primary"></i> سعر التابع (ريال)
                                                </label>
                                                <input type="number" step="0.01" class="form-control dependent-price" name="existing_dependents[{{ $i }}][dependent_price]" value="{{ $dependent->dependent_price }}" placeholder="سعر التابع">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-dependent w-100" title="حذف التابع">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12 mt-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="submit" class="btn-admin btn-primary">
                                    <i class="fa-solid fa-save"></i>
                                    <span>حفظ التعديلات</span>
                                </button>
                                <button type="reset" class="btn-admin btn-outline-secondary">
                                    <i class="fa-solid fa-rotate"></i>
                                    <span>إعادة تعيين</span>
                                </button>
                                <a href="{{ route('admin.subscribers.index') }}" class="btn-admin btn-outline-primary">
                                    <i class="fa-solid fa-times"></i>
                                    <span>إلغاء</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Card Preview -->
        <div class="admin-card mb-4">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <h5 class="card-title mb-0">معاينة البطاقة</h5>
                </div>
            </div>
            <div class="card-body text-center">
                <button type="button" onclick="showCardPreview({{ $subscriber->id }})" class="btn-admin btn-success w-100 mb-3" data-bs-toggle="modal" data-bs-target="#cardPreviewModal">
                    <i class="fa-solid fa-eye"></i>
                    <span>معاينة البطاقة</span>
                </button>
                <div class="text-muted small">
                    <i class="fa-solid fa-info-circle"></i>
                    يمكنك معاينة وطباعة البطاقة
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
        language: {
            noResults: function() { return "لا توجد نتائج"; }
        }
    });

    // حساب الخصم تلقائياً
    $('#discount_percentage').on('input', function() {
        const percentage = parseFloat($(this).val()) || 0;
        const cardPrice = parseFloat($('#card_price').val()) || 0;
        const discountAmount = (cardPrice * percentage) / 100;
        $('#discount_amount').val(discountAmount.toFixed(2));
    });

    // تحديث الأسعار عند اختيار الباقة
    $('#package_id').on('change', function() {
        const selected = this.options[this.selectedIndex];
        const price = selected.getAttribute('data-price');
        const dependentPrice = selected.getAttribute('data-dependent-price') || 0;
        
        if (price && price !== '') {
            $('#card_price').val(price);
        }
        
        // تحديث أسعار التابعين
        $('.dependent-price').each(function() {
            if (dependentPrice && dependentPrice !== '') {
                $(this).val(dependentPrice);
            }
        });
    });

    // إضافة تابع جديد
    let dependentCount = {{ $subscriber->dependents->count() }};
    $('#addDependentBtn').on('click', function() {
        dependentCount++;
        const selectedNationality = $('#nationality').val() || '';
        const selectedPackage = $('#package_id option:selected');
        const dependentPrice = selectedPackage.attr('data-dependent-price') || 0;
        
        const dependentHtml = `
            <div class="row g-2 align-items-end mb-2 dependent-row">
                <div class="col-md-3">
                    <label class="form-label">اسم التابع: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="new_dependents[${dependentCount}][name]" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">الجنسية <span class="text-danger">*</span></label>
                    <select class="form-select select2-field nationality-select" name="new_dependents[${dependentCount}][nationality]" required>
                        <option value="">اختر الجنسية</option>
                        @foreach(config('nationalities', []) as $nat)
                            <option value="{{ $nat['name'] }}" ${selectedNationality === '{{ $nat['name'] }}' ? 'selected' : ''}>
                                {{ $nat['emoji'] }} {{ $nat['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">رقم الهوية / الإقامة</label>
                    <input type="text" class="form-control" name="new_dependents[${dependentCount}][id_number]">
                </div>
                <div class="col-md-2">
                    <label class="form-label">سعر التابع (ريال)</label>
                    <input type="number" step="0.01" class="form-control dependent-price" name="new_dependents[${dependentCount}][dependent_price]" value="${dependentPrice}" placeholder="سعر التابع">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-danger btn-sm remove-dependent"><i class="fas fa-trash"></i></button>
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
    $('input[name="phone"]').on('input', function() {
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
        // التحقق من أن التواريخ موجودة
        if (!$('input[name="start_date"]').val() || !$('input[name="end_date"]').val()) {
            e.preventDefault();
            alert('يرجى التأكد من تعيين تواريخ بدء وانتهاء الاشتراك');
            return false;
        }
        
        // التحقق من أن تاريخ الانتهاء بعد تاريخ البداية
        const startDate = new Date($('input[name="start_date"]').val());
        const endDate = new Date($('input[name="end_date"]').val());
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
});

// Card Preview Functionality
function showCardPreview(subscriberId) {
    // إظهار مؤشر التحميل
    const modalBody = document.querySelector('#cardPreviewModal .modal-body');
    modalBody.innerHTML = `
        <div class="text-center p-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <p class="mt-2">جاري تحميل بيانات البطاقة...</p>
        </div>
    `;

    // تحميل بيانات البطاقة
    fetch(`/admin/subscribers/${subscriberId}/card-preview`)
        .then(response => response.text())
        .then(html => {
            // إضافة أزرار التحكم
            const cardPreviewHtml = `
                <div class="card-preview-container">
                    <div class="card-preview-controls mb-3">
                        <button class="btn btn-primary flip-card-btn" onclick="flipCard()">
                            <i class="fas fa-sync-alt me-1"></i>
                            قلب البطاقة
                        </button>
                        <button class="btn btn-success print-card-btn ms-2" onclick="printCard()">
                            <i class="fas fa-print me-1"></i>
                            طباعة البطاقة
                        </button>
                        <a href="/admin/subscribers/${subscriberId}/card-pdf" class="btn btn-info ms-2" target="_blank">
                            <i class="fas fa-download me-1"></i>
                            تحميل PDF
                        </a>
                    </div>

                    <div class="card-preview-wrapper">
                        ${html}
                    </div>
                </div>
            `;
            modalBody.innerHTML = cardPreviewHtml;
        })
        .catch(error => {
            console.error('Error loading card preview:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger m-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    حدث خطأ أثناء تحميل البطاقة
                    <button class="btn btn-sm btn-outline-danger ms-3" onclick="showCardPreview(${subscriberId})">
                        <i class="fas fa-redo me-1"></i>
                        إعادة المحاولة
                    </button>
                </div>
            `;
        });
}
</script>

<!-- Card Preview Modal -->
<x-card-preview-modal />

@endpush