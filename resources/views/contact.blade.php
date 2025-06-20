@extends('layouts.frontend')

@section('title', 'تواصل معنا')

@section('content')
    <!-- Hero Section -->
    <section class="py-5 bg-primary text-white text-center">
        <div class="container">
            <div class="mb-3">
                <i class="bi bi-headset me-2"></i>
                خدمة العملاء المتميزة
            </div>
            <h1 class="display-4 fw-bold mb-3">تواصل معنا</h1>
            <p class="lead mb-4">نحن هنا لمساعدتك والإجابة على جميع استفساراتك وتقديم أفضل خدمة عملاء على مدار الساعة طوال أيام الأسبوع</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <button class="btn btn-light btn-lg px-4" disabled><i class="bi bi-telephone me-2"></i>اتصل بنا الآن</button>
                <button class="btn btn-outline-light btn-lg px-4" disabled><i class="bi bi-envelope me-2"></i>أرسل رسالة</button>
            </div>
        </div>
    </section>

    <!-- Contact Info Section -->
    <section class="container py-5">
        <div class="text-center mb-5">
            <span class="badge bg-primary mb-2"><i class="bi bi-phone me-2"></i>طرق التواصل</span>
            <h2 class="mb-3">كيف يمكنك الوصول إلينا</h2>
            <p class="text-muted">نحن متاحون لخدمتك عبر قنوات متعددة لضمان حصولك على أفضل تجربة وخدمة متميزة</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="mb-3"><i class="bi bi-telephone-fill display-4 text-primary"></i></div>
                        <h5 class="fw-bold mb-2">اتصل بنا مباشرة</h5>
                        <p class="text-muted">خدمة العملاء متاحة على مدار الساعة طوال أيام الأسبوع للرد على استفساراتك</p>
                        <div class="fw-bold">920000000</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="mb-3"><i class="bi bi-envelope-fill display-4 text-primary"></i></div>
                        <h5 class="fw-bold mb-2">البريد الإلكتروني</h5>
                        <p class="text-muted">نرد على رسائلك خلال 24 ساعة كحد أقصى مع ضمان الحصول على إجابة شاملة</p>
                        <div class="fw-bold">info@altakafulalsehi.com</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <div class="mb-3"><i class="bi bi-geo-alt-fill display-4 text-primary"></i></div>
                        <h5 class="fw-bold mb-2">موقعنا الجغرافي</h5>
                        <p class="text-muted mb-2">المملكة العربية السعودية<br>الرياض - حي الملقا<br>مجمع الأعمال التجاري</p>
                        <div class="fw-bold">عرض الموقع على الخريطة</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="container py-5">
        <div class="text-center mb-5">
            <span class="badge bg-primary mb-2"><i class="bi bi-envelope me-2"></i>نموذج التواصل</span>
            <h2 class="mb-3">أرسل رسالتك إلينا</h2>
            <p class="text-muted">يسعدنا تلقي استفساراتك واقتراحاتك وسنقوم بالرد عليك في أقرب وقت ممكن</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card p-4">
                    <form>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-person me-2"></i>الاسم الكامل</label>
                                    <input type="text" class="form-control" placeholder="أدخل اسمك الكامل" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-telephone me-2"></i>رقم الجوال</label>
                                    <input type="tel" class="form-control" placeholder="أدخل رقم جوالك" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-envelope me-2"></i>البريد الإلكتروني</label>
                                    <input type="email" class="form-control" placeholder="أدخل بريدك الإلكتروني" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-tag me-2"></i>الموضوع</label>
                                    <input type="text" class="form-control" placeholder="موضوع الرسالة" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-chat-dots me-2"></i>الرسالة</label>
                                    <textarea class="form-control" rows="5" placeholder="اكتب رسالتك هنا..." disabled></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-primary w-100" disabled><i class="bi bi-send"></i> إرسال الرسالة</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="container py-5">
        <div class="text-center mb-5">
            <span class="badge bg-primary mb-2"><i class="bi bi-question-circle me-2"></i>الأسئلة الشائعة</span>
            <h2 class="mb-3">الأسئلة الأكثر شيوعاً</h2>
            <p class="text-muted">إجابات شاملة على الأسئلة الأكثر شيوعاً حول خدماتنا وبطاقة التكافل الصحي</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                كيف يمكنني الحصول على بطاقة التكافل الصحي؟
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                يمكنك الحصول على البطاقة من خلال تعبئة نموذج الطلب عبر موقعنا الإلكتروني، اختيار الباقة المناسبة، وإتمام عملية الدفع الآمن. ستحصل على البطاقة فوراً بعد التفعيل.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                ما هي مدة صلاحية البطاقة وكيف يمكن تجديدها؟
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                مدة صلاحية البطاقة سنة كاملة من تاريخ التفعيل. يمكنك تجديدها بسهولة عبر الموقع الإلكتروني أو التطبيق الذكي قبل انتهاء الصلاحية.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                هل يمكن استخدام البطاقة لجميع أفراد العائلة؟
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                نعم، يمكن استخدام البطاقة من قبل جميع أفراد العائلة المسجلين في الباقة. تشمل التغطية الزوج/الزوجة والأطفال حتى سن 25 عاماً.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                ما هي المراكز الطبية المشمولة بالخدمة؟
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                لدينا شبكة واسعة تضم أكثر من 3500 مركز طبي معتمد في جميع أنحاء المملكة، تشمل المستشفيات والعيادات والمراكز التخصصية.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                كيف يمكنني حجز موعد في المراكز الطبية؟
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                يمكنك حجز موعد بعدة طرق: عبر التطبيق الذكي، الموقع الإلكتروني، أو الاتصال المباشر بخدمة العملاء على الرقم 920000000.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


