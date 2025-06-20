@extends('layouts.admin')
@section('title', 'تعديل صلاحية المستخدم')
@section('content-header', 'تعديل مستخدم')
@section('content-subtitle', 'تعديل بيانات وصلاحية المستخدم')
@section('content')
<div class="card mb-5">
    <div class="card-header border-0 pb-0">
        <h3 class="fw-bold mb-0">تعديل صلاحية المستخدم</h3>
        <div class="text-muted">يمكنك تغيير صلاحية المستخدم فقط</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">الاسم</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الصلاحية <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">اختر الصلاحية</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="d-flex gap-2 justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4"><i class="fa fa-save me-2"></i>تحديث</button>
                <a href="{{ route('users.index') }}" class="btn btn-light-danger px-4"><i class="fa fa-times me-2"></i>إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection 