<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if(Schema::hasTable('tagging_tags')){
            return;
        }

        Schema::create('tagging_tags', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->index();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('suggest')->default(false);
            $table->integer('count')->unsigned()->default(0);
            $table->integer('tag_group_id')->unsigned()->nullable();
            $table->string('locale')->nullable();
            $table->timestamps();


        });

        try {
            Schema::create('tagging_tags', function (Blueprint $table) {
                $table->index('slug');
            });
        } catch (\Exception $e) {
            // Handle the exception if needed
        }
    }

    public function down()
    {
        Schema::dropIfExists('tagging_tags');
    }
};
