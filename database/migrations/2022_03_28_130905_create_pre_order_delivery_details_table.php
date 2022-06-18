<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreOrderDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_order_delivery_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pre_order_id');
            $table->unsignedBigInteger('product_id');
            $table->date('date');
            $table->integer('delivery_quantity');
            $table->string('delivery_quantity_in_unit');
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
        Schema::dropIfExists('pre_order_delivery_details');
    }
}
