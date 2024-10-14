<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tax_types')) {
            return;
        }


        Schema::create('tax_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->text('settings')->nullable();
            $table->decimal('rate', 5, 2)->nullable();
            $table->decimal('compound_tax', 5, 2)->nullable();
            $table->decimal('collective_tax', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){


        Schema::dropIfExists('tax_types');
    }
};
