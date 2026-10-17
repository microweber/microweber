<?php

namespace MicroweberPackages\User\Services\Concerns;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ReadsUserProfile
{
    public function attributes($user_id = false)
    {
        if (!$user_id) {
            $user_id = $this->id();
        }
        if (!$user_id) {
            return;
        }

        $data = array();
        $data['rel_type'] = 'users';
        $data['rel_id'] = intval($user_id);
        $res = array();
        $get = $this->app->attributes_manager->get($data);
        if (!empty($get)) {
            foreach ($get as $item) {
                if (isset($item['attribute_name']) and isset($item['attribute_value'])) {
                    $res[$item['attribute_name']] = $item['attribute_value'];
                }
            }
        }
        if (!empty($res)) {
            return $res;
        }

        return $get;
    }

    public function data_fields($user_id = false)
    {
        if (!$user_id) {
            $user_id = $this->id();
        }
        if (!$user_id) {
            return;
        }

        $data = array();
        $data['rel_type'] = 'users';
        $data['rel_id'] = intval($user_id);
        $res = array();
        $get = $this->app->content_manager->get_data($data);
        if (!empty($get)) {
            foreach ($get as $item) {
                if (isset($item['field_name']) and isset($item['field_value'])) {
                    $res[$item['field_name']] = $item['field_value'];
                }
            }
        }
        if (!empty($res)) {
            return $res;
        }

        return $get;
    }

    public function has_picture($user_id = false)
    {

        if (!$user_id) {
            $user_id = $this->id();
        }

        $name = $this->get_by_id($user_id);
        if (isset($name['thumbnail']) and $name['thumbnail'] != '') {
            return true;
        }

        return false;
    }

    public function picture($user_id = false)
    {

        if (!$user_id) {
            $user_id = $this->id();
        }

        $name = $this->get_by_id($user_id);
        if (isset($name['thumbnail']) and $name['thumbnail'] != '') {
            if (is_https()) {
                $rep = 1;
                $name['thumbnail'] = str_ireplace('http://', '//', $name['thumbnail'], $rep);
            }
            return $name['thumbnail'];
        }

        return modules_url() . 'microweber/api/libs/mw-ui/assets/img/no-user.svg';
    }

    /**
     * @function user_name
     * gets the user's FULL name
     *
     * @param        $user_id the id of the user. If false it will use the curent user (you)
     * @param string $mode full|first|last|username
     *                        'full' //prints full name (first +last)
     *                        'first' //prints first name
     *                        'last' //prints last name
     *                        'username' //prints username
     *
     * @return string
     */
    public function name($user_id = false, $mode = 'full')
    {
        if ($mode != 'username') {
            if ($user_id == user_id()) {
                // return 'You';
            }
        }
        if ($user_id == false) {
            $user_id = user_id();
        }

        $name = $this->nice_name($user_id, $mode);

        return $name;
    }

    public function nice_name($id = false, $mode = 'full')
    {

        if (!$id) {
            $id = $this->id();
        }

        if (isset($this->nice_name_cache[$mode . $id])) {
            return $this->nice_name_cache[$mode . $id];
        }

        $user = $this->get_by_id($id);

        $user_data = $user;
        if (empty($user)) {
            return false;
        }

        switch ($mode) {
            case 'first' :
            case 'fist' :
                // because of a common typo :)
                $user_data['first_name'] ? $name = $user_data['first_name'] : $name = $user_data['username'];
                $name = ucwords($name);

                if (trim($name) == '' and $user_data['email'] != '') {
                    $n = explode('@', $user_data['email']);
                    $name = $n[0];
                }
                // return $name;
                break;

            case 'last' :
                $user_data['last_name'] ? $name = $user_data['last_name'] : $name = $user_data['last_name'];
                $name = ucwords($name);
                break;

            case 'username' :
                $name = $user_data['username'];
                break;

            case 'email' :
                $name = $user_data['email'];
                break;

            case 'full' :
            default :

                $name = '';
                if (isset($user_data['first_name'])) {
                    if ($user_data['first_name']) {
                        $name = $user_data['first_name'];
                    }
                }

                if (isset($user_data['last_name'])) {
                    if ($user_data['last_name']) {
                        $name .= ' ' . $user_data['last_name'];
                    }
                }
                $name = ucwords($name);

                if (trim($name) == '' and $user_data['email'] != '') {
                    $name = $user_data['email'];
                    $name_from_email = explode('@', $user_data['email']);
                    $name = $name_from_email[0];
                }

                if (trim($name) == '' and $user_data['username'] != '') {
                    $name = $user_data['username'];
                    $name = ucwords($name);
                }

                break;
        }

        if (!isset($name) or $name == false or $name == null or trim($name) == '') {
            if (isset($user_data['username']) and $user_data['username'] != false and trim($user_data['username']) != '') {
                $name = $user_data['username'];
            } elseif (isset($user_data['email']) and $user_data['email'] != false and trim($user_data['email']) != '') {
                $name_from_email = explode('@', $user_data['email']);
                $name = $name_from_email[0];
            }
        }
        $this->nice_name_cache[$mode . $id] = $name;
        return $name;
    }
}
