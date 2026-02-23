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
        Schema::create('environmental_clearance_cmrs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('environmental_clearance_id');
            $table->date('date_prepared')->nullable();
            $table->date('date_submitted')->nullable();
            $table->string('file')->nullable();
            $table->string('remarks')->nullable();
            $table->foreignUuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('environmental_clearance_cmrs');
    }
};
