@extends('layouts.frontend')

@section('title', $specialty->name . ' - الاستشارات الطبية')

@section('content')
<div class="container-fluid py-4">
    <!-- رأس الصفحة -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">الرئيسية</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('consultations.specialties') }}">التخصصات الطبية</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $specialty->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- معلومات التخصص -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        @if($specialty->icon)
                            <i class="{{ $specialty->icon }} fa-4x text-primary mb-3"></i>
                        @else
                            <i class="fas fa-stethoscope fa-4x text-primary mb-3"></i>
                        @endif
                    </div>
                    
                    <h2 class="mb-3">{{ $specialty->name }}</h2>
                    
                    @if($specialty->description)
                        <p class="text-muted mb-4">{{ $specialty->description }}</p>
                    @endif
                    
                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary mb-1">{{ $doctors->count() }}</h4>
                                <small class="text-muted">طبيب متاح</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success mb-1">{{ $specialty->consultations_count ?? 0 }}</h4>
                                <small class="text-muted">استشارة مكتملة</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- قائمة الأطباء -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user-md me-2"></i>
                        الأطباء المتاحون في {{ $specialty->name }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($doctors->count() > 0)
                        <div class="row">
                            @foreach($doctors as $doctor)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm doctor-card">
                                    <div class="card-body text-center p-4">
                                        <!-- صورة الطبيب -->
                                        <div class="mb-3">
                                            <img src="{{ $doctor->avatar_url }}" 
                                                 alt="{{ $doctor->name }}" 
                                                 class="rounded-circle doctor-avatar mb-3"
                                                 width="120" height="120">
                                        </div>
                                        
                                        <!-- معلومات الطبيب -->
                                        <h5 class="card-title mb-2">د. {{ $doctor->name }}</h5>
                                        <p class="text-muted mb-3">{{ $doctor->specialty->name }}</p>
                                        
                                        <!-- التقييم -->
                                        @if($doctor->rating)
                                        <div class="mb-3">
                                            <div class="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $doctor->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                                <span class="text-muted ms-1">({{ number_format($doctor->rating, 1) }})</span>
                                            </div>
                                            <small class="text-muted">{{ $doctor->consultations_count ?? 0 }} استشارة</small>
                                        </div>
                                        @else
                                        <div class="mb-3">
                                            <small class="text-muted">لا توجد تقييمات بعد</small>
                                        </div>
                                        @endif
                                        
                                        <!-- الخبرة -->
                                        <div class="mb-3">
                                            <span class="badge bg-success">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $doctor->experience_years }} سنوات خبرة
                                            </span>
                                        </div>
                                        
                                        <!-- السعر -->
                                        <div class="mb-3">
                                            <h6 class="text-primary">
                                                @if($doctor->consultation_fee > 0)
                                                    {{ $doctor->consultation_fee }} ريال
                                                @else
                                                    مجاناً
                                                @endif
                                            </h6>
                                            <small class="text-muted">لكل استشارة</small>
                                        </div>
                                        
                                        <!-- الوصف -->
                                        @if($doctor->description)
                                        <p class="card-text text-muted mb-3">
                                            {{ Str::limit($doctor->description, 100) }}
                                        </p>
                                        @endif
                                        
                                        <!-- الأزرار -->
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-primary" 
                                                    onclick="viewDoctor({{ $doctor->id }})">
                                                <i class="fas fa-eye me-2"></i>
                                                عرض التفاصيل
                                            </button>
                                            <button type="button" class="btn btn-outline-success" 
                                                    onclick="startConsultation({{ $doctor->id }})">
                                                <i class="fas fa-comments me-2"></i>
                                                ابدأ استشارة
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-md fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">لا يوجد أطباء متاحون</h4>
                            <p class="text-muted">لا يوجد أطباء متاحون في هذا التخصص حالياً</p>
                            <a href="{{ route('consultations.specialties') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>
                                العودة للتخصصات
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal تفاصيل الطبيب -->
<div class="modal fade" id="doctorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="doctorModalTitle">تفاصيل الطبيب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="doctorModalBody">
                <!-- سيتم تحميل المحتوى هنا -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-primary" id="startConsultationModalBtn">
                    <i class="fas fa-comments me-2"></i>
                    ابدأ استشارة
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.doctor-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.doctor-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    border-color: #007bff;
}

.doctor-avatar {
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.doctor-card:hover .doctor-avatar {
    transform: scale(1.1);
}

.rating-stars {
    display: inline-block;
}

.rating-stars i {
    font-size: 14px;
}
</style>
@endpush

@push('scripts')
<script>
let currentDoctorId = null;

// عرض تفاصيل الطبيب
function viewDoctor(doctorId) {
    currentDoctorId = doctorId;
    
    fetch(`/consultations/doctor/${doctorId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showDoctorModal(data.doctor);
            } else {
                alert('حدث خطأ في تحميل بيانات الطبيب');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في تحميل بيانات الطبيب');
        });
}

// عرض modal الطبيب
function showDoctorModal(doctor) {
    document.getElementById('doctorModalTitle').textContent = `د. ${doctor.name}`;
    
    const modalBody = document.getElementById('doctorModalBody');
    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="${doctor.avatar_url}" alt="${doctor.name}" 
                     class="rounded-circle mb-3" width="150" height="150">
                <h5>د. ${doctor.name}</h5>
                <p class="text-muted">${doctor.specialty.name}</p>
                
                ${doctor.rating ? `
                <div class="mb-3">
                    <div class="rating-stars">
                        ${Array(5).fill().map((_, i) => 
                            `<i class="fas fa-star ${i < Math.round(doctor.rating) ? 'text-warning' : 'text-muted'}"></i>`
                        ).join('')}
                        <span class="text-muted ms-1">(${doctor.rating})</span>
                    </div>
                    <small class="text-muted">${doctor.consultations_count || 0} استشارة</small>
                </div>
                ` : ''}
                
                <div class="mb-3">
                    <span class="badge bg-success">
                        <i class="fas fa-clock me-1"></i>
                        ${doctor.experience_years} سنوات خبرة
                    </span>
                </div>
                
                <h6 class="text-primary">
                    ${doctor.consultation_fee > 0 ? doctor.consultation_fee + ' ريال' : 'مجاناً'}
                </h6>
                <small class="text-muted">لكل استشارة</small>
            </div>
            <div class="col-md-8">
                ${doctor.description ? `
                <h6>نبذة عن الطبيب</h6>
                <p class="text-muted">${doctor.description}</p>
                ` : ''}
                
                ${doctor.qualifications ? `
                <h6>المؤهلات</h6>
                <p class="text-muted">${doctor.qualifications}</p>
                ` : ''}
                
                ${doctor.specializations ? `
                <h6>التخصصات الفرعية</h6>
                <p class="text-muted">${doctor.specializations}</p>
                ` : ''}
                
                ${doctor.languages ? `
                <h6>اللغات</h6>
                <p class="text-muted">${doctor.languages}</p>
                ` : ''}
                
                ${doctor.availability ? `
                <h6>أوقات العمل</h6>
                <p class="text-muted">${doctor.availability}</p>
                ` : ''}
            </div>
        </div>
    `;
    
    document.getElementById('startConsultationModalBtn').onclick = () => {
        bootstrap.Modal.getInstance(document.getElementById('doctorModal')).hide();
        startConsultation(doctor.id);
    };
    
    new bootstrap.Modal(document.getElementById('doctorModal')).show();
}

// بدء استشارة
function startConsultation(doctorId) {
    window.location.href = `/consultations/create?doctor_id=${doctorId}`;
}
</script>
@endpush 