<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار المدن</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>اختبار نظام المدن والمناطق</h1>
        
        <div class="row">
            <div class="col-md-6">
                <h3>جميع المدن</h3>
                <div class="card">
                    <div class="card-body">
                        @if(isset($cities) && $cities->count() > 0)
                            <p class="text-success">عدد المدن: {{ $cities->count() }}</p>
                            <select class="form-select">
                                <option value="">اختر المدينة</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city['name'] }}">{{ $city['name'] }} - {{ $city['region_name'] }}</option>
                                @endforeach
                            </select>
                        @else
                            <p class="text-danger">لا توجد مدن متاحة</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <h3>جميع المناطق</h3>
                <div class="card">
                    <div class="card-body">
                        @if(isset($regions) && $regions->count() > 0)
                            <p class="text-success">عدد المناطق: {{ $regions->count() }}</p>
                            <ul class="list-group">
                                @foreach($regions as $region)
                                    <li class="list-group-item">
                                        {{ $region['name'] }} ({{ $region['name_en'] }})
                                        <span class="badge bg-primary">{{ count($region['cities']) }} مدينة</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-danger">لا توجد مناطق متاحة</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h3>تفاصيل التصحيح</h3>
            <div class="card">
                <div class="card-body">
                    <pre>{{ print_r(compact('cities', 'regions'), true) }}</pre>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
