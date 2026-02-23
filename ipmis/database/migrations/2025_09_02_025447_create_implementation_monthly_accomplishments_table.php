<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('implementation_monthly_accomplishments', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignUuid('implementation_id');
            $table->integer('month');
            $table->decimal('target', 2, 2);
            $table->decimal('actual', 2, 2)->nullable();
            $table->date('date_actual_submitted')->nullable();
            $table->foreignUuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('implementation_monthly_accomplishments');
    }
};
