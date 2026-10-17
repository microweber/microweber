<?php

namespace MicroweberPackages\User\Services\Concerns;

use MicroweberPackages\Format\Facades\Format;
use MicroweberPackages\Database\Facades\DatabaseManager;
use MicroweberPackages\User\Models\User;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait QueriesUsers
{
    public function get($params = false)
    {
        $id = $params;
        if ($id == false) {
            $id = $this->id();
        }
        if ($id == 0) {
            return false;
        }
        $res = $this->get_by_id($id);
        if (empty($res)) {
            $res = $this->get_by_username($id);
        }

        return $res;
    }

    public function get_by_email($email)
    {
        $data = array();
        $data['email'] = $email;
        $data['limit'] = 1;
        $data = $this->get_all($data);
        if (isset($data[0])) {
            $data = $data[0];
        }

        return $data;
    }

    public function get_by_username($username)
    {
        $data = array();
        $data['username'] = $username;
        $data['limit'] = 1;
        $data = $this->get_all($data);
        if (isset($data[0])) {
            $data = $data[0];
        }

        return $data;
    }

    /**
     * Generic function to get the user by id.
     * Uses the getUsers function to get the data.
     *
     * @param
     *            int id
     *
     * @return array
     */
    public function get_by_id($id)
    {
        $id = intval($id);
        if ($id == 0) {
            return false;
        }

        $findUser = User::where('id', $id)->first();
        if ($findUser == null) {
            return false;
        }

        return $findUser->toArray();
    }

    public function count()
    {
        $options = array();
        $options['count'] = true;
        $options['cache_group'] = 'users/global/';
        $data = $this->get_all($options);

        return $data;
    }

    /**
     * @function get_users
     *
     * @param $params array|string;
     * @params $params['username'] string username for user
     * @params $params['email'] string email for user
     * @params $params['password'] string password for user
     *
     *
     * @usage $this->get_all('email=my_email');
     *
     * @return array of users;
     */
    public function get_all($params)
    {
        $params = parse_params($params);

        $table = 'users';

        $data = Format::clean_html($params);
        $orig_data = $data;

        if (isset($data['ids']) and is_array($data['ids'])) {
            if (!empty($data['ids'])) {
                $ids = $data['ids'];
            }
        }
        if (!isset($params['search_in_fields'])) {
            $data['search_in_fields'] = array('id', 'first_name', 'last_name', 'username', 'email');
        }
        $cache_group = 'users/global';
        if (isset($limit) and $limit != false) {
            $data['limit'] = $limit;
        }
        if (isset($count_only) and $count_only != false) {
            $data['count'] = $count_only;
        }
        if (isset($data['username']) and $data['username'] == false) {
            unset($data['username']);
        }

        $data['table'] = $table;
        $data['exclude_shorthand'] = true;
        // $data['no_cache'] = 1;

        $get = DatabaseManager::get($data);

        return $get;
    }
}
