<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('shipping_providers')) {
            return;
        }

        Schema::create('shipping_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('provider')->nullable();
            $table->integer('is_active')->nullable();
            $table->integer('is_default')->nullable();
            $table->integer('position')->nullable();
            $table->text('settings')->nullable();
            $table->timestamps();

        });

    }


}
