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

            $table->integer('reserved')->nullable();
            $table->integer('mw_processed')->nullable();
            $table->longText('job_hash')->nullable();

            if (!Schema::hasColumn('failed_jobs', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }

        });
    }

}
