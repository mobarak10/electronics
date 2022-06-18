<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pre_order_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('purchase_price', 10, 4)->comment('per unit');
            $table->decimal('sale_price', 10, 4)->comment('per unit');
            $table->integer('quantity');
            $table->string('quantity_in_unit');
            $table->integer('delivery_quantity');
            $table->string('delivery_quantity_in_unit');
            $table->decimal('vat', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->string('discount_type')->comment('flat/percentage');
            $table->decimal('line_total', 10, 2);
            $table->timestamps();

            $table->foreign('pre_order_id')
                ->references('id')
                ->on('pre_orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_order_details');
    }
}
