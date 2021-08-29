<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersOauthTable extends Migration {

	public function up()
	{
		Schema::create('users_oauth', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->string('provider');
			$table->string('data_id');
			$table->string('data_name', 32);
			$table->string('data_email', 320);
			$table->string('data_passward', 64);
			$table->string('data_token', 100)->nullable();
			$table->string('data_avatar')->nullable();
			$table->string('data_raw');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('users_oauth');
	}

}
