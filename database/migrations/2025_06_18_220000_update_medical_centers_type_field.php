<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medical_centers', function (Blueprint $table) {
            // تغيير نوع الحقل من enum إلى integer
            $table->integer('type_id')->nullable()->after('type');
        });

        // نسخ البيانات الموجودة
        DB::statement("UPDATE medical_centers SET type_id = CASE 
            WHEN type = 'hospital' THEN 1
            WHEN type = 'clinic' THEN 2
            WHEN type = 'pharmacy' THEN 9
            WHEN type = 'lab' THEN 4
            WHEN type = 'radiology' THEN 5
            WHEN type = 'dental' THEN 6
            WHEN type = 'optical' THEN 8
            WHEN type = 'physiotherapy' THEN 3
            ELSE 3
        END");

        Schema::table('medical_centers', function (Blueprint $table) {
            // حذف الحقل القديم
            $table->dropColumn('type');
        });

        Schema::table('medical_centers', function (Blueprint $table) {
            // إعادة تسمية الحقل الجديد
            $table->renameColumn('type_id', 'type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_centers', function (Blueprint $table) {
            $table->integer('type_old')->nullable()->after('type');
        });

        // نسخ البيانات
        DB::statement("UPDATE medical_centers SET type_old = type");

        Schema::table('medical_centers', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('medical_centers', function (Blueprint $table) {
            $table->enum('type', ['hospital', 'clinic', 'pharmacy', 'lab', 'radiology', 'dental', 'optical', 'physiotherapy', 'other'])->default('clinic')->after('type_old');
        });

        // استعادة البيانات
        DB::statement("UPDATE medical_centers SET type = CASE 
            WHEN type_old = 1 THEN 'hospital'
            WHEN type_old = 2 THEN 'clinic'
            WHEN type_old = 3 THEN 'physiotherapy'
            WHEN type_old = 4 THEN 'lab'
            WHEN type_old = 5 THEN 'radiology'
            WHEN type_old = 6 THEN 'dental'
            WHEN type_old = 8 THEN 'optical'
            WHEN type_old = 9 THEN 'pharmacy'
            ELSE 'other'
        END");

        Schema::table('medical_centers', function (Blueprint $table) {
            $table->dropColumn('type_old');
        });
    }
};
