@extends('layouts.frontend')

@section('title', $post->title_ar)

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="py-3 bg-light">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">المنشورات</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title_ar, 50) }}</li>
        </ol>
    </div>
</nav>

<!-- Post Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="card border-0 shadow-sm">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title_ar }}" style="height: 400px; object-fit: cover;">
                    @endif
                    
                    <div class="card-body">
                        <!-- Post Meta -->
                        <div class="mb-3">
                            <span class="badge" style="background-color: {{ $post->category->color ?? '#007bff' }}">
                                {{ $post->category->name_ar }}
                            </span>
                            @if($post->is_featured)
                                <span class="badge bg-warning text-dark">مميز</span>
                            @endif
                            @if($post->priority === 'urgent')
                                <span class="badge bg-danger">عاجل</span>
                            @elseif($post->priority === 'high')
                                <span class="badge bg-warning">مهم</span>
                            @endif
                        </div>
                        
                        <!-- Post Title -->
                        <h1 class="fw-bold mb-3">{{ $post->title_ar }}</h1>
                        
                        <!-- Post Info -->
                        <div class="row text-muted small mb-4">
                            <div class="col-md-6">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $post->published_at ? $post->published_at->format('Y/m/d H:i') : $post->created_at->format('Y/m/d H:i') }}
                            </div>
                            <div class="col-md-6">
                                <i class="bi bi-person me-1"></i>
                                {{ $post->author->name }}
                            </div>
                            <div class="col-md-6 mt-2">
                                <i class="bi bi-hospital me-1"></i>
                                <a href="{{ route('medical-center.detail', $post->medicalCenter->slug) }}" class="text-decoration-none">
                                    {{ $post->medicalCenter->name }}
                                </a>
                            </div>
                            <div class="col-md-6 mt-2">
                                <i class="bi bi-eye me-1"></i>
                                {{ $post->views_count }} مشاهدة
                                <i class="bi bi-heart ms-3 me-1"></i>
                                {{ $post->likes_count }} إعجاب
                            </div>
                        </div>
                        
                        <!-- Post Excerpt -->
                        @if($post->excerpt_ar)
                            <div class="alert alert-info">
                                <strong>ملخص:</strong> {{ $post->excerpt_ar }}
                            </div>
                        @endif
                        
                        <!-- Post Content -->
                        <div class="post-content">
                            {!! nl2br(e($post->content_ar)) !!}
                        </div>
                        
                        <!-- Tags -->
                        @if($post->tags && count($post->tags) > 0)
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="fw-bold mb-2">الكلمات المفتاحية:</h6>
                                @foreach($post->tags as $tag)
                                    <span class="badge bg-secondary me-1 mb-1">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                        
                        <!-- Share Buttons -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold mb-2">شارك المقال:</h6>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                                   target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-facebook me-1"></i>
                                    فيسبوك
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title_ar) }}" 
                                   target="_blank" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-twitter me-1"></i>
                                    تويتر
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($post->title_ar . ' ' . request()->url()) }}" 
                                   target="_blank" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-whatsapp me-1"></i>
                                    واتساب
                                </a>
                                <button onclick="copyToClipboard('{{ request()->url() }}')" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-link me-1"></i>
                                    نسخ الرابط
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
                
                <!-- Post Attachments -->
                @if($post->attachments->count() > 0)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">المرفقات</h5>
                        <div class="row g-3">
                            @foreach($post->attachments as $attachment)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-2 border rounded">
                                        <i class="bi bi-file-earmark text-primary me-2" style="font-size: 1.5rem;"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">{{ $attachment->file_name }}</div>
                                            <small class="text-muted">{{ $attachment->file_size_human }}</small>
                                        </div>
                                        <a href="{{ $attachment->full_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Medical Center Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">عن المركز الطبي</h5>
                        <div class="d-flex align-items-center mb-3">
                            @if($post->medicalCenter->image)
                                <img src="{{ asset('storage/' . $post->medicalCenter->image) }}" 
                                     class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                            @else
                                <div class="bg-primary rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-hospital text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="fw-bold mb-1">{{ $post->medicalCenter->name }}</h6>
                                <small class="text-muted">{{ $post->medicalCenter->city }}، {{ $post->medicalCenter->region }}</small>
                            </div>
                        </div>
                        
                        @if($post->medicalCenter->description)
                            <p class="text-muted small">{{ Str::limit($post->medicalCenter->description, 100) }}</p>
                        @endif
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('medical-center.detail', $post->medicalCenter->slug) }}" class="btn btn-primary btn-sm">
                                عرض المركز
                            </a>
                            @if($post->medicalCenter->phone)
                                <a href="tel:{{ $post->medicalCenter->phone }}" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-telephone me-1"></i>
                                    اتصل الآن
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">مقالات ذات صلة</h5>
                        @foreach($relatedPosts as $relatedPost)
                            <div class="d-flex mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
                                @if($relatedPost->featured_image)
                                    <img src="{{ asset('storage/' . $relatedPost->featured_image) }}" 
                                         class="rounded me-3" width="80" height="60" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                        <i class="bi bi-file-text text-muted"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">
                                        <a href="{{ route('posts.show', $relatedPost->slug) }}" class="text-decoration-none">
                                            {{ Str::limit($relatedPost->title_ar, 60) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        {{ $relatedPost->published_at ? $relatedPost->published_at->format('Y/m/d') : $relatedPost->created_at->format('Y/m/d') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('تم نسخ الرابط بنجاح!');
    });
}
</script>
@endsection
