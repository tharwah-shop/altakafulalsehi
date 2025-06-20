@extends('layouts.frontend')

@section('title', 'الأطباء - ' . ($specialty ? $specialty->name : 'جميع التخصصات'))

@section('content')
<div class="container py-5">
    <!-- رأس الصفحة -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-2 text-primary">
                @if($specialty)
                    أطباء {{ $specialty->name }}
                @else
                    جميع الأطباء
                @endif
            </h1>
            <p class="text-muted mb-0">
                @if($specialty)
                    اختر من بين أطباء {{ $specialty->name }} المتخصصين
                @else
                    استكشف جميع الأطباء المتاحين في النظام
                @endif
            </p>
        </div>
        <a href="{{ route('consultations.specialties') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-right me-2"></i>
            العودة للتخصصات
        </a>
    </div>

    <!-- الفلاتر والبحث -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="searchInput" class="form-label">البحث</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="searchInput" 
                               placeholder="ابحث عن طبيب...">
                    </div>
                </div>
                
                @if(!$specialty)
                <div class="col-md-3">
                    <label for="specialtyFilter" class="form-label">التخصص</label>
                    <select class="form-select" id="specialtyFilter">
                        <option value="">جميع التخصصات</option>
                        @foreach($specialties as $spec)
                        <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="col-md-3">
                    <label for="experienceFilter" class="form-label">الخبرة</label>
                    <select class="form-select" id="experienceFilter">
                        <option value="">جميع المستويات</option>
                        <option value="1-5">1-5 سنوات</option>
                        <option value="6-10">6-10 سنوات</option>
                        <option value="11-15">11-15 سنة</option>
                        <option value="16+">16+ سنة</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="ratingFilter" class="form-label">التقييم</label>
                    <select class="form-select" id="ratingFilter">
                        <option value="">جميع التقييمات</option>
                        <option value="5">5 نجوم</option>
                        <option value="4">4+ نجوم</option>
                        <option value="3">3+ نجوم</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- قائمة الأطباء -->
    <div class="row" id="doctorsContainer">
        @foreach($doctors as $doctor)
        <div class="col-lg-4 col-md-6 mb-4 doctor-item" 
             data-name="{{ strtolower($doctor->name) }}"
             data-specialty="{{ strtolower($doctor->specialty->name) }}"
             data-experience="{{ $doctor->experience_years }}"
             data-rating="{{ $doctor->rating ?? 0 }}">
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

    <!-- رسالة عدم وجود نتائج -->
    <div id="noResults" class="text-center py-5" style="display: none;">
        <i class="fas fa-user-md fa-4x text-muted mb-4"></i>
        <h4 class="text-muted mb-3">لا توجد نتائج</h4>
        <p class="text-muted">جرب تغيير معايير البحث</p>
    </div>

    <!-- الترقيم -->
    @if($doctors->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $doctors->links() }}
    </div>
    @endif
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
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.rating-stars {
    font-size: 1.1rem;
}

.doctor-item {
    transition: all 0.3s ease;
}

.doctor-item.hidden {
    display: none;
}

.doctor-item.fade-out {
    opacity: 0;
    transform: scale(0.8);
}

.doctor-item.fade-in {
    opacity: 1;
    transform: scale(1);
}

/* تأثيرات بصرية إضافية */
.card-body {
    position: relative;
    overflow: hidden;
}

.card-body::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent, rgba(0,123,255,0.05), transparent);
    opacity: 0;
    transition: opacity 0.3s;
}

.doctor-card:hover .card-body::after {
    opacity: 1;
}

/* تحسين مظهر الأزرار */
.btn {
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.3s, height 0.3s;
}

.btn:hover::before {
    width: 300px;
    height: 300px;
}
</style>
@endpush

@push('scripts')
<script>
let currentDoctorId = null;

// البحث والفلترة
function filterDoctors() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    const specialtyFilter = document.getElementById('specialtyFilter')?.value;
    const experienceFilter = document.getElementById('experienceFilter').value;
    const ratingFilter = document.getElementById('ratingFilter').value;
    
    const doctorItems = document.querySelectorAll('.doctor-item');
    let visibleCount = 0;
    
    doctorItems.forEach(item => {
        const name = item.dataset.name;
        const specialty = item.dataset.specialty;
        const experience = parseInt(item.dataset.experience);
        const rating = parseFloat(item.dataset.rating);
        
        let show = true;
        
        // فلتر البحث
        if (searchTerm && !name.includes(searchTerm)) {
            show = false;
        }
        
        // فلتر التخصص
        if (specialtyFilter && item.dataset.specialtyId !== specialtyFilter) {
            show = false;
        }
        
        // فلتر الخبرة
        if (experienceFilter) {
            const [min, max] = experienceFilter.split('-').map(x => x === '+' ? 999 : parseInt(x));
            if (experience < min || (max !== 999 && experience > max)) {
                show = false;
            }
        }
        
        // فلتر التقييم
        if (ratingFilter && rating < parseFloat(ratingFilter)) {
            show = false;
        }
        
        if (show) {
            item.classList.remove('hidden', 'fade-out');
            item.classList.add('fade-in');
            visibleCount++;
        } else {
            item.classList.add('hidden', 'fade-out');
            item.classList.remove('fade-in');
        }
    });
    
    // إظهار/إخفاء رسالة عدم وجود نتائج
    const noResults = document.getElementById('noResults');
    if (visibleCount === 0) {
        noResults.style.display = 'block';
    } else {
        noResults.style.display = 'none';
    }
}

// مستمعي الأحداث للفلترة
document.getElementById('searchInput').addEventListener('input', filterDoctors);
document.getElementById('specialtyFilter')?.addEventListener('change', filterDoctors);
document.getElementById('experienceFilter').addEventListener('change', filterDoctors);
document.getElementById('ratingFilter').addEventListener('change', filterDoctors);

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

// تأثيرات بصرية عند التحميل
document.addEventListener('DOMContentLoaded', function() {
    const doctorItems = document.querySelectorAll('.doctor-item');
    
    doctorItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.5s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// تحسين تجربة المستخدم
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = bootstrap.Modal.getInstance(document.getElementById('doctorModal'));
        if (modal) {
            modal.hide();
        }
    }
});
</script>
@endpush 