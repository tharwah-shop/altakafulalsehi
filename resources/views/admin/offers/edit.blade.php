@extends('layouts.admin')

@section('title', 'تعديل العرض')

@section('content')
<!-- Hero Section -->
<div class="admin-card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="/admin" class="text-muted text-decoration-none">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.offers.index') }}" class="text-muted text-decoration-none">العروض</a></li>
                        <li class="breadcrumb-item active" aria-current="page">تعديل العرض</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-2 text-primary">تعديل العرض: {{ $offer->title }}</h1>
                <p class="text-muted mb-0">تعديل معلومات العرض الطبي</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('admin.offers.index') }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-arrow-right"></i>
                    <span>العودة للقائمة</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                        <i class="fa-solid fa-edit"></i>
                    </div>
                    <h5 class="card-title mb-0">تعديل معلومات العرض</h5>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST" enctype="multipart/form-data" id="offerForm">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <!-- Title -->
                        <div class="col-md-6">
                            <label for="title" class="form-label">
                                <i class="fa-solid fa-tag text-primary"></i> عنوان العرض <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $offer->title) }}"
                                   placeholder="اكتب عنوان العرض"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Medical Center -->
                        <div class="col-md-6">
                            <label for="medical_center_id" class="form-label">
                                <i class="fa-solid fa-hospital text-primary"></i> المركز الطبي <span class="text-danger">*</span>
                            </label>
                            <select name="medical_center_id" id="medical_center_id" class="form-select @error('medical_center_id') is-invalid @enderror" required>
                                <option value="">اختر المركز الطبي</option>
                                @foreach($medicalCenters as $center)
                                    <option value="{{ $center->id }}" {{ old('medical_center_id', $offer->medical_center_id) == $center->id ? 'selected' : '' }}>
                                        {{ $center->name }} - {{ $center->city_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('medical_center_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left text-primary"></i> وصف العرض
                            </label>
                            <textarea name="description"
                                      id="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="4"
                                      placeholder="اكتب وصفاً مفصلاً عن العرض وشروطه">{{ old('description', $offer->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Discount Type Selection -->
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-percent text-primary"></i> نوع الخصم
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="discount_type" id="percentage_discount" value="percentage"
                                               {{ old('discount_type', $offer->discount_percentage ? 'percentage' : 'amount') == 'percentage' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="percentage_discount">
                                            خصم بالنسبة المئوية
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="discount_type" id="amount_discount" value="amount"
                                               {{ old('discount_type', $offer->discount_amount ? 'amount' : 'percentage') == 'amount' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="amount_discount">
                                            خصم بمبلغ ثابت
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Discount Percentage -->
                        <div class="col-md-6" id="percentage_field">
                            <label for="discount_percentage" class="form-label">
                                <i class="fas fa-percentage text-primary"></i> نسبة الخصم (%)
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       name="discount_percentage"
                                       id="discount_percentage"
                                       class="form-control @error('discount_percentage') is-invalid @enderror"
                                       min="1" max="100" step="0.01"
                                       value="{{ old('discount_percentage', $offer->discount_percentage) }}"
                                       placeholder="مثال: 25">
                                <span class="input-group-text">%</span>
                            </div>
                            @error('discount_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Discount Amount -->
                        <div class="col-md-6" id="amount_field" style="display: none;">
                            <label for="discount_amount" class="form-label">
                                <i class="fas fa-money-bill text-primary"></i> مبلغ الخصم (ريال)
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       name="discount_amount"
                                       id="discount_amount"
                                       class="form-control @error('discount_amount') is-invalid @enderror"
                                       min="1" step="0.01"
                                       value="{{ old('discount_amount', $offer->discount_amount) }}"
                                       placeholder="مثال: 100">
                                <span class="input-group-text">ريال</span>
                            </div>
                            @error('discount_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Original Price -->
                        <div class="col-md-6">
                            <label for="original_price" class="form-label">
                                <i class="fas fa-tag text-primary"></i> السعر الأصلي (ريال)
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       name="original_price"
                                       id="original_price"
                                       class="form-control @error('original_price') is-invalid @enderror"
                                       min="0" step="0.01"
                                       value="{{ old('original_price', $offer->original_price) }}"
                                       placeholder="مثال: 500">
                                <span class="input-group-text">ريال</span>
                            </div>
                            @error('original_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Discounted Price -->
                        <div class="col-md-6">
                            <label for="discounted_price" class="form-label">
                                <i class="fas fa-tags text-primary"></i> السعر بعد الخصم (ريال)
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       name="discounted_price"
                                       id="discounted_price"
                                       class="form-control @error('discounted_price') is-invalid @enderror"
                                       min="0" step="0.01"
                                       value="{{ old('discounted_price', $offer->discounted_price) }}"
                                       placeholder="سيتم حسابه تلقائياً"
                                       readonly>
                                <span class="input-group-text">ريال</span>
                            </div>
                            @error('discounted_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">
                                <i class="fas fa-calendar-alt text-primary"></i> تاريخ بداية العرض <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   name="start_date"
                                   id="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date', $offer->start_date ? $offer->start_date->format('Y-m-d') : '') }}"
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">
                                <i class="fas fa-calendar-times text-primary"></i> تاريخ انتهاء العرض <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   name="end_date"
                                   id="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date', $offer->end_date ? $offer->end_date->format('Y-m-d') : '') }}"
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Max Uses -->
                        <div class="col-md-6">
                            <label for="max_uses" class="form-label">
                                <i class="fas fa-users text-primary"></i> الحد الأقصى للاستخدام
                            </label>
                            <input type="number"
                                   name="max_uses"
                                   id="max_uses"
                                   class="form-control @error('max_uses') is-invalid @enderror"
                                   min="0"
                                   value="{{ old('max_uses', $offer->max_uses) }}"
                                   placeholder="اتركه فارغاً للاستخدام غير المحدود">
                            @error('max_uses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">اتركه فارغاً أو 0 للاستخدام غير المحدود</div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on text-primary"></i> حالة العرض <span class="text-danger">*</span>
                            </label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $offer->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status', $offer->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                <option value="pending" {{ old('status', $offer->status) == 'pending' ? 'selected' : '' }}>في انتظار المراجعة</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="col-12">
                            <label for="terms_conditions" class="form-label">
                                <i class="fas fa-file-contract text-primary"></i> الشروط والأحكام
                            </label>
                            <textarea name="terms_conditions"
                                      id="terms_conditions"
                                      class="form-control @error('terms_conditions') is-invalid @enderror"
                                      rows="3"
                                      placeholder="اكتب الشروط والأحكام الخاصة بالعرض">{{ old('terms_conditions', $offer->terms_conditions) }}</textarea>
                            @error('terms_conditions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Is Featured -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $offer->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    عرض مميز (سيظهر في المقدمة)
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12 mt-5">
                            <div class="border-top pt-4">
                                <div class="d-flex gap-3 justify-content-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i>
                                        <span>تحديث العرض</span>
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-redo me-2"></i>
                                        <span>إعادة تعيين</span>
                                    </button>
                                    <a href="{{ route('admin.offers.index') }}" class="btn btn-outline-danger px-4">
                                        <i class="fas fa-times me-2"></i>
                                        <span>إلغاء</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Current Image -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-image text-primary me-2"></i>
                    صورة العرض
                </h5>
            </div>
            <div class="card-body">
                <div id="current-image-container">
                    @if($offer->image)
                        <div class="text-center mb-3">
                            <img src="{{ $offer->image_url }}"
                                 alt="{{ $offer->title }}"
                                 class="img-fluid rounded"
                                 style="max-height: 200px;"
                                 id="current-image">
                            <p class="text-muted small mt-2">الصورة الحالية</p>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="remove-current-image">
                                <i class="fas fa-trash me-1"></i>إزالة الصورة الحالية
                            </button>
                        </div>
                    @else
                        <div class="text-center text-muted" id="no-image-placeholder">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-image fa-3x mb-2"></i>
                                <p class="mb-0 small">لا توجد صورة حالياً</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="text-center text-muted" id="new-image-preview" style="display: none;">
                    <div class="border rounded p-3 bg-light">
                        <img id="preview-img" src="" alt="معاينة الصورة الجديدة" class="img-fluid rounded" style="max-height: 200px;">
                        <p class="text-muted small mt-2">الصورة الجديدة</p>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="remove-new-image">
                            <i class="fas fa-trash me-1"></i>إزالة الصورة الجديدة
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">
                        <i class="fas fa-upload text-primary"></i>
                        {{ $offer->image ? 'تغيير الصورة' : 'تحميل صورة العرض' }}
                    </label>
                    <input type="file"
                           name="image"
                           id="image"
                           class="form-control @error('image') is-invalid @enderror"
                           accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/bmp">
                    @error('image')
                        <div class="invalid-feedback">
                            @if(is_array($message))
                                @foreach($message as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            @else
                                {{ $message }}
                            @endif
                        </div>
                    @enderror
                    <div class="form-text">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            الأنواع المدعومة: JPEG, PNG, GIF, WebP, BMP | الحد الأقصى: 5 ميجابايت | الأبعاد المفضلة: 800×600 بيكسل
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle text-info me-2"></i>
                    معلومات العرض
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <small class="text-muted">تاريخ الإنشاء:</small>
                        <div class="fw-bold">{{ $offer->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">آخر تحديث:</small>
                        <div class="fw-bold">{{ $offer->updated_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">الرابط المخصص:</small>
                        <div class="fw-bold">{{ $offer->slug }}</div>
                    </div>
                    @if($offer->current_uses > 0)
                    <div class="col-12">
                        <small class="text-muted">عدد مرات الاستخدام:</small>
                        <div class="fw-bold">{{ $offer->current_uses }}
                            @if($offer->max_uses > 0)
                                من {{ $offer->max_uses }}
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Discount type toggle
        const percentageRadio = document.getElementById('percentage_discount');
        const amountRadio = document.getElementById('amount_discount');
        const percentageField = document.getElementById('percentage_field');
        const amountField = document.getElementById('amount_field');

        function toggleDiscountFields() {
            if (percentageRadio.checked) {
                percentageField.style.display = 'block';
                amountField.style.display = 'none';
                document.getElementById('discount_amount').value = '';
            } else {
                percentageField.style.display = 'none';
                amountField.style.display = 'block';
                document.getElementById('discount_percentage').value = '';
            }
        }

        // Initialize on page load
        toggleDiscountFields();

        percentageRadio.addEventListener('change', toggleDiscountFields);
        amountRadio.addEventListener('change', toggleDiscountFields);

        // Auto-calculate discounted price
        const originalPriceInput = document.getElementById('original_price');
        const discountPercentageInput = document.getElementById('discount_percentage');
        const discountAmountInput = document.getElementById('discount_amount');
        const discountedPriceInput = document.getElementById('discounted_price');

        function calculateDiscountedPrice() {
            const originalPrice = parseFloat(originalPriceInput.value) || 0;
            let discountedPrice = originalPrice;

            if (percentageRadio.checked && discountPercentageInput.value) {
                const percentage = parseFloat(discountPercentageInput.value) || 0;
                discountedPrice = originalPrice - (originalPrice * percentage / 100);
            } else if (amountRadio.checked && discountAmountInput.value) {
                const amount = parseFloat(discountAmountInput.value) || 0;
                discountedPrice = originalPrice - amount;
            }

            discountedPriceInput.value = discountedPrice > 0 ? discountedPrice.toFixed(2) : '';
        }

        originalPriceInput.addEventListener('input', calculateDiscountedPrice);
        discountPercentageInput.addEventListener('input', calculateDiscountedPrice);
        discountAmountInput.addEventListener('input', calculateDiscountedPrice);

        // Image preview functionality
        const imageInput = document.getElementById('image');
        const currentImageContainer = document.getElementById('current-image-container');
        const newImagePreview = document.getElementById('new-image-preview');
        const previewImg = document.getElementById('preview-img');
        const removeNewImageBtn = document.getElementById('remove-new-image');
        const removeCurrentImageBtn = document.getElementById('remove-current-image');

        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // التحقق من نوع الملف
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('نوع الملف غير مدعوم. يرجى اختيار صورة بصيغة JPEG, PNG, GIF, WebP, أو BMP');
                        this.value = '';
                        return;
                    }

                    // التحقق من حجم الملف (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('حجم الملف كبير جداً. الحد الأقصى 5 ميجابايت');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        newImagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    newImagePreview.style.display = 'none';
                }
            });

            // إزالة الصورة الجديدة
            if (removeNewImageBtn) {
                removeNewImageBtn.addEventListener('click', function() {
                    imageInput.value = '';
                    newImagePreview.style.display = 'none';
                });
            }

            // إزالة الصورة الحالية (إخفاؤها فقط)
            if (removeCurrentImageBtn) {
                removeCurrentImageBtn.addEventListener('click', function() {
                    if (confirm('هل أنت متأكد من إزالة الصورة الحالية؟')) {
                        currentImageContainer.style.display = 'none';
                        // يمكن إضافة حقل مخفي لإشارة حذف الصورة
                    }
                });
            }
        }
    });
</script>
@endsection