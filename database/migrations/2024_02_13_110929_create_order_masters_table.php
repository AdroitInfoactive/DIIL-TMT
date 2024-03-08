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
        Schema::create('order_masters', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_entity_id');
            $table->string('order_main_prefix');//bsa
            $table->string('order_entity_prefix');//ab
            $table->string('order_financial_year');//23-24
            $table->integer('user_id');
            $table->integer('client_id');
            $table->string('order_type')->default('N');
            $table->string('order_no');
            $table->string('order_note')->nullable();
            $table->date('order_date');
            $table->float('order_total_quantity');
            $table->float('order_total_amount');
            $table->float('order_total_amount_withcharges');
            $table->string('tax_type')->nullable();
            $table->string('po_raised_status')->default('no');
            $table->string('invoice_raised_status')->default('no');
            $table->string('order_delete_status')->default('n');
            $table->string('order_status')->default('p');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_masters');
    }
};
