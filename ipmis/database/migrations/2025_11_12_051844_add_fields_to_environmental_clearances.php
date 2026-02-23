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
        Schema::table('environmental_clearances', function (Blueprint $table) {
            $table->date('pamb_date_applied')->nullable();
            $table->date('pamb_date_issued')->nullable();
            $table->string('pamb_status')->nullable();
            $table->date('cmr_date_prepared')->nullable();
            $table->date('cmr_date_submitted')->nullable();
            $table->string('cmr_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('environmental_clearances', function (Blueprint $table) {
            //
        });
    }
};
