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
        Schema::create('environmental_clearances', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('funded_project_id');
            $table->foreignUuid('e_c_certificate_type_id');
            $table->foreignUuid('e_c_pamb_type_id');
            $table->date('date_application');
            $table->date('date_issued')->nullable();
            $table->foreignUuid('employee_id');
            $table->string('status');
            $table->boolean('is_transmitted_peo');
            $table->date('date_transmitted_peo')->nullable();
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
        Schema::dropIfExists('environmental_clearances');
    }
};
