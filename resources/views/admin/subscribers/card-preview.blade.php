@extends('layouts.admin')

@section('title', 'معاينة بطاقة المشترك')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">معاينة بطاقة المشترك</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.subscribers.index') }}">المشتركين</a></li>
                    <li class="breadcrumb-item active">معاينة البطاقة</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.subscribers.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>
                العودة
            </a>
            <button type="button" class="btn btn-primary" onclick="printCard()">
                <i class="fas fa-print me-1"></i>
                طباعة
            </button>
            <a href="{{ route('admin.subscribers.card-pdf', $subscriber) }}" class="btn btn-success" target="_blank">
                <i class="fas fa-download me-1"></i>
                تحميل PDF
            </a>
        </div>
    </div>

    <!-- Card Preview -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg" id="subscriber-card">
                <div class="card-body p-0">
                    <!-- Front Side -->
                    <div class="card-front" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 15px; position: relative; overflow: hidden;">
                        <!-- Background Pattern -->
                        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 1;"></div>
                        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%; z-index: 1;"></div>
                        
                        <!-- Card Content -->
                        <div style="position: relative; z-index: 2;">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h4 class="fw-bold mb-1">التكافل الصحي</h4>
                                    <p class="mb-0 opacity-75">بطاقة العضوية</p>
                                </div>
                                <div class="text-end">
                                    <div class="bg-white text-dark px-3 py-1 rounded-pill">
                                        <small class="fw-bold">{{ $subscriber->status }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Subscriber Info -->
                            <div class="row mb-4">
                                <div class="col-8">
                                    <div class="mb-3">
                                        <label class="small opacity-75 mb-1">اسم المشترك</label>
                                        <h5 class="fw-bold mb-0">{{ $subscriber->name }}</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="small opacity-75 mb-1">رقم البطاقة</label>
                                            <div class="fw-bold h6 mb-0 font-monospace">{{ $subscriber->card_number }}</div>
                                        </div>
                                        <div class="col-6">
                                            <label class="small opacity-75 mb-1">الجنسية</label>
                                            <div class="fw-bold">{{ $subscriber->nationality }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fas fa-user text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Package Info -->
                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="small opacity-75 mb-1">الباقة</label>
                                    <div class="fw-bold">{{ $subscriber->package->name ?? 'غير محدد' }}</div>
                                </div>
                            </div>

                            <!-- Validity -->
                            <div class="row">
                                <div class="col-6">
                                    <label class="small opacity-75 mb-1">تاريخ البداية</label>
                                    <div class="fw-bold">{{ $subscriber->start_date->format('Y/m/d') }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="small opacity-75 mb-1">تاريخ الانتهاء</label>
                                    <div class="fw-bold">{{ $subscriber->end_date->format('Y/m/d') }}</div>
                                </div>
                            </div>

                            <!-- QR Code Placeholder -->
                            <div class="position-absolute" style="bottom: 20px; right: 20px;">
                                <div class="bg-white p-2 rounded">
                                    <div style="width: 60px; height: 60px; background: #000; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px;">
                                        QR
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back Side -->
                    <div class="card-back mt-4" style="background: #f8f9fa; padding: 30px; border-radius: 15px; border: 2px solid #e9ecef;">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    معلومات الاتصال
                                </h6>
                                <div class="mb-2">
                                    <strong>الجوال:</strong> {{ $subscriber->formatted_phone }}
                                </div>
                                @if($subscriber->email)
                                <div class="mb-2">
                                    <strong>البريد:</strong> {{ $subscriber->email }}
                                </div>
                                @endif
                                <div class="mb-2">
                                    <strong>رقم الهوية:</strong> {{ $subscriber->id_number }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="fas fa-users me-2"></i>
                                    التابعين ({{ $subscriber->dependents->count() }})
                                </h6>
                                @if($subscriber->dependents->count() > 0)
                                    @foreach($subscriber->dependents as $dependent)
                                    <div class="mb-2 p-2 bg-white rounded border">
                                        <div class="fw-bold">{{ $dependent->name }}</div>
                                        <small class="text-muted">{{ $dependent->nationality }}</small>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">لا يوجد تابعين</p>
                                @endif
                            </div>
                        </div>

                        @if($subscriber->package && $subscriber->package->features)
                        <hr>
                        <h6 class="fw-bold text-warning mb-3">
                            <i class="fas fa-star me-2"></i>
                            مميزات الباقة
                        </h6>
                        <div class="row">
                            @foreach($subscriber->package->features as $feature)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <small>{{ $feature }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <hr>
                        <div class="text-center">
                            <p class="mb-1 fw-bold">للاستفسارات والدعم الفني</p>
                            <p class="mb-0">
                                <i class="fas fa-phone me-1"></i> 920000000
                                <span class="mx-2">|</span>
                                <i class="fas fa-envelope me-1"></i> support@altakafulalsehi.com
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">معلومات إضافية</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">تاريخ الإنشاء:</label>
                                <span>{{ $subscriber->created_at->format('Y/m/d H:i') }}</span>
                            </div>
                            @if($subscriber->creator)
                            <div class="mb-3">
                                <label class="fw-bold">أنشئ بواسطة:</label>
                                <span>{{ $subscriber->creator->name }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">الأيام المتبقية:</label>
                                <span class="badge {{ $subscriber->days_remaining > 30 ? 'bg-success' : ($subscriber->days_remaining > 7 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $subscriber->days_remaining }} يوم
                                </span>
                            </div>
                            @if($subscriber->source)
                            <div class="mb-3">
                                <label class="fw-bold">مصدر الاشتراك:</label>
                                <span>{{ $subscriber->source }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .container-fluid > div:first-child,
    .card:last-child,
    .btn {
        display: none !important;
    }
    
    #subscriber-card {
        box-shadow: none !important;
        border: none !important;
    }
    
    .card-front {
        page-break-after: always;
    }
}

.font-monospace {
    font-family: 'Courier New', monospace;
}
</style>

<script>
function printCard() {
    window.print();
}
</script>
@endsection
