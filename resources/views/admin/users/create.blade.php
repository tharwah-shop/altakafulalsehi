@extends('layouts.admin')
@section('title', 'إضافة مستخدم جديد')
@section('content-header', 'إضافة مستخدم جديد')
@section('content-subtitle', 'إدخال بيانات مستخدم جديد للنظام')
@section('content')
<div class="card mb-5">
    <div class="card-header border-0 pb-0">
        <h3 class="fw-bold mb-0">إضافة مستخدم جديد</h3>
        <div class="text-muted">يرجى تعبئة جميع الحقول المطلوبة</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}" autocomplete="off">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">الاسم <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الصلاحية <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">اختر الصلاحية</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="d-flex gap-2 justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4"><i class="fa fa-save me-2"></i>حفظ</button>
                <a href="{{ route('users.index') }}" class="btn btn-light-danger px-4"><i class="fa fa-times me-2"></i>إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection 