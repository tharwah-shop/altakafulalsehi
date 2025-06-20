@extends('layouts.frontend')

@section('title', 'المنشورات والأخبار')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">المنشورات والأخبار</h1>
        <p class="lead mb-4">آخر الأخبار والمقالات الطبية من شبكة التكافل الصحي</p>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="ابحث في المنشورات..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">كل التصنيفات</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="medical_center" class="form-select">
                            <option value="">كل المراكز</option>
                            @foreach($medicalCenters as $center)
                                <option value="{{ $center->id }}" {{ request('medical_center') == $center->id ? 'selected' : '' }}>
                                    {{ $center->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">بحث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h5 class="fw-bold mb-3">التصنيفات</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('posts.index') }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                        الكل
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('posts.index', ['category' => $category->id]) }}" 
                           class="btn {{ request('category') == $category->id ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            <i class="{{ $category->icon ?? 'bi bi-tag' }} me-1"></i>
                            {{ $category->name_ar }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Posts Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @forelse($posts as $post)
            <div class="col-lg-4 col-md-6">
                <article class="card h-100 shadow-sm">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title_ar }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-file-text text-white" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge" style="background-color: {{ $post->category->color ?? '#007bff' }}">
                                {{ $post->category->name_ar }}
                            </span>
                            @if($post->is_featured)
                                <span class="badge bg-warning text-dark">مميز</span>
                            @endif
                        </div>
                        
                        <h5 class="card-title fw-bold">{{ $post->title_ar }}</h5>
                        
                        @if($post->excerpt_ar)
                            <p class="card-text text-muted">{{ Str::limit($post->excerpt_ar, 120) }}</p>
                        @else
                            <p class="card-text text-muted">{{ Str::limit(strip_tags($post->content_ar), 120) }}</p>
                        @endif
                        
                        <div class="mt-auto">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $post->published_at ? $post->published_at->format('Y/m/d') : $post->created_at->format('Y/m/d') }}
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-eye me-1"></i>
                                    {{ $post->views_count }}
                                </small>
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">
                                    <i class="bi bi-hospital me-1"></i>
                                    {{ $post->medicalCenter->name }}
                                </small>
                                <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-primary btn-sm">
                                    اقرأ المزيد
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="py-5">
                    <i class="bi bi-file-text text-muted" style="font-size: 4rem;"></i>
                    <h4 class="text-muted mt-3">لا توجد منشورات</h4>
                    <p class="text-muted">لم يتم العثور على منشورات تطابق معايير البحث</p>
                </div>
            </div>
            @endforelse
        </div>
        
        @if($posts->hasPages())
        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Featured Posts Section -->
@if(!request()->hasAny(['search', 'category', 'medical_center']))
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h3 class="fw-bold">المنشورات المميزة</h3>
                <p class="text-muted">أهم المقالات والأخبار الطبية</p>
            </div>
        </div>
        <div class="row g-4">
            @php
                $featuredPosts = \App\Models\Post::published()->featured()->with(['category', 'medicalCenter'])->take(3)->get();
            @endphp
            @foreach($featuredPosts as $post)
            <div class="col-lg-4">
                <div class="card border-0 shadow">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title_ar }}" style="height: 180px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <span class="badge bg-warning text-dark mb-2">مميز</span>
                        <h6 class="card-title fw-bold">{{ $post->title_ar }}</h6>
                        <p class="card-text small text-muted">{{ Str::limit($post->excerpt_ar ?? strip_tags($post->content_ar), 80) }}</p>
                        <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">اقرأ المزيد</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
