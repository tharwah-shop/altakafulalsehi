@extends('layouts.admin')
@section('title', 'تعديل منطقة')
@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-3">تعديل منطقة</h3>
    <form method="POST" action="{{ route('regions.update', $region->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">اسم المنطقة</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $region->name) }}">
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" required value="{{ old('slug', $region->slug) }}">
            @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">الوصف</label>
            <input type="text" name="description" class="form-control" value="{{ old('description', $region->description) }}">
            @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">العنوان</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $region->address) }}">
            @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">الصورة (رابط أو اسم ملف)</label>
            <input type="text" name="image" class="form-control" value="{{ old('image', $region->image) }}">
            @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Latitude</label>
            <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $region->latitude) }}">
            @error('latitude')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Longitude</label>
            <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $region->longitude) }}">
            @error('longitude')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">تحديث</button>
        <a href="{{ route('regions.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection 