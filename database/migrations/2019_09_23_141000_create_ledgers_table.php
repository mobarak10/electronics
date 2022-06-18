<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->text('description');
            $table->decimal('debit', 10, 4)->default(0.00);
            $table->decimal('credit', 10, 4)->default(0.00);
            $table->decimal('balance', 10, 4)->default(0.00);
            $table->string('note')->nullable();
            $table->timestamps();
        });

        if(Schema::hasTable('ledgers')) {
            Schema::create('ledgerables', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('ledger_id');
                $table->nullableMorphs('ledgerable');
                $table->timestamps();

                $table->foreign('ledger_id')
                    ->references('id')
                    ->on('ledgers')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledgerables');
        Schema::dropIfExists('ledgers');
    }
}
