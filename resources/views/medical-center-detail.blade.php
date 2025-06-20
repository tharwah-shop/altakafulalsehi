@extends('layouts.frontend')
@section('title', $center->name)
@section('content')
<!-- Hero Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2 text-center mb-3 mb-md-0">
                @if($center->image)
                    <img src="{{ $center->image_url }}" alt="{{ $center->name }}"
                         class="rounded-circle border border-3 border-white"
                         style="width: 90px; height: 90px; object-fit: cover;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                @else
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center border border-3 border-white"
                         style="width: 90px; height: 90px;">
                        <i class="bi bi-hospital text-primary fs-2"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-7">
                <h1 class="h3 fw-bold mb-2">{{ $center->name }}</h1>
                <p class="mb-0">{{ $center->description }}</p>
            </div>
            <div class="col-md-3 text-md-end text-center mt-3 mt-md-0">
                <a href="#discounts" class="btn btn-light text-primary fw-bold px-4">احصل على الخصم</a>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-4">
    <div class="container">
        <div class="row g-4 flex-row-reverse">
            <!-- Sidebar: Center Info -->
            <aside class="col-lg-4 order-lg-2">
                <div class="card mb-4">
                    <div class="card-header bg-white border-bottom-0">
                        <h5 class="mb-0 text-primary"><i class="bi bi-info-circle me-1"></i> معلومات المركز</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-geo-alt-fill text-primary fs-5 me-2"></i>
                                <span>{{ $center->city }}، {{ $center->region }}</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-telephone-fill text-warning fs-5 me-2"></i>
                                <span>{{ $center->phone ?? '-' }}</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-pin-map-fill text-secondary fs-5 me-2"></i>
                                <span>{{ $center->address ?? '-' }}</span>
                            </li>
                        </ul>
                        <div class="mt-4">
                            <div class="mb-2 fw-bold">الموقع على الخريطة</div>
                            <div class="ratio ratio-16x9 rounded bg-light">
                                @if($center->location)
                                    <iframe src="{{ $center->location }}" frameborder="0" allowfullscreen></iframe>
                                @else
                                    <div class="text-muted">لا يوجد موقع محدد</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Info Card -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="mb-2"><i class="bi bi-people-fill me-1"></i> عدد التقييمات: {{ $center->reviews->count() }}</div>
                        <div class="mb-2"><i class="bi bi-geo-alt me-1"></i> المنطقة: <span class="fw-bold">{{ $center->region }}</span></div>
                        @if($center->contract_status)
                            <div class="mb-2"><i class="bi bi-file-earmark-check me-1"></i> حالة التعاقد: <span class="fw-bold">{{ $center->contract_status_name }}</span></div>
                        @endif
                        @if($center->medical_discounts && count($center->medical_discounts) > 0)
                            <div><i class="bi bi-percent me-1"></i> خصومات متاحة</div>
                        @endif
                    </div>
                </div>
            </aside>
            <!-- Main Content -->
            <div class="col-lg-8 order-lg-1">
                <!-- Discounts Card -->
                <div class="card mb-4" id="discounts">
                    <div class="card-header bg-white border-bottom-0">
                        <h5 class="mb-0 text-primary"><i class="bi bi-percent me-1"></i> الخصومات الطبية</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>الخدمة الطبية</th>
                                        <th>قيمة الخصم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(is_array($center->medical_discounts))
                                        @foreach($center->medical_discounts as $discount)
                                            <tr>
                                                <td>{{ $discount['service'] ?? '-' }}</td>
                                                <td><span class="badge bg-success fs-6">{{ $discount['discount'] ?? '-' }}</span></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="2">لا توجد بيانات خصم</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">
                            <div class="mb-2">أنواع الخدمات المتوفرة:</div>
                            @if(is_array($center->medical_service_types) && count($center->medical_service_types) > 0)
                                @php
                                    $serviceNames = [
                                        'dentistry' => 'الأسنان',
                                        'surgical-procedures' => 'العمليات الجراحية',
                                        'laboratory-tests' => 'التحاليل',
                                        'ophthalmology' => 'العيون',
                                        'check-ups' => 'الكشوفات',
                                        'medications' => 'الادوية',
                                        'emergency' => 'الطوارئ',
                                        'dermatology' => 'الجلدية',
                                        'pharmacy' => 'الصيدلية',
                                        'orthopedics' => 'العظام',
                                        'clinics' => 'العيادات',
                                        'pregnancy-birth' => 'الحمل والولادة',
                                        'lasik' => 'الليزك',
                                        'radiology' => 'الأشعة',
                                        'cosmetics' => 'التجميل',
                                        'laboratory' => 'المختبر',
                                        'hospitalization' => 'التنويم',
                                        'other-services' => 'خدمات اخرى',
                                    ];
                                @endphp
                                @foreach($center->medical_service_types as $service)
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary me-2 mb-1">{{ $serviceNames[$service] ?? $service }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">لا توجد بيانات خدمات</span>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Ratings System -->
                <div class="card mb-4">
                    <div class="card-header bg-white border-bottom-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 text-primary"><i class="bi bi-star-fill me-1"></i> تقييمات المشتركين</h5>
                        <span class="fw-bold text-warning"><i class="bi bi-star-fill"></i> {{ number_format($center->reviews->avg('rating'), 1) }}/5</span>
                    </div>
                    <div class="card-body">
                        <!-- Ratings List -->
                        <div class="mb-4">
                            @forelse($center->reviews as $review)
                            <div class="mb-3 border-bottom pb-2">
                                <div class="d-flex align-items-center mb-1">
                                    <span class="fw-bold me-2">{{ $review->reviewer_name }}</span>
                                    <span class="text-warning small">
                                        @for($i=1; $i<=5; $i++)
                                            @if($review->rating >= $i)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </span>
                                    <span class="text-muted small ms-2">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-muted">{{ $review->comment }}</div>
                            </div>
                            @empty
                                <div class="text-muted">لا توجد تقييمات بعد.</div>
                            @endforelse
                        </div>
                        <!-- Add Rating Form -->
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form method="POST" action="{{ route('medical-center.review', $center->slug) }}">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">اسمك</label>
                                <input type="text" name="reviewer_name" class="form-control" placeholder="أدخل اسمك" required value="{{ old('reviewer_name') }}">
                                @error('reviewer_name')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-2">
                                <label class="form-label">تقييمك</label>
                                <select name="rating" class="form-select w-auto d-inline-block" required>
                                    <option value="5">5 نجوم</option>
                                    <option value="4">4 نجوم</option>
                                    <option value="3">3 نجوم</option>
                                    <option value="2">2 نجوم</option>
                                    <option value="1">1 نجمة</option>
                                </select>
                                @error('rating')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-2">
                                <label class="form-label">تعليقك</label>
                                <textarea name="comment" class="form-control" rows="2" placeholder="اكتب تعليقك هنا" required>{{ old('comment') }}</textarea>
                                @error('comment')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- CTA Section -->
<section class="py-5 bg-primary bg-opacity-75">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <h3 class="text-white fw-bold mb-2">احصل على خصومات حصرية في هذا المركز</h3>
                <p class="text-white-50 mb-0">انضم إلى شبكة التكافل الصحي واستمتع بخصومات تصل إلى {{ $center->max_discount }}% في هذا المركز الطبي</p>
            </div>
            <div class="col-lg-4 text-lg-end text-center">
                <a href="#" class="btn btn-light btn-lg px-5 disabled">اشترك الآن</a>
            </div>
        </div>
    </div>
</section>
@endsection