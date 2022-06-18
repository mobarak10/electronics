<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->enum('payment_type', ['cash', 'bank', 'bkash']);
            $table->unsignedBigInteger('party_id')->comment('Supplier id');
            $table->unsignedBigInteger('cash_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('warehouse_id');
            $table->string('voucher_no');
            $table->decimal('subtotal', 10, 4)->default(0);
            $table->decimal('discount', 10, 4)->default(0);
            $table->string('discount_type')->default('percentage')->comment('percentage/flat');
            $table->decimal('labour_cost', 10, 2)->default(0.00);
            $table->decimal('transport_cost', 10, 2)->default(0.00);
            $table->decimal('paid', 10, 4)->default(0);
            $table->decimal('due', 10, 2)->default(0);
            $table->decimal('previous_balance', 10, 2)->default(0)->comment('positive balance means receivable negative balance means payable');
            $table->text('note')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('business_id');
            $table->timestamps();

            $table->foreign('party_id')
                ->references('id')
                ->on('parties')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('purchases');
    }
}
