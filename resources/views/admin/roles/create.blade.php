@extends('layouts.admin')

@section('title', 'إضافة دور جديد')

@section('content')
<div class="admin-card mb-4">
    <div class="card-header">
        <h5 class="mb-0">إضافة دور جديد</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">اسم الدور <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">حفظ</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection 