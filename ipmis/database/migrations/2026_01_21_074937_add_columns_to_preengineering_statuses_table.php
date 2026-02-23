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
        Schema::table('preengineering_statuses', function (Blueprint $table) {
            $table->date('date_received_by_ape')->nullable();
            $table->date('date_reviewed')->nullable();
            $table->date('date_recommended_for_approval')->nullable();
            $table->date('date_submitted_to_lce')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preengineering_statuses', function (Blueprint $table) {
            //
        });
    }
};
