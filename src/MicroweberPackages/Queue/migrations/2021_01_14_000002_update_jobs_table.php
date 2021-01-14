<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {

            $table->unsignedTinyInteger('reserved');
            $table->unsignedTinyInteger('mw_processed');
            $table->longText('job_hash');
            $table->timestamp('updated_at')->useCurrent();

        });
    }

}
