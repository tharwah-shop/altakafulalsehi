<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار التحويل البنكي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>اختبار التحويل البنكي</h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
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
                        
                        <form action="{{ route('subscription.bank-transfer') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الاسم</label>
                                    <input type="text" class="form-control" name="name" value="محمد أحمد" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">رقم الجوال</label>
                                    <input type="text" class="form-control" name="phone" value="0501234567" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control" name="email" value="test@example.com">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">المدينة</label>
                                    <select class="form-control" name="city_id" required>
                                        @foreach(\App\Models\City::take(5)->get() as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الجنسية</label>
                                    <input type="text" class="form-control" name="nationality" value="سعودي" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">رقم الهوية</label>
                                    <input type="text" class="form-control" name="id_number" value="1234567890" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الباقة</label>
                                    <select class="form-control" name="package_id" required>
                                        @foreach(\App\Models\Package::get() as $package)
                                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">طريقة الدفع</label>
                                    <select class="form-control" name="payment_method" required>
                                        <option value="bank_transfer">تحويل بنكي</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    اختبار التحويل البنكي
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
