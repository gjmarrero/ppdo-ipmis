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
            $table->foreignUuid('archive_id')->nullable();
            $table->dropColumn('pdc_resolution_number');
            $table->dropColumn('sb_resolution_number');
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
