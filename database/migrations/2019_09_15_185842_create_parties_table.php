<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 45)->unique();
            $table->string('name');
            $table->string('genus')->nullable();
            $table->string('type')->nullable();
            $table->longText('description')->nullable();
            $table->string('phone', 45)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('thana')->nullable();
            $table->longText('address')->nullable();
            $table->string('thumbnail')->nullable();
            $table->decimal('balance', 10, 2)->default(0.0)->comment('positive(+) balance means receivable and negative(-) is payable');
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('business_id');
            $table->SoftDeletes();
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
        Schema::dropIfExists('parties');
    }
}
