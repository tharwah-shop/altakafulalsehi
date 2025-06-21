<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار استيراد بطاقات التأمين</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-upload me-2"></i>
                            اختبار استيراد ملف بطاقات التأمين
                        </h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(session('import_report'))
                            @php $report = session('import_report'); @endphp
                            <div class="alert alert-info">
                                <h5><i class="fas fa-chart-bar me-2"></i>تقرير الاستيراد:</h5>
                                <ul class="mb-0">
                                    <li><strong>إجمالي السجلات المعالجة:</strong> {{ $report['total_processed'] }}</li>
                                    <li><strong>مشتركين جدد:</strong> {{ $report['imported'] }}</li>
                                    <li><strong>مشتركين محدثين:</strong> {{ $report['updated'] }}</li>
                                    <li><strong>أخطاء:</strong> {{ $report['errors'] }}</li>
                                </ul>
                                
                                @if($report['errors'] > 0 && !empty($report['error_details']))
                                <hr>
                                <h6>تفاصيل الأخطاء:</h6>
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
                                            @foreach($report['error_details'] as $error)
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
                                @endif
                            </div>
                        @endif

                        <form action="{{ route('admin.subscribers.import.custom') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="import_file" class="form-label">اختر ملف بطاقات التأمين</label>
                                <input type="file" name="import_file" id="import_file" class="form-control" 
                                       accept=".xlsx,.xls,.csv" required>
                                @error('import_file')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    الصيغ المدعومة: Excel (.xlsx, .xls) أو CSV (.csv) - الحد الأقصى: 10 ميجابايت
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>تنسيق الملف المطلوب:</h6>
                                <p class="mb-2">يجب أن يحتوي الملف على الأعمدة التالية:</p>
                                <ul class="mb-0">
                                    <li><code>name</code> - اسم المشترك</li>
                                    <li><code>phone</code> - رقم الجوال</li>
                                    <li><code>email</code> - البريد الإلكتروني (اختياري)</li>
                                    <li><code>id_number</code> - رقم الهوية/الإقامة</li>
                                    <li><code>nationality</code> - الجنسية</li>
                                    <li><code>start_date</code> - تاريخ البداية (DD/MM/YYYY)</li>
                                    <li><code>end_date</code> - تاريخ النهاية (DD/MM/YYYY)</li>
                                    <li><code>card_price</code> - سعر البطاقة</li>
                                    <li><code>status</code> - حالة الاشتراك</li>
                                    <li><code>created_at</code> - تاريخ الإنشاء (DD/MM/YYYY)</li>
                                </ul>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-upload me-2"></i>
                                    استيراد ملف بطاقات التأمين
                                </button>
                            </div>
                        </form>

                        <hr>

                        <div class="text-center">
                            <h6>ملف تجريبي للاختبار:</h6>
                            <p class="text-muted">يمكنك تحميل ملف CSV تجريبي لاختبار الاستيراد</p>
                            <a href="{{ asset('storage/test-import.csv') }}" class="btn btn-outline-secondary" download>
                                <i class="fas fa-download me-2"></i>
                                تحميل ملف تجريبي
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
