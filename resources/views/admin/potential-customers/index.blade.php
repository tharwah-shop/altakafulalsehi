@extends('layouts.admin')

@section('title', 'العملاء المحتملين')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
    </div>
@endif

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">
                            <i class="fas fa-users me-2"></i>العملاء المحتملين
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.potential-customers.import-form') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-file-import me-1"></i> استيراد
                            </a>
                            <div class="btn-group">
                                <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-download me-1"></i> تصدير
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="exportData('csv')">
                                            <i class="fas fa-file-csv me-2"></i> تصدير CSV
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="exportData('excel')">
                                            <i class="fas fa-file-excel me-2"></i> تصدير Excel
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="exportData('csv', true)">
                                            <i class="fas fa-filter me-2"></i> تصدير CSV مفلتر
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="exportData('excel', true)">
                                            <i class="fas fa-filter me-2"></i> تصدير Excel مفلتر
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route('admin.potential-customers.download-template') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-download me-1"></i> نموذج الاستيراد
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- الإحصائيات -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1">{{ number_format($statistics['total']) }}</h3>
                                    <small>إجمالي العملاء</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1">{{ number_format($statistics['today']) }}</h3>
                                    <small>اليوم</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1">{{ number_format($statistics['this_week']) }}</h3>
                                    <small>هذا الأسبوع</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1">{{ number_format($statistics['this_month']) }}</h3>
                                    <small>هذا الشهر</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات الحالات -->
                    <div class="row mb-4">
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                            <div class="card border-warning">
                                <div class="card-body text-center py-2">
                                    <h5 class="text-warning mb-1">{{ number_format($statistics['pending']) }}</h5>
                                    <small class="text-muted">لم يتم التواصل</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                            <div class="card border-info">
                                <div class="card-body text-center py-2">
                                    <h5 class="text-info mb-1">{{ number_format($statistics['contacted']) }}</h5>
                                    <small class="text-muted">تم التواصل</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                            <div class="card border-success">
                                <div class="card-body text-center py-2">
                                    <h5 class="text-success mb-1">{{ number_format($statistics['issued']) }}</h5>
                                    <small class="text-muted">تم الإصدار</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                            <div class="card border-danger">
                                <div class="card-body text-center py-2">
                                    <h5 class="text-danger mb-1">{{ number_format($statistics['rejected']) }}</h5>
                                    <small class="text-muted">رفض</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                            <div class="card border-primary">
                                <div class="card-body text-center py-2">
                                    <h5 class="text-primary mb-1">{{ number_format($statistics['converted']) }}</h5>
                                    <small class="text-muted">تم التحويل</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                            <div class="card border-secondary">
                                <div class="card-body text-center py-2">
                                    <h5 class="text-secondary mb-1">{{ number_format($statistics['total'] - $statistics['converted']) }}</h5>
                                    <small class="text-muted">لم يتم التحويل</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- فلاتر البحث -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">فلاتر البحث</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.potential-customers.index') }}" class="row g-3">
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label">البحث</label>
                                    <input type="text" name="search" class="form-control" placeholder="الاسم، الجوال، البريد الإلكتروني" value="{{ request('search') }}">
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label">المدينة</label>
                                    <select name="city_id" class="form-select">
                                        <option value="">جميع المدن</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city['name'] }}" {{ request('city_id') == $city['name'] ? 'selected' : '' }}>
                                                {{ $city['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label">الحالة</label>
                                    <select name="status" class="form-select">
                                        <option value="">جميع الحالات</option>
                                        <option value="لم يتم التواصل" {{ request('status') == 'لم يتم التواصل' ? 'selected' : '' }}>لم يتم التواصل</option>
                                        <option value="لم يرد" {{ request('status') == 'لم يرد' ? 'selected' : '' }}>لم يرد</option>
                                        <option value="رفض" {{ request('status') == 'رفض' ? 'selected' : '' }}>رفض</option>
                                        <option value="تأجيل" {{ request('status') == 'تأجيل' ? 'selected' : '' }}>تأجيل</option>
                                        <option value="تم الاصدار" {{ request('status') == 'تم الاصدار' ? 'selected' : '' }}>تم الاصدار</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label">المصدر</label>
                                    <select name="source" class="form-select">
                                        <option value="">جميع المصادر</option>
                                        <option value="google_ads" {{ request('source') == 'google_ads' ? 'selected' : '' }}>إعلانات جوجل</option>
                                        <option value="facebook_ads" {{ request('source') == 'facebook_ads' ? 'selected' : '' }}>إعلانات فيسبوك</option>
                                        <option value="direct" {{ request('source') == 'direct' ? 'selected' : '' }}>دخول مباشر</option>
                                        <option value="organic" {{ request('source') == 'organic' ? 'selected' : '' }}>بحث طبيعي</option>
                                        <option value="referral" {{ request('source') == 'referral' ? 'selected' : '' }}>إحالة</option>
                                        <option value="social" {{ request('source') == 'social' ? 'selected' : '' }}>وسائل التواصل</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label">نوع الجهاز</label>
                                    <select name="device_type" class="form-select">
                                        <option value="">جميع الأجهزة</option>
                                        <option value="mobile" {{ request('device_type') == 'mobile' ? 'selected' : '' }}>جوال</option>
                                        <option value="desktop" {{ request('device_type') == 'desktop' ? 'selected' : '' }}>كمبيوتر</option>
                                        <option value="tablet" {{ request('device_type') == 'tablet' ? 'selected' : '' }}>تابلت</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label">من تاريخ</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label">إلى تاريخ</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label">عدد النتائج</label>
                                    <select name="per_page" class="form-select">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> بحث
                                        </button>
                                        <a href="{{ route('admin.potential-customers.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> إعادة تعيين
                                        </a>
                                        <button type="button" class="btn btn-info" onclick="toggleAdvancedStats()">
                                            <i class="fas fa-chart-bar"></i> إحصائيات متقدمة
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- الإحصائيات المتقدمة -->
                    <div class="card mb-4" id="advanced-stats" style="display: none;">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>إحصائيات متقدمة
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- إحصائيات المصادر -->
                                <div class="col-lg-4 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">توزيع المصادر</h6>
                                        </div>
                                        <div class="card-body">
                                            @if(!empty($statistics['by_source']))
                                                @foreach($statistics['by_source'] as $source => $count)
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">{{ $source ?: 'غير محدد' }}</span>
                                                        <span class="badge bg-primary">{{ number_format($count) }}</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted">لا توجد بيانات</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- إحصائيات الأجهزة -->
                                <div class="col-lg-4 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">توزيع الأجهزة</h6>
                                        </div>
                                        <div class="card-body">
                                            @if(!empty($statistics['by_device']))
                                                @foreach($statistics['by_device'] as $device => $count)
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">{{ $device ?: 'غير محدد' }}</span>
                                                        <span class="badge bg-info">{{ number_format($count) }}</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted">لا توجد بيانات</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- إحصائيات المدن -->
                                <div class="col-lg-4 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">أهم المدن (أول 10)</h6>
                                        </div>
                                        <div class="card-body">
                                            @if(!empty($statistics['by_city']))
                                                @php $topCities = array_slice($statistics['by_city'], 0, 10, true); @endphp
                                                @foreach($topCities as $city => $count)
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">{{ $city ?: 'غير محدد' }}</span>
                                                        <span class="badge bg-success">{{ number_format($count) }}</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted">لا توجد بيانات</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات الصفحة الحالية -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="text-muted">
                                            عرض {{ $potentialCustomers->firstItem() ?? 0 }} إلى {{ $potentialCustomers->lastItem() ?? 0 }}
                                            من أصل {{ number_format($potentialCustomers->total()) }} نتيجة
                                        </span>
                                        @if(request()->hasAny(['search', 'city_id', 'status', 'source', 'device_type', 'date_from', 'date_to']))
                                            <span class="badge bg-info">
                                                <i class="fas fa-filter me-1"></i>مفلتر
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                        <span class="text-muted">الصفحة:</span>
                                        <span class="fw-bold text-primary">{{ $potentialCustomers->currentPage() }}</span>
                                        <span class="text-muted">من</span>
                                        <span class="fw-bold text-primary">{{ $potentialCustomers->lastPage() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- جدول البيانات -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="5%">الصورة</th>
                                            <th width="12%">الاسم</th>
                                            <th width="12%">البريد الإلكتروني</th>
                                            <th width="10%">رقم الجوال</th>
                                            <th width="8%">المدينة</th>
                                            <th width="8%">عنوان IP</th>
                                            <th width="8%">نوع الجهاز</th>
                                            <th width="8%">المصدر</th>
                                            <th width="8%">تاريخ الطلب</th>
                                            <th width="12%">الحالة</th>
                                            <th width="12%">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($potentialCustomers as $customer)
                                        <tr>
                                            <td>{{ ($potentialCustomers->currentPage() - 1) * $potentialCustomers->perPage() + $loop->iteration }}</td>
                                            <td>
                                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($customer->email ?? 'default@example.com'))) }}?s=40&d=identicon"
                                                     alt="صورة {{ $customer->name }}"
                                                     class="rounded-circle"
                                                     width="40" height="40">
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $customer->name }}</div>
                                                <small class="text-muted">ID: {{ $customer->id }}</small>
                                            </td>
                                            <td>{{ $customer->email ?? '-' }}</td>
                                            <td>{{ $customer->phone }}</td>
                                            <td>{{ $customer->city ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $customer->ip_address ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if($customer->device_type)
                                                    @php
                                                        $deviceIcons = [
                                                            'mobile' => ['icon' => 'mobile-alt', 'color' => 'success'],
                                                            'tablet' => ['icon' => 'tablet-alt', 'color' => 'warning'],
                                                            'desktop' => ['icon' => 'desktop', 'color' => 'primary']
                                                        ];
                                                        $deviceInfo = $deviceIcons[$customer->device_type] ?? ['icon' => 'question', 'color' => 'secondary'];
                                                    @endphp
                                                    <span class="badge bg-{{ $deviceInfo['color'] }}" title="{{ $customer->device_type_display }}">
                                                        <i class="fas fa-{{ $deviceInfo['icon'] }} me-1"></i>
                                                        {{ $customer->device_type_display }}
                                                    </span>
                                                @else
                                                    <span class="text-muted" title="نوع الجهاز غير محدد">
                                                        <i class="fas fa-question-circle me-1"></i>
                                                        غير محدد
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($customer->referrer_url)
                                                    @php
                                                        $domain = parse_url($customer->referrer_url, PHP_URL_HOST);
                                                        $domain = $domain ? preg_replace('/^www\./', '', $domain) : $customer->referrer_url;
                                                        $sourceColors = [
                                                            'google' => 'success',
                                                            'facebook' => 'primary',
                                                            'instagram' => 'danger',
                                                            'twitter' => 'info',
                                                            'youtube' => 'warning',
                                                            'linkedin' => 'primary'
                                                        ];
                                                        $color = 'secondary';
                                                        foreach ($sourceColors as $platform => $platformColor) {
                                                            if (strpos($domain, $platform) !== false) {
                                                                $color = $platformColor;
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    <span class="badge bg-{{ $color }}" title="{{ $customer->referrer_url }}">
                                                        <i class="fas fa-external-link-alt me-1"></i>
                                                        {{ $domain }}
                                                    </span>
                                                @elseif($customer->source)
                                                    @php
                                                        $sourceColors = [
                                                            'google_ads' => 'success',
                                                            'facebook_ads' => 'primary',
                                                            'direct' => 'secondary',
                                                            'organic' => 'warning',
                                                            'referral' => 'info',
                                                            'social' => 'danger',
                                                            'card_request' => 'dark'
                                                        ];
                                                        $color = $sourceColors[$customer->source] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $color }}">{{ $customer->source_display }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $customer->created_at->format('Y-m-d') }}</small><br>
                                                <small class="text-primary">{{ $customer->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="mb-2">
                                                    <span class="badge bg-{{ $customer->status == 'تم الاصدار' ? 'success' : ($customer->status == 'تأجيل' ? 'secondary' : ($customer->status == 'رفض' ? 'danger' : 'warning')) }}">
                                                        {{ $customer->status ?? 'لم يتم التواصل' }}
                                                    </span>
                                                    @if($customer->status == 'تم الاصدار' && $customer->user)
                                                        <br><small class="text-success">{{ $customer->user->name }}</small>
                                                    @endif
                                                </div>
                                                @if($customer->user)
                                                    <small class="text-muted">موظف: {{ $customer->user->name }}</small>
                                                @endif
                                                @if($customer->call_summary)
                                                    <br><small class="text-info" title="{{ $customer->call_summary }}">
                                                        <i class="fas fa-comment"></i> يوجد ملاحظات
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-primary"
                                                            onclick="showCustomerDetails({{ $customer->id }})"
                                                            title="عرض التفاصيل">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-success"
                                                            onclick="editCustomer({{ $customer->id }})"
                                                            title="تعديل البيانات">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-info"
                                                            onclick="addCallSummary({{ $customer->id }})"
                                                            title="إضافة ملخص مكالمة">
                                                        <i class="fas fa-phone"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="12" class="text-center">لا يوجد عملاء محتملين</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $potentialCustomers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAdvancedStats() {
    const statsDiv = document.getElementById('advanced-stats');
    if (statsDiv.style.display === 'none') {
        statsDiv.style.display = 'block';
    } else {
        statsDiv.style.display = 'none';
    }
}

function exportData(format, useFilters = false) {
    let url = '{{ route("admin.potential-customers.export") }}';
    let params = new URLSearchParams();

    // إضافة نوع الملف
    params.append('format', format);

    // إضافة الفلاتر إذا كانت مطلوبة
    if (useFilters) {
        const searchParams = new URLSearchParams(window.location.search);

        // نسخ جميع المعاملات الحالية
        for (const [key, value] of searchParams) {
            if (value && key !== 'page') { // تجاهل معامل الصفحة
                params.append(key, value);
            }
        }
    }

    // إنشاء الرابط وتحميل الملف
    const finalUrl = url + '?' + params.toString();
    window.open(finalUrl, '_blank');
}
</script>
<!-- Modal لعرض تفاصيل العميل -->
<div class="modal fade" id="customerDetailsModal" tabindex="-1" aria-labelledby="customerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerDetailsModalLabel">تفاصيل العميل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="customerDetailsContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal لتعديل بيانات العميل -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">تعديل بيانات العميل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCustomerForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">الاسم</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" id="edit_email" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_phone" class="form-label">رقم الجوال</label>
                                <input type="text" class="form-control" id="edit_phone" name="phone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_city" class="form-label">المدينة</label>
                                <select class="form-select" id="edit_city" name="city" required>
                                    <option value="">اختر المدينة</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">الحالة</label>
                                <select class="form-select" id="edit_status" name="status" required>
                                    <option value="لم يتم التواصل">لم يتم التواصل</option>
                                    <option value="تم التواصل">تم التواصل</option>
                                    <option value="لم يرد">لم يرد</option>
                                    <option value="تأجيل">تأجيل</option>
                                    <option value="تم الاصدار">تم الاصدار</option>
                                    <option value="رفض">رفض</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_ip_address" class="form-label">عنوان IP</label>
                                <input type="text" class="form-control" id="edit_ip_address" name="ip_address">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_device_type" class="form-label">نوع الجهاز</label>
                                <select class="form-select" id="edit_device_type" name="device_type">
                                    <option value="">غير محدد</option>
                                    <option value="mobile">جوال</option>
                                    <option value="desktop">كمبيوتر</option>
                                    <option value="tablet">تابلت</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_source" class="form-label">المصدر</label>
                                <select class="form-select" id="edit_source" name="source">
                                    <option value="">غير محدد</option>
                                    <option value="google_ads">إعلانات جوجل</option>
                                    <option value="facebook_ads">إعلانات فيسبوك</option>
                                    <option value="direct">دخول مباشر</option>
                                    <option value="organic">بحث طبيعي</option>
                                    <option value="referral">إحالة</option>
                                    <option value="social">وسائل التواصل</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_call_summary" class="form-label">ملخص المكالمة</label>
                        <textarea class="form-control" id="edit_call_summary" name="call_summary" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal لإضافة ملخص مكالمة -->
<div class="modal fade" id="callSummaryModal" tabindex="-1" aria-labelledby="callSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="callSummaryModalLabel">إضافة ملخص مكالمة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="callSummaryForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="call_summary" class="form-label">ملخص المكالمة</label>
                        <textarea class="form-control" id="call_summary" name="call_summary" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="call_status" class="form-label">تحديث الحالة</label>
                        <select class="form-select" id="call_status" name="status">
                            <option value="تم التواصل">تم التواصل</option>
                            <option value="لم يرد">لم يرد</option>
                            <option value="تأجيل">تأجيل</option>
                            <option value="تم الاصدار">تم الاصدار</option>
                            <option value="رفض">رفض</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentCustomerId = null;

// عرض تفاصيل العميل
function showCustomerDetails(customerId) {
    currentCustomerId = customerId;
    const modal = new bootstrap.Modal(document.getElementById('customerDetailsModal'));

    // إظهار حالة التحميل
    document.getElementById('customerDetailsContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
        </div>
    `;

    modal.show();

    // جلب بيانات العميل
    fetch(`/admin/potential-customers/${customerId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('customerDetailsContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>المعلومات الشخصية</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-4"><strong>الاسم:</strong></div>
                                    <div class="col-8">${data.name}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>البريد:</strong></div>
                                    <div class="col-8">${data.email || '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>الجوال:</strong></div>
                                    <div class="col-8">${data.phone}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>المدينة:</strong></div>
                                    <div class="col-8">${data.city}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>الحالة:</strong></div>
                                    <div class="col-8">
                                        <span class="badge bg-${getStatusColor(data.status)}">${data.status}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-globe me-2"></i>معلومات تقنية</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-4"><strong>IP:</strong></div>
                                    <div class="col-8"><code>${data.ip_address}</code></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>الجهاز:</strong></div>
                                    <div class="col-8">${data.device_type_display}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>المصدر:</strong></div>
                                    <div class="col-8">${data.source_display}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>الإحالة:</strong></div>
                                    <div class="col-8"><small>${data.referrer_url || '-'}</small></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4"><strong>الصفحة:</strong></div>
                                    <div class="col-8"><small>${data.landing_page || '-'}</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                ${data.utm_source ? `
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>معلومات التسويق</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>المصدر:</strong><br>
                                <span class="badge bg-info">${data.utm_source}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>الوسيط:</strong><br>
                                <span class="badge bg-secondary">${data.utm_medium || '-'}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>الحملة:</strong><br>
                                <span class="badge bg-success">${data.utm_campaign || '-'}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>الكلمة:</strong><br>
                                <span class="badge bg-warning">${data.utm_term || '-'}</span>
                            </div>
                        </div>
                    </div>
                </div>
                ` : ''}

                ${data.call_summary ? `
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-comment me-2"></i>ملخص المكالمة</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">${data.call_summary}</p>
                    </div>
                </div>
                ` : ''}

                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات إضافية</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>تاريخ الإنشاء:</strong><br>
                                <small class="text-muted">${new Date(data.created_at).toLocaleString('ar-SA')}</small>
                            </div>
                            <div class="col-md-6">
                                <strong>User Agent:</strong><br>
                                <small class="text-muted">${data.user_agent}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('customerDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    حدث خطأ أثناء تحميل البيانات
                </div>
            `;
        });
}

// تعديل بيانات العميل
function editCustomer(customerId) {
    currentCustomerId = customerId;
    const modal = new bootstrap.Modal(document.getElementById('editCustomerModal'));

    // جلب بيانات العميل وتعبئة النموذج
    fetch(`/admin/potential-customers/${customerId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_email').value = data.email || '';
            document.getElementById('edit_phone').value = data.phone;
            document.getElementById('edit_city').value = data.city;
            document.getElementById('edit_status').value = data.status;
            document.getElementById('edit_ip_address').value = data.ip_address || '';
            document.getElementById('edit_device_type').value = data.device_type || '';
            document.getElementById('edit_source').value = data.source || '';
            document.getElementById('edit_call_summary').value = data.call_summary || '';

            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحميل البيانات');
        });
}

// إضافة ملخص مكالمة
function addCallSummary(customerId) {
    currentCustomerId = customerId;
    const modal = new bootstrap.Modal(document.getElementById('callSummaryModal'));

    // مسح النموذج
    document.getElementById('call_summary').value = '';
    document.getElementById('call_status').value = 'تم التواصل';

    modal.show();
}

// معالجة نموذج تعديل العميل
document.getElementById('editCustomerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    fetch(`/admin/potential-customers/${currentCustomerId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            bootstrap.Modal.getInstance(document.getElementById('editCustomerModal')).hide();
            showAlert('success', result.message);
            // إعادة تحميل الصفحة لإظهار التغييرات
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('danger', 'حدث خطأ أثناء حفظ البيانات');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'حدث خطأ أثناء حفظ البيانات');
    });
});

// معالجة نموذج ملخص المكالمة
document.getElementById('callSummaryForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    fetch(`/admin/potential-customers/${currentCustomerId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            bootstrap.Modal.getInstance(document.getElementById('callSummaryModal')).hide();
            showAlert('success', 'تم حفظ ملخص المكالمة بنجاح');
            // إعادة تحميل الصفحة لإظهار التغييرات
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('danger', 'حدث خطأ أثناء حفظ البيانات');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'حدث خطأ أثناء حفظ البيانات');
    });
});

// دالة مساعدة لتحديد لون الحالة
function getStatusColor(status) {
    const colors = {
        'تم الاصدار': 'success',
        'تأجيل': 'secondary',
        'رفض': 'danger',
        'تم التواصل': 'info',
        'لم يرد': 'warning'
    };
    return colors[status] || 'warning';
}

// دالة مساعدة لإظهار التنبيهات
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // إضافة التنبيه في أعلى الصفحة
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);

    // إزالة التنبيه تلقائياً بعد 5 ثوان
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

// تحسين تجربة المستخدم - إضافة tooltips
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // إضافة تأثيرات hover للصور
    document.querySelectorAll('.rounded-circle').forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.transition = 'transform 0.2s ease';
        });

        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: box-shadow 0.15s ease-in-out;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-lg {
    max-width: 900px;
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 2px;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.table td {
    vertical-align: middle;
}

.rounded-circle {
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
}

.rounded-circle:hover {
    border-color: #007bff;
}

.spinner-border {
    color: #007bff;
}

.alert {
    border-radius: 0.5rem;
    border: none;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
}

.text-muted {
    font-size: 0.875em;
}

code {
    background-color: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
}
</style>
@endpush

@endsection

