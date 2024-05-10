<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddIndexToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('users', function (Blueprint $table) {

                if(!$table->hasIndex('is_admin')) {
                    $table->index('is_admin');
                }

                //check if username is unique
                if(!$table->hasIndex('username')) {
                    $table->unique('username');
                }

            });
        } catch (Exception $e) {

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
