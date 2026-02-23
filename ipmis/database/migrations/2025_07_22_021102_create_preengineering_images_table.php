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
        Schema::create('preengineering_images', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('file');
            $table->decimal('lat',15,6);
            $table->decimal('long', 15, 6);
            $table->foreignUuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preengineering_images');
    }
};
