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
        Schema::create('implementation_inspections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('implementation_id');
            $table->date('date_request_received')->required();
            $table->date('date_pre_inspection')->nullable();
            $table->date('date_post_inspection')->nullable();
            $table->date('date_final_inspection')->nullable();
            $table->date('date_report_prepared')->nullable();
            $table->date('date_file_compilation_prepared')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('implementation_inspections');
    }
};
