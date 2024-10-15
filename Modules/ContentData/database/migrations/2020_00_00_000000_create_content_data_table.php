<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('content_data')) {
            return;
        }


        Schema::create('content_data', function (Blueprint $table) {
            $table->string('rel_type');
            $table->string('rel_id');
            $table->text('field_name');
            $table->longText('field_value');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
            $table->integer('created_by');
            $table->integer('edited_by');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_data');
    }
};
