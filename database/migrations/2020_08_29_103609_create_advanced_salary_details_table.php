<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvancedSalaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advanced_salary_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('advanced_salary_id');
            $table->decimal('adv_amount', 10, 4)->default(0.00);
            $table->decimal('installment_amount', 10, 4)->default(0.00);
            $table->text('note')->nullable();
            $table->boolean('is_paid')->default(0)->comment('0 or 1');
            $table->timestamps();

            $table->foreign('advanced_salary_id')
                  ->references('id')
                  ->on('advanced_salaries')
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
        Schema::dropIfExists('advanced_salary_details');
    }
}
