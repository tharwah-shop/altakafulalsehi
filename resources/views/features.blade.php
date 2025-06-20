@extends('layouts.frontend')

@section('title', 'مميزات البطاقة')

@section('content')
<section class="container py-5">
    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-7 text-center text-lg-end mb-4 mb-lg-0">
            <h1 class="display-4 fw-bold mb-3">مميزات البطاقة</h1>
            <p class="lead">استفد من بطاقة التكافل الصحي المميزة، توفير تكاليف، تغطية شاملة، وسهولة الوصول إلى الرعاية الصحية. تجربة رعاية متقدمة تلبي احتياجاتك الفردية بكفاءة وراحة.</p>
            <div class="d-flex justify-content-center justify-content-lg-end gap-2 mt-4">
                <button class="btn btn-primary btn-lg" disabled>اطلب بطاقتك الآن</button>
            </div>
        </div>
        <div class="col-lg-5 text-center">
            <img src="/logo.png" alt="شعار التكافل الصحي" class="img-fluid" style="max-width: 300px;">
        </div>
    </div>

    <!-- الخدمات الطبية التي يشملها الخصم -->
    <div class="bg-light rounded-4 p-4 mb-5">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h3 class="fw-bold mb-3">ماهي الخدمات الطبية التي يشملها الخصم؟</h3>
                <p class="text-muted">الخصم يشمل مجموعة واسعة من الخدمات الطبية، بما في ذلك الزيارات الطبية، والفحوصات التشخيصية، والأدوية، مما يتيح للمستفيدين الاستفادة من الرعاية الصحية بتكلفة أقل.</p>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item border-0 ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i>الاسنان</li>
                    <li class="list-group-item border-0 ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i>العظام</li>
                    <li class="list-group-item border-0 ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i>العمليات الجراحية</li>
                    <li class="list-group-item border-0 ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i>العيادات</li>
                    <li class="list-group-item border-0 ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i>التحاليل</li>
                    <li class="list-group-item border-0 ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i>الحمل و الولادة</li>
                    <li class="list-group-item border-0 ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i>العيون</li>
                    <li class="list-group-item border-0 ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i>الليزك</li>
                    <li class="list-group-item border-0 ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i>الكشوفات</li>
                    <li class="list-group-item border-0 ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i>الأشعة</li>
                    <li class="list-group-item border-0 ps-0"><i class="bi bi-check-circle-fill text-success me-2"></i>وهناك الكثير من الخدمات الأخرى....</li>
                </ul>
                <button class="btn btn-success px-4" disabled>اطلب البطاقة الآن</button>
            </div>
            <div class="col-md-6 text-center">
                <div class="bg-info bg-opacity-10 rounded-4 p-5 h-100 d-flex align-items-center justify-content-center">
                    <i class="bi bi-heart-pulse display-1 text-info"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- مميزات الحصول على البطاقة -->
    <div class="mb-5">
        <h2 class="fw-bold text-center mb-4">مميزات الحصول على بطاقة التكافل الصحي.</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm p-3">
                    <div class="mb-3"><i class="bi bi-people-fill display-4 text-success"></i></div>
                    <h5 class="fw-bold mb-2">رعاية شاملة للجميع</h5>
                    <p class="text-muted">حل تأمين متكامل يخدم المواطنين والمقيمين والزوار والمعتمرين بتغطية واسعة وخدمات صحية حديثة.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm p-3">
                    <div class="mb-3"><i class="bi bi-cash-coin display-4 text-success"></i></div>
                    <h5 class="fw-bold mb-2">توفير التكاليف</h5>
                    <p class="text-muted">يتيح للمستفيدين تحمل تكاليف الرعاية الصحية بشكل أقل من خلال توفير خصومات وتخفيضات تصل إلى 80%.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm p-3">
                    <div class="mb-3"><i class="bi bi-geo-alt-fill display-4 text-success"></i></div>
                    <h5 class="fw-bold mb-2">تغطية شاملة</h5>
                    <p class="text-muted">بطاقة التكافل الصحي تضمن لك الوصول إلى أكثر من 3500 مركز طبي ومستشفى في المملكة.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm p-3">
                    <div class="mb-3"><i class="bi bi-exclamation-triangle-fill display-4 text-success"></i></div>
                    <h5 class="fw-bold mb-2">تغطية للطوارئ</h5>
                    <p class="text-muted">يتيح للمستفيدين الوصول الفوري والمتقدم إلى خدمات الطوارئ دون التفكير في التكاليف.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm p-3">
                    <div class="mb-3"><i class="bi bi-lightning-charge-fill display-4 text-success"></i></div>
                    <h5 class="fw-bold mb-2">رعاية فورية بلا انتظار</h5>
                    <p class="text-muted">بطاقة التكافل الصحي تضمن لك الوصول السريع إلى جميع التخصصات دون موافقات مسبقة أو أي فترات انتظار.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm p-3">
                    <div class="mb-3"><i class="bi bi-infinity display-4 text-success"></i></div>
                    <h5 class="fw-bold mb-2">بدون حد تأميني</h5>
                    <p class="text-muted">نقدم الحرية في الاستفادة من الخدمات الطبية دون القلق بشأن الحدود التأمينية، مما يوفر تجربة رعاية صحية مريحة ومرنة للجميع.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- لماذا تحتاج البطاقة -->
    <div class="row align-items-center my-5">
        <div class="col-lg-5 text-center mb-4 mb-lg-0">
            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 320px; height: 320px;">
                <img src="/logo.png" alt="شعار التكافل الصحي" class="img-fluid" style="max-width: 200px;">
            </div>
        </div>
        <div class="col-lg-7">
            <h2 class="fw-bold mb-3"><span class="text-success">لماذا تحتاج</span> <span class="text-primary">بطاقة التكافل الصحي</span>؟</h2>
            <p class="mb-4">تحتاج إلى بطاقة التكافل الصحي لضمان حماية مالية أثناء تلقي الرعاية الطبية، مع تقليل تكاليف العلاج وتوفير سهولة الوصول إلى مقدمي الخدمات الصحية. توفير الراحة والأمان المالي يجعلان بطاقة التكافل الصحي شريكًا أساسيًا لتحسين صحتك وجودة حياتك.</p>
            <button class="btn btn-success btn-lg" disabled>اطلب البطاقة الآن</button>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="text-center mt-5">
        <h4 class="mb-3">ماعندك البطاقة للحين؟ اطلبها الحين ووفر الكثير من الاموال واحصل على خصومات طبية رهيبة</h4>
        <button class="btn btn-primary btn-lg" disabled>اطلب بطاقتك الآن</button>
    </div>
</section>
@endsection






