@extends('layouts.admin')

@section('content-header', 'تصدير المشتركين والتابعين')
@section('content-subtitle', 'تصدير البيانات بصيغ مختلفة مع خيارات الفلترة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-download me-2"></i>
                        تصدير البيانات
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subscribers.export') }}" method="GET" id="exportForm">
                        <div class="row">
                            <!-- نوع التصدير -->
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">نوع البيانات المراد تصديرها</label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="subscribers">المشتركين فقط</option>
                                    <option value="dependents">التابعين فقط</option>
                                    <option value="combined">المشتركين مع التابعين (مدمج)</option>
                                </select>
                            </div>

                            <!-- صيغة الملف -->
                            <div class="col-md-6 mb-3">
                                <label for="format" class="form-label">صيغة الملف</label>
                                <select name="format" id="format" class="form-select" required>
                                    <option value="xlsx">Excel (.xlsx)</option>
                                    <option value="csv">CSV (.csv)</option>
                                    <option value="xls">Excel القديم (.xls)</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">
                            <i class="fas fa-filter me-2"></i>
                            خيارات الفلترة (اختيارية)
                        </h5>

                        <div class="row">
                            <!-- البحث -->
                            <div class="col-md-6 mb-3">
                                <label for="search" class="form-label">البحث</label>
                                <input type="text" name="search" id="search" class="form-control" 
                                       placeholder="البحث في الاسم، الجوال، البريد، رقم البطاقة، رقم الهوية">
                            </div>

                            <!-- الجنسية -->
                            <div class="col-md-6 mb-3">
                                <label for="nationality" class="form-label">الجنسية</label>
                                <select name="nationality" id="nationality" class="form-select">
                                    <option value="">جميع الجنسيات</option>
                                    @foreach($nationalities as $nationality)
                                        <option value="{{ $nationality }}">{{ $nationality }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- الحالة -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">حالة المشترك</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">جميع الحالات</option>
                                    <option value="فعال">فعال</option>
                                    <option value="منتهي">منتهي</option>
                                    <option value="ملغي">ملغي</option>
                                    <option value="معلق">معلق</option>
                                    <option value="بانتظار الدفع">بانتظار الدفع</option>
                                    <option value="في انتظار التحقق من الدفع">في انتظار التحقق من الدفع</option>
                                    <option value="معلق - مشكلة في الدفع">معلق - مشكلة في الدفع</option>
                                </select>
                            </div>

                            <!-- الباقة -->
                            <div class="col-md-6 mb-3">
                                <label for="package_id" class="form-label">الباقة</label>
                                <select name="package_id" id="package_id" class="form-select">
                                    <option value="">جميع الباقات</option>
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- المدينة -->
                            <div class="col-md-6 mb-3">
                                <label for="city_id" class="form-label">المدينة</label>
                                <select name="city_id" id="city_id" class="form-select">
                                    <option value="">جميع المدن</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- تاريخ البداية -->
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">من تاريخ</label>
                                <input type="date" name="start_date" id="start_date" class="form-control">
                            </div>

                            <!-- تاريخ النهاية -->
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">إلى تاريخ</label>
                                <input type="date" name="end_date" id="end_date" class="form-control">
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-download me-2"></i>
                                    تصدير البيانات
                                </button>
                                <a href="{{ route('admin.subscribers.index') }}" class="btn btn-secondary btn-lg ms-2">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    العودة
                                </a>
                            </div>
                            <div class="text-muted">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    سيتم تحميل الملف تلقائياً بعد التصدير
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات إضافية -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات مهمة حول التصدير
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-primary">
                                <i class="fas fa-users me-2"></i>
                                المشتركين فقط
                            </h6>
                            <p class="text-muted small">
                                يشمل جميع بيانات المشتركين الأساسية مع ملخص التابعين في أعمدة منفصلة.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-success">
                                <i class="fas fa-user-friends me-2"></i>
                                التابعين فقط
                            </h6>
                            <p class="text-muted small">
                                يشمل جميع بيانات التابعين مع معلومات المشترك الأساسي المرتبط بهم.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-warning">
                                <i class="fas fa-layer-group me-2"></i>
                                مدمج
                            </h6>
                            <p class="text-muted small">
                                صف منفصل لكل مشترك وتابع، مع تكرار بيانات المشترك لكل تابع.
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>نصائح:</strong>
                        <ul class="mb-0 mt-2">
                            <li>استخدم صيغة Excel (.xlsx) للحصول على أفضل تنسيق وإمكانيات متقدمة</li>
                            <li>استخدم صيغة CSV للتوافق مع أنظمة أخرى أو للملفات الكبيرة</li>
                            <li>يمكن استخدام الفلاتر لتصدير مجموعة محددة من البيانات فقط</li>
                            <li>التصدير المدمج مفيد للتحليل الشامل ولكنه ينتج ملفات أكبر</li>
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
    const form = document.getElementById('exportForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التصدير...';
        
        // إعادة تفعيل الزر بعد 5 ثوان
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }, 5000);
    });
});
</script>
@endpush
@endsection
