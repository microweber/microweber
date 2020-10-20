<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tax_type_id')->unsigned();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->integer('invoice_item_id')->unsigned()->nullable();
            $table->integer('item_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->string('name');
            $table->unsignedBigInteger('amount');
            $table->string('type');
            $table->decimal('rate', 5, 2);
            $table->tinyInteger('compound_tax')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxes');
    }
}
