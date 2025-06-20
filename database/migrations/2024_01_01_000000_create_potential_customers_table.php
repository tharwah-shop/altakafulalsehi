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
        Schema::create('potential_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['لم يتم التواصل', 'لم يرد', 'رفض', 'تأجيل', 'تم الاصدار'])->default('لم يتم التواصل');
            $table->enum('source', ['google_ads', 'facebook_ads', 'direct', 'organic', 'referral', 'social'])->nullable();
            $table->enum('device_type', ['mobile', 'desktop', 'tablet'])->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('call_summary')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('converted_to_subscriber')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['status', 'created_at']);
            $table->index(['source', 'created_at']);
            $table->index(['device_type', 'created_at']);
            $table->index(['city_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('phone');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potential_customers');
    }
};
