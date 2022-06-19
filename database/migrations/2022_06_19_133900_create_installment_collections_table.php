<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment_collections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('hire_sale_id');
            $table->unsignedBigInteger('party_id');
            $table->date('date');
            $table->string('paid_by')->nullable();
            $table->decimal('payment_amount')->default(0.00);
            $table->decimal('remission')->default(0.00);
            $table->decimal('adjustment')->default(0.00);
            $table->timestamps();

            $table->foreign('hire_sale_id')
                ->references('id')
                ->on('hire_sales');

            $table->foreign('party_id')
                ->references('id')
                ->on('parties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('installment_collections');
    }
}
