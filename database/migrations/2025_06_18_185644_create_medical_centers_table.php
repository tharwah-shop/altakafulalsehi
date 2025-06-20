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
        Schema::create('medical_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // معلومات الموقع
            $table->string('region'); // المنطقة
            $table->string('city'); // المدينة
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // معلومات الاتصال
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // نوع المركز والخدمات
            $table->integer('type')->nullable(); // نوع المركز (1-12)
            $table->json('medical_service_types')->nullable(); // أنواع الخدمات الطبية
            $table->json('medical_discounts')->nullable(); // الخصومات الطبية

            // معلومات إضافية
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('pending');
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable();
            $table->json('working_hours')->nullable(); // ساعات العمل
            $table->boolean('is_available_247')->default(false); // متاح 24/7
            $table->integer('max_discount')->default(0); // أقصى خصم

            // الصور والملفات
            $table->string('image')->nullable(); // الشعار
            $table->json('images')->nullable(); // صور إضافية
            $table->string('location')->nullable(); // رابط الخريطة

            // التقييمات والإحصائيات
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('reviews_count')->default(0);
            $table->integer('views_count')->default(0);

            // معلومات الإنشاء
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_centers');
    }
};
