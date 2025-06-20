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
        Schema::table('medical_centers', function (Blueprint $table) {
            $table->enum('contract_status', ['active', 'pending', 'expired', 'suspended', 'terminated'])->nullable()->after('status');
            $table->date('contract_start_date')->nullable()->after('contract_status');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_centers', function (Blueprint $table) {
            $table->dropColumn(['contract_status', 'contract_start_date', 'contract_end_date']);
        });
    }
};
