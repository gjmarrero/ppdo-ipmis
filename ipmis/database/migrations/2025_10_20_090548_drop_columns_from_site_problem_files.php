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
        Schema::table('site_problem_files', function (Blueprint $table) {
            $table->dropColumn(['pdc_result','pdc_resolution_number','sb_resolution_number','new_project_title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_problem_files', function (Blueprint $table) {
            //
        });
    }
};
