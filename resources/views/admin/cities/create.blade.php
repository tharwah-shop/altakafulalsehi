@extends('layouts.admin')
@section('title', 'إضافة مدينة')
@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-3">إضافة مدينة جديدة</h3>
    <form method="POST" action="{{ route('cities.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">اسم المدينة</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('cities.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection 