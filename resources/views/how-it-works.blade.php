@extends('layouts.frontend')
@section('title', 'كيف تعمل البطاقة')
@section('content')
<section class="container py-5">
    <div class="text-center mb-5">
        <span class="badge bg-primary mb-2"><i class="bi bi-gear me-2"></i>كيف تعمل البطاقة</span>
        <h1 class="mb-3">كيف تعمل بطاقة التكافل الصحي؟</h1>
        <p class="lead">تعرف على خطوات الاشتراك واستخدام بطاقة التكافل الصحي والاستفادة من جميع المزايا الطبية والخصومات.</p>
    </div>
    <div class="row g-4 mb-5">
        <div class="col-md-3 col-6 text-center">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-3"><i class="bi bi-person-plus display-5 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">1. التسجيل</h5>
                    <p class="text-muted">سجّل بياناتك عبر الموقع أو التطبيق.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 text-center">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-3"><i class="bi bi-credit-card display-5 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">2. اختيار الباقة</h5>
                    <p class="text-muted">اختر الباقة المناسبة لك ولعائلتك.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 text-center">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-3"><i class="bi bi-cash-coin display-5 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">3. الدفع</h5>
                    <p class="text-muted">ادفع عبر وسائل الدفع المتاحة بأمان.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 text-center">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-3"><i class="bi bi-check-circle display-5 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">4. التفعيل والاستخدام</h5>
                    <p class="text-muted">استلم بطاقتك وابدأ باستخدامها فوراً في المراكز الطبية.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-3">ملاحظات هامة:</h3>
            <ul class="list-group">
                <li class="list-group-item">البطاقة صالحة للاستخدام في جميع المراكز الطبية المعتمدة.</li>
                <li class="list-group-item">يمكنك إضافة أفراد الأسرة في أي وقت.</li>
                <li class="list-group-item">الدعم الفني متاح على مدار الساعة.</li>
                <li class="list-group-item">لا توجد فترات انتظار أو موافقات مسبقة.</li>
            </ul>
        </div>
    </div>
    <div class="text-center mt-5">
        <a href="/subscribe" class="btn btn-primary btn-lg"><i class="bi bi-plus-circle me-2"></i>اشترك الآن</a>
    </div>
</section>
@endsection


