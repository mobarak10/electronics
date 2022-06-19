<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHireSaleInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hire_sale_installments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('hire_sale_id');
            $table->date('installment_date');
            $table->decimal('installment_amount', 10, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('hire_sale_id')
                ->references('id')
                ->on('hire_sales')
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
        Schema::dropIfExists('hire_sale_installments');
    }
}
