@extends('layouts.admin')
@section('title', 'إدارة المستخدمين')
@section('content-header', 'إدارة المستخدمين')
@section('content-subtitle', 'قائمة جميع المستخدمين في النظام')
@section('content')
<div class="card mb-5">
    <div class="card-body d-flex flex-wrap gap-3 justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1">المستخدمون</h3>
            <div class="text-muted">إدارة جميع المستخدمين وصلاحياتهم</div>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> إضافة مستخدم
        </a>
    </div>
</div>
<div class="card mb-5">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end mb-3">
            <div class="col-md-4">
                <label class="form-label">بحث بالاسم أو البريد</label>
                <input type="text" name="q" class="form-control" placeholder="بحث..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">الدور</label>
                <select name="role" class="form-select">
                    <option value="">كل الصلاحيات</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>مستخدم</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>مشرف</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100"><i class="fa fa-search me-1"></i> بحث</button>
            </div>
        </form>
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
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الدور</th>
                        <th class="text-center">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                    <i class="fa fa-user"></i>
                                </div>
                                <span class="fw-bold">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-info">مستخدم</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-info me-1" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">لا يوجد مستخدمون</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 