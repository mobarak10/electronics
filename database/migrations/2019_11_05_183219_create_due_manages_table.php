<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDueManagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('due_manages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('party_id')->nullable();
            $table->date('date');
            $table->decimal('amount', 10, 4);
            $table->string('payment_type')->comment('	supplier paid means given amount to supplier, customer paid means taken amount from customer.For receive vice versa');
            $table->unsignedBigInteger('cash_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->date('check_issue_date')->nullable();
            $table->decimal('check_number', 50, 4)->nullable();
            $table->integer('user_id')->unsigned();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('business_id');
            $table->timestamps();

            // relation
            $table->foreign('party_id')
                  ->references('id')
                  ->on('parties')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('cash_id')
                  ->references('id')
                  ->on('cashes')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('bank_id')
                  ->references('id')
                  ->on('banks')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('bank_account_id')
                  ->references('id')
                  ->on('bank_accounts')
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('due_manages');
    }
}
