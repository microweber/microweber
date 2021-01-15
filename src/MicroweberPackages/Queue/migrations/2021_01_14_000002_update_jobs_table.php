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

            $table->unsignedTinyInteger('reserved')->nullable();
            $table->unsignedTinyInteger('mw_processed')->nullable();
            $table->longText('job_hash')->nullable();
            $table->timestamp('updated_at')->useCurrent();

        });
    }

}
