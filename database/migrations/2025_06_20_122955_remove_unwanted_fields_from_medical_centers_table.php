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
            // إزالة الحقول المطلوبة
            if (Schema::hasColumn('medical_centers', 'license_number')) {
                $table->dropColumn('license_number');
            }
            if (Schema::hasColumn('medical_centers', 'license_expiry')) {
                $table->dropColumn('license_expiry');
            }
            if (Schema::hasColumn('medical_centers', 'working_hours')) {
                $table->dropColumn('working_hours');
            }
            if (Schema::hasColumn('medical_centers', 'is_available_247')) {
                $table->dropColumn('is_available_247');
            }
            if (Schema::hasColumn('medical_centers', 'max_discount')) {
                $table->dropColumn('max_discount');
            }
            if (Schema::hasColumn('medical_centers', 'images')) {
                $table->dropColumn('images');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_centers', function (Blueprint $table) {
            // إعادة إضافة الحقول في حالة التراجع
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable();
            $table->json('working_hours')->nullable();
            $table->boolean('is_available_247')->default(false);
            $table->integer('max_discount')->default(0);
            $table->json('images')->nullable();
        });
    }
};
