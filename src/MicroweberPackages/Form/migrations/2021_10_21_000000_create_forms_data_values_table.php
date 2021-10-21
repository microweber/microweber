<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsDataValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms_data_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('form_data_id')->nullable();
            $table->string('field_type')->nullable();
            $table->string('field_key')->nullable();
            $table->string('field_name')->nullable();
            $table->longText('field_value')->nullable();
        });
    }
}
