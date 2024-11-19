<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableExists = Schema::hasTable('product_meta_data');
        if (!$tableExists) {
            Schema::create('product_meta_data', function ($table) {
                $table->increments('id');
                $table->integer('product_id')->nullable();

                $table->string('qty')->nullable();
                $table->string('sku')->nullable();
                $table->string('barcode')->nullable();
                $table->string('track_quantity')->nullable();
                $table->string('max_quantity_per_order')->nullable();
                $table->string('sell_oos')->nullable();
                $table->string('physical_product')->nullable();
                $table->string('free_shipping')->nullable();
                $table->string('shipping_fixed_cost')->nullable();
                $table->string('weight_type')->nullable();
                $table->string('params_in_checkout')->nullable();
                $table->string('has_special_price')->nullable();
                $table->string('weight')->nullable();
                $table->string('width')->nullable();
                $table->string('height')->nullable();
                $table->string('depth')->nullable();

                $table->timestamps();
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
        // delete
    }

};
