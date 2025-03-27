<?php

namespace Modules\Log\Services;

use Illuminate\Support\Facades\DB;

class LogManager
{
    public $app;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

    public function get_entry_by_id($id)
    {
        $params = array();
        $params['id'] = intval($id);
        $params['one'] = true;

        $get = $this->get($params);

        return $get;
    }

    public function get($params)
    {
        $params = parse_params($params);
        $table = 'log';
        $params['table'] = $table;

        if (is_admin() == false) {
            $params['user_ip'] = user_ip();
        }

        $q = $this->app->database_manager->get($params);

        return $q;
    }

    public function reset()
    {
        $adm = $this->app->user_manager->is_admin();
        if (defined('MW_API_CALL') and $adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = 'log';
        DB::table($table)->truncate();
        $cg = 'log';
        $this->app->cache_manager->delete($cg);

        return array('success' => 'System log is cleaned up.');
    }

    public function delete($params)
    {
        $params = parse_params($params);
        $table = 'log';
        $params['table'] = $table;
        if (is_admin() == false) {
            $params['user_ip'] = user_ip();
        }
        $q = $this->app->database_manager->get($params);
        if (is_array($q)) {
            foreach ($q as $val) {
                $c_id = intval($val['id']);
                $this->app->database_manager->delete_by_id('log', $c_id);
            }
        }
        $this->app->cache_manager->delete('log' . DIRECTORY_SEPARATOR . 'global');

        return true;
    }

    public function save($params)
    {
        $table = 'log';
        $params = parse_params($params);
        $params['user_ip'] = user_ip();
        $params['table'] = $table;

        $save = $this->app->database_manager->save($params);
        $id = $save;
        $this->app->cache_manager->delete('log' . DIRECTORY_SEPARATOR . 'global');

        return $id;
    }

    public function delete_entry($data)
    {
        $id = false;
        if (!isset($data['id'])) {
            $id = intval($data);
        } elseif (isset($data['id'])) {
            $id = intval($data['id']);
        }
        if ($id > 0) {
            $c_id = intval($id);
            $table = 'log';
            $old = date('Y-m-d H:i:s', strtotime('-1 month'));
            mw()->database_manager->table($table)->where('created_at', '<', $old)->delete();
            mw()->database_manager->table($table)->where('id', '=', $c_id)->delete();
            $this->app->cache_manager->delete('log' . DIRECTORY_SEPARATOR . $c_id);

            return $c_id;
        }

        return $id;
    }
}
