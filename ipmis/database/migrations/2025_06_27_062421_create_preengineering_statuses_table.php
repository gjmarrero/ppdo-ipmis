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
        Schema::create('preengineering_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('funded_project_id');
            $table->foreignUuid('employee_id');
            $table->foreignUuid('project_type_id');
            $table->date('date_conducted');
            $table->decimal('programmed_cost',15,2);
            $table->string('indicator');
            $table->date('date_submitted_divhead')->nullable();
            $table->date('date_approved_pe')->nullable();
            $table->date('date_submitted_lce')->nullable();
            $table->date('date_approved_pe')->nullable();
            $table->foreignUuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preengineering_statuses');
    }
};
