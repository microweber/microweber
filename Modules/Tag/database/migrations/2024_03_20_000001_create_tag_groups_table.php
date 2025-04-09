<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if(Schema::hasTable('tagging_tag_groups')){
            return;
        }

        Schema::create('tagging_tag_groups', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->timestamps();
        });


        try {
            Schema::create('tagging_tag_groups', function (Blueprint $table) {
                $table->index('slug');
            });
        } catch (\Exception $e) {
            // Handle the exception if needed
        }
    }

    public function down()
    {
        Schema::dropIfExists('tagging_tag_groups');
    }
};
