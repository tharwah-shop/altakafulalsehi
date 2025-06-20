@extends('layouts.frontend')

@section('title', 'استشاراتي الطبية')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- رأس الصفحة -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-2 text-primary">استشاراتي الطبية</h1>
                    <p class="text-muted mb-0">إدارة جميع استشاراتك الطبية مع الأطباء الافتراضيين</p>
                </div>
                <a href="{{ route('consultations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>
                    استشارة جديدة
                </a>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-stethoscope fa-2x mb-2"></i>
                            <h5 class="card-title">{{ $consultations->total() }}</h5>
                            <p class="card-text">إجمالي الاستشارات</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h5 class="card-title">{{ $consultations->where('status', 'active')->count() }}</h5>
                            <p class="card-text">استشارات نشطة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h5 class="card-title">{{ $consultations->where('status', 'completed')->count() }}</h5>
                            <p class="card-text">استشارات مكتملة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-star fa-2x mb-2"></i>
                            <h5 class="card-title">{{ number_format($consultations->whereNotNull('rating')->avg('rating'), 1) }}</h5>
                            <p class="card-text">متوسط التقييم</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- قائمة الاستشارات -->
            @if($consultations->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>رقم الاستشارة</th>
                                        <th>الطبيب</th>
                                        <th>التخصص</th>
                                        <th>الحالة</th>
                                        <th>التاريخ</th>
                                        <th>التقييم</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($consultations as $consultation)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary">{{ $consultation->consultation_number }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $consultation->doctor->avatar_url }}" 
                                                     alt="{{ $consultation->doctor->name }}" 
                                                     class="rounded-circle me-2" 
                                                     width="40" height="40">
                                                <div>
                                                    <div class="fw-bold">{{ $consultation->doctor->name }}</div>
                                                    <small class="text-muted">{{ $consultation->doctor->specialty->name }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $consultation->specialty->name }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($consultation->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">في الانتظار</span>
                                                    @break
                                                @case('active')
                                                    <span class="badge bg-success">نشطة</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-info">مكتملة</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">ملغية</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <div>{{ $consultation->created_at->format('Y-m-d') }}</div>
                                            <small class="text-muted">{{ $consultation->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            @if($consultation->rating)
                                                <div class="d-flex align-items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $consultation->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                    <span class="ms-1 text-muted">({{ $consultation->rating }})</span>
                                                </div>
                                            @else
                                                <span class="text-muted">لم يتم التقييم</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('consultations.show', $consultation->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($consultation->status === 'active')
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-success"
                                                            onclick="completeConsultation({{ $consultation->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                @if($consultation->status === 'completed' && !$consultation->rating)
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-warning"
                                                            onclick="rateConsultation({{ $consultation->id }})">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- الترقيم -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $consultations->links() }}
                </div>
            @else
                <!-- حالة عدم وجود استشارات -->
                <div class="text-center py-5">
                    <i class="fas fa-stethoscope fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted mb-3">لا توجد استشارات بعد</h4>
                    <p class="text-muted mb-4">ابدأ أول استشارة طبية مع أطبائنا الافتراضيين المتخصصين</p>
                    <a href="{{ route('consultations.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus-circle me-2"></i>
                        بدء استشارة جديدة
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal تقييم الاستشارة -->
<div class="modal fade" id="rateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تقييم الاستشارة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rateForm">
                <div class="modal-body">
                    <input type="hidden" id="consultationId" name="consultation_id">
                    
                    <div class="mb-3">
                        <label class="form-label">التقييم</label>
                        <div class="rating-stars">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}">
                                <label for="star{{ $i }}" class="star-label">
                                    <i class="fas fa-star"></i>
                                </label>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="review" class="form-label">التعليق (اختياري)</label>
                        <textarea class="form-control" id="review" name="review" rows="3" 
                                  placeholder="اكتب تعليقك عن الاستشارة..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.rating-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-stars input {
    display: none;
}

.star-label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.star-label:hover,
.star-label:hover ~ .star-label,
.rating-stars input:checked ~ .star-label {
    color: #ffc107;
}

.rating-stars input:checked ~ .star-label {
    color: #ffc107;
}
</style>
@endpush

@push('scripts')
<script>
function completeConsultation(consultationId) {
    if (confirm('هل أنت متأكد من إنهاء هذه الاستشارة؟')) {
        fetch(`/consultations/${consultationId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في إنهاء الاستشارة');
        });
    }
}

function rateConsultation(consultationId) {
    document.getElementById('consultationId').value = consultationId;
    new bootstrap.Modal(document.getElementById('rateModal')).show();
}

document.getElementById('rateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const consultationId = formData.get('consultation_id');
    
    fetch(`/consultations/${consultationId}/rate`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            rating: formData.get('rating'),
            review: formData.get('review')
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('rateModal')).hide();
            location.reload();
        } else {
            alert('حدث خطأ: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في إرسال التقييم');
    });
});
</script>
@endpush 