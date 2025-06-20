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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->decimal('price', 10, 2); // سعر الباقة الأساسي
            $table->decimal('dependent_price', 10, 2)->nullable(); // سعر التابع
            $table->integer('duration_months'); // مدة الاشتراك بالأشهر
            $table->integer('max_dependents')->default(0); // أقصى عدد تابعين (0 = غير محدود)
            $table->json('features')->nullable(); // مميزات الباقة
            $table->decimal('discount_percentage', 5, 2)->default(0); // نسبة الخصم الافتراضية
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->boolean('is_featured')->default(false); // باقة مميزة
            $table->integer('sort_order')->default(0);
            $table->string('color')->default('#007bff'); // لون الباقة في الواجهة
            $table->string('icon')->nullable(); // أيقونة الباقة
            $table->timestamps();

            // فهارس
            $table->index(['status', 'is_featured', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
