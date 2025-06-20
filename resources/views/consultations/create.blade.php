@extends('layouts.frontend')

@section('title', 'استشارة طبية جديدة')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- رأس الصفحة -->
            <div class="text-center mb-5">
                <h1 class="h2 mb-3 text-primary">استشارة طبية جديدة</h1>
                <p class="text-muted">اختر التخصص والطبيب المناسب لاستشارتك الطبية</p>
            </div>

            <form action="{{ route('consultations.store') }}" method="POST" id="consultationForm">
                @csrf
                
                <!-- اختيار التخصص -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-md me-2"></i>
                            الخطوة الأولى: اختر التخصص الطبي
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="specialtiesContainer">
                            @foreach($specialties as $specialty)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="specialty-card" data-specialty-id="{{ $specialty->id }}">
                                    <div class="card h-100 border-2 specialty-option" 
                                         style="cursor: pointer; transition: all 0.3s;">
                                        <div class="card-body text-center p-3">
                                            @if($specialty->icon)
                                                <i class="{{ $specialty->icon }} fa-3x mb-3" 
                                                   style="color: {{ $specialty->color ?? '#007bff' }};"></i>
                                            @else
                                                <i class="fas fa-stethoscope fa-3x mb-3 text-primary"></i>
                                            @endif
                                            <h6 class="card-title mb-2">{{ $specialty->name }}</h6>
                                            @if($specialty->description)
                                                <small class="text-muted">{{ Str::limit($specialty->description, 60) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <input type="hidden" name="specialty_id" id="selectedSpecialty" required>
                        @error('specialty_id')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- اختيار الطبيب -->
                <div class="card shadow-sm mb-4" id="doctorsSection" style="display: none;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-md me-2"></i>
                            الخطوة الثانية: اختر الطبيب
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="doctorsContainer">
                            <!-- سيتم تحميل الأطباء هنا -->
                        </div>
                        
                        <input type="hidden" name="doctor_id" id="selectedDoctor" required>
                        @error('doctor_id')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- رسالة أولية (اختيارية) -->
                <div class="card shadow-sm mb-4" id="messageSection" style="display: none;">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-comment me-2"></i>
                            الخطوة الثالثة: رسالة أولية (اختيارية)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="initial_message" class="form-label">وصف مختصر لحالتك</label>
                            <textarea class="form-control" id="initial_message" name="initial_message" 
                                      rows="4" placeholder="اكتب وصفاً مختصراً لحالتك أو الأعراض التي تعاني منها..."></textarea>
                            <div class="form-text">هذا سيساعد الطبيب في فهم حالتك بشكل أفضل</div>
                        </div>
                    </div>
                </div>

                <!-- زر إنشاء الاستشارة -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg" id="createButton" disabled>
                        <i class="fas fa-plus-circle me-2"></i>
                        إنشاء الاستشارة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
                <h6>جاري إنشاء الاستشارة...</h6>
                <p class="text-muted mb-0">يرجى الانتظار</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.specialty-option {
    transition: all 0.3s ease;
}

.specialty-option:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.specialty-option.selected {
    border-color: #007bff !important;
    background-color: #f8f9ff;
}

.doctor-option {
    transition: all 0.3s ease;
}

.doctor-option:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.doctor-option.selected {
    border-color: #28a745 !important;
    background-color: #f8fff9;
}

.doctor-avatar {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.rating-stars {
    color: #ffc107;
}

.experience-badge {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    border-radius: 20px;
    padding: 2px 8px;
    font-size: 0.8rem;
}
</style>
@endpush

@push('scripts')
<script>
let selectedSpecialty = null;
let selectedDoctor = null;

// اختيار التخصص
document.querySelectorAll('.specialty-option').forEach(card => {
    card.addEventListener('click', function() {
        // إزالة التحديد من جميع البطاقات
        document.querySelectorAll('.specialty-option').forEach(c => {
            c.classList.remove('selected');
        });
        
        // تحديد البطاقة المختارة
        this.classList.add('selected');
        
        const specialtyId = this.closest('.specialty-card').dataset.specialtyId;
        selectedSpecialty = specialtyId;
        document.getElementById('selectedSpecialty').value = specialtyId;
        
        // إظهار قسم الأطباء
        document.getElementById('doctorsSection').style.display = 'block';
        
        // تحميل الأطباء
        loadDoctors(specialtyId);
        
        // إخفاء قسم الرسالة
        document.getElementById('messageSection').style.display = 'none';
        selectedDoctor = null;
        document.getElementById('selectedDoctor').value = '';
        updateCreateButton();
    });
});

// تحميل الأطباء
function loadDoctors(specialtyId) {
    const container = document.getElementById('doctorsContainer');
    container.innerHTML = '<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">جاري تحميل الأطباء...</p></div>';
    
    fetch(`/consultations/get-doctors?specialty_id=${specialtyId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.doctors.length > 0) {
                displayDoctors(data.doctors);
            } else {
                container.innerHTML = '<div class="col-12 text-center"><p class="text-muted">لا يوجد أطباء متاحون لهذا التخصص حالياً</p></div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<div class="col-12 text-center"><p class="text-danger">حدث خطأ في تحميل الأطباء</p></div>';
        });
}

// عرض الأطباء
function displayDoctors(doctors) {
    const container = document.getElementById('doctorsContainer');
    container.innerHTML = '';
    
    doctors.forEach(doctor => {
        const doctorCard = document.createElement('div');
        doctorCard.className = 'col-md-6 mb-3';
        doctorCard.innerHTML = `
            <div class="doctor-card" data-doctor-id="${doctor.id}">
                <div class="card h-100 border-2 doctor-option" style="cursor: pointer;">
                    <div class="card-body text-center p-3">
                        <img src="${doctor.avatar_url}" alt="${doctor.name}" class="doctor-avatar mb-3">
                        <h6 class="card-title mb-2">د. ${doctor.name}</h6>
                        <p class="text-muted mb-2">${doctor.specialty.name}</p>
                        
                        <div class="mb-2">
                            <span class="experience-badge">
                                <i class="fas fa-clock me-1"></i>
                                ${doctor.experience_years} سنوات خبرة
                            </span>
                        </div>
                        
                        ${doctor.rating ? `
                        <div class="rating-stars mb-2">
                            ${Array(5).fill().map((_, i) => 
                                `<i class="fas fa-star ${i < Math.round(doctor.rating) ? 'text-warning' : 'text-muted'}"></i>`
                            ).join('')}
                            <small class="text-muted">(${doctor.rating})</small>
                        </div>
                        ` : ''}
                        
                        <div class="text-primary fw-bold">
                            ${doctor.consultation_fee > 0 ? doctor.consultation_fee + ' ريال' : 'مجاناً'}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(doctorCard);
        
        // إضافة مستمع الحدث
        doctorCard.querySelector('.doctor-option').addEventListener('click', function() {
            // إزالة التحديد من جميع البطاقات
            document.querySelectorAll('.doctor-option').forEach(c => {
                c.classList.remove('selected');
            });
            
            // تحديد البطاقة المختارة
            this.classList.add('selected');
            
            selectedDoctor = doctor.id;
            document.getElementById('selectedDoctor').value = doctor.id;
            
            // إظهار قسم الرسالة
            document.getElementById('messageSection').style.display = 'block';
            
            updateCreateButton();
        });
    });
}

// تحديث حالة زر الإنشاء
function updateCreateButton() {
    const button = document.getElementById('createButton');
    if (selectedSpecialty && selectedDoctor) {
        button.disabled = false;
    } else {
        button.disabled = true;
    }
}

// إرسال النموذج
document.getElementById('consultationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // إظهار modal التحميل
    new bootstrap.Modal(document.getElementById('loadingModal')).show();
    
    // إرسال النموذج
    this.submit();
});
</script>
@endpush 