@extends('layouts.admin')

@section('title', 'إدارة التقييمات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">التقييمات</h2>
        <p class="text-muted mb-0">إدارة تقييمات المراكز الطبية</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-star text-warning me-2"></i>
            قائمة التقييمات
        </h5>
    </div>
    <div class="card-body">
        @if($reviews->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المستخدم</th>
                            <th>المركز الطبي</th>
                            <th>التقييم</th>
                            <th>التعليق</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width:35px;height:35px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $review->user->name ?? 'مستخدم محذوف' }}</div>
                                        <small class="text-muted">{{ $review->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $review->medicalCenter->name ?? 'مركز محذوف' }}</div>
                                <small class="text-muted">{{ $review->medicalCenter->city ?? '' }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-warning me-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#ffc107' : '#dee2e6' }};"></i>
                                        @endfor
                                    </div>
                                    <span class="badge bg-warning text-dark">{{ $review->rating }}/5</span>
                                </div>
                            </td>
                            <td>
                                @if($review->comment)
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $review->comment }}">
                                        {{ Str::limit($review->comment, 50) }}
                                    </span>
                                @else
                                    <span class="text-muted">لا يوجد تعليق</span>
                                @endif
                            </td>
                            <td>{{ $review->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا التقييم؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $reviews->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-star text-muted fa-3x mb-3"></i>
                <p class="text-muted">لا توجد تقييمات بعد</p>
            </div>
        @endif
    </div>
</div>
@endsection
