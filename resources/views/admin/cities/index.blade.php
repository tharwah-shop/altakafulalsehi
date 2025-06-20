@extends('layouts.admin')
@section('title', 'إدارة المدن')
@section('content-header', 'إدارة المدن')
@section('content-subtitle', 'قائمة جميع المدن في النظام')
@section('content')
<div class="card mb-5">
    <div class="card-body d-flex flex-wrap gap-3 justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1">المدن</h3>
            <div class="text-muted">إدارة جميع المدن وربطها بالمناطق</div>
        </div>
        <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> إضافة مدينة
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
                        <th>اسم المدينة</th>
                        <th>المنطقة</th>
                        <th>عدد المراكز الطبية</th>
                        <th class="text-center">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cities as $city)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                                    <i class="fas fa-city"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $city->name }}</div>
                                    <small class="text-muted">مدينة</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $city->region }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $city->medical_centers_count }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.cities.show', urlencode($city->name)) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.cities.edit', urlencode($city->name)) }}" class="btn btn-sm btn-outline-info" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-center">
                                <i class="fas fa-city text-muted fa-3x mb-3"></i>
                                <h5 class="text-muted mb-2">لا توجد مدن</h5>
                                <p class="text-muted">سيتم إضافة المدن تلقائياً عند إضافة المراكز الطبية</p>
                                <a href="{{ route('admin.medical-centers.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>إضافة مركز طبي
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