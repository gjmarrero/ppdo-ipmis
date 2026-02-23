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
        Schema::create('site_problems', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('funded_project_id');
            $table->string('problem_type');
            $table->date('date_report_prepared');
            $table->date('date_report_checked');
            $table->date('date_report_submitted');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_problems');
    }
};
