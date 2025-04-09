<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaggedTable extends Migration
{

    public function up()
    {

        if (Schema::hasTable('tagging_tagged')) {
            return;
        }


        Schema::create('tagging_tagged', function (Blueprint $table) {
            $table->increments('id');
            if (config('tagging.primary_keys_type') == 'string') {
                $table->string('taggable_id', 36);
            } else {
                $table->integer('taggable_id')->unsigned();
            }
            $table->string('taggable_type', 125);
            $table->string('tag_name', 125);
            $table->string('tag_slug', 125);
        });


        try {
            Schema::create('tagging_tagged', function (Blueprint $table) {
                $table->index('tag_name');
                $table->index('tag_slug');
                $table->index('taggable_type');
                $table->index('taggable_id');


            });
        } catch (\Exception $e) {
            // Handle the exception if needed
        }

    }

    public function down()
    {
        Schema::dropIfExists('tagging_tagged');
    }
}
