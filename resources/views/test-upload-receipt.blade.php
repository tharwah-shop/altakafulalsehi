<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار رفع إيصال التحويل</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-upload me-2"></i>
                            اختبار رفع إيصال التحويل
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- معلومات الاختبار -->
                        <div class="alert alert-info">
                            <h6 class="fw-bold">معلومات الاختبار:</h6>
                            <ul class="mb-0">
                                <li>سيتم اختبار رفع إيصال للدفع رقم 6</li>
                                <li>يمكنك رفع أي صورة أو ملف PDF</li>
                                <li>سيتم حفظ البيانات في قاعدة البيانات</li>
                                <li>سيتم إنشاء مشترك جديد بعد التأكيد</li>
                            </ul>
                        </div>

                        <!-- نموذج رفع الإيصال -->
                        <form action="{{ route('payment.bank-transfer.confirm', 6) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="payment_id" value="6">
                            <input type="hidden" name="bank_name" value="مصرف الراجحي">

                            <!-- عرض رسائل الخطأ -->
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <h6 class="fw-bold mb-2">يرجى تصحيح الأخطاء التالية:</h6>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- عرض رسائل النجاح -->
                            @if(session('success'))
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- عرض رسائل الخطأ -->
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">المبلغ المحول</label>
                                    <input type="number" step="0.01" class="form-control @error('transfer_amount') is-invalid @enderror"
                                           name="transfer_amount" value="{{ old('transfer_amount', '199.00') }}" required>
                                    @error('transfer_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">اسم المرسل</label>
                                    <input type="text" class="form-control @error('sender_name') is-invalid @enderror"
                                           name="sender_name" value="{{ old('sender_name', 'اختبار المرسل') }}" 
                                           placeholder="اسم صاحب الحساب المحول منه" required>
                                    @error('sender_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">ملاحظات إضافية (اختياري)</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              name="notes" rows="3"
                                              placeholder="أي معلومات إضافية تريد إضافتها...">{{ old('notes', 'اختبار رفع إيصال التحويل البنكي') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">إيصال التحويل <span class="text-danger">*</span></label>
                                    <div class="border border-2 border-dashed rounded p-4 text-center bg-light @error('receipt_file') border-danger @enderror"
                                         id="upload-area" style="cursor: pointer; transition: all 0.3s ease;">
                                        <input type="file" id="receipt_file" name="receipt_file"
                                               accept="image/*,.pdf" style="display: none;" required>
                                        <div id="upload-content">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <h5 class="fw-bold text-dark">اسحب وأفلت الملف هنا</h5>
                                            <p class="text-muted mb-2">أو اضغط لاختيار ملف (صورة أو PDF)</p>
                                            <small class="text-muted">الحد الأقصى: 5 ميجابايت</small>
                                        </div>
                                        <div id="file-preview" style="display: none;">
                                            <img id="preview-image" class="img-fluid rounded mb-3" style="max-width: 200px; max-height: 200px;">
                                            <div id="file-info" class="mb-3"></div>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeFile()">
                                                <i class="fas fa-trash me-1"></i>
                                                إزالة الملف
                                            </button>
                                        </div>
                                    </div>
                                    @error('receipt_file')
                                        <div class="text-danger mt-2">
                                            <small><i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</small>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-5" id="submit-btn">
                                    <span id="submit-text">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        إرسال تأكيد التحويل (اختبار)
                                    </span>
                                    <span id="loading-text" style="display: none;">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        جاري الإرسال...
                                    </span>
                                </button>
                            </div>
                        </form>

                        <!-- روابط مفيدة -->
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="fw-bold">روابط مفيدة:</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="/bank-transfer/6" class="btn btn-outline-primary btn-sm">صفحة التحويل البنكي</a>
                                <a href="/test-bank-transfer-flow" class="btn btn-outline-info btn-sm">اختبار التدفق الشامل</a>
                                <a href="/admin/payments" class="btn btn-outline-secondary btn-sm">لوحة إدارة المدفوعات</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // نفس JavaScript من صفحة التحويل البنكي
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('receipt_file');
            const uploadContent = document.getElementById('upload-content');
            const filePreview = document.getElementById('file-preview');
            const previewImage = document.getElementById('preview-image');
            const fileInfo = document.getElementById('file-info');

            // Click to upload
            uploadArea.addEventListener('click', (e) => {
                if (e.target.type !== 'button') {
                    fileInput.click();
                }
            });

            // File input change
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    handleFile(e.target.files[0]);
                }
            });

            function handleFile(file) {
                // Show preview
                uploadContent.style.display = 'none';
                filePreview.style.display = 'block';

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImage.style.display = 'none';
                }

                fileInfo.innerHTML = `
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-file-${file.type.startsWith('image/') ? 'image' : 'pdf'} fa-2x text-primary me-2"></i>
                        <div>
                            <div class="fw-bold">${file.name}</div>
                            <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} ميجابايت</small>
                        </div>
                    </div>
                `;
            }

            // Global function for removing file
            window.removeFile = function() {
                fileInput.value = '';
                uploadContent.style.display = 'block';
                filePreview.style.display = 'none';
                previewImage.src = '';
            };
        });
    </script>
</body>
</html>
