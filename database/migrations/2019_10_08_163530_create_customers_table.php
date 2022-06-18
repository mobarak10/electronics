<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 45)->unique();
            $table->string('name');
            $table->string('type')->nullable();
            $table->decimal('credit_limit')->nullable()->default(0.00);
            $table->longText('description')->nullable();
            $table->string('phone', 45)->nullable();
            $table->string('email')->nullable();
            $table->decimal('balance', 10, 2)->default(0.00)->comment('positive balance means receivable negative balance means payable');
            $table->longText('address')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('customers');
    }
}
