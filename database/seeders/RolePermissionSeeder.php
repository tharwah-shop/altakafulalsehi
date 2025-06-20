<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الصلاحيات
        $permissions = [
            // صلاحيات المستخدمين
            ['name' => 'users.view', 'display_name' => 'عرض المستخدمين', 'module' => 'users'],
            ['name' => 'users.create', 'display_name' => 'إنشاء مستخدم', 'module' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'تعديل المستخدمين', 'module' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'حذف المستخدمين', 'module' => 'users'],

            // صلاحيات الأدوار والصلاحيات
            ['name' => 'roles.view', 'display_name' => 'عرض الأدوار', 'module' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'إنشاء دور', 'module' => 'roles'],
            ['name' => 'roles.edit', 'display_name' => 'تعديل الأدوار', 'module' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'حذف الأدوار', 'module' => 'roles'],

            // صلاحيات المراكز الطبية
            ['name' => 'medical_centers.view', 'display_name' => 'عرض المراكز الطبية', 'module' => 'medical_centers'],
            ['name' => 'medical_centers.create', 'display_name' => 'إنشاء مركز طبي', 'module' => 'medical_centers'],
            ['name' => 'medical_centers.edit', 'display_name' => 'تعديل المراكز الطبية', 'module' => 'medical_centers'],
            ['name' => 'medical_centers.delete', 'display_name' => 'حذف المراكز الطبية', 'module' => 'medical_centers'],
            ['name' => 'medical_centers.approve', 'display_name' => 'الموافقة على المراكز الطبية', 'module' => 'medical_centers'],

            // صلاحيات المنشورات
            ['name' => 'posts.view', 'display_name' => 'عرض المنشورات', 'module' => 'posts'],
            ['name' => 'posts.create', 'display_name' => 'إنشاء منشور', 'module' => 'posts'],
            ['name' => 'posts.edit', 'display_name' => 'تعديل المنشورات', 'module' => 'posts'],
            ['name' => 'posts.delete', 'display_name' => 'حذف المنشورات', 'module' => 'posts'],
            ['name' => 'posts.publish', 'display_name' => 'نشر المنشورات', 'module' => 'posts'],

            // صلاحيات التقييمات
            ['name' => 'reviews.view', 'display_name' => 'عرض التقييمات', 'module' => 'reviews'],
            ['name' => 'reviews.approve', 'display_name' => 'الموافقة على التقييمات', 'module' => 'reviews'],
            ['name' => 'reviews.delete', 'display_name' => 'حذف التقييمات', 'module' => 'reviews'],

            // صلاحيات لوحة التحكم
            ['name' => 'dashboard.view', 'display_name' => 'عرض لوحة التحكم', 'module' => 'dashboard'],
            ['name' => 'settings.manage', 'display_name' => 'إدارة الإعدادات', 'module' => 'settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // إنشاء الأدوار
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'مدير عام',
                'description' => 'مدير عام للنظام مع جميع الصلاحيات',
            ],
            [
                'name' => 'admin',
                'display_name' => 'مدير',
                'description' => 'مدير النظام',
            ],
            [
                'name' => 'medical_center_manager',
                'display_name' => 'مدير مركز طبي',
                'description' => 'مدير مركز طبي يمكنه إدارة مركزه ومنشوراته',
            ],
            [
                'name' => 'content_manager',
                'display_name' => 'مدير المحتوى',
                'description' => 'مدير المحتوى والمنشورات',
            ],
            [
                'name' => 'user',
                'display_name' => 'مستخدم',
                'description' => 'مستخدم عادي',
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(['name' => $roleData['name']], $roleData);

            // ربط الصلاحيات بالأدوار
            if ($roleData['name'] === 'super_admin') {
                // المدير العام له جميع الصلاحيات
                $role->permissions()->sync(Permission::all());
            } elseif ($roleData['name'] === 'admin') {
                // المدير له معظم الصلاحيات
                $adminPermissions = Permission::whereNotIn('name', ['settings.manage'])->get();
                $role->permissions()->sync($adminPermissions);
            } elseif ($roleData['name'] === 'medical_center_manager') {
                // مدير المركز الطبي
                $managerPermissions = Permission::whereIn('name', [
                    'medical_centers.view', 'medical_centers.edit',
                    'posts.view', 'posts.create', 'posts.edit', 'posts.delete',
                    'reviews.view', 'dashboard.view'
                ])->get();
                $role->permissions()->sync($managerPermissions);
            } elseif ($roleData['name'] === 'content_manager') {
                // مدير المحتوى
                $contentPermissions = Permission::whereIn('name', [
                    'posts.view', 'posts.create', 'posts.edit', 'posts.delete', 'posts.publish',
                    'medical_centers.view', 'reviews.view', 'reviews.approve',
                    'dashboard.view'
                ])->get();
                $role->permissions()->sync($contentPermissions);
            }
        }

        // إنشاء مستخدم مدير عام افتراضي
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@altakaful.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('password123'),
                'status' => 'active',
            ]
        );

        // ربط المدير العام بدوره
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if (!$superAdmin->roles->contains($superAdminRole)) {
            $superAdmin->roles()->attach($superAdminRole, [
                'assigned_at' => now(),
                'assigned_by' => $superAdmin->id,
            ]);
        }
    }
}
