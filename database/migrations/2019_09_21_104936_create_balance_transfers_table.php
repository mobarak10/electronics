<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->date('transfer_date');
            $table->string('transfer_from')->comment('bank/cash');
            $table->string('transfer_from_id')->comment('bank account/cash id');
            $table->string('transfer_to')->comment('bank/cash');
            $table->string('transfer_to_id')->comment('bank account/cash id');
            $table->string('cheque_no')->nullable();
            $table->string('cheque_issue_date')->nullable();
            $table->decimal('amount', 10, 4)->default(0.00);
            $table->longText('note')->nullable();
            $table->unsignedBigInteger('operator');
            $table->unsignedBigInteger('business_id');
            $table->timestamps();

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
        Schema::dropIfExists('balance_transfers');
    }
}
