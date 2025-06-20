@extends('layouts.frontend')

@section('title', 'الملف الشخصي - التكافل الصحي')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-fill text-white fs-1"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold mb-2">{{ Auth::user()->name }}</h2>
                    <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>معلومات الملف الشخصي</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.profile.update') }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">الاسم الكامل</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="status" class="form-label">حالة الحساب</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->status == 'active' ? 'نشط' : 'غير نشط' }}" readonly>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h6 class="fw-bold mb-3">تغيير كلمة المرور</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                            </div>
                            
                            <div class="col-md-4">
                                <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            
                            <div class="col-md-4">
                                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="/dashboard" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-right me-2"></i>العودة للوحة التحكم
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
