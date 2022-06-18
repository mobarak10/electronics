<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvancedSalaryPaidDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advanced_salary_paid_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('advanced_salary_details_id');
            $table->decimal('installment_pay', 10, 4)->default(0.00);
            $table->timestamps();

            $table->foreign('advanced_salary_details_id')
                  ->references('id')
                  ->on('advanced_salary_details')
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
        Schema::dropIfExists('advanced_salary_paid_details');
    }
}
