@extends('layouts.frontend')

@section('title', 'الملف الشخصي')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- الملف الشخصي -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <!-- صورة المستخدم -->
                    <div class="mb-4">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar_url }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="rounded-circle profile-avatar mb-3"
                                 width="150" height="150">
                        @else
                            <div class="profile-avatar-placeholder mb-3">
                                <i class="fas fa-user fa-4x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- معلومات المستخدم -->
                    <h4 class="mb-2">{{ auth()->user()->name }}</h4>
                    <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                    
                    <!-- حالة الاشتراك -->
                    <div class="mb-3">
                        @if(auth()->user()->subscription && auth()->user()->subscription->isActive())
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                مشترك نشط
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                غير مشترك
                            </span>
                        @endif
                    </div>
                    
                    <!-- تاريخ الانضمام -->
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            انضم في {{ auth()->user()->created_at->format('Y-m-d') }}
                        </small>
                    </div>
                    
                    <!-- أزرار الإجراءات -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="editProfile()">
                            <i class="fas fa-edit me-2"></i>
                            تعديل الملف الشخصي
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="changePassword()">
                            <i class="fas fa-key me-2"></i>
                            تغيير كلمة المرور
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- معلومات الاشتراك -->
            @if(auth()->user()->subscription)
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-crown me-2"></i>
                        معلومات الاشتراك
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">نوع الاشتراك</label>
                        <div class="fw-bold">{{ auth()->user()->subscription->plan->name }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">تاريخ البداية</label>
                        <div>{{ auth()->user()->subscription->start_date->format('Y-m-d') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">تاريخ الانتهاء</label>
                        <div>{{ auth()->user()->subscription->end_date->format('Y-m-d') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">الحالة</label>
                        <div>
                            @if(auth()->user()->subscription->isActive())
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-danger">منتهي</span>
                            @endif
                        </div>
                    </div>
                    
                    @if(auth()->user()->subscription->isActive())
                    <div class="progress mb-3" style="height: 8px;">
                        @php
                            $totalDays = auth()->user()->subscription->start_date->diffInDays(auth()->user()->subscription->end_date);
                            $remainingDays = now()->diffInDays(auth()->user()->subscription->end_date);
                            $progress = (($totalDays - $remainingDays) / $totalDays) * 100;
                        @endphp
                        <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                    </div>
                    <small class="text-muted">{{ $remainingDays }} يوم متبقي</small>
                    @endif
                </div>
            </div>
            @endif
        </div>
        
        <!-- الإحصائيات والأنشطة -->
        <div class="col-lg-8">
            <!-- إحصائيات سريعة -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-stethoscope fa-2x mb-2"></i>
                            <h5 class="card-title">{{ $consultationsCount }}</h5>
                            <p class="card-text">إجمالي الاستشارات</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h5 class="card-title">{{ $activeConsultationsCount }}</h5>
                            <p class="card-text">استشارات نشطة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h5 class="card-title">{{ $completedConsultationsCount }}</h5>
                            <p class="card-text">استشارات مكتملة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-star fa-2x mb-2"></i>
                            <h5 class="card-title">{{ number_format($averageRating, 1) }}</h5>
                            <p class="card-text">متوسط التقييم</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- التخصصات المفضلة -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-heart me-2"></i>
                        التخصصات المفضلة
                    </h6>
                </div>
                <div class="card-body">
                    @if($favoriteSpecialties->count() > 0)
                        <div class="row">
                            @foreach($favoriteSpecialties as $specialty)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center p-2 border rounded">
                                    @if($specialty->icon)
                                        <i class="{{ $specialty->icon }} me-2" 
                                           style="color: {{ $specialty->color ?? '#007bff' }};"></i>
                                    @else
                                        <i class="fas fa-stethoscope me-2 text-primary"></i>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $specialty->name }}</div>
                                        <small class="text-muted">{{ $specialty->consultations_count }} استشارة</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">لا توجد تخصصات مفضلة بعد</p>
                    @endif
                </div>
            </div>
            
            <!-- آخر الاستشارات -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        آخر الاستشارات
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($recentConsultations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>الطبيب</th>
                                        <th>التخصص</th>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentConsultations as $consultation)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $consultation->doctor->avatar_url }}" 
                                                     alt="{{ $consultation->doctor->name }}" 
                                                     class="rounded-circle me-2" 
                                                     width="30" height="30">
                                                <div>
                                                    <div class="fw-bold">د. {{ $consultation->doctor->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $consultation->specialty->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $consultation->created_at->format('Y-m-d') }}</small>
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
                                            <a href="{{ route('consultations.show', $consultation->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-stethoscope fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">لا توجد استشارات بعد</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- التقييمات -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-star me-2"></i>
                        تقييماتي
                    </h6>
                </div>
                <div class="card-body">
                    @if($ratings->count() > 0)
                        @foreach($ratings as $rating)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">د. {{ $rating->consultation->doctor->name }}</h6>
                                    <p class="text-muted mb-2">{{ $rating->consultation->specialty->name }}</p>
                                    <div class="rating-stars mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $rating->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    @if($rating->review)
                                        <p class="mb-0">{{ $rating->review }}</p>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $rating->created_at->format('Y-m-d') }}</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center mb-0">لا توجد تقييمات بعد</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal تعديل الملف الشخصي -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل الملف الشخصي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProfileForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">الاسم</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ auth()->user()->name }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ auth()->user()->email }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="{{ auth()->user()->phone }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="avatar" class="form-label">الصورة الشخصية</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" 
                               accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal تغيير كلمة المرور -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تغيير كلمة المرور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changePasswordForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                        <input type="password" class="form-control" id="current_password" 
                               name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                        <input type="password" class="form-control" id="new_password" 
                               name="new_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" class="form-control" id="new_password_confirmation" 
                               name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تغيير كلمة المرور</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.profile-avatar {
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.profile-avatar-placeholder {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.rating-stars {
    font-size: 1rem;
}

.progress {
    background-color: #e9ecef;
}

.progress-bar {
    transition: width 0.6s ease;
}
</style>
@endpush

@push('scripts')
<script>
function editProfile() {
    new bootstrap.Modal(document.getElementById('editProfileModal')).show();
}

function changePassword() {
    new bootstrap.Modal(document.getElementById('changePasswordModal')).show();
}

// تعديل الملف الشخصي
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('/profile/update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('editProfileModal')).hide();
            location.reload();
        } else {
            alert('حدث خطأ: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في تحديث الملف الشخصي');
    });
});

// تغيير كلمة المرور
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('/profile/change-password', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
            alert('تم تغيير كلمة المرور بنجاح');
            this.reset();
        } else {
            alert('حدث خطأ: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في تغيير كلمة المرور');
    });
});
</script>
@endpush 