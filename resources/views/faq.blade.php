@extends('layouts.frontend')

@section('title', 'الأسئلة الشائعة')

@section('content')
<section class="container py-5">
    <div class="text-center mb-5">
        <span class="badge bg-primary mb-2"><i class="bi bi-question-circle me-2"></i>الأسئلة الشائعة</span>
        <h1 class="mb-3">الأسئلة الشائعة</h1>
        <p class="lead">هنا تجد إجابات لأكثر الأسئلة شيوعاً حول بطاقة التكافل الصحي وخدماتنا</p>
    </div>
    <div class="accordion" id="faqAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq1">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                    ما هي بطاقة التكافل الصحي؟
                </button>
            </h2>
            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    بطاقة التكافل الصحي هي بطاقة خصومات طبية تتيح لك ولعائلتك الاستفادة من خصومات حصرية في شبكة واسعة من المراكز الطبية والصيدليات.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                    كيف يمكنني الحصول على البطاقة؟
                </button>
            </h2>
            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    يمكنك الاشتراك عبر موقعنا الإلكتروني والحصول على البطاقة فوراً بشكل رقمي على هاتفك أو بريدك الإلكتروني.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq3">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                    هل البطاقة تغطي جميع أفراد الأسرة؟
                </button>
            </h2>
            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    نعم، يمكنك إضافة جميع أفراد الأسرة للاستفادة من الخصومات والمزايا.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq4">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                    ما هي مدة صلاحية البطاقة؟
                </button>
            </h2>
            <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    البطاقة صالحة لمدة عام كامل من تاريخ التفعيل.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq5">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                    كيف يمكنني التواصل مع الدعم الفني؟
                </button>
            </h2>
            <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    يمكنك التواصل مع فريق الدعم عبر صفحة <a href="/contact">اتصل بنا</a> أو عبر الرقم الموحد.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
