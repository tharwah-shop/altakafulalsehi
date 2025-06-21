@extends('admin.layouts.app')

@section('title', 'استيراد المراكز الطبية من CSV')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">استيراد المراكز الطبية من CSV</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.medical-centers.index') }}">المراكز الطبية</a></li>
                    <li class="breadcrumb-item active">استيراد CSV</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.medical-centers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة
            </a>
        </div>
    </div>

    <!-- Import Instructions -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-upload me-2"></i>استيراد ملف CSV
                    </h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.medical-centers.import-csv') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="csv_file" class="form-label">اختر ملف CSV</label>
                            <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv,.txt" required>
                            <div class="form-text">الملفات المدعومة: .csv, .txt (حد أقصى 10 ميجابايت)</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>استيراد البيانات
                            </button>
                            <a href="{{ route('admin.medical-centers.download-csv-template') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-download me-2"></i>تحميل قالب CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Import Errors -->
            @if(session('import_errors'))
                <div class="card shadow">
                    <div class="card-header py-3 bg-warning">
                        <h6 class="m-0 font-weight-bold text-dark">
                            <i class="fas fa-exclamation-triangle me-2"></i>تفاصيل الأخطاء
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>رقم الصف</th>
                                        <th>الخطأ</th>
                                        <th>البيانات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(session('import_errors') as $error)
                                        <tr>
                                            <td>{{ $error['row'] }}</td>
                                            <td class="text-danger">{{ $error['error'] }}</td>
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
            @endif
        </div>

        <!-- Instructions -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>تعليمات الاستيراد
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="text-primary">الحقول المطلوبة:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>اسم المركز</li>
                        <li><i class="fas fa-check text-success me-2"></i>المنطقة</li>
                        <li><i class="fas fa-check text-success me-2"></i>رقم المدينة (1-51)</li>
                        <li><i class="fas fa-check text-success me-2"></i>نوع المركز (1-12)</li>
                    </ul>

                    <h6 class="text-primary mt-4">أنواع المراكز:</h6>
                    <ul class="list-unstyled small">
                        <li>1 - مستشفى</li>
                        <li>2 - عيادة</li>
                        <li>3 - صيدلية</li>
                        <li>4 - مختبر</li>
                        <li>5 - أشعة</li>
                        <li>6 - أسنان</li>
                        <li>7 - بصريات</li>
                        <li>8 - علاج طبيعي</li>
                        <li>9-12 - أخرى</li>
                    </ul>

                    <h6 class="text-primary mt-4">حالات المركز:</h6>
                    <ul class="list-unstyled small">
                        <li>active - نشط</li>
                        <li>inactive - غير نشط</li>
                        <li>pending - معلق</li>
                        <li>suspended - موقوف</li>
                    </ul>

                    <div class="alert alert-info mt-4">
                        <small>
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>نصيحة:</strong> قم بتحميل قالب CSV أولاً لمعرفة التنسيق الصحيح للبيانات.
                        </small>
                    </div>

                    <div class="alert alert-warning mt-3">
                        <small>
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>تنبيه:</strong> تأكد من صحة البيانات قبل الاستيراد. البيانات المكررة سيتم تحديثها تلقائياً.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File input validation
    const fileInput = document.getElementById('csv_file');
    const form = fileInput.closest('form');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Check file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('حجم الملف كبير جداً. الحد الأقصى 10 ميجابايت.');
                this.value = '';
                return;
            }

            // Check file type
            const allowedTypes = ['.csv', '.txt'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            if (!allowedTypes.includes(fileExtension)) {
                alert('نوع الملف غير مدعوم. يرجى اختيار ملف CSV.');
                this.value = '';
                return;
            }
        }
    });
    
    // Form submission loading
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الاستيراد...';
        submitBtn.disabled = true;
    });
});
</script>
@endpush
