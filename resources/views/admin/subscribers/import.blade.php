@extends('layouts.admin')

@section('content-header', 'استيراد المشتركين والتابعين')
@section('content-subtitle', 'استيراد البيانات من ملفات Excel أو CSV')

@section('content')
<div class="container-fluid">
    <!-- عرض الأخطاء والرسائل -->
    @if(session('import_errors') && count(session('import_errors')) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        أخطاء الاستيراد
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>رقم الصف</th>
                                    <th>الأخطاء</th>
                                    <th>البيانات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(session('import_errors') as $error)
                                <tr>
                                    <td>{{ $error['row'] }}</td>
                                    <td>
                                        @foreach($error['errors'] as $err)
                                            <span class="badge bg-danger me-1">{{ $err }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ json_encode($error['data'], JSON_UNESCAPED_UNICODE) }}
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-upload me-2"></i>
                        استيراد البيانات
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subscribers.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        
                        <div class="row">
                            <!-- نوع الاستيراد -->
                            <div class="col-md-6 mb-3">
                                <label for="import_type" class="form-label">نوع البيانات المراد استيرادها</label>
                                <select name="import_type" id="import_type" class="form-select" required>
                                    <option value="">اختر نوع البيانات</option>
                                    <option value="subscribers">المشتركين</option>
                                    <option value="dependents">التابعين</option>
                                </select>
                                @error('import_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- خيار التحديث -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">خيارات الاستيراد</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="update_existing" id="update_existing" value="1">
                                    <label class="form-check-label" for="update_existing">
                                        تحديث البيانات الموجودة (إذا وُجد مشترك بنفس الجوال أو رقم الهوية)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- رفع الملف -->
                        <div class="mb-4">
                            <label for="import_file" class="form-label">اختر ملف الاستيراد</label>
                            <input type="file" name="import_file" id="import_file" class="form-control" 
                                   accept=".xlsx,.xls,.csv" required>
                            @error('import_file')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                الصيغ المدعومة: Excel (.xlsx, .xls) أو CSV (.csv) - الحد الأقصى: 10 ميجابايت
                            </div>
                        </div>

                        <!-- معاينة الملف -->
                        <div id="filePreview" class="mb-4" style="display: none;">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-file me-2"></i>معلومات الملف:</h6>
                                <div id="fileInfo"></div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                    <i class="fas fa-upload me-2"></i>
                                    بدء الاستيراد
                                </button>
                                <a href="{{ route('admin.subscribers.index') }}" class="btn btn-secondary btn-lg ms-2">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    العودة
                                </a>
                            </div>
                            <div class="text-muted">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    تأكد من صحة البيانات قبل الاستيراد
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- تحميل النماذج -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-download me-2"></i>
                        تحميل النماذج
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        قم بتحميل النماذج الجاهزة لضمان صحة تنسيق البيانات:
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.subscribers.download-template', ['type' => 'subscribers', 'format' => 'xlsx']) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-users me-2"></i>
                                    نموذج المشتركين (Excel)
                                </a>
                                <a href="{{ route('admin.subscribers.download-template', ['type' => 'subscribers', 'format' => 'csv']) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-users me-2"></i>
                                    نموذج المشتركين (CSV)
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.subscribers.download-template', ['type' => 'dependents', 'format' => 'xlsx']) }}" 
                                   class="btn btn-outline-success">
                                    <i class="fas fa-user-friends me-2"></i>
                                    نموذج التابعين (Excel)
                                </a>
                                <a href="{{ route('admin.subscribers.download-template', ['type' => 'dependents', 'format' => 'csv']) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-user-friends me-2"></i>
                                    نموذج التابعين (CSV)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إرشادات الاستيراد -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        إرشادات الاستيراد
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">
                                <i class="fas fa-users me-2"></i>
                                استيراد المشتركين
                            </h6>
                            <ul class="text-muted small">
                                <li>الحقول المطلوبة: الاسم، رقم الجوال، الجنسية، رقم الهوية، تاريخ البداية، تاريخ النهاية</li>
                                <li>يمكن إضافة التابعين في نفس الصف (مفصولة بفواصل)</li>
                                <li>سيتم توليد رقم البطاقة تلقائياً إذا لم يتم تحديده</li>
                                <li>البحث عن التطابق يتم بالجوال أو رقم الهوية</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">
                                <i class="fas fa-user-friends me-2"></i>
                                استيراد التابعين
                            </h6>
                            <ul class="text-muted small">
                                <li>الحقول المطلوبة: اسم التابع، جنسية التابع</li>
                                <li>يجب تحديد المشترك الأساسي (بالاسم، الجوال، رقم البطاقة، أو رقم الهوية)</li>
                                <li>سيتم ربط التابع بالمشترك المطابق</li>
                                <li>يمكن تحديث التابعين الموجودين</li>
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>تنبيهات مهمة:</strong>
                        <ul class="mb-0 mt-2">
                            <li>تأكد من صحة تنسيق التواريخ (YYYY-MM-DD)</li>
                            <li>تأكد من صحة أرقام الجوال (يفضل بدء بـ 05)</li>
                            <li>استخدم النماذج المتوفرة لضمان التوافق</li>
                            <li>قم بعمل نسخة احتياطية قبل الاستيراد</li>
                            <li>راجع الأخطاء بعناية وصحح البيانات إذا لزم الأمر</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('import_file');
    const filePreview = document.getElementById('filePreview');
    const fileInfo = document.getElementById('fileInfo');
    const form = document.getElementById('importForm');
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // معاينة الملف
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            const fileName = file.name;
            const fileType = file.type;
            
            fileInfo.innerHTML = `
                <strong>اسم الملف:</strong> ${fileName}<br>
                <strong>الحجم:</strong> ${fileSize} ميجابايت<br>
                <strong>النوع:</strong> ${fileType}
            `;
            filePreview.style.display = 'block';
        } else {
            filePreview.style.display = 'none';
        }
    });
    
    // معالجة إرسال النموذج
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الاستيراد...';
    });
});
</script>
@endpush
@endsection
