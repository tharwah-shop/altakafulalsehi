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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();

            // البيانات الأساسية
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->string('nationality');
            $table->string('id_number')->unique();

            // بيانات الاشتراك
            $table->date('start_date');
            $table->date('end_date');
            $table->string('card_number')->unique(); // رقم البطاقة المُولد تلقائياً
            $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('set null');
            $table->decimal('card_price', 10, 2)->nullable(); // سعر البطاقة
            $table->decimal('total_amount', 10, 2)->nullable(); // المبلغ الإجمالي
            $table->integer('dependents_count')->default(0); // عدد التابعين
            $table->enum('status', ['فعال', 'منتهي', 'ملغي', 'معلق', 'بانتظار الدفع'])->default('فعال');

            // بيانات التتبع والخصم (للعملاء المحتملين)
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('source')->nullable(); // مصدر المشترك
            $table->string('referrer')->nullable(); // المرجع
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();

            // معلومات إضافية
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // فهارس
            $table->index(['status', 'start_date', 'end_date']);
            $table->index(['package_id', 'status']);
            $table->index(['city_id', 'nationality']);
            $table->index('phone');
            $table->index('card_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
