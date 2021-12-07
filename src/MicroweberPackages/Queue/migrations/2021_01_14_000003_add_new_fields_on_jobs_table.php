<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsOnJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {

            if (!Schema::hasColumn('jobs', 'reserved')) {
                $table->integer('reserved')->nullable();
            }

            if (!Schema::hasColumn('jobs', 'mw_processed')) {
                $table->integer('mw_processed')->nullable();
            }

            if (!Schema::hasColumn('jobs', 'job_hash')) {
                $table->longText('job_hash')->nullable();
            }

            if (!Schema::hasColumn('jobs', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }

        });
    }

}
