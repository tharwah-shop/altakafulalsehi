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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // bank_transfer, myfatoorah, tabby, etc.
            $table->string('status')->default('pending'); // pending, pending_verification, completed, failed, cancelled
            $table->string('currency', 3)->default('SAR');

            // بيانات التحويل البنكي
            $table->decimal('transfer_amount', 10, 2)->nullable();
            $table->string('sender_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('receipt_file')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('transfer_confirmed_at')->nullable();

            // بيانات التحقق
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            $table->index(['status', 'payment_method']);
            $table->index('transfer_confirmed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
