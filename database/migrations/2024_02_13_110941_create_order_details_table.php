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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_detail_master_id')->constrained('order_masters')->onDelete('cascade');
            $table->integer('product_id');
            $table->string('description')->nullable();
            $table->integer('uom_id');
            $table->string('quantity');
            $table->integer('make_id');
            $table->float('price');
            $table->float('priceXqty');
            $table->float('total_price');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
