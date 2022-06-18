<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_management', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_no');
            $table->unsignedBigInteger('party_id')->comment('customer id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedInteger('sr_id')->comment('sales representative');
            $table->unsignedInteger('delivery_man_id')->nullable()->comment('delivery man');
            $table->boolean('status')->default(0)->comment('0 or 1');
            $table->timestamps();

            $table->foreign('business_id')
                ->references('id')
                ->on('businesses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

                $table->foreign('party_id')
                ->references('id')
                ->on('parties')
                ->onUpdate('cascade');

                $table->foreign('sr_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');

                $table->foreign('delivery_man_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_management');
    }
}
