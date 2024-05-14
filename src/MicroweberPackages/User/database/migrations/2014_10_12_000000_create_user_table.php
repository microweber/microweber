<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createUsersTable();
        app()->database_manager->build_tables($this->getSchema());
    }

    public function createUsersTable()
    {

        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->dateTime('expires_on')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('edited_by')->nullable();
            $table->string('username')->unique();
            $table->string('password')->nullable();;
            $table->string('email')->unique();
            $table->rememberToken();
            $table->integer('is_active')->nullable();
            $table->integer('is_admin')->nullable();
            $table->integer('is_verified')->nullable();
            $table->integer('is_public')->nullable();
            $table->string('basic_mode')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('api_key')->nullable();
            $table->text('user_information')->nullable();
            $table->string('subscr_id')->nullable();
            $table->string('role')->nullable();
            $table->string('medium')->nullable();
            $table->string('oauth_uid')->nullable();
            $table->string('oauth_provider')->nullable();
            $table->text('oauth_token')->nullable();
            $table->text('oauth_token_secret')->nullable();
            $table->string('profile_url')->nullable();
            $table->string('website_url')->nullable();
            $table->string('password_reset_hash')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->text('two_factor_secret')->nullable();
        });
    }

    public function getSchema()
    {
        return [


            'users_oauth' => [
                'user_id' => 'integer',
                'provider' => 'string',
                'data_id' => 'string',
                'data_name' => 'string',
                'data_email' => 'string',
                'data_token' => 'string',
                'data_avatar' => 'string',
                'data_raw' => 'string',
            ],

            //            'users_temp_login_tokens' => [
            //                'user_id' => 'integer',
            //                'token' => 'text',
            //                'server_ip' => 'string',
            //                'login_ip' => 'string',
            //                'login_at' => 'dateTime',
            //                'created_at' => 'dateTime'
            //            ],

            'login_attempts' => [
                'user_id' => 'integer',
                'username' => 'string',
                'email' => 'string',
                'ip' => 'string',
                'success' => 'integer',
                'time' => 'string',
            ],

        ];
    }
}
