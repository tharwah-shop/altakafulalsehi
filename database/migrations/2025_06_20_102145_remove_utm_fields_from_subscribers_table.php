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
            // حذف حقول التتبع والمصدر
            $table->dropColumn([
                'source',
                'referrer',
                'utm_source',
                'utm_medium',
                'utm_campaign'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            // إعادة إضافة الحقول في حالة التراجع
            $table->string('source')->nullable()->comment('مصدر المشترك');
            $table->string('referrer')->nullable()->comment('المرجع');
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
        });
    }
};
