<?php

namespace Microweber\Install\Schema;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema as DbSchema;


class JobsQueue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!DbSchema::hasTable('jobs')) {
            DbSchema::create('jobs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('queue');
                $table->longText('payload');
                $table->tinyInteger('attempts')->unsigned();
                $table->tinyInteger('reserved')->unsigned();
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
                $table->index(['queue', 'reserved', 'reserved_at']);
            });
        }
        if (!DbSchema::hasTable('failed_jobs')) {
            DbSchema::create('failed_jobs', function (Blueprint $table) {
                $table->increments('id');
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->timestamp('failed_at');
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
        DbSchema::drop('jobs');
    }
}
