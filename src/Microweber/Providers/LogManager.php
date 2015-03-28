<?php
namespace Microweber\Providers;

use DB;
use SystemLogger;

class LogManager
{

    public $app;
    public $table = 'log';

    function __construct($app = null)
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
        $table = $this->table;
        $params['table'] = $table;

        if (is_admin() == false) {
            $params['user_ip'] = MW_USER_IP;
        }

        $q = $this->app->database->get($params);

        return $q;
    }

    public function reset()
    {
        $adm = $this->app->user_manager->is_admin();
        if (defined("MW_API_CALL") and $adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = $this->table;
        DB::table($table)->truncate();
        $cg = guess_cache_group($table);
        $this->app->cache_manager->delete($cg);
        return array('success' => 'System log is cleaned up.');
    }

    public function delete($params)
    {
        $params = parse_params($params);
        $table = $this->table;
        $params['table'] = $table;
        if (is_admin() == false) {
            $params['user_ip'] = MW_USER_IP;
        }
        $q = $this->app->database->get($params);
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

        $table = $this->table;
        $params = parse_params($params);
        $params['user_ip'] = MW_USER_IP;
        $params['table'] = $table;

        $save = $this->app->database->save($params);
        $id = $save;
        $this->app->cache_manager->delete('log' . DIRECTORY_SEPARATOR . 'global');
        return $id;
    }

    public function delete_entry($data)
    {

        $id = false;
        if (!isset($data['id'])) {
            $id = intval($data);
        } else if (isset($data['id'])) {
            $id = intval($data['id']);
        }
        if ($id > 0) {
            $c_id = intval($id);
            $table = $this->table;
            $old = date("Y-m-d H:i:s", strtotime('-1 month'));
             mw()->database->table($table)->where('created_at', '<', $old)->delete();
            mw()->database->table($table)->where('id', '=', $c_id)->delete();
            $this->app->cache_manager->delete('log' . DIRECTORY_SEPARATOR . $c_id);
            return $c_id;

        }
        return $id;
    }
}