<?php


namespace Microweber\Providers\Users;

use Microweber\Providers\Database\Crud;

class TosManager extends Crud
{
    /** @var \Microweber\Application */
    public $app;

    public $table = 'terms_accept_log';

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

    public function terms_accept($tos_name, $user_id_or_email = false)
    {
        if (!$tos_name) {
            return;
        }
        if (!$user_id_or_email) {
            $user_id_or_email = $this->app->user_manager->id();
        }
        if (!$user_id_or_email) {
            return;
        }
        $table = $this->table;

        $existing = $this->tos_check($tos_name, $user_id_or_email);
        if (!$existing) {
            $save = array();
            $save['table'] = $table;

            if (is_numeric($user_id_or_email)) {
                $save['user_id'] = intval($user_id_or_email);
            } else if (is_numeric($user_id_or_email)) {
                $save['user_email'] = trim($user_id_or_email);
            }
            $save['tos_name'] = $tos_name;
            $s = $this->app->database_manager->save($save);
            if ($s) {
                return $s;
            }
        }

    }

    public function terms_check($tos_name = false, $user_id_or_email = false)
    {

        if (!$tos_name) {
            return;
        }

        if (!$user_id_or_email) {
            $user_id_or_email = $this->app->user_manager->id();
        }
        if (!$user_id_or_email) {
            return;
        }

        $table = $this->table;
        $data['table'] = $table;
        $data['limit'] = 1;
        if (is_numeric($user_id_or_email)) {
            $data['user_id'] = intval($user_id_or_email);
        } else if (is_numeric($user_id_or_email)) {
            $data['user_email'] = trim($user_id_or_email);
        }
        $data['tos_name'] = $tos_name;

        $get = $this->app->database_manager->get($data);
        if ($get) {
            return true;
        }


    }
}
