<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            try {

                if (!Schema::hasIndex('users', 'users_username_index')) {
                    $table->unique('username', 'users_username_index');
                }

                if (!Schema::hasIndex('users', 'users_email_index')) {
                    $table->unique('email', 'users_email_index');
                }

            } catch (\Exception $e) {
                // do nothing
            }


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {


            if (Schema::hasIndex('users', 'users_username_index')) {
                $table->dropUnique('users_username_index');
            }

            if (Schema::hasIndex('users', 'users_email_index')) {
                $table->dropUnique('users_email_index');
            }


        });
    }

}
