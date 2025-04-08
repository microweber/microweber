<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()->database_manager->build_tables($this->getSchema());
    }

    public function getSchema()
    {
        return [
            'users' => [
                'username' => 'string',
                'password' => 'string',
                'email' => 'string',
                'is_active' => 'integer',
                'is_admin' => 'integer',
                'is_verified' => 'integer',
                'is_public' => 'integer',

                'last_login' => 'dateTime',
                'last_login_ip' => 'string',
                'created_by' => 'integer',
                'edited_by' => 'integer',

                'remember_token' => 'string',

                'basic_mode' => 'string',
                'first_name' => 'string',
                'middle_name' => 'string',
                'last_name' => 'string',
                'phone' => 'string',
                'thumbnail' => 'string',
                'parent_id' => 'integer',
                'api_key' => 'string',
                'user_information' => 'text',
                'subscr_id' => 'string',
                'role' => 'string',
                'medium' => 'string',
                'oauth_uid' => 'string',
                'oauth_provider' => 'string',
                'oauth_token' => 'text',
                'oauth_token_secret' => 'text',
                'profile_url' => 'string',
                'website_url' => 'string',
                'password_reset_hash' => 'string',
                'email_verified_at' => 'dateTime',
                'two_factor_recovery_codes' => 'text',
                'two_factor_secret' => 'text',
                'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'expires_on' => 'dateTime',
                '$index' => ['username', 'email'],
            ],

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
};
