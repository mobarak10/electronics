<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierDueManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_due_management', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('party_id')->nullable();
            $table->date('date');
            $table->decimal('amount', 10, 4)->default(0.00)->comment('positive balance means paid negative balance means received');
            $table->enum('paid_from', ['cash', 'bank']);
            $table->unsignedBigInteger('cash_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->date('check_issue_date')->nullable();
            $table->decimal('check_number', 50, 4)->nullable();
            $table->integer('user_id')->unsigned();
            $table->string('description')->nullable();
            $table->decimal('current_balance', 12, 2)->comment('supplier balance after this transaction');
            $table->unsignedBigInteger('business_id');
            $table->timestamps();

            $table->foreign('party_id')
                ->references('id')
                ->on('parties')
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

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('supplier_due_management');
    }
}
