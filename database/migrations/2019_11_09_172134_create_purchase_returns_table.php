<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('return_no');
            $table->unsignedInteger('user_id')->comment('operator id');
            $table->unsignedBigInteger('party_id')->comment('supplier_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->date('date');
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('paid', 10, 2)->default(0.00);
            $table->enum('paid_from', ['cash', 'bank'])->nullable()->comment('cash/bank');
            $table->unsignedBigInteger('cash_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('business_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
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

            $table->foreign('business_id')
                ->references('id')
                ->on('businesses')
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
        Schema::dropIfExists('purchase_returns');
    }
}
