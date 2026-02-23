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
        Schema::table('funded_projects', function (Blueprint $table) {
            $table->year('year_funded')->required();
            $table->string('sb_number')->nullable();
            $table->boolean('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('funded_projects', function (Blueprint $table) {
            //
        });
    }
};
