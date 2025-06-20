@extends('layouts.admin')
@section('title', 'إضافة منطقة')
@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-3">إضافة منطقة جديدة</h3>
    <form method="POST" action="{{ route('regions.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">اسم المنطقة</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" required value="{{ old('slug') }}">
            @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">الوصف</label>
            <input type="text" name="description" class="form-control" value="{{ old('description') }}">
            @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">العنوان</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
            @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">الصورة (رابط أو اسم ملف)</label>
            <input type="text" name="image" class="form-control" value="{{ old('image') }}">
            @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Latitude</label>
            <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}">
            @error('latitude')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Longitude</label>
            <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}">
            @error('longitude')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('regions.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection 