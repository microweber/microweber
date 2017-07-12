<?php

namespace Microweber\Install\Schema;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;


class Tags extends Migration
{


    public function up()
    {
        if (!Schema::hasTable('tagging_tagged')) {
            Schema::create('tagging_tagged', function (Blueprint $table) {
                $table->increments('id');
                if (config('tagging.primary_keys_type') == 'string') {
                    $table->string('taggable_id', 36)->index();
                } else {
                    $table->integer('taggable_id')->unsigned()->index();
                }
                $table->string('taggable_type', 255)->index();
                $table->string('tag_name', 255);
                $table->string('tag_slug', 255)->index();
            });
        }
        if (!Schema::hasTable('tagging_tags')) {
            Schema::create('tagging_tags', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug', 255)->index();
                $table->string('name', 255);
                $table->boolean('suggest')->default(false);
                $table->integer('count')->unsigned()->default(0); // count of how many times this tag was used
            });
        }
        if (!Schema::hasTable('tagging_tag_groups')) {
            Schema::create('tagging_tag_groups', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug', 255)->index();
                $table->string('name', 255);
            });
        }
        if (!Schema::hasTable('tagging_tags')) {
            Schema::table('tagging_tags', function ($table) {

                try {
                    $table->integer('tag_group_id')->unsigned()->nullable()->after('id');
                    $table->foreign('tag_group_id')->references('id')->on('tagging_tag_groups');
                } catch (\Illuminate\Database\QueryException $e) {

                }



            });
        }

    }

    public function down()
    {
        Schema::drop('tagging_tagged');
        Schema::drop('tagging_tags');
        Schema::drop('tagging_tag_groups');
        Schema::table('tagging_tags', function ($table) {
            $table->dropForeign('tagging_tags_tag_group_id_foreign');
            $table->dropColumn('tag_group_id');
        });


    }

}
