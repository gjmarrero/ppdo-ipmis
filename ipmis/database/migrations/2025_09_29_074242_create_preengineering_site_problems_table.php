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
        Schema::create('preengineering_site_problems', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('preengineering_status_id');
            $table->string('site_problem');
            $table->date('date_prepared_report')->nullable();
            $table->date('date_approved_report')->nullable();
            $table->date('date_submitted_lce_report')->nullable();
            $table->foreignUuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preengineering_site_problems');
    }
};
