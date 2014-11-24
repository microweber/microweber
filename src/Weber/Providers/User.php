<?php




/*
 * This file is part of the Weber framework.
 *
 * (c) Weber LTD
 *
 * For full license information see
 * http://WeberCMS.com/license/
 *
 */

namespace Weber\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\User as DefaultUserProvider;
use Weber\Utils\Database;

class User
{
    public $tables = array();


    function __construct($app = null)
    {
        $this->set_table_names();
    }

    public function install()
    {
        $this->db_init();
    }


    public function set_table_names($tables = false)
    {

        if (!is_array($tables)) {
            $tables = array();
        }
        if (!isset($tables['users'])) {
            $tables['users'] = 'users';
        }
        if (!isset($tables['log'])) {
            $tables['log'] = 'log';
        }
        $this->tables['users'] = $tables['users'];
        $this->tables['log'] = $tables['log'];


    }

    public function db_init()
    {


        $table_name = $this->tables['users'];

        $fields_to_add = array();

        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['expires_on'] = 'dateTime';
        $fields_to_add['last_login'] = 'dateTime';
        $fields_to_add['last_login_ip'] = 'longText';

        $fields_to_add['created_by'] = 'integer';

        $fields_to_add['edited_by'] = 'integer';

        $fields_to_add['username'] = 'longText';

        $fields_to_add['password'] = 'longText';
        $fields_to_add['email'] = 'longText';

        $fields_to_add['is_active'] = "string";
        $fields_to_add['is_admin'] = "string";
        $fields_to_add['is_verified'] = "string";
        $fields_to_add['is_public'] = "string";

        $fields_to_add['basic_mode'] = "string";

        $fields_to_add['first_name'] = 'longText';
        $fields_to_add['last_name'] = 'longText';
        $fields_to_add['thumbnail'] = 'longText';

        $fields_to_add['parent_id'] = 'integer';

        $fields_to_add['api_key'] = 'longText';

        $fields_to_add['user_information'] = 'longText';
        $fields_to_add['subscr_id'] = 'longText';
        $fields_to_add['role'] = 'longText';
        $fields_to_add['medium'] = 'longText';

        $fields_to_add['oauth_uid'] = 'longText';
        $fields_to_add['oauth_provider'] = 'longText';
        $fields_to_add['oauth_token'] = 'longText';
        $fields_to_add['oauth_token_secret'] = 'longText';

        $fields_to_add['profile_url'] = 'longText';
        $fields_to_add['website_url'] = 'longText';
        $fields_to_add['password_reset_hash'] = 'longText';

        wb()->database->build_table($table_name, $fields_to_add);

        wb()->database->add_table_index('username', $table_name, array('username(255)'));
        wb()->database->add_table_index('email', $table_name, array('email(255)'));


        $table_name = $this->tables['log'];

        $fields_to_add = array();

        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['created_by'] = 'integer';
        $fields_to_add['edited_by'] = 'integer';
        $fields_to_add['rel'] = 'longText';

        $fields_to_add['rel_id'] = 'longText';
        $fields_to_add['position'] = 'integer';

        $fields_to_add['field'] = 'longlongText';
        $fields_to_add['value'] = 'longText';
        $fields_to_add['module'] = 'longlongText';

        $fields_to_add['data_type'] = 'longText';
        $fields_to_add['title'] = 'longlongText';
        $fields_to_add['description'] = 'longText';
        $fields_to_add['content'] = 'longText';
        $fields_to_add['user_ip'] = 'longText';
        $fields_to_add['session_id'] = 'longlongText';
        $fields_to_add['is_system'] = "string";

        wb()->database->build_table($table_name, $fields_to_add);

        return true;

    }

    public function is_admin()
    {





    }

}