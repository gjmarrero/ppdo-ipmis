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
        Schema::create('project_other_requirements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id');
            $table->string('requirement_type');
            $table->foreignUuid('pamb_type_id')->nullable();
            $table->string('status')->nullable();
            $table->date('date_applied')->nullable();
            $table->date('date_issued')->nullable();
            $table->foreignUuid('employee_id')->nullable();
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
        Schema::dropIfExists('project_other_requirements');
    }
};
