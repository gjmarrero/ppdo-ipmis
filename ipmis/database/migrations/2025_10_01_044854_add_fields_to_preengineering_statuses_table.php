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
        Schema::table('preengineering_statuses', function (Blueprint $table) {
            $table->date('date_received_by_qc')->nullable();
            $table->foreignUuid('employee_id_qcp')->nullable();
            $table->date('date_qcp_prepared')->nullable();
            $table->date('date_qcp_reviewed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preengineering_statuses', function (Blueprint $table) {
            
        });
    }
};
