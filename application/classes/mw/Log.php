<?php
namespace mw;

if (!defined("MW_DB_TABLE_LOG")) {
    define('MW_DB_TABLE_LOG', MW_TABLE_PREFIX . 'log');
}


class Log
{

    static function get_entry_by_id($id)
    {

        $params = array();
        $params['id'] = intval($id);
        $params['one'] = true;

        $get = get_log($params);
        return $get;

    }

    static function get($params)
    {
        $params = parse_params($params);
        $table = MW_DB_TABLE_LOG;
        $params['table'] = $table;

        if (is_admin() == false) {
            $params['user_ip'] = USER_IP;
        }

        $q = get($params);

        return $q;
    }

    static function reset()
    {
        $adm = is_admin();
        if ($adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = MW_DB_TABLE_LOG;

        $q = "DELETE FROM $table ";

        $cg = guess_cache_group($table);

        cache_clean_group($cg);
        $q = db_q($q);
        return array('success' => 'System log is cleaned up.');

        //return $data;
    }

    static function delete($params)
    {
        $params = parse_params($params);
        $table = MW_DB_TABLE_LOG;
        $params['table'] = $table;

        if (is_admin() == false) {
            $params['user_ip'] = USER_IP;
        }

        $q = get($params);
        if (isarr($q)) {
            foreach ($q as $val) {
                $c_id = intval($val['id']);
                db_delete_by_id('log', $c_id);
            }

        }
        cache_clean_group('log' . DIRECTORY_SEPARATOR . 'global');
        return true;
    }

    static function save($params)
    {
        $params = parse_params($params);

        $params['user_ip'] = USER_IP;
        $data_to_save = $params;
        $table = MW_DB_TABLE_LOG;
        mw_var('FORCE_SAVE', $table);
        $save = save_data($table, $params);
        $id = $save;
        cache_clean_group('log' . DIRECTORY_SEPARATOR . 'global');
        return $id;
    }

    static function delete_entry($data)
    {
        $adm = is_admin();
        if ($adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            db_delete_by_id('log', $c_id);
            $table = MW_DB_TABLE_LOG;
            $old = date("Y-m-d H:i:s", strtotime('-1 month'));
            $q = "DELETE FROM $table WHERE created_on < '{$old}'";

            $q = db_q($q);

            return $c_id;

        }
        return $data;
    }
}