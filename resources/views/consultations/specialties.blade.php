@extends('layouts.frontend')

@section('title', 'التخصصات الطبية')

@section('content')
<div class="container py-5">
    <!-- رأس الصفحة -->
    <div class="text-center mb-5">
        <h1 class="h2 mb-3 text-primary">التخصصات الطبية</h1>
        <p class="text-muted">اختر من بين مجموعة واسعة من التخصصات الطبية للحصول على استشارة متخصصة</p>
    </div>

    <!-- البحث والفلترة -->
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <div class="input-group">
                <span class="input-group-text bg-primary text-white">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" class="form-control" id="searchInput" 
                       placeholder="ابحث عن تخصص طبي...">
            </div>
        </div>
    </div>

    <!-- قائمة التخصصات -->
    <div class="row" id="specialtiesContainer">
        @foreach($specialties as $specialty)
        <div class="col-lg-4 col-md-6 mb-4 specialty-item" 
             data-name="{{ strtolower($specialty->name) }}"
             data-description="{{ strtolower($specialty->description ?? '') }}">
            <div class="card h-100 shadow-sm specialty-card" 
                 style="transition: all 0.3s ease; cursor: pointer;"
                 onclick="selectSpecialty({{ $specialty->id }})">
                <div class="card-body text-center p-4">
                    <!-- أيقونة التخصص -->
                    <div class="mb-3">
                        @if($specialty->icon)
                            <i class="{{ $specialty->icon }} fa-4x" 
                               style="color: {{ $specialty->color ?? '#007bff' }};"></i>
                        @else
                            <i class="fas fa-stethoscope fa-4x text-primary"></i>
                        @endif
                    </div>
                    
                    <!-- اسم التخصص -->
                    <h5 class="card-title mb-3">{{ $specialty->name }}</h5>
                    
                    <!-- وصف التخصص -->
                    @if($specialty->description)
                        <p class="card-text text-muted mb-3">
                            {{ Str::limit($specialty->description, 120) }}
                        </p>
                    @endif
                    
                    <!-- إحصائيات التخصص -->
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-primary mb-1">{{ $specialty->doctors_count ?? 0 }}</h6>
                                <small class="text-muted">الأطباء</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-success mb-1">{{ $specialty->consultations_count ?? 0 }}</h6>
                            <small class="text-muted">الاستشارات</small>
                        </div>
                    </div>
                    
                    <!-- زر البدء -->
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-arrow-left me-2"></i>
                        ابدأ استشارة
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- رسالة عدم وجود نتائج -->
    <div id="noResults" class="text-center py-5" style="display: none;">
        <i class="fas fa-search fa-4x text-muted mb-4"></i>
        <h4 class="text-muted mb-3">لا توجد نتائج</h4>
        <p class="text-muted">جرب البحث بكلمات مختلفة</p>
    </div>

    <!-- معلومات إضافية -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="mb-3">كيف تعمل الاستشارات الطبية؟</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <i class="fas fa-user-md fa-2x text-primary mb-2"></i>
                                <h6>اختر التخصص</h6>
                                <small class="text-muted">اختر التخصص المناسب لحالتك</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <i class="fas fa-comments fa-2x text-success mb-2"></i>
                                <h6>تواصل مع الطبيب</h6>
                                <small class="text-muted">ابدأ محادثة مع الطبيب المختص</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <i class="fas fa-check-circle fa-2x text-info mb-2"></i>
                                <h6>احصل على النصيحة</h6>
                                <small class="text-muted">تلقى النصائح والإرشادات الطبية</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal تفاصيل التخصص -->
<div class="modal fade" id="specialtyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="specialtyModalTitle">تفاصيل التخصص</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="specialtyModalBody">
                <!-- سيتم تحميل المحتوى هنا -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <a href="#" class="btn btn-primary" id="startConsultationBtn">
                    <i class="fas fa-arrow-left me-2"></i>
                    ابدأ استشارة
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.specialty-card {
    transition: all 0.3s ease;
}

.specialty-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.specialty-card:active {
    transform: translateY(-5px);
}

.specialty-item {
    transition: all 0.3s ease;
}

.specialty-item.hidden {
    display: none;
}

.specialty-item.fade-out {
    opacity: 0;
    transform: scale(0.8);
}

.specialty-item.fade-in {
    opacity: 1;
    transform: scale(1);
}

/* تأثيرات بصرية إضافية */
.card-body {
    position: relative;
    overflow: hidden;
}

.card-body::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.specialty-card:hover .card-body::before {
    left: 100%;
}

/* تحسين مظهر الأيقونات */
.fa-4x {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}

/* تحسين مظهر الأزرار */
.btn-primary {
    position: relative;
    overflow: hidden;
}

.btn-primary::before {
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

.btn-primary:hover::before {
    width: 300px;
    height: 300px;
}
</style>
@endpush

@push('scripts')
<script>
let currentSpecialtyId = null;

// البحث في التخصصات
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();
    const specialtyItems = document.querySelectorAll('.specialty-item');
    let visibleCount = 0;
    
    specialtyItems.forEach(item => {
        const name = item.dataset.name;
        const description = item.dataset.description;
        
        if (name.includes(searchTerm) || description.includes(searchTerm)) {
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
});

// اختيار تخصص
function selectSpecialty(specialtyId) {
    currentSpecialtyId = specialtyId;
    
    // تحميل تفاصيل التخصص
    fetch(`/consultations/specialty/${specialtyId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSpecialtyModal(data.specialty);
            } else {
                // إذا لم تكن هناك تفاصيل، انتقل مباشرة لإنشاء الاستشارة
                window.location.href = `/consultations/create?specialty_id=${specialtyId}`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // في حالة الخطأ، انتقل مباشرة لإنشاء الاستشارة
            window.location.href = `/consultations/create?specialty_id=${specialtyId}`;
        });
}

// عرض modal التخصص
function showSpecialtyModal(specialty) {
    document.getElementById('specialtyModalTitle').textContent = specialty.name;
    
    const modalBody = document.getElementById('specialtyModalBody');
    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="mb-3">
                    ${specialty.icon ? 
                        `<i class="${specialty.icon} fa-4x" style="color: ${specialty.color || '#007bff'};"></i>` :
                        `<i class="fas fa-stethoscope fa-4x text-primary"></i>`
                    }
                </div>
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-primary">${specialty.doctors_count || 0}</h6>
                        <small class="text-muted">الأطباء</small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-success">${specialty.consultations_count || 0}</h6>
                        <small class="text-muted">الاستشارات</small>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h6>الوصف</h6>
                <p class="text-muted">${specialty.description || 'لا يوجد وصف متاح لهذا التخصص.'}</p>
                
                ${specialty.services ? `
                <h6 class="mt-3">الخدمات المقدمة</h6>
                <ul class="text-muted">
                    ${specialty.services.split(',').map(service => `<li>${service.trim()}</li>`).join('')}
                </ul>
                ` : ''}
                
                ${specialty.conditions ? `
                <h6 class="mt-3">الحالات التي يعالجها</h6>
                <ul class="text-muted">
                    ${specialty.conditions.split(',').map(condition => `<li>${condition.trim()}</li>`).join('')}
                </ul>
                ` : ''}
            </div>
        </div>
    `;
    
    document.getElementById('startConsultationBtn').href = `/consultations/create?specialty_id=${specialty.id}`;
    
    new bootstrap.Modal(document.getElementById('specialtyModal')).show();
}

// تأثيرات بصرية عند التحميل
document.addEventListener('DOMContentLoaded', function() {
    const specialtyItems = document.querySelectorAll('.specialty-item');
    
    specialtyItems.forEach((item, index) => {
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
        const modal = bootstrap.Modal.getInstance(document.getElementById('specialtyModal'));
        if (modal) {
            modal.hide();
        }
    }
});
</script>
@endpush 