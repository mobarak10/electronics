<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 45)->unique();
            $table->string('barcode', 45)->nullable()->unique();
            $table->string('model')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('unit_id');
            $table->decimal('purchase_price', 10, 2)->default(0.00);
            $table->decimal('wholesale_price', 10, 2)->default(0.00);
            $table->decimal('retail_price', 10, 2)->default(0.00);
            $table->decimal('stock_alert', 10, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('business_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('party_id')
                ->references('id')
                ->on('parties')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onUpdate('cascade')
                ->onDelete('RESTRICT');

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
        Schema::dropIfExists('products');
    }
}
