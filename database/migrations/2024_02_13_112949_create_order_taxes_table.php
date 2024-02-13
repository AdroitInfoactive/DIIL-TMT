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
        Schema::create('order_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_tax_master_id');
            $table->foreignId('order_tax_detail_id')->constrained('order_details')->onDelete('cascade');
            $table->integer('order_tax_id');
            $table->string('order_tax_name');
            $table->string('order_tax_value');
            $table->float('order_tax_amount');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_taxes');
    }
};
