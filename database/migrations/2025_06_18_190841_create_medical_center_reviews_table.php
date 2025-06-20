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
        Schema::create('medical_center_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_center_id')->constrained()->onDelete('cascade');
            $table->string('reviewer_name');
            $table->string('reviewer_email')->nullable();
            $table->integer('rating')->unsigned(); // من 1 إلى 5
            $table->text('comment');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_center_reviews');
    }
};
