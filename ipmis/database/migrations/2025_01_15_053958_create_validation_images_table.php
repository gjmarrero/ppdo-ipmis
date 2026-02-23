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
        Schema::create('validation_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('validation_id');
            $table->string('file')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->foreignUuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validation_images');
    }
};
