<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHireSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hire_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_no')->unique();
            $table->date('date');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('party_id');
            $table->decimal('previous_balance')->default(0.00);
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('due', 10, 2)->default(0.00);
            $table->decimal('pay', 10, 2)->default(0.00);
            $table->boolean('installment_status')->default(0);
            $table->decimal('added_value', 10, 2)->default(0.00);
            $table->decimal('down_payment', 10, 2)->default(0.00);
            $table->string('installment_number')->nullable();
            $table->timestamps();


            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('party_id')
                ->references('id')
                ->on('parties')
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
        Schema::dropIfExists('hire_sales');
    }
}
