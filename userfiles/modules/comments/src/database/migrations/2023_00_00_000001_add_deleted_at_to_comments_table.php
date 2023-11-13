<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            // check if column exists
            if (!Schema::hasColumn('comments', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable();
            }
         });

    }

}
