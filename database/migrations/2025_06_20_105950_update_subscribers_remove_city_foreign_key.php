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
        Schema::table('subscribers', function (Blueprint $table) {
            // إزالة الفهارس أولاً
            if (Schema::hasColumn('subscribers', 'city_id')) {
                // إزالة الفهارس التي تحتوي على city_id
                $table->dropIndex(['city_id', 'nationality']);
                $table->dropForeign(['city_id']);
                $table->dropColumn('city_id');
            }

            // إضافة عمود المدينة كنص
            if (!Schema::hasColumn('subscribers', 'city')) {
                $table->string('city')->nullable()->after('email');
            }
        });

        // إضافة فهرس جديد للمدينة والجنسية
        Schema::table('subscribers', function (Blueprint $table) {
            if (Schema::hasColumn('subscribers', 'city') && Schema::hasColumn('subscribers', 'nationality')) {
                $table->index(['city', 'nationality']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            // إعادة إضافة العمود city_id
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');

            // حذف عمود النص
            $table->dropColumn('city');
        });
    }
};
