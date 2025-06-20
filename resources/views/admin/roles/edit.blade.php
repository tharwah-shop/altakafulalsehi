@extends('layouts.admin')

@section('title', 'تعديل الدور')

@section('content')
<div class="admin-card mb-4">
    <div class="card-header">
        <h5 class="mb-0">تعديل الدور: {{ $role->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">اسم الدور <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $role->slug) }}" required>
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">الوصف</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $role->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">تحديث</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">إلغاء</a>
        </form>
    </div>
</div>
@endsection 