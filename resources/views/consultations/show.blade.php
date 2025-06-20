@extends('layouts.frontend')

@section('title', 'الاستشارة #' . $consultation->consultation_number)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- معلومات الاستشارة -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        معلومات الاستشارة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ $consultation->doctor->avatar_url }}" 
                             alt="{{ $consultation->doctor->name }}" 
                             class="rounded-circle mb-3" width="100" height="100">
                        <h6 class="mb-1">د. {{ $consultation->doctor->name }}</h6>
                        <p class="text-muted mb-2">{{ $consultation->specialty->name }}</p>
                        
                        @if($consultation->rating)
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill {{ $i <= $consultation->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="text-muted">({{ $consultation->rating }})</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-primary mb-1">{{ $consultation->messages->count() }}</h6>
                                <small class="text-muted">الرسائل</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-success mb-1">{{ $consultation->files->count() }}</h6>
                            <small class="text-muted">الملفات</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">رقم الاستشارة</label>
                        <div class="fw-bold">{{ $consultation->consultation_number }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">التاريخ</label>
                        <div>{{ $consultation->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">الحالة</label>
                        <div>
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
                        </div>
                    </div>
                    
                    @if($consultation->status === 'completed' && !$consultation->rating)
                        <button type="button" class="btn btn-warning btn-sm w-100" onclick="showRatingModal()">
                            <i class="bi bi-star-fill me-2"></i>
                            تقييم الاستشارة
                        </button>
                    @endif
                    
                    @if($consultation->status === 'active')
                        <button type="button" class="btn btn-success btn-sm w-100" onclick="completeConsultation()">
                            <i class="bi bi-check-lg me-2"></i>
                            إنهاء الاستشارة
                        </button>
                    @endif
                </div>
            </div>
            
            <!-- الملفات المرفقة -->
            @if($consultation->files->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-paperclip me-2"></i>
                        الملفات المرفقة
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($consultation->files as $file)
                    <div class="d-flex align-items-center mb-2 p-2 border rounded">
                        <i class="bi bi-file-earmark me-2 text-primary"></i>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $file->file_name }}</div>
                            <small class="text-muted">{{ $file->created_at->format('Y-m-d H:i') }}</small>
                        </div>
                        <a href="{{ route('consultations.download-file', $file->id) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <!-- المحادثة -->
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-chat-dots me-2"></i>
                            المحادثة
                        </h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="uploadFile()">
                                <i class="bi bi-paperclip me-1"></i>
                                إرفاق ملف
                            </button>
                            @if($consultation->status === 'active')
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="sendMessage()">
                                    <i class="bi bi-send me-1"></i>
                                    إرسال
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <!-- منطقة المحادثة -->
                    <div class="chat-container" id="chatContainer" style="height: 500px; overflow-y: auto;">
                        @foreach($consultation->messages()->orderBy('created_at', 'asc')->get() as $message)
                        <div class="message {{ $message->sender_type === 'subscriber' ? 'message-user' : 'message-doctor' }}">
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="message-sender">
                                        {{ $message->sender_type === 'subscriber' ? 'أنت' : 'د. ' . $consultation->doctor->name }}
                                    </span>
                                    <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                                </div>
                                <div class="message-text">
                                    {!! nl2br(e($message->content)) !!}
                                </div>
                                @if($message->file_path)
                                <div class="message-attachments">
                                    <div class="attachment-item">
                                        <i class="bi bi-file-earmark me-2"></i>
                                        <a href="{{ route('consultations.download-file', $message->id) }}" 
                                           class="text-decoration-none">
                                            {{ $message->file_name }}
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- منطقة الكتابة -->
                    @if($consultation->status === 'active')
                    <div class="chat-input-area p-3 border-top">
                        <form id="messageForm">
                            <div class="row g-2">
                                <div class="col">
                                    <textarea class="form-control" id="messageText" rows="2" 
                                              placeholder="اكتب رسالتك هنا..." maxlength="1000"></textarea>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary h-100">
                                        <i class="bi bi-send"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @else
                    <div class="p-3 border-top bg-light text-center">
                        <p class="text-muted mb-0">
                            @if($consultation->status === 'completed')
                                هذه الاستشارة مكتملة ولا يمكن إرسال رسائل جديدة
                            @elseif($consultation->status === 'cancelled')
                                هذه الاستشارة ملغية
                            @else
                                الاستشارة في حالة انتظار
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal رفع الملف -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">رفع ملف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">اختر الملف</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                        <div class="form-text">الملفات المدعومة: PDF, JPG, PNG, DOC, DOCX (الحد الأقصى: 5MB)</div>
                    </div>
                    <div class="mb-3">
                        <label for="fileDescription" class="form-label">وصف الملف (اختياري)</label>
                        <textarea class="form-control" id="fileDescription" name="description" 
                                  rows="2" placeholder="وصف مختصر للملف..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">رفع الملف</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal تقييم الاستشارة -->
<div class="modal fade" id="ratingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تقييم الاستشارة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="ratingForm">
                <div class="modal-body">
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
.chat-container {
    background-color: #f8f9fa;
}

.message {
    margin: 15px;
    display: flex;
}

.message-user {
    justify-content: flex-end;
}

.message-doctor {
    justify-content: flex-start;
}

.message-content {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 18px;
    position: relative;
}

.message-user .message-content {
    background-color: #007bff;
    color: white;
    border-bottom-right-radius: 4px;
}

.message-doctor .message-content {
    background-color: white;
    border: 1px solid #dee2e6;
    border-bottom-left-radius: 4px;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
    font-size: 0.85rem;
}

.message-user .message-header {
    color: rgba(255, 255, 255, 0.8);
}

.message-doctor .message-header {
    color: #6c757d;
}

.message-text {
    line-height: 1.4;
}

.message-attachments {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.message-doctor .message-attachments {
    border-top-color: #dee2e6;
}

.attachment-item {
    margin-bottom: 5px;
}

.attachment-item a {
    color: inherit;
}

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
</style>
@endpush

@push('scripts')
<script>
// تمرير إلى نهاية المحادثة
function scrollToBottom() {
    const container = document.getElementById('chatContainer');
    container.scrollTop = container.scrollHeight;
}

// إرسال رسالة
function sendMessage() {
    const messageText = document.getElementById('messageText').value.trim();
    if (!messageText) return;
    
    const formData = new FormData();
    formData.append('message', messageText);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("consultations.send-message", $consultation->id) }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('messageText').value = '';
            location.reload(); // إعادة تحميل لعرض الرسالة الجديدة
        } else {
            alert('حدث خطأ: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في إرسال الرسالة');
    });
}

// رفع ملف
function uploadFile() {
    new bootstrap.Modal(document.getElementById('uploadModal')).show();
}

// إنهاء الاستشارة
function completeConsultation() {
    if (confirm('هل أنت متأكد من إنهاء هذه الاستشارة؟')) {
        fetch('{{ route("consultations.complete", $consultation->id) }}', {
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

// عرض modal التقييم
function showRatingModal() {
    new bootstrap.Modal(document.getElementById('ratingModal')).show();
}

// إرسال النموذج
document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    sendMessage();
});

// رفع ملف
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("consultations.upload-file", $consultation->id) }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
            location.reload();
        } else {
            alert('حدث خطأ: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في رفع الملف');
    });
});

// تقييم الاستشارة
document.getElementById('ratingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("consultations.rate", $consultation->id) }}', {
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
            bootstrap.Modal.getInstance(document.getElementById('ratingModal')).hide();
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

// تمرير إلى نهاية المحادثة عند التحميل
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
});
</script>
@endpush