-- Active: 1750338575056@@127.0.0.1@3306
@extends('layouts.admin')

@section('title', 'تعديل مركز طبي')
@section('content-header', 'تعديل مركز طبي')
@section('content-subtitle', 'تحديث بيانات المركز الطبي')

@section('content')
<!-- Header Actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">تعديل مركز طبي</h2>
        <p class="text-muted mb-0">تحديث بيانات {{ $medicalCenter->name }}</p>
    </div>
    <div>
        <a href="{{ route('admin.medical-centers.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-right me-2"></i>
            العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit text-primary me-2"></i>
                    تعديل معلومات المركز الطبي
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.medical-centers.update', $medicalCenter->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="remove_current_image" id="remove_current_image_input" value="0">

                    <div class="row g-3">
                        <!-- Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                <i class="fas fa-hospital text-primary"></i> اسم المركز الطبي <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $medicalCenter->name) }}"
                                   placeholder="اكتب اسم المركز الطبي"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="col-md-6">
                            <label for="slug" class="form-label">
                                <i class="fas fa-link text-primary"></i> الرابط المخصص (Slug)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">/medical-centers/</span>
                                <input type="text"
                                       name="slug"
                                       id="slug"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       value="{{ old('slug', $medicalCenter->slug) }}"
                                       placeholder="سيتم إنشاؤه تلقائياً"
                                       dir="ltr">
                                <button type="button" class="btn btn-outline-secondary" id="generate-slug" title="إنشاء رابط من الاسم">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">سيتم تحديثه تلقائياً عند تغيير اسم المركز</div>
                            <div id="slug-feedback" class="form-text text-success mt-1" style="display: none;">
                                <i class="fas fa-check-circle"></i> تم تحديث الرابط المخصص
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone text-primary"></i> رقم الهاتف
                            </label>
                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $medicalCenter->phone) }}"
                                   placeholder="مثال: 966-11-123-4567">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope text-primary"></i> البريد الإلكتروني
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $medicalCenter->email) }}"
                                   placeholder="example@hospital.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Website -->
                        <div class="col-md-6">
                            <label for="website" class="form-label">
                                <i class="fas fa-globe text-primary"></i> الموقع الإلكتروني
                            </label>
                            <input type="url"
                                   name="website"
                                   id="website"
                                   class="form-control @error('website') is-invalid @enderror"
                                   value="{{ old('website', $medicalCenter->website) }}"
                                   placeholder="https://example.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- City -->
                        <div class="col-md-6">
                            <label for="city" class="form-label">
                                <i class="fas fa-city text-primary"></i> المدينة <span class="text-danger">*</span>
                            </label>
                            <select name="city" id="city" class="form-select @error('city') is-invalid @enderror" required>
                                <option value="">اختر المدينة</option>
                                @if(isset($citiesByRegion))
                                    @foreach($citiesByRegion as $regionName => $cities)
                                        <optgroup label="{{ $regionName }}">
                                            @foreach($cities as $city)
                                                <option value="{{ $city['name'] }}" {{ old('city', $medicalCenter->city) == $city['name'] ? 'selected' : '' }}>
                                                    {{ $city['name'] }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                @else
                                    <option value="" disabled>لا توجد مدن متاحة</option>
                                @endif
                            </select>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left text-primary"></i> وصف المركز
                            </label>
                            <textarea name="description"
                                      id="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="4"
                                      placeholder="اكتب وصفاً مختصراً عن المركز الطبي وخدماته">{{ old('description', $medicalCenter->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="col-md-6">
                            <label for="type" class="form-label">
                                <i class="fas fa-clinic-medical text-primary"></i> نوع المركز <span class="text-danger">*</span>
                            </label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">اختر نوع المركز</option>
                                <option value="1" {{ old('type', $medicalCenter->type) == '1' ? 'selected' : '' }}>مستشفى عام</option>
                                <option value="2" {{ old('type', $medicalCenter->type) == '2' ? 'selected' : '' }}>عيادة تخصصية</option>
                                <option value="3" {{ old('type', $medicalCenter->type) == '3' ? 'selected' : '' }}>مركز طبي</option>
                                <option value="4" {{ old('type', $medicalCenter->type) == '4' ? 'selected' : '' }}>مختبر طبي</option>
                                <option value="5" {{ old('type', $medicalCenter->type) == '5' ? 'selected' : '' }}>مركز أشعة</option>
                                <option value="6" {{ old('type', $medicalCenter->type) == '6' ? 'selected' : '' }}>مجمع أسنان</option>
                                <option value="7" {{ old('type', $medicalCenter->type) == '7' ? 'selected' : '' }}>مركز عيون</option>
                                <option value="8" {{ old('type', $medicalCenter->type) == '8' ? 'selected' : '' }}>بصريات</option>
                                <option value="9" {{ old('type', $medicalCenter->type) == '9' ? 'selected' : '' }}>صيدلية</option>
                                <option value="10" {{ old('type', $medicalCenter->type) == '10' ? 'selected' : '' }}>مركز حجامة</option>
                                <option value="11" {{ old('type', $medicalCenter->type) == '11' ? 'selected' : '' }}>مركز تجميل</option>
                                <option value="12" {{ old('type', $medicalCenter->type) == '12' ? 'selected' : '' }}>مركز ليزر</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Medical Service Types -->
                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-stethoscope text-primary"></i> أنواع الخدمات الطبية
                            </label>
                            <div class="row">
                                @php
                                    $medicalServiceTypes = [
                                        ['key' => 'dentistry', 'name' => 'الأسنان', 'icon' => 'fa-tooth'],
                                        ['key' => 'surgical-procedures', 'name' => 'العمليات الجراحية', 'icon' => 'fa-procedures'],
                                        ['key' => 'laboratory-tests', 'name' => 'التحاليل', 'icon' => 'fa-flask'],
                                        ['key' => 'ophthalmology', 'name' => 'العيون', 'icon' => 'fa-eye'],
                                        ['key' => 'check-ups', 'name' => 'الكشوفات', 'icon' => 'fa-clipboard-check'],
                                        ['key' => 'medications', 'name' => 'الادوية', 'icon' => 'fa-pills'],
                                        ['key' => 'emergency', 'name' => 'الطوارئ', 'icon' => 'fa-ambulance'],
                                        ['key' => 'dermatology', 'name' => 'الجلدية', 'icon' => 'fa-allergies'],
                                        ['key' => 'pharmacy', 'name' => 'الصيدلية', 'icon' => 'fa-prescription-bottle-alt'],
                                        ['key' => 'orthopedics', 'name' => 'العظام', 'icon' => 'fa-bone'],
                                        ['key' => 'clinics', 'name' => 'العيادات', 'icon' => 'fa-stethoscope'],
                                        ['key' => 'pregnancy-birth', 'name' => 'الحمل والولادة', 'icon' => 'fa-baby'],
                                        ['key' => 'lasik', 'name' => 'الليزك', 'icon' => 'fa-eye'],
                                        ['key' => 'radiology', 'name' => 'الأشعة', 'icon' => 'fa-x-ray'],
                                        ['key' => 'cosmetics', 'name' => 'التجميل', 'icon' => 'fa-magic'],
                                        ['key' => 'laboratory', 'name' => 'المختبر', 'icon' => 'fa-vial'],
                                        ['key' => 'hospitalization', 'name' => 'التنويم', 'icon' => 'fa-bed'],
                                        ['key' => 'other-services', 'name' => 'خدمات اخرى', 'icon' => 'fa-plus-circle'],
                                    ];
                                    $selectedServices = old('medical_service_types', $medicalCenter->medical_service_types ?? []);
                                @endphp
                                @foreach($medicalServiceTypes as $type)
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="medical_service_types[]" value="{{ $type['key'] }}" id="service_types_{{ $type['key'] }}" {{ in_array($type['key'], $selectedServices) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="service_types_{{ $type['key'] }}">
                                                <i class="fas {{ $type['icon'] }} me-2"></i>
                                                {{ $type['name'] }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('medical_service_types')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Medical Discounts -->
                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-percent text-primary"></i> الخصومات الطبية
                            </label>
                            <div id="discounts-list">
                                @php
                                    $discounts = old('discounts', $medicalCenter->medical_discounts ?? []);
                                @endphp
                                @if(empty($discounts))
                                    @php $discounts = [['service' => '', 'discount' => '']]; @endphp
                                @endif
                                @foreach($discounts as $i => $discount)
                                    <div class="row mb-2 discount-row">
                                        <div class="col-md-6">
                                            <input type="text" name="discounts[{{ $i }}][service]" class="form-control" placeholder="الخدمة" value="{{ $discount['service'] ?? '' }}">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" name="discounts[{{ $i }}][discount]" class="form-control" placeholder="القيمة/الخصم" value="{{ $discount['discount'] ?? '' }}">
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-discount" tabindex="-1"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="add-discount"><i class="fas fa-plus"></i> إضافة خصم</button>
                            @error('discounts')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="col-12">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marked-alt text-primary"></i> العنوان التفصيلي
                            </label>
                            <input type="text"
                                   name="address"
                                   id="address"
                                   class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address', $medicalCenter->address) }}"
                                   placeholder="اكتب العنوان التفصيلي للمركز الطبي">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on text-primary"></i> حالة المركز <span class="text-danger">*</span>
                            </label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $medicalCenter->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status', $medicalCenter->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                <option value="pending" {{ old('status', $medicalCenter->status) == 'pending' ? 'selected' : '' }}>في انتظار المراجعة</option>
                                <option value="suspended" {{ old('status', $medicalCenter->status) == 'suspended' ? 'selected' : '' }}>معلق</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contract Status -->
                        <div class="col-md-6">
                            <label for="contract_status" class="form-label">
                                <i class="fas fa-handshake text-primary"></i> حالة التعاقد
                            </label>
                            <select name="contract_status" id="contract_status" class="form-select @error('contract_status') is-invalid @enderror">
                                <option value="">اختر حالة التعاقد</option>
                                <option value="active" {{ old('contract_status', $medicalCenter->contract_status) == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="pending" {{ old('contract_status', $medicalCenter->contract_status) == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                                <option value="expired" {{ old('contract_status', $medicalCenter->contract_status) == 'expired' ? 'selected' : '' }}>منتهي</option>
                                <option value="suspended" {{ old('contract_status', $medicalCenter->contract_status) == 'suspended' ? 'selected' : '' }}>معلق</option>
                                <option value="terminated" {{ old('contract_status', $medicalCenter->contract_status) == 'terminated' ? 'selected' : '' }}>ملغي</option>
                            </select>
                            @error('contract_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contract Start Date -->
                        <div class="col-md-6">
                            <label for="contract_start_date" class="form-label">
                                <i class="fas fa-calendar-alt text-primary"></i> بداية التعاقد
                            </label>
                            <input type="date"
                                   name="contract_start_date"
                                   id="contract_start_date"
                                   class="form-control @error('contract_start_date') is-invalid @enderror"
                                   value="{{ old('contract_start_date', $medicalCenter->contract_start_date ? $medicalCenter->contract_start_date->format('Y-m-d') : '') }}">
                            @error('contract_start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contract End Date -->
                        <div class="col-md-6">
                            <label for="contract_end_date" class="form-label">
                                <i class="fas fa-calendar-times text-primary"></i> انتهاء التعاقد
                            </label>
                            <input type="date"
                                   name="contract_end_date"
                                   id="contract_end_date"
                                   class="form-control @error('contract_end_date') is-invalid @enderror"
                                   value="{{ old('contract_end_date', $medicalCenter->contract_end_date ? $medicalCenter->contract_end_date->format('Y-m-d') : '') }}">
                            @error('contract_end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12 mt-5">
                            <div class="border-top pt-4">
                                <div class="d-flex gap-3 justify-content-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i>
                                        <span>حفظ التغييرات</span>
                                    </button>
                                    <a href="{{ route('admin.medical-centers.index') }}" class="btn btn-outline-danger px-4">
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
        <!-- Logo Upload Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-image text-primary me-2"></i>
                    شعار المركز الطبي
                </h5>
            </div>
            <div class="card-body">
                <!-- Current Image Display -->
                @if($medicalCenter->image)
                <div class="mb-3" id="current-image-container">
                    <label class="form-label">
                        <i class="fas fa-image text-success"></i>
                        الصورة الحالية
                    </label>
                    <div class="border rounded p-3 bg-light">
                        <div class="text-center mb-3">
                            <img src="{{ $medicalCenter->image_url }}"
                                 alt="{{ $medicalCenter->name }}"
                                 class="img-fluid rounded shadow"
                                 style="max-height: 200px;"
                                 id="current-image"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div class="text-center text-muted" style="display: none;">
                                <div class="border rounded p-3 bg-light">
                                    <i class="fas fa-image fa-3x mb-2"></i>
                                    <p class="mb-0 small">خطأ في تحميل الصورة</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold">{{ basename($medicalCenter->image) }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-link me-1"></i>{{ $medicalCenter->image_url }}
                                </small>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm" id="remove-current-image">
                                <i class="fas fa-trash me-1"></i>إزالة الصورة
                            </button>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center text-muted mb-3" id="no-image-placeholder">
                    <div class="border rounded p-3 bg-light">
                        <i class="fas fa-image fa-3x mb-2"></i>
                        <p class="mb-0 small">لا يوجد شعار حالياً</p>
                    </div>
                </div>
                @endif

                <!-- New Image Upload Area -->
                <div class="mb-3">
                    <label for="image" class="form-label">
                        <i class="fas fa-upload text-primary"></i>
                        {{ $medicalCenter->image ? 'تغيير الشعار' : 'تحميل شعار المركز' }}
                    </label>

                    <!-- Drag & Drop Area -->
                    <div class="image-upload-area border-2 border-dashed rounded p-4 text-center position-relative"
                         id="image-upload-area"
                         style="border-color: #dee2e6; transition: all 0.3s ease;">

                        <input type="file"
                               name="image"
                               id="image"
                               class="form-control position-absolute w-100 h-100 opacity-0 @error('image') is-invalid @enderror"
                               accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/bmp"
                               style="top: 0; left: 0; cursor: pointer;">

                        <div id="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">اسحب الصورة هنا أو انقر للاختيار</h6>
                            <p class="text-muted small mb-0">JPEG, PNG, GIF, WebP, BMP - حتى 5 ميجابايت</p>
                        </div>

                        <div id="image-preview" style="display: none;">
                            <div class="position-relative d-inline-block">
                                <img id="preview-img" src="" alt="معاينة الصورة الجديدة" class="img-fluid rounded shadow" style="max-height: 200px;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle"
                                        id="remove-image" style="transform: translate(50%, -50%);">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="mt-3">
                                <div id="image-info" class="small text-muted"></div>
                                <div class="progress mt-2" id="upload-progress" style="display: none;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @error('image')
                        <div class="invalid-feedback d-block">
                            @if(is_array($message))
                                @foreach($message as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            @else
                                {{ $message }}
                            @endif
                        </div>
                    @enderror

                    <div class="form-text mt-2">
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
                    معلومات المركز
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <small class="text-muted">تاريخ الإنشاء:</small>
                        <div class="fw-bold">{{ $medicalCenter->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">آخر تحديث:</small>
                        <div class="fw-bold">{{ $medicalCenter->updated_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">الرابط المخصص:</small>
                        <div class="fw-bold">{{ $medicalCenter->slug }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // لا حاجة لمنطق المناطق والمدن لأن المدن تُعرض مباشرة من الخادم

        // Discounts management
        let discountIndex = {{ count($discounts ?? []) }};

        // Add discount
        document.getElementById('add-discount').addEventListener('click', function() {
            const discountsList = document.getElementById('discounts-list');
            const newDiscountRow = document.createElement('div');
            newDiscountRow.className = 'row mb-2 discount-row';
            newDiscountRow.innerHTML = `
                <div class="col-md-6">
                    <input type="text" name="discounts[${discountIndex}][service]" class="form-control" placeholder="الخدمة">
                </div>
                <div class="col-md-5">
                    <input type="text" name="discounts[${discountIndex}][discount]" class="form-control" placeholder="القيمة/الخصم">
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-discount" tabindex="-1"><i class="fas fa-trash"></i></button>
                </div>
            `;
            discountsList.appendChild(newDiscountRow);
            discountIndex++;
        });

        // Remove discount
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-discount')) {
                const discountRow = e.target.closest('.discount-row');
                if (document.querySelectorAll('.discount-row').length > 1) {
                    discountRow.remove();
                }
            }
        });

        // Auto-generate slug from name
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const generateSlugBtn = document.getElementById('generate-slug');
        const slugFeedback = document.getElementById('slug-feedback');

        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                const name = this.value;
                const slug = generateSlugFromName(name);
                slugInput.value = slug;

                if (slug) {
                    slugFeedback.style.display = 'block';
                    setTimeout(() => {
                        slugFeedback.style.display = 'none';
                    }, 2000);
                }
            });

            if (generateSlugBtn) {
                generateSlugBtn.addEventListener('click', function() {
                    const name = nameInput.value;
                    if (name) {
                        const slug = generateSlugFromName(name);
                        slugInput.value = slug;
                        slugFeedback.style.display = 'block';
                        setTimeout(() => {
                            slugFeedback.style.display = 'none';
                        }, 2000);
                    }
                });
            }
        }

        function generateSlugFromName(name) {
            return name.toLowerCase()
                .replace(/[أ-ي]/g, function(match) {
                    const arabicToEnglish = {
                        'أ': 'a', 'ب': 'b', 'ت': 't', 'ث': 'th', 'ج': 'j', 'ح': 'h', 'خ': 'kh',
                        'د': 'd', 'ذ': 'th', 'ر': 'r', 'ز': 'z', 'س': 's', 'ش': 'sh', 'ص': 's',
                        'ض': 'd', 'ط': 't', 'ظ': 'th', 'ع': 'a', 'غ': 'gh', 'ف': 'f', 'ق': 'q',
                        'ك': 'k', 'ل': 'l', 'م': 'm', 'ن': 'n', 'ه': 'h', 'و': 'w', 'ي': 'y'
                    };
                    return arabicToEnglish[match] || match;
                })
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }

        // Enhanced Image Upload with Drag & Drop
        const imageInput = document.getElementById('image');
        const uploadArea = document.getElementById('image-upload-area');
        const uploadPlaceholder = document.getElementById('upload-placeholder');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        const removeImageBtn = document.getElementById('remove-image');
        const imageInfo = document.getElementById('image-info');
        const currentImageContainer = document.getElementById('current-image-container');
        const removeCurrentImageBtn = document.getElementById('remove-current-image');

        if (imageInput && uploadArea) {
            // Drag & Drop Events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                uploadArea.style.borderColor = '#007bff';
                uploadArea.style.backgroundColor = '#f8f9fa';
            }

            function unhighlight(e) {
                uploadArea.style.borderColor = '#dee2e6';
                uploadArea.style.backgroundColor = 'transparent';
            }

            uploadArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    imageInput.files = files;
                    handleImageSelect(files[0]);
                }
            }

            // File Input Change
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    handleImageSelect(file);
                } else {
                    resetImagePreview();
                }
            });

            function handleImageSelect(file) {
                // التحقق من نوع الملف
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
                if (!allowedTypes.includes(file.type)) {
                    showAlert('نوع الملف غير مدعوم. يرجى اختيار صورة بصيغة JPEG, PNG, GIF, WebP, أو BMP', 'error');
                    resetImagePreview();
                    return;
                }

                // التحقق من حجم الملف (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showAlert('حجم الملف كبير جداً. الحد الأقصى 5 ميجابايت', 'error');
                    resetImagePreview();
                    return;
                }

                // عرض معلومات الملف
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                imageInfo.innerHTML = `
                    <i class="fas fa-file-image me-1"></i>
                    ${file.name} (${fileSize} ميجابايت)
                `;

                // قراءة الصورة وعرضها
                const reader = new FileReader();
                reader.onload = function(e) {
                    // إنشاء صورة للتحقق من الأبعاد
                    const img = new Image();
                    img.onload = function() {
                        previewImg.src = e.target.result;
                        uploadPlaceholder.style.display = 'none';
                        imagePreview.style.display = 'block';

                        // إضافة معلومات الأبعاد
                        imageInfo.innerHTML += `<br><i class="fas fa-expand-arrows-alt me-1"></i>${img.width} × ${img.height} بيكسل`;

                        // تحذير إذا كانت الأبعاد صغيرة جداً
                        if (img.width < 100 || img.height < 100) {
                            showAlert('أبعاد الصورة صغيرة جداً. الحد الأدنى المفضل 100×100 بيكسل', 'warning');
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            function resetImagePreview() {
                imageInput.value = '';
                uploadPlaceholder.style.display = 'block';
                imagePreview.style.display = 'none';
                imageInfo.innerHTML = '';
            }

            // إزالة الصورة الجديدة
            if (removeImageBtn) {
                removeImageBtn.addEventListener('click', function() {
                    resetImagePreview();
                });
            }

            // إزالة الصورة الحالية (إخفاؤها فقط)
            if (removeCurrentImageBtn) {
                removeCurrentImageBtn.addEventListener('click', function() {
                    if (confirm('هل أنت متأكد من إزالة الصورة الحالية؟')) {
                        currentImageContainer.style.display = 'none';
                        // تعيين قيمة الحقل المخفي لإشارة حذف الصورة
                        document.getElementById('remove_current_image_input').value = '1';
                        showAlert('سيتم حذف الصورة الحالية عند حفظ التغييرات', 'warning');
                    }
                });
            }

            function showAlert(message, type = 'info') {
                // إنشاء تنبيه مؤقت
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show mt-2`;
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                uploadArea.parentNode.insertBefore(alertDiv, uploadArea.nextSibling);

                // إزالة التنبيه تلقائياً بعد 5 ثوان
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
        }
    });
</script>
@endsection
