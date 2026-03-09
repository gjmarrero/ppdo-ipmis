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
        Schema::table('implementation_orders', function (Blueprint $table) {
            $table->date('date_prepared')->nullable();
            $table->date('date_checked')->nullable();
            $table->date('date_reviewed')->nullable();
            $table->date('date_recommended_for_approval')->nullable();
            $table->date('date_forwarded_to_lce')->nullable();
            $table->date('date_approved_by_lce')->nullable();
            $table->date('date_signed_by_contractor')->nullable();
            $table->string('dts_barcode')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('implementation_orders', function (Blueprint $table) {
            //
        });
    }
};
