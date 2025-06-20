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
            // إزالة العمود city_id إذا كان موجوداً
            if (Schema::hasColumn('medical_centers', 'city_id')) {
                $table->dropForeign(['city_id']);
                $table->dropColumn('city_id');
            }

            // إزالة العمود region_id إذا كان موجوداً
            if (Schema::hasColumn('medical_centers', 'region_id')) {
                $table->dropForeign(['region_id']);
                $table->dropColumn('region_id');
            }

            // التأكد من وجود أعمدة المدينة والمنطقة كنصوص
            if (!Schema::hasColumn('medical_centers', 'city')) {
                $table->string('city')->nullable()->after('address');
            }

            if (!Schema::hasColumn('medical_centers', 'region')) {
                $table->string('region')->nullable()->after('city');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_centers', function (Blueprint $table) {
            // إعادة إضافة العمود city_id
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');

            // إعادة إضافة العمود region_id
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('set null');

            // حذف أعمدة النصوص
            $table->dropColumn(['city', 'region']);
        });
    }
};
