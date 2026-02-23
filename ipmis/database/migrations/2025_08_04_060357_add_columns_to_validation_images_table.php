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
        Schema::table('validation_images', function (Blueprint $table) {
            $table->decimal('lat',15,6)->nullable();
            $table->decimal('long', 15,6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('validation_images', function (Blueprint $table) {
            //
        });
    }
};
