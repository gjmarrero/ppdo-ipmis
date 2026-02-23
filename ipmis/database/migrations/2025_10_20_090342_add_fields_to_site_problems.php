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
        Schema::table('site_problems', function (Blueprint $table) {
            $table->string('pdc_result')->nullable();
            $table->string('pdc_resolution_number')->nullable();
            $table->string('sb_resolution_number')->nullable();
            $table->string('new_project_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_problems', function (Blueprint $table) {
            //
        });
    }
};
