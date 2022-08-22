<?php


use Illuminate\Database\Migrations\Migration;

class CreateContentDataVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_data_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('custom_field_id')->nullable();
            $table->integer('custom_field_value_id')->nullable();
            $table->integer('rel_id')->nullable();
            $table->text('rel_type')->nullable();
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
        Schema::drop('content_data_variants');
    }
}
