<?php

namespace Microweber\Install\Schema;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema as DbSchema;


class JobsQueue extends Migration
{


    public function get()
    {


        if (DbSchema::hasTable('jobs')) {
            if (!DbSchema::hasColumn('jobs', 'mw_processed')) {
                DbSchema::dropIfExists('jobs');
                DbSchema::dropIfExists('failed_jobs');
            }
        }

        return [
            'jobs' => [
                'queue' => 'text',
                'payload' => 'longText',
                'attempts' => 'integer',
                'reserved' => 'integer',
                'reserved_at' => 'integer',
                'available_at' => 'integer',
                'created_at' => 'integer',
                'updated_at' => 'dateTime',
                'mw_processed' => 'integer',
                'job_hash' => 'text',
            ], 'failed_jobs' => [
                'connection' => 'text',
                'queue' => 'longText',
                'payload' => 'longText',
                'created_at' => 'dateTime',
                'job_hash' => 'text',

            ],
        ];
    }




//    /**
//     * Run the migrations.
//     *
//     * @return void
//     */
//    public function up()
//    {
//
//        if (!DbSchema::hasTable('jobs')) {
//            DbSchema::create('jobs', function (Blueprint $table) {
//                $table->bigIncrements('id');
//                $table->string('queue');
//                $table->longText('payload');
//                $table->tinyInteger('attempts')->unsigned();
//                $table->tinyInteger('reserved')->unsigned()->nullable();
//                $table->unsignedInteger('reserved_at')->nullable();
//                $table->unsignedInteger('available_at');
//                $table->unsignedInteger('created_at');
//                //$table->index(['queue', 'reserved', 'reserved_at']);
//                // error on index SQLSTATE[HY000]: General error: 1 index jobs_queue_reserved_reserved_at_index already exists (SQL: create index jobs_queue_reserved_reserved_at_index on "localhost_jobs" ("queue", "reserved", "reserved_at"))
//            });
//        }
//        if (!DbSchema::hasTable('failed_jobs')) {
//            DbSchema::create('failed_jobs', function (Blueprint $table) {
//                $table->increments('id');
//                $table->text('connection');
//                $table->text('queue');
//                $table->longText('payload');
//                $table->timestamp('failed_at');
//            });
//        }
//    }
//
//    /**
//     * Reverse the migrations.
//     *
//     * @return void
//     */
//    public function down()
//    {
//        DbSchema::drop('jobs');
//    }
}
