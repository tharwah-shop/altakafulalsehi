<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بطاقات التكافل الصحي - متعددة</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        body {
            font-family: 'Cairo', sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .page {
            page-break-after: always;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .card-wrapper {
            display: flex;
            gap: 2vw;
            flex-wrap: wrap;
            justify-content: center;
        }
        .takaful-card {
            width: min(95vw, 970px);
            aspect-ratio: 1.6/1;
            min-width: 260px;
            max-width: 750px;
            border-radius: 2.2em;
            box-shadow: 0 8px 32px rgba(0,188,212,0.18), 0 2px 8px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            margin-bottom: 2vw;
        }
        .takaful-card.front {
            background: url('{{ public_path('images/cards/card-front.png') }}') center/cover no-repeat, linear-gradient(135deg, #00bcd4 0%, #00e676 100%);
        }
        .takaful-card.back {
            background: url('{{ public_path('images/cards/card-back.png') }}') center/cover no-repeat, linear-gradient(135deg, #00bcd4 0%, #00e676 100%);
        }
        .bg-squares {
            position: absolute;
            left: 0; right: 0; bottom: 0; top: 0;
            z-index: 1;
            pointer-events: none;
        }
        .bg-squares svg {
            width: 100%;
            height: 100%;
        }
        .corner-bg {
            position: absolute;
            left: 1.5em;
            top: 1.5em;
            width: 4.5em;
            height: 4.5em;
            background: rgba(255,255,255,0.12);
            border-radius: 1.2em;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .corner-bg img {
            width: 2.8em;
            height: 2.8em;
        }
        .card-content {
            position: relative;
            z-index: 2;
            padding: 2.2em 2.5em 1.5em 2.5em;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .card-title {
            font-size: clamp(1.2em, 1.8vw, 2.2em);
            font-weight: 800;
            color: #fff;
            text-align: right;
            letter-spacing: 0.5px;
            margin-bottom: 0.2em;
            margin-top: 3em;
        }
        .card-subtitle {
            font-size: clamp(1em, 1.3vw, 1.3em);
            color: #fff;
            font-weight: 500;
            text-align: right;
            margin-bottom: 2.2em;
        }
        .info-table {
            width: 100%;
            margin-bottom: 2.2em;
            font-size: clamp(1.1em, 1.4vw, 1.4em);
            border-collapse: separate;
            border-spacing: 0 0.7em;
        }
        .info-label {
            color: #fff;
            font-weight: 700;
            text-align: right;
            padding-left: 1.5em;
            white-space: nowrap;
            font-size: inherit;
        }
        .info-value {
            color: #fff;
            font-weight: 400;
            text-align: left;
            direction: ltr;
            font-size: inherit;
        }
        .divider {
            border: none;
            border-top: 2px solid rgba(255,255,255,0.18);
            margin: 1.5em 0 1.2em 0;
        }
        .footer-text {
            color: #fff;
            font-size: clamp(0.8em, 1vw, 1em);
            text-align: right;
            opacity: 0.85;
            margin-top: 0.7em;
            line-height: 1.6;
        }
        .qr-section {
            position: absolute;
            bottom: 2.5em;
            left: 2.5em;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .qr-section img {
            width: 6em;
            height: 6em;
            background: #fff;
            border-radius: 0.7em;
            padding: 0.5em;
            margin-bottom: 0.5em;
        }
        .qr-label {
            color: #fff;
            font-size: clamp(1em, 1.2vw, 1.2em);
            font-weight: 700;
            text-align: center;
        }
        /* Back card styles */
        .back-content {
            position: relative;
            z-index: 2;
            padding: 2.2em 2.5em 1.5em 2.5em;
            height: 100%;
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1em;
        }
        .back-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
        }
        .back-logo {
            margin-bottom: 0.7em;
            position: absolute;
            top: 2.2em;
            right: 2.5em;
            height: 2.8em;
            width: auto;
        }
        .back-logo img {
            height: 2.8em;
            width: auto;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.7em;
            display: block;
        }
        .back-title {
            font-size: clamp(1.3em, 1.7vw, 1.7em);
            color: #fff;
            font-weight: 800;
            text-align: right;
            margin-bottom: 0.2em;
            margin-top: 6em;
        }
        .back-desc {
            color: #fff;
            font-size: clamp(1em, 1.2vw, 1.2em);
            font-weight: 500;
            margin-bottom: 2.2em;
            text-align: right;
            line-height: 1.4;
        }
        .back-qr {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 0;
            margin-top: 5em;
            margin-right: 5em;
        }
        .card-number {
            color: #fff;
            font-size: clamp(1.1em, 1.4vw, 1.4em);
            font-weight: bold;
            letter-spacing: 2px;
            text-shadow: 0 2px 8px rgba(0,0,0,0.12);
            margin-bottom: 0.5em;
            text-align: center;
        }
        .contact-row {
            display: flex;
            align-items: center;
            justify-content: space-around;
            color: #fff;
            font-size: clamp(0.9em, 1.1vw, 1.1em);
            margin-top: auto;
            margin-bottom: 2.5em;
            width: 100%;
            padding: 0 0.2em;
        }
        .icon {
            margin-left: 0.2em;
        }
        @media print {
            body { margin: 0; padding: 0; }
            .card-wrapper { box-shadow: none; }
            .page { height: auto; min-height: 100vh; }
        }
    </style>
</head>
<body>
    @foreach($subscribers ?? [] as $index => $subscriber)
        <div class="page">
            <div class="card-wrapper">
                <!-- البطاقة الأمامية -->
                <div class="takaful-card front">
                    <div class="bg-squares">
                        <svg viewBox="0 0 500 320" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.13">
                                <rect x="20" y="20" width="24" height="24" rx="6" fill="#fff"/>
                                <rect x="80" y="60" width="18" height="18" rx="4" fill="#fff"/>
                                <rect x="180" y="100" width="16" height="16" rx="4" fill="#fff"/>
                                <rect x="300" y="40" width="20" height="20" rx="5" fill="#fff"/>
                                <rect x="400" y="200" width="24" height="24" rx="6" fill="#fff"/>
                                <rect x="350" y="270" width="18" height="18" rx="4" fill="#fff"/>
                                <rect x="120" y="250" width="16" height="16" rx="4" fill="#fff"/>
                            </g>
                        </svg>
                    </div>
                    <div class="corner-bg">
                        <img src="{{ public_path('images/icon.svg') }}" alt="logo" />
                    </div>
                    <div class="card-content">
                        <div class="card-title">بطاقة التكافل الصحي</div>
                        <div class="card-subtitle">حماية مالية ميسرة، ووصول سهل للرعاية الصحية بتكاليف منخفضة.</div>
                        <table class="info-table">
                            <tr>
                                <td class="info-label">رقم البطاقة</td>
                                <td class="info-value">{{ $subscriber->card_number }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">اسم المستفيد</td>
                                <td class="info-value">{{ $subscriber->name }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">تاريخ الانتهاء</td>
                                <td class="info-value">{{ $subscriber->end_date ? \Carbon\Carbon::parse($subscriber->end_date)->format('d/m/Y') : 'غير محدد' }}</td>
                            </tr>
                        </table>
                        <hr class="divider">
                        <div class="footer-text">
                            هذه البطاقة شخصية ولا تستخدم إلا من صاحبها. يجب إبراز البطاقة والإثبات الشخصي لدى المراكز الطبية للحصول على الخصم.
                        </div>
                    </div>
                    <div class="qr-section">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode('https://altakafulalsehi.com/verify/?card_id=' . $subscriber->card_number) }}" alt="QR Code" />
                        <div class="qr-label">التحقق من البطاقة</div>
                    </div>
                </div>
                <!-- البطاقة الخلفية -->
                <div class="takaful-card back">
                    <div class="bg-squares">
                        <svg viewBox="0 0 500 320" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.13">
                                <rect x="20" y="20" width="24" height="24" rx="6" fill="#fff"/>
                                <rect x="80" y="60" width="18" height="18" rx="4" fill="#fff"/>
                                <rect x="180" y="100" width="16" height="16" rx="4" fill="#fff"/>
                                <rect x="300" y="40" width="20" height="20" rx="5" fill="#fff"/>
                                <rect x="400" y="200" width="24" height="24" rx="6" fill="#fff"/>
                                <rect x="350" y="270" width="18" height="18" rx="4" fill="#fff"/>
                                <rect x="120" y="250" width="16" height="16" rx="4" fill="#fff"/>
                            </g>
                        </svg>
                    </div>
                    <div class="card-content back-content">
                        <div class="back-info">
                            <div class="back-logo">
                                <img src="{{ public_path('images/logo-white.svg') }}" alt="logo" />
                            </div>
                            <div class="back-title">التكافل الصحي</div>
                            <div class="back-desc">حماية مالية ميسرة، ووصول سهل للرعاية الصحية بتكاليف منخفضة.</div>
                        </div>
                        <div class="back-qr">
                            <div class="card-number">{{ $subscriber->card_number }}</div>
                            <div class="qr-section">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode('https://altakafulalsehi.com/verify/?card_id=' . $subscriber->card_number) }}" alt="QR Code" />
                                <div class="qr-label">التحقق من البطاقة</div>
                            </div>
                        </div>
                    </div>
                    <div class="contact-row">
                        <span><span class="icon">📞</span>+966920031304</span>
                        <span><span class="icon">📧</span>info@altakafulalsehi.com</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>
