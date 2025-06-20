@extends('layouts.admin')
@section('title', 'إدارة العروض')
@section('content-header', 'إدارة العروض')
@section('content-subtitle', 'قائمة جميع العروض الطبية في النظام')
@section('content')
<div class="card mb-5">
    <div class="card-body d-flex flex-wrap gap-3 justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1">العروض</h3>
            <div class="text-muted">إدارة جميع العروض الطبية وربطها بالمراكز</div>
        </div>
        <a href="{{ route('admin.offers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> إضافة عرض
        </a>
    </div>
</div>
<div class="card mb-5">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover align-middle table-row-dashed">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>العرض</th>
                        <th>المركز الطبي</th>
                        <th>الخصم</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الحالة</th>
                        <th class="text-center">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offers as $offer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($offer->image)
                                    <img src="{{ $offer->image_url }}" alt="{{ $offer->title }}" class="rounded me-3" style="width:50px;height:40px;object-fit:cover;">
                                @else
                                    <div class="bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center me-3" style="width:50px;height:40px;">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $offer->title }}</div>
                                    <small class="text-muted">{{ Str::limit($offer->description, 50) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($offer->medicalCenter && $offer->medicalCenter->image)
                                    <img src="{{ $offer->medicalCenter->image_url }}" alt="{{ $offer->medicalCenter->name }}" class="rounded-circle me-2" style="width:30px;height:30px;object-fit:cover;">
                                @else
                                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-2" style="width:30px;height:30px;">
                                        <i class="fas fa-hospital"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $offer->medicalCenter->name ?? 'غير محدد' }}</div>
                                    <small class="text-muted">{{ $offer->medicalCenter->city_name ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($offer->discount_percentage)
                                <span class="badge bg-success fs-6">{{ $offer->discount_percentage }}%</span>
                            @elseif($offer->discount_amount)
                                <span class="badge bg-info fs-6">{{ number_format($offer->discount_amount) }} ريال</span>
                            @else
                                <span class="badge bg-secondary fs-6">غير محدد</span>
                            @endif
                        </td>
                        <td>
                            @if($offer->end_date)
                                <div class="text-{{ $offer->is_expired ? 'danger' : ($offer->remaining_days <= 7 ? 'warning' : 'success') }}">
                                    {{ $offer->end_date->format('Y-m-d') }}
                                    @if(!$offer->is_expired)
                                        <br><small>({{ $offer->remaining_days }} يوم متبقي)</small>
                                    @else
                                        <br><small class="text-danger">(منتهي)</small>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">غير محدد</span>
                            @endif
                        </td>
                        <td>
                            @if($offer->status == 'active')
                                @if($offer->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-warning">منتهي</span>
                                @endif
                            @elseif($offer->status == 'pending')
                                <span class="badge bg-info">قيد المراجعة</span>
                            @else
                                <span class="badge bg-secondary">غير نشط</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.offers.show', $offer->id) }}" class="btn btn-sm btn-outline-primary" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.offers.edit', $offer->id) }}" class="btn btn-sm btn-outline-info" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.offers.toggle-status', $offer->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-{{ $offer->status == 'active' ? 'warning' : 'success' }}" title="{{ $offer->status == 'active' ? 'إيقاف' : 'تفعيل' }}">
                                        <i class="fas fa-{{ $offer->status == 'active' ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف العرض" onclick="return confirm('هل أنت متأكد من حذف هذا العرض؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-center">
                                <i class="fas fa-tags text-muted fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">لا توجد عروض طبية</h5>
                                <p class="text-muted">ابدأ بإضافة عروض وخصومات للمراكز الطبية</p>
                                <a href="{{ route('admin.offers.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>إضافة عرض جديد
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 