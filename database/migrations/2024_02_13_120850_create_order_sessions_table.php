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
        Schema::create('order_sessions', function (Blueprint $table) {
            $table->id();
            $table->integer('order_session_master_id')->constrained('order_masters')->onDelete('cascade');
            $table->text('order_session')->nullable();
            $table->text('order_terms_session')->nullable();
            $table->text('order_charges_session')->nullable();
            $table->text('order_totalcalculations_session')->nullable();
            $table->text('order_make1totalTaxes_session')->nullable();
            $table->text('order_make2totalTaxes_session')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_sessions');
    }
};
