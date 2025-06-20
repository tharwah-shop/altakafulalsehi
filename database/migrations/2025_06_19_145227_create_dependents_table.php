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
        Schema::create('dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')->constrained('subscribers')->onDelete('cascade');
            $table->string('name');
            $table->string('nationality');
            $table->string('id_number')->nullable(); // رقم الهوية/الإقامة للتابع
            $table->decimal('dependent_price', 10, 2)->nullable(); // سعر التابع
            $table->text('notes')->nullable();
            $table->timestamps();

            // فهارس
            $table->index(['subscriber_id', 'name']);
            $table->index('nationality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependents');
    }
};
