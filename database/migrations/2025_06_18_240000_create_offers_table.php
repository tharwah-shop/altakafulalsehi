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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // ربط بالمركز الطبي
            $table->foreignId('medical_center_id')->constrained('medical_centers')->onDelete('cascade');
            
            // معلومات الخصم
            $table->decimal('discount_percentage', 5, 2)->nullable(); // نسبة الخصم
            $table->decimal('discount_amount', 10, 2)->nullable(); // مبلغ الخصم
            $table->decimal('original_price', 10, 2)->nullable(); // السعر الأصلي
            $table->decimal('discounted_price', 10, 2)->nullable(); // السعر بعد الخصم
            
            // تواريخ العرض
            $table->date('start_date');
            $table->date('end_date');
            
            // الحالة
            $table->enum('status', ['active', 'inactive', 'expired', 'pending'])->default('pending');
            
            // الصورة
            $table->string('image')->nullable();
            
            // الشروط والأحكام
            $table->text('terms_conditions')->nullable();
            
            // حدود الاستخدام
            $table->integer('max_uses')->default(0); // 0 = غير محدود
            $table->integer('current_uses')->default(0);
            
            // عرض مميز
            $table->boolean('is_featured')->default(false);
            
            // معلومات الإنشاء
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // فهارس
            $table->index(['status', 'start_date', 'end_date']);
            $table->index(['medical_center_id', 'status']);
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
