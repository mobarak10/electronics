<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->nullable();
            $table->string('order_no')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('cash_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->enum('payment_type', ['cash', 'bank', 'bkash']);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('vat', 10, 2)->default(0)->comment('percentage');
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('discount_type')->comment('flat/percentage');
            $table->decimal('labour_cost', 10, 2)->default(0.00);
            $table->decimal('transport_cost', 10, 2)->default(0.00);
            $table->decimal('paid', 10, 2)->default(0);
            $table->decimal('due', 10, 2)->default(0);
            $table->decimal('change', 10, 2)->default(0);
            $table->decimal('customer_balance', 10, 2)->default(0)->comment('Customer balance after pre order');
            $table->boolean('delivered')->comment('Delivery status of pre order');
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('business_id');
            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('business_id')
                ->references('id')
                ->on('businesses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('cash_id')
                ->references('id')
                ->on('cashes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('bank_account_id')
                ->references('id')
                ->on('bank_accounts')
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
        Schema::dropIfExists('pre_orders');
    }
}
