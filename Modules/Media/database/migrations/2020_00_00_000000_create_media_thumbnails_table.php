<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('media_thumbnails')) {
            return;
        }


        Schema::create('media_thumbnails', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->text('filename')->index()->nullable();
            $table->longText('image_options')->nullable();
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
        Schema::dropIfExists('media_thumbnails');
    }
};
