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
        Schema::create('order_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_terms_master_id')->constrained('order_masters')->onDelete('cascade');
            $table->integer('order_term_id');
            $table->string('order_term_name');
            $table->string('order_term_description')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_terms');
    }
};
