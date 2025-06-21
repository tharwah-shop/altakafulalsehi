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
        Schema::table('pending_subscriptions', function (Blueprint $table) {
            // إزالة العلاقة الخارجية مع جدول المدن المحذوف
            if (Schema::hasColumn('pending_subscriptions', 'city_id')) {
                $table->dropForeign(['city_id']);
                $table->dropColumn('city_id');
            }

            // إضافة عمود المدينة كنص
            if (!Schema::hasColumn('pending_subscriptions', 'city')) {
                $table->string('city')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pending_subscriptions', function (Blueprint $table) {
            // إعادة إضافة city_id (لكن هذا لن يعمل لأن جدول cities محذوف)
            if (Schema::hasColumn('pending_subscriptions', 'city')) {
                $table->dropColumn('city');
            }

            // ملاحظة: لا يمكن إعادة إضافة city_id لأن جدول cities محذوف
            // $table->foreignId('city_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};
