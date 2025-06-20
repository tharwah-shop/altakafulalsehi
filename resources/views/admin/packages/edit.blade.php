@extends('layouts.admin')

@section('title', 'تعديل الباقة')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">تعديل الباقة: {{ $package->name }}</h2>
        <p class="text-muted mb-0">تحديث بيانات الباقة</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.packages.show', $package->id) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye me-2"></i>
            عرض الباقة
        </a>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row g-5 g-xl-10">
    <!-- Main Form -->
    <div class="col-xl-8">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">معلومات الباقة</span>
                    <span class="text-gray-400 mt-1 fw-semibold fs-6">تحديث البيانات الأساسية للباقة</span>
                </h3>
            </div>
            <div class="card-body pt-6">
                <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" id="packageForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Package Names -->
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="form-label required">اسم الباقة (عربي)</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $package->name) }}" placeholder="مثال: الباقة الذهبية" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">اسم الباقة (إنجليزي)</label>
                            <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" 
                                   value="{{ old('name_en', $package->name_en) }}" placeholder="Example: Gold Package">
                            @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Descriptions -->
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="form-label">الوصف (عربي)</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="4" placeholder="وصف مفصل للباقة...">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">الوصف (إنجليزي)</label>
                            <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" 
                                      rows="4" placeholder="Detailed package description...">{{ old('description_en', $package->description_en) }}</textarea>
                            @error('description_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="row mb-6">
                        <div class="col-md-4">
                            <label class="form-label required">سعر الباقة (ريال)</label>
                            <div class="input-group">
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                       value="{{ old('price', $package->price) }}" placeholder="199.00" step="0.01" min="0" required>
                                <span class="input-group-text">ريال</span>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">سعر التابع (ريال)</label>
                            <div class="input-group">
                                <input type="number" name="dependent_price" class="form-control @error('dependent_price') is-invalid @enderror" 
                                       value="{{ old('dependent_price', $package->dependent_price) }}" placeholder="99.00" step="0.01" min="0">
                                <span class="input-group-text">ريال</span>
                            </div>
                            @error('dependent_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">اتركه فارغاً إذا لم تكن الباقة تدعم التابعين</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">نسبة الخصم الافتراضية (%)</label>
                            <div class="input-group">
                                <input type="number" name="discount_percentage" class="form-control @error('discount_percentage') is-invalid @enderror" 
                                       value="{{ old('discount_percentage', $package->discount_percentage) }}" placeholder="30" step="0.01" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                            @error('discount_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Duration and Dependents -->
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="form-label required">مدة الاشتراك (بالأشهر)</label>
                            <select name="duration_months" class="form-select @error('duration_months') is-invalid @enderror" required>
                                <option value="">اختر المدة</option>
                                <option value="1" {{ old('duration_months', $package->duration_months) == 1 ? 'selected' : '' }}>شهر واحد</option>
                                <option value="3" {{ old('duration_months', $package->duration_months) == 3 ? 'selected' : '' }}>3 أشهر</option>
                                <option value="6" {{ old('duration_months', $package->duration_months) == 6 ? 'selected' : '' }}>6 أشهر</option>
                                <option value="12" {{ old('duration_months', $package->duration_months) == 12 ? 'selected' : '' }}>سنة واحدة</option>
                                <option value="24" {{ old('duration_months', $package->duration_months) == 24 ? 'selected' : '' }}>سنتان</option>
                                <option value="36" {{ old('duration_months', $package->duration_months) == 36 ? 'selected' : '' }}>3 سنوات</option>
                            </select>
                            @error('duration_months')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">أقصى عدد تابعين</label>
                            <select name="max_dependents" class="form-select @error('max_dependents') is-invalid @enderror" required>
                                <option value="0" {{ old('max_dependents', $package->max_dependents) == 0 ? 'selected' : '' }}>غير محدود</option>
                                <option value="1" {{ old('max_dependents', $package->max_dependents) == 1 ? 'selected' : '' }}>تابع واحد</option>
                                <option value="2" {{ old('max_dependents', $package->max_dependents) == 2 ? 'selected' : '' }}>تابعان</option>
                                <option value="3" {{ old('max_dependents', $package->max_dependents) == 3 ? 'selected' : '' }}>3 تابعين</option>
                                <option value="4" {{ old('max_dependents', $package->max_dependents) == 4 ? 'selected' : '' }}>4 تابعين</option>
                                <option value="5" {{ old('max_dependents', $package->max_dependents) == 5 ? 'selected' : '' }}>5 تابعين</option>
                                <option value="10" {{ old('max_dependents', $package->max_dependents) == 10 ? 'selected' : '' }}>10 تابعين</option>
                            </select>
                            @error('max_dependents')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Package Features -->
                    <div class="mb-6">
                        <label class="form-label">مميزات الباقة</label>
                        <div id="features-container">
                            @php
                                $features = old('features', $package->features ?? []);
                            @endphp
                            @if($features && count($features) > 0)
                                @foreach($features as $index => $feature)
                                    <div class="input-group mb-2 feature-item">
                                        <input type="text" name="features[]" class="form-control" 
                                               value="{{ $feature }}" placeholder="أدخل ميزة من مميزات الباقة">
                                        <button type="button" class="btn btn-outline-danger remove-feature">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2 feature-item">
                                    <input type="text" name="features[]" class="form-control" 
                                           placeholder="أدخل ميزة من مميزات الباقة">
                                    <button type="button" class="btn btn-outline-danger remove-feature">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-feature">
                            <i class="fas fa-plus me-1"></i>
                            إضافة ميزة
                        </button>
                        @error('features')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status and Settings -->
                    <div class="row mb-6">
                        <div class="col-md-4">
                            <label class="form-label required">حالة الباقة</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $package->status) === 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status', $package->status) === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                <option value="draft" {{ old('status', $package->status) === 'draft' ? 'selected' : '' }}>مسودة</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ترتيب العرض</label>
                            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                   value="{{ old('sort_order', $package->sort_order) }}" placeholder="0" min="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">الرقم الأقل يظهر أولاً</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">لون الباقة</label>
                            <input type="color" name="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                   value="{{ old('color', $package->color) }}">
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Icon and Featured -->
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="form-label">أيقونة الباقة</label>
                            <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" 
                                   value="{{ old('icon', $package->icon) }}" placeholder="fas fa-shield-alt">
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">استخدم أيقونات Font Awesome (مثل: fas fa-shield-alt)</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" 
                                       {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    باقة مميزة
                                </label>
                                <div class="form-text">الباقات المميزة تظهر بشكل بارز في الموقع</div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary" onclick="console.log('تم النقر على زر التحديث');">
                            <i class="fas fa-save me-2"></i>
                            تحديث الباقة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview Card -->
    <div class="col-xl-4">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">معاينة الباقة</span>
                    <span class="text-gray-400 mt-1 fw-semibold fs-6">كيف ستظهر الباقة للمستخدمين</span>
                </h3>
            </div>
            <div class="card-body pt-6">
                <div class="card border" id="package-preview">
                    <div class="card-header text-center" style="background-color: {{ $package->color }}; color: white;">
                        <h5 class="mb-0" id="preview-name">{{ $package->name }}</h5>
                        <small id="preview-name-en">{{ $package->name_en }}</small>
                    </div>
                    <div class="card-body text-center">
                        <div class="display-6 fw-bold text-primary mb-2" id="preview-price">{{ number_format($package->price, 2) }} ريال</div>
                        <div class="text-muted mb-3" id="preview-duration">{{ $package->duration_text }}</div>
                        <div class="text-muted mb-3" id="preview-dependents">
                            {{ $package->max_dependents == 0 ? 'تابعين غير محدود' : $package->max_dependents . ' تابعين كحد أقصى' }}
                        </div>
                        <ul class="list-unstyled text-start" id="preview-features">
                            @if($package->features && count($package->features) > 0)
                                @foreach($package->features as $feature)
                                    <li><i class="fas fa-check text-success me-2"></i>{{ $feature }}</li>
                                @endforeach
                            @else
                                <li><i class="fas fa-check text-success me-2"></i>لم يتم إضافة مميزات بعد</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Package Statistics -->
                <div class="mt-4">
                    <h6 class="fw-bold mb-3">إحصائيات الباقة</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center p-3">
                                    <div class="fw-bold">{{ $package->subscribers()->count() }}</div>
                                    <small>مشترك</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center p-3">
                                    <div class="fw-bold">{{ $package->subscribers()->where('status', 'فعال')->count() }}</div>
                                    <small>نشط</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
console.log('تحميل JavaScript لصفحة تعديل الباقة...');
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM محمل بنجاح');
    // Add/Remove Features
    document.getElementById('add-feature').addEventListener('click', function() {
        const container = document.getElementById('features-container');
        const newFeature = document.createElement('div');
        newFeature.className = 'input-group mb-2 feature-item';
        newFeature.innerHTML = `
            <input type="text" name="features[]" class="form-control" placeholder="أدخل ميزة من مميزات الباقة">
            <button type="button" class="btn btn-outline-danger remove-feature">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newFeature);
        updatePreview();
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            const featureItems = document.querySelectorAll('.feature-item');
            if (featureItems.length > 1) {
                e.target.closest('.feature-item').remove();
                updatePreview();
            }
        }
    });

    // Update preview on input change
    document.getElementById('packageForm').addEventListener('input', updatePreview);
    document.getElementById('packageForm').addEventListener('change', updatePreview);

    function updatePreview() {
        const name = document.querySelector('[name="name"]').value || 'اسم الباقة';
        const nameEn = document.querySelector('[name="name_en"]').value || 'Package Name';
        const price = document.querySelector('[name="price"]').value || '0';
        const duration = document.querySelector('[name="duration_months"]').value;
        const maxDependents = document.querySelector('[name="max_dependents"]').value;
        const color = document.querySelector('[name="color"]').value || '#007bff';
        const features = Array.from(document.querySelectorAll('[name="features[]"]')).map(input => input.value).filter(f => f);

        // Update preview
        document.getElementById('preview-name').textContent = name;
        document.getElementById('preview-name-en').textContent = nameEn;

        // تحسين عرض السعر
        const priceValue = parseFloat(price) || 0;
        document.getElementById('preview-price').textContent = priceValue.toLocaleString('ar-SA', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }) + ' ريال';
        
        // Duration text - مطابق لمنطق النموذج
        let durationText = 'مدة الاشتراك';
        if (duration) {
            const months = parseInt(duration);
            if (months == 1) {
                durationText = 'شهر واحد';
            } else if (months == 12) {
                durationText = 'سنة واحدة';
            } else if (months < 12) {
                durationText = months + ' أشهر';
            } else {
                const years = Math.floor(months / 12);
                const remainingMonths = months % 12;
                durationText = years + ' سنة';
                if (remainingMonths > 0) {
                    durationText += ' و ' + remainingMonths + ' أشهر';
                }
            }
        }
        document.getElementById('preview-duration').textContent = durationText;

        // Dependents text
        let dependentsText = 'عدد التابعين';
        if (maxDependents !== '') {
            dependentsText = maxDependents == 0 ? 'تابعين غير محدود' : maxDependents + ' تابعين كحد أقصى';
        }
        document.getElementById('preview-dependents').textContent = dependentsText;

        // Features
        const featuresList = document.getElementById('preview-features');
        featuresList.innerHTML = '';
        if (features.length > 0) {
            features.forEach(feature => {
                const li = document.createElement('li');
                li.innerHTML = `<i class="fas fa-check text-success me-2"></i>${feature}`;
                featuresList.appendChild(li);
            });
        } else {
            featuresList.innerHTML = '<li><i class="fas fa-check text-success me-2"></i>لم يتم إضافة مميزات بعد</li>';
        }

        // Update color
        document.querySelector('#package-preview .card-header').style.backgroundColor = color;
    }

    // Initial preview update
    console.log('تحديث المعاينة الأولية...');
    updatePreview();

    console.log('JavaScript محمل بالكامل');
});
</script>
@endpush
@endsection
