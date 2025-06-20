@extends('layouts.frontend')
@section('title', 'آراء العملاء')
@section('content')
<section class="container py-5">
    <div class="text-center mb-5">
        <span class="badge bg-primary mb-2"><i class="bi bi-chat-quote me-2"></i>آراء العملاء</span>
        <h1 class="mb-3">ماذا قال عملاؤنا؟</h1>
        <p class="lead">تعرف على تجارب وآراء عملائنا الذين استفادوا من بطاقة التكافل الصحي.</p>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card h-100 text-center p-4">
                <i class="bi bi-emoji-smile display-4 text-success mb-3"></i>
                <blockquote class="blockquote mb-3">"بطاقة التكافل الصحي وفرت لي الكثير من التكاليف الطبية. أنصح بها للجميع!"</blockquote>
                <footer class="blockquote-footer">أحمد الشهري - الرياض</footer>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center p-4">
                <i class="bi bi-emoji-heart-eyes display-4 text-danger mb-3"></i>
                <blockquote class="blockquote mb-3">"خدمة العملاء ممتازة والشبكة الطبية واسعة. تجربة رائعة مع البطاقة."</blockquote>
                <footer class="blockquote-footer">فاطمة العتيبي - جدة</footer>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center p-4">
                <i class="bi bi-emoji-laughing display-4 text-warning mb-3"></i>
                <blockquote class="blockquote mb-3">"استفدت من خصومات كبيرة في عدة تخصصات. البطاقة فعلاً مميزة."</blockquote>
                <footer class="blockquote-footer">خالد المطيري - الدمام</footer>
            </div>
        </div>
    </div>
    <div class="text-center mt-5">
        <a href="/subscribe" class="btn btn-primary btn-lg"><i class="bi bi-plus-circle me-2"></i>اشترك الآن</a>
    </div>
</section>
@endsection

