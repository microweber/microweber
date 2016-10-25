<?php

namespace Microweber\Install\Schema;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema as DbSchema;


class Tags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!DbSchema::hasTable('tags')) {
            DbSchema::create('tags', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('edited_by')->nullable();

                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
                $table->string('session_id')->nullable();

            });
        }
        if (!DbSchema::hasTable('content_tag')) {
            DbSchema::create('content_tag', function (Blueprint $table) {
                $table->integer('content_id')->unsigned()->index();
                $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');
                $table->integer('tag_id')->unsigned()->index();
                $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
                $table->primary(['content_id', 'tag_id']);

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
        DbSchema::drop('tags');
        DbSchema::drop('content_tag');

    }
}
