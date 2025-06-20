@extends('layouts.admin')

@section('title', 'استيراد العملاء المحتملين')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-file-import me-2"></i>
                            استيراد العملاء المحتملين
                        </h3>
                        <div>
                            <a href="{{ route('admin.potential-customers.download-template') }}" 
                               class="btn btn-outline-success btn-sm">
                                <i class="fas fa-download me-1"></i>
                                تحميل نموذج الاستيراد
                            </a>
                            <a href="{{ route('admin.potential-customers.index') }}" 
                               class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                العودة للقائمة
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- تعليمات الاستيراد -->
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>
                            تعليمات الاستيراد
                        </h5>
                        <ul class="mb-0">
                            <li>يجب أن يكون الملف بصيغة Excel (.xlsx, .xls) أو CSV (.csv)</li>
                            <li>الحد الأقصى لحجم الملف: 10 ميجابايت</li>
                            <li>يجب أن يحتوي الملف على الأعمدة التالية كحد أدنى: الاسم، رقم الجوال، المدينة</li>
                            <li>سيتم تجاهل السجلات المكررة (نفس رقم الجوال)</li>
                            <li>سيتم تحديد نوع الجهاز تلقائياً إذا لم يكن محدداً</li>
                            <li>يمكن استخدام الأسماء العربية أو الإنجليزية للأعمدة</li>
                            <li>إذا تم تحديد تاريخ الطلب، سيتم استخدامه كتاريخ إنشاء السجل، وإلا سيتم استخدام التاريخ الحالي</li>
                            <li>تنسيق تاريخ الطلب المدعوم: YYYY-MM-DD HH:MM:SS (مثال: 2024-01-15 10:30:00)</li>
                        </ul>
                    </div>

                    <!-- نموذج الاستيراد -->
                    <form action="{{ route('admin.potential-customers.import') }}" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          id="importForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="file" class="form-label">
                                        <i class="fas fa-file me-1"></i>
                                        اختر ملف الاستيراد
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('file') is-invalid @enderror" 
                                           id="file" 
                                           name="file" 
                                           accept=".xlsx,.xls,.csv"
                                           required>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        الصيغ المدعومة: Excel (.xlsx, .xls), CSV (.csv) - الحد الأقصى: 10MB
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary" id="importBtn">
                                            <i class="fas fa-upload me-1"></i>
                                            بدء الاستيراد
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- معاينة الملف -->
                    <div id="filePreview" class="mt-4" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-eye me-2"></i>
                                    معاينة الملف
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="previewContent">
                                    <!-- سيتم ملء المحتوى بـ JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- جدول الأعمدة المدعومة -->
                    <div class="mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-table me-2"></i>
                                    الأعمدة المدعومة
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>اسم العمود (عربي)</th>
                                                <th>اسم العمود (إنجليزي)</th>
                                                <th>مطلوب</th>
                                                <th>الوصف</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>الاسم</td>
                                                <td>name</td>
                                                <td><span class="badge bg-danger">مطلوب</span></td>
                                                <td>اسم العميل المحتمل</td>
                                            </tr>
                                            <tr>
                                                <td>رقم_الجوال</td>
                                                <td>phone</td>
                                                <td><span class="badge bg-danger">مطلوب</span></td>
                                                <td>رقم الجوال (يجب أن يكون فريد)</td>
                                            </tr>
                                            <tr>
                                                <td>المدينة</td>
                                                <td>city</td>
                                                <td><span class="badge bg-danger">مطلوب</span></td>
                                                <td>اسم المدينة</td>
                                            </tr>
                                            <tr>
                                                <td>البريد_الالكتروني</td>
                                                <td>email</td>
                                                <td><span class="badge bg-secondary">اختياري</span></td>
                                                <td>البريد الإلكتروني</td>
                                            </tr>
                                            <tr>
                                                <td>الحالة</td>
                                                <td>status</td>
                                                <td><span class="badge bg-secondary">اختياري</span></td>
                                                <td>حالة العميل (افتراضي: لم يتم التواصل)</td>
                                            </tr>
                                            <tr>
                                                <td>المصدر</td>
                                                <td>source</td>
                                                <td><span class="badge bg-secondary">اختياري</span></td>
                                                <td>مصدر العميل</td>
                                            </tr>
                                            <tr>
                                                <td>نوع_الجهاز</td>
                                                <td>device_type</td>
                                                <td><span class="badge bg-secondary">اختياري</span></td>
                                                <td>نوع الجهاز (mobile, tablet, desktop)</td>
                                            </tr>
                                            <tr>
                                                <td>عنوان_ip</td>
                                                <td>ip_address</td>
                                                <td><span class="badge bg-secondary">اختياري</span></td>
                                                <td>عنوان IP</td>
                                            </tr>
                                            <tr>
                                                <td>رابط_الاحالة</td>
                                                <td>referrer_url</td>
                                                <td><span class="badge bg-secondary">اختياري</span></td>
                                                <td>رابط الإحالة</td>
                                            </tr>
                                            <tr>
                                                <td>ملخص_المكالمة</td>
                                                <td>call_summary</td>
                                                <td><span class="badge bg-secondary">اختياري</span></td>
                                                <td>ملخص المكالمة أو الملاحظات</td>
                                            </tr>
                                            <tr>
                                                <td>تاريخ_الطلب</td>
                                                <td>request_date</td>
                                                <td><span class="badge bg-secondary">اختياري</span></td>
                                                <td>تاريخ ووقت الطلب (مثال: 2024-01-15 10:30:00)</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const importForm = document.getElementById('importForm');
    const importBtn = document.getElementById('importBtn');
    const filePreview = document.getElementById('filePreview');

    // معالجة تغيير الملف
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            showFileInfo(file);
        } else {
            filePreview.style.display = 'none';
        }
    });

    // معالجة إرسال النموذج
    importForm.addEventListener('submit', function(e) {
        const file = fileInput.files[0];
        if (!file) {
            e.preventDefault();
            alert('يرجى اختيار ملف للاستيراد');
            return;
        }

        // إظهار حالة التحميل
        importBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الاستيراد...';
        importBtn.disabled = true;
    });

    function showFileInfo(file) {
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const fileType = file.type || 'غير محدد';
        
        document.getElementById('previewContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <strong>اسم الملف:</strong> ${file.name}
                </div>
                <div class="col-md-3">
                    <strong>الحجم:</strong> ${fileSize} MB
                </div>
                <div class="col-md-3">
                    <strong>النوع:</strong> ${fileType}
                </div>
            </div>
            <div class="mt-2">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    الملف جاهز للاستيراد
                </div>
            </div>
        `;
        
        filePreview.style.display = 'block';
    }
});
</script>
@endpush

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.alert {
    border-radius: 0.5rem;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
}
</style>
@endpush

@endsection
