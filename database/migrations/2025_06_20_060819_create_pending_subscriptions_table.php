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
        Schema::create('pending_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique(); // معرف الجلسة
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->string('nationality');
            $table->string('id_number');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('payment_method');
            $table->json('dependents')->nullable(); // بيانات التابعين
            $table->decimal('total_amount', 10, 2);
            $table->integer('dependents_count')->default(0);
            $table->string('status')->default('pending'); // pending, completed, expired
            $table->timestamp('expires_at'); // انتهاء صلاحية البيانات المؤقتة
            $table->timestamps();

            $table->index(['session_id', 'status']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_subscriptions');
    }
};
