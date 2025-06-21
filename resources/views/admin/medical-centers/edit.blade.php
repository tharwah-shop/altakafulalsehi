@extends('layouts.admin')

@section('title', 'تعديل مركز طبي')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none">لوحة التحكم</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.medical-centers.index') }}" class="text-decoration-none">المراكز الطبية</a></li>
                            <li class="breadcrumb-item active">تعديل مركز</li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-0 fw-bold">
                        <i class="fas fa-edit text-primary me-2"></i>
                        تعديل مركز طبي: {{ $medicalCenter->name }}
                    </h1>
                </div>
                <a href="{{ route('admin.medical-centers.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <form action="{{ route('admin.medical-centers.update', $medicalCenter->id) }}" method="POST" enctype="multipart/form-data" id="medicalCenterForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="remove_current_image" id="remove_current_image_input" value="0">

        <div class="row g-4">
            <!-- Left Column - Main Content -->
            <div class="col-lg-8">
                <!-- Basic Information Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            المعلومات الأساسية
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fas fa-hospital text-primary me-1"></i>
                                    اسم المركز الطبي
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $medicalCenter->name) }}"
                                       placeholder="أدخل اسم المركز الطبي"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div class="col-md-6">
                                <label for="slug" class="form-label fw-semibold">
                                    <i class="fas fa-link text-primary me-1"></i>
                                    الرابط المخصص
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
                                    <button type="button" class="btn btn-outline-primary" id="generate-slug">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">سيتم تحديثه تلقائياً عند تغيير اسم المركز</div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">
                                    <i class="fas fa-phone text-primary me-1"></i>
                                    رقم الهاتف
                                </label>
                                <input type="text"
                                       name="phone"
                                       id="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $medicalCenter->phone) }}"
                                       placeholder="مثال: 966501234567">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope text-primary me-1"></i>
                                    البريد الإلكتروني
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

                            <!-- City -->
                            <div class="col-md-6">
                                <label for="city" class="form-label fw-semibold">
                                    <i class="fas fa-city text-primary me-1"></i>
                                    المدينة
                                    <span class="text-danger">*</span>
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

                            <!-- Type -->
                            <div class="col-md-6">
                                <label for="type" class="form-label fw-semibold">
                                    <i class="fas fa-clinic-medical text-primary me-1"></i>
                                    نوع المركز
                                    <span class="text-danger">*</span>
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

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">
                                    <i class="fas fa-align-left text-primary me-1"></i>
                                    وصف المركز
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
                        </div>
                    </div>
                </div>

                <!-- Services & Discounts Card -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-stethoscope me-2"></i>
                            الخدمات والخصومات
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Medical Service Types -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-3">
                                <i class="fas fa-medical-kit text-info me-1"></i>
                                أنواع الخدمات الطبية
                            </label>
                            <div class="row g-2">
                                @php
                                    $medicalServiceTypes = [
                                        ['key' => 'dentistry', 'name' => 'الأسنان', 'icon' => 'fa-tooth'],
                                        ['key' => 'surgical-procedures', 'name' => 'العمليات الجراحية', 'icon' => 'fa-procedures'],
                                        ['key' => 'laboratory-tests', 'name' => 'التحاليل', 'icon' => 'fa-flask'],
                                        ['key' => 'ophthalmology', 'name' => 'العيون', 'icon' => 'fa-eye'],
                                        ['key' => 'check-ups', 'name' => 'الكشوفات', 'icon' => 'fa-clipboard-check'],
                                        ['key' => 'medications', 'name' => 'الأدوية', 'icon' => 'fa-pills'],
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
                                        ['key' => 'other-services', 'name' => 'خدمات أخرى', 'icon' => 'fa-plus-circle'],
                                    ];
                                    $selectedServices = old('medical_service_types', $medicalCenter->medical_service_types ?? []);
                                @endphp
                                @foreach($medicalServiceTypes as $type)
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="medical_service_types[]" value="{{ $type['key'] }}" id="service_types_{{ $type['key'] }}" {{ in_array($type['key'], $selectedServices) ? 'checked' : '' }}>
                                            <label class="form-check-label d-flex align-items-center" for="service_types_{{ $type['key'] }}">
                                                <i class="fas {{ $type['icon'] }} text-primary me-2"></i>
                                                {{ $type['name'] }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('medical_service_types')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Medical Discounts -->
                        <div>
                            <label class="form-label fw-semibold mb-3">
                                <i class="fas fa-percent text-success me-1"></i>
                                الخصومات الطبية
                            </label>
                            <div id="discounts-list" class="border rounded p-3 bg-light">
                                @php
                                    $discounts = old('discounts', $medicalCenter->medical_discounts ?? []);
                                @endphp
                                @if(empty($discounts))
                                    @php $discounts = [['service' => '', 'discount' => '']]; @endphp
                                @endif
                                @foreach($discounts as $i => $discount)
                                    <div class="row mb-3 discount-row align-items-center">
                                        <div class="col-md-5">
                                            <input type="text" name="discounts[{{ $i }}][service]" class="form-control" placeholder="اسم الخدمة" value="{{ $discount['service'] ?? '' }}">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" name="discounts[{{ $i }}][discount]" class="form-control" placeholder="نسبة أو قيمة الخصم" value="{{ $discount['discount'] ?? '' }}">
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-discount">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-success btn-sm" id="add-discount">
                                    <i class="fas fa-plus me-1"></i> إضافة خصم جديد
                                </button>
                            </div>
                            @error('discounts')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact & Contract Information Card -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-address-book me-2"></i>
                            معلومات الاتصال والتعاقد
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Address -->
                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold">
                                    <i class="fas fa-map-marked-alt text-warning me-1"></i>
                                    العنوان التفصيلي
                                </label>
                                <input type="text"
                                       name="address"
                                       id="address"
                                       class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address', $medicalCenter->address) }}"
                                       placeholder="أدخل العنوان التفصيلي للمركز الطبي">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Website -->
                            <div class="col-md-6">
                                <label for="website" class="form-label fw-semibold">
                                    <i class="fas fa-globe text-warning me-1"></i>
                                    الموقع الإلكتروني
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

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="fas fa-toggle-on text-warning me-1"></i>
                                    حالة المركز
                                    <span class="text-danger">*</span>
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
                                <label for="contract_status" class="form-label fw-semibold">
                                    <i class="fas fa-handshake text-warning me-1"></i>
                                    حالة التعاقد
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
                                <label for="contract_start_date" class="form-label fw-semibold">
                                    <i class="fas fa-calendar-alt text-warning me-1"></i>
                                    بداية التعاقد
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
                                <label for="contract_end_date" class="form-label fw-semibold">
                                    <i class="fas fa-calendar-times text-warning me-1"></i>
                                    انتهاء التعاقد
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="col-lg-4">
                <!-- Image Upload Card -->
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-image me-2"></i>
                            شعار المركز الطبي
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Current Image Display -->
                        @if($medicalCenter->image)
                        <div class="mb-4" id="current-image-container">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-image text-success me-1"></i>
                                الصورة الحالية
                            </label>
                            <div class="border rounded p-3 bg-light">
                                <div class="text-center mb-3">
                                    <img src="{{ $medicalCenter->image_url }}"
                                         alt="{{ $medicalCenter->name }}"
                                         class="img-fluid rounded shadow"
                                         style="max-height: 150px;"
                                         id="current-image">
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small">{{ basename($medicalCenter->image) }}</div>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $medicalCenter->updated_at->format('Y-m-d') }}
                                        </small>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm" id="remove-current-image">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="text-center text-muted mb-4" id="no-image-placeholder">
                            <div class="border rounded p-4 bg-light">
                                <i class="fas fa-image fa-3x mb-2 opacity-50"></i>
                                <p class="mb-0 small">لا يوجد شعار حالياً</p>
                            </div>
                        </div>
                        @endif

                        <!-- New Image Upload Area -->
                        <div class="mb-3">
                            <label for="image" class="form-label fw-semibold">
                                <i class="fas fa-upload text-secondary me-1"></i>
                                {{ $medicalCenter->image ? 'تغيير الشعار' : 'تحميل شعار المركز' }}
                            </label>

                            <div class="upload-zone border-2 border-dashed rounded p-4 text-center bg-light"
                                 id="image-upload-area"
                                 style="min-height: 200px;">

                                <input type="file"
                                       name="image"
                                       id="image"
                                       class="form-control position-absolute w-100 h-100 opacity-0 @error('image') is-invalid @enderror"
                                       accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/bmp"
                                       style="top: 0; left: 0; cursor: pointer; z-index: 2;">

                                <div id="upload-placeholder" class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <div class="upload-icon mb-3">
                                        <i class="fas fa-cloud-upload-alt fa-4x text-secondary opacity-50"></i>
                                    </div>
                                    <h6 class="text-dark fw-semibold mb-2">اسحب الصورة هنا أو انقر للاختيار</h6>
                                    <p class="text-muted small mb-2">JPEG, PNG, GIF, WebP, BMP</p>
                                    <div class="badge bg-info text-dark">
                                        <i class="fas fa-info-circle me-1"></i>
                                        حد أقصى: 5 ميجابايت
                                    </div>
                                </div>

                                <div id="image-preview" style="display: none;" class="h-100 d-flex flex-column align-items-center justify-content-center">
                                    <div class="position-relative mb-3">
                                        <img id="preview-img" src="" alt="معاينة الصورة الجديدة" class="img-fluid rounded shadow" style="max-height: 150px; max-width: 100%;">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle"
                                                id="remove-image" style="transform: translate(50%, -50%); z-index: 3;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="text-center">
                                        <div id="image-info" class="small text-muted mb-1"></div>
                                        <div id="image-dimensions" class="small text-info" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>

                            @error('image')
                                <div class="invalid-feedback d-block mt-2">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text mt-3">
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="fas fa-lightbulb me-2 text-warning"></i>
                                    <span>للحصول على أفضل جودة، استخدم صورة بأبعاد 800×600 بيكسل أو أكبر</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            معلومات المركز
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <small class="text-muted">تاريخ الإنشاء:</small>
                                <div class="fw-semibold">{{ $medicalCenter->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">آخر تحديث:</small>
                                <div class="fw-semibold">{{ $medicalCenter->updated_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">الرابط المخصص:</small>
                                <div class="fw-semibold text-break">{{ $medicalCenter->slug }}</div>
                            </div>
                            @if($medicalCenter->views_count)
                            <div class="col-12">
                                <small class="text-muted">عدد المشاهدات:</small>
                                <div class="fw-semibold">{{ number_format($medicalCenter->views_count) }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-3 justify-content-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save me-2"></i>
                                حفظ التغييرات
                            </button>
                            <button type="reset" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-redo me-2"></i>
                                إعادة تعيين
                            </button>
                            <a href="{{ route('admin.medical-centers.index') }}" class="btn btn-outline-danger btn-lg px-4">
                                <i class="fas fa-times me-2"></i>
                                إلغاء
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('styles')
<style>
.upload-zone {
    transition: all 0.3s ease;
    position: relative;
}

.upload-zone:hover {
    border-color: var(--bs-primary) !important;
    background-color: var(--bs-primary-bg-subtle) !important;
}

.upload-zone:hover .upload-icon i {
    color: var(--bs-primary) !important;
    transform: scale(1.1);
}

.discount-row {
    background: white;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #e9ecef;
}

.form-check-input:checked + .form-check-label {
    color: var(--bs-primary);
    font-weight: 500;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Slug Generation
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const generateSlugBtn = document.getElementById('generate-slug');

    function generateSlug(text) {
        return text
            .toLowerCase()
            .replace(/[\u0600-\u06FF\u0750-\u077F]/g, function(match) {
                const arabicToEnglish = {
                    'ا': 'a', 'ب': 'b', 'ت': 't', 'ث': 'th', 'ج': 'j', 'ح': 'h', 'خ': 'kh',
                    'د': 'd', 'ذ': 'dh', 'ر': 'r', 'ز': 'z', 'س': 's', 'ش': 'sh', 'ص': 's',
                    'ض': 'd', 'ط': 't', 'ظ': 'z', 'ع': 'a', 'غ': 'gh', 'ف': 'f', 'ق': 'q',
                    'ك': 'k', 'ل': 'l', 'م': 'm', 'ن': 'n', 'ه': 'h', 'و': 'w', 'ي': 'y',
                    'ى': 'a', 'ة': 'h', 'أ': 'a', 'إ': 'i', 'آ': 'a', 'ؤ': 'o', 'ئ': 'e'
                };
                return arabicToEnglish[match] || match;
            })
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
    }

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            if (!slugInput.dataset.manual) {
                slugInput.value = generateSlug(this.value);
            }
        });

        slugInput.addEventListener('input', function() {
            this.dataset.manual = 'true';
        });

        if (generateSlugBtn) {
            generateSlugBtn.addEventListener('click', function() {
                slugInput.value = generateSlug(nameInput.value);
                slugInput.dataset.manual = 'false';
            });
        }
    }

    // Image Upload
    const imageInput = document.getElementById('image');
    const uploadArea = document.getElementById('image-upload-area');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const imageInfo = document.getElementById('image-info');
    const removeImageBtn = document.getElementById('remove-image');
    const removeCurrentImageBtn = document.getElementById('remove-current-image');
    const removeCurrentImageInput = document.getElementById('remove_current_image_input');

    if (imageInput && uploadArea) {
        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-primary');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-primary');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-primary');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                imageInput.files = files;
                handleImagePreview(files[0]);
            }
        });

        // File input change
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                handleImagePreview(this.files[0]);
            }
        });

        // Remove image
        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', function() {
                imageInput.value = '';
                showUploadPlaceholder();
            });
        }

        // Remove current image
        if (removeCurrentImageBtn && removeCurrentImageInput) {
            removeCurrentImageBtn.addEventListener('click', function() {
                if (confirm('هل أنت متأكد من حذف الصورة الحالية؟')) {
                    removeCurrentImageInput.value = '1';
                    document.getElementById('current-image-container').style.display = 'none';
                    document.getElementById('no-image-placeholder').style.display = 'block';
                }
            });
        }
    }

    function handleImagePreview(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imageInfo.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                showImagePreview();
            };
            reader.readAsDataURL(file);
        }
    }

    function showImagePreview() {
        if (uploadPlaceholder && imagePreview) {
            uploadPlaceholder.style.display = 'none';
            imagePreview.style.display = 'flex';
        }
    }

    function showUploadPlaceholder() {
        if (uploadPlaceholder && imagePreview) {
            uploadPlaceholder.style.display = 'flex';
            imagePreview.style.display = 'none';
        }
    }

    // Discounts Management
    const discountsList = document.getElementById('discounts-list');
    const addDiscountBtn = document.getElementById('add-discount');

    if (addDiscountBtn && discountsList) {
        addDiscountBtn.addEventListener('click', function() {
            const discountRows = discountsList.querySelectorAll('.discount-row');
            const newIndex = discountRows.length;

            const newRow = document.createElement('div');
            newRow.className = 'row mb-3 discount-row align-items-center';
            newRow.innerHTML = `
                <div class="col-md-5">
                    <input type="text" name="discounts[${newIndex}][service]" class="form-control" placeholder="اسم الخدمة">
                </div>
                <div class="col-md-5">
                    <input type="text" name="discounts[${newIndex}][discount]" class="form-control" placeholder="نسبة أو قيمة الخصم">
                </div>
                <div class="col-md-2 text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-discount">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

            discountsList.appendChild(newRow);
        });

        // Remove discount
        discountsList.addEventListener('click', function(e) {
            if (e.target.closest('.remove-discount')) {
                const row = e.target.closest('.discount-row');
                if (discountsList.querySelectorAll('.discount-row').length > 1) {
                    row.remove();
                } else {
                    // Clear inputs instead of removing the last row
                    row.querySelectorAll('input').forEach(input => input.value = '');
                }
            }
        });
    }
});
</script>
@endpush
