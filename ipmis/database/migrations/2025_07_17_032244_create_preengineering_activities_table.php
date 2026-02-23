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
        Schema::create('preengineering_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('preengineering_status_id');
            $table->string('activity');
            $table->foreignUuid('employee_id');
            $table->string('remarks');
            $table->foreignUuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preengineering_activities');
    }
};
