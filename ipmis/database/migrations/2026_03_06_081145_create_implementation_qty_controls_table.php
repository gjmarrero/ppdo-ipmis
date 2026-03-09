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
        Schema::create('implementation_qty_controls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('implementation_id');
            $table->boolean('is_contruction_materials_needed')->default(true);
            $table->foreignUuid('qc_in_charge_id')->nullable();
            $table->date('date_materials_inspected')->nullable();
            $table->boolean('is_materials_approved')->default(true);
            $table->date('date_replacement_requested')->nullable();
            $table->boolean('is_pouring_permit_needed')->default(false);
            $table->date('date_pouring_permit_issued')->nullable();
            $table->date('date_pouring_inspected')->nullable();
            $table->boolean('is_pouring_approved')->default(false);
            $table->date('date_rejected_pouring_requested')->nullable();
            $table->date('samples_tested')->nullable();
            $table->date('date_rejected_samples_reported')->nullable();
            $table->date('date_ir_op_prepared')->nullable();
            $table->date('date_ir_checked')->nullable();
            $table->date('date_ir_approved')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('implementation_qty_controls');
    }
};
