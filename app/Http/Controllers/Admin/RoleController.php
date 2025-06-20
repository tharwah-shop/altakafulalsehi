<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        // صفحة بسيطة لإدارة الأدوار
        return view('admin.roles.index');
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.roles.index')->with('success', 'تم إضافة الدور بنجاح');
    }

    public function show($id)
    {
        return view('admin.roles.show');
    }

    public function edit($id)
    {
        return view('admin.roles.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.roles.index')->with('success', 'تم تحديث الدور بنجاح');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.roles.index')->with('success', 'تم حذف الدور بنجاح');
    }
}
