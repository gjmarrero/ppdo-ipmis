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
        Schema::create('preengineering_scope_of_works', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('preengineering_status_id');
            $table->foreignUuid('scope_of_work_id');
            $table->string('description');
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
        Schema::dropIfExists('preengineering_scope_of_works');
    }
};
