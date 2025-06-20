@extends('layouts.admin')
@section('title', 'تعديل مدينة')
@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-3">تعديل مدينة</h3>
    <form method="POST" action="{{ route('cities.update', $city->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">اسم المدينة</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $city->name) }}">
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">تحديث</button>
        <a href="{{ route('cities.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection 