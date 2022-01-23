<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->text('title')->nullable();
                $table->text('description')->nullable();
                $table->text('embed_code')->nullable();
                $table->text('filename')->nullable();
                $table->text('media_type')->nullable()->index();

                $table->string('rel_type')->nullable()->index();
                $table->string('rel_id')->nullable()->index();

                $table->integer('created_by')->nullable();
                $table->integer('edited_by')->nullable();
                $table->string('session_id')->nullable();

                $table->longText('image_options')->nullable();
                $table->integer('position')->nullable();
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
        Schema::dropIfExists('media');
    }
}
