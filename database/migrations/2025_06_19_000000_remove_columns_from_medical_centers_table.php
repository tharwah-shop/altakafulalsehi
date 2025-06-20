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
            // حذف الأعمدة
            $table->dropColumn('working_hours');
            $table->dropColumn('is_available_247');
            $table->dropColumn('license_number');
            $table->dropColumn('license_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_centers', function (Blueprint $table) {
            // إعادة إضافة الأعمدة في حالة التراجع
            $table->json('working_hours')->nullable();
            $table->boolean('is_available_247')->default(false);
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable();
        });
    }
};