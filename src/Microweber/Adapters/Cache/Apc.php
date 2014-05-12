<?php
namespace Microweber\Adapters\Cache;
$mw_cache_get_content_memory = array();
$mw_skip_memory = array();
if (!defined('MW_CACHE_CONTENT_PREPEND')) {
    define('MW_CACHE_CONTENT_PREPEND', '<?php exit(); ?>');
}
class Apc
{

    public $ttl = 300; //ttl in seconds
    public $mw_cache_mem = array();
    public $group_prefix = false;
    public $fallback_adapter = false;

    function __construct()
    {

        if ($this->group_prefix == false and isset($_SERVER["SERVER_NAME"])) {
            $this->group_prefix = 'apc-' . ($_SERVER["SERVER_NAME"]);
        }
        $apc_apc_delete = function_exists('apc_delete');
        if ($apc_apc_delete == false) {
            $this->_set_fallback_adapter();
        }
    }

    private function _set_fallback_adapter()
    {
        if (!is_object($this->fallback_adapter)) {
            $this->fallback_adapter = new Files();
        }
        return $this->fallback_adapter;
    }

    public function save($data_to_cache, $cache_id, $cache_group = 'global')
    {
        if (is_object($this->fallback_adapter)) {
            return $this->fallback_adapter->save($data_to_cache, $cache_id, $cache_group);
        }


        $cache_group = $this->_hash_group($cache_group);

        global $mw_cache_get_content_memory;

        $cache_id = $this->_hash_id($cache_group, $cache_id);
        static $apc_apc_delete;

        $mw_cache_get_content_memory[$cache_id] = $data_to_cache;
        $cache = $data_to_cache = serialize($data_to_cache);
        $ttl = $this->ttl;
        try {
            @apc_store($cache_id, $cache, $ttl);
        } catch (Exception $e) {

        }


    }

    private function _hash_group($cache_group)
    {
        $cache_group = $this->group_prefix . '_' . $cache_group;
        $cache_group = str_replace('.', '-', $cache_group);
        $cache_group = str_replace('_', '-', $cache_group);
        $cache_group = str_replace('/', '-', $cache_group);
        $cache_group = str_replace('\\', '-', $cache_group);

        return $cache_group;
    }

    private function _hash_id($cache_group, $cache_id)
    {
        $new_id = $this->_hash_group($cache_group) . crc32($this->group_prefix . $cache_group . $cache_id);
        return $new_id;
    }

    public function clear()
    {
        if (is_object($this->fallback_adapter)) {
            return $this->fallback_adapter->clear();
        }
        return apc_clear_cache('user');


    }

    public function delete($cache_group = 'global')
    {
        $cache_group = $this->_hash_group($cache_group);
        if (is_object($this->fallback_adapter)) {
            return $this->fallback_adapter->delete($cache_group);
        }

        global $mw_cache_deleted_groups;

        if (!is_array($mw_cache_deleted_groups)) {
            $mw_cache_deleted_groups = array();
        }
        if (!in_array($cache_group, $mw_cache_deleted_groups)) {

            $mw_cache_deleted_groups[] = $cache_group;
        } else {
            return;
        }


        $is_cleaning_now = mw_var('is_cleaning_now');

        $apc_no_clear = mw_var('apc_no_clear');

        if ($apc_no_clear == false) {
            $apc_exists = function_exists('apc_clear_cache');
            if ($apc_exists == true) {
                $hits = apc_cache_info('user');
                if (isset($hits["cache_list"]) and is_array($hits["cache_list"])) {

                    foreach ($hits["cache_list"] as $cache_list_value) {
                        if (isset($cache_list_value['info'])) {
                            if (stristr($cache_list_value['info'], $cache_group)) {
                                @apc_delete($cache_list_value['info']);
                            }
                        }
                    }
                }

            }
        } else {
            mw_var('is_cleaning_now', false);

        }

    }

    public function debug()
    {

        return apc_cache_info('user');

    }

    public function get($cache_id, $cache_group = 'global', $time = false)
    {

        if (is_object($this->fallback_adapter)) {
            return $this->fallback_adapter->get($cache_id, $cache_group, $time);
        }
        global $mw_cache_deleted_groups;

        if (!is_array($mw_cache_deleted_groups)) {
            $mw_cache_deleted_groups = array();
        }
        if (in_array($cache_group, $mw_cache_deleted_groups)) {
            return false;
        }
        $cache_group = $this->_hash_group($cache_group);


        $cache_id_apc = $this->_hash_id($cache_group, $cache_id);
        //   $cache_id_apc = $cache_id;
        global $mw_cache_get_content_memory;
        if (isset($mw_cache_get_content_memory[$cache_id_apc])) {
            return ($mw_cache_get_content_memory[$cache_id_apc]);
        }

        $cache = apc_fetch($cache_id_apc);
        // print 'get: '. ($cache_id_apc) . "\n";

        if ($cache) {
            if (isset($cache) and strval($cache) != '') {

                $search = MW_CACHE_CONTENT_PREPEND;

                $replace = '';

                $count = 1;

                // $cache = str_replace($search, $replace, $cache, $count);
                $cache = unserialize($cache);
            }
            $mw_cache_get_content_memory[$cache_id_apc] = $cache;

            return $cache;
        }
        $mw_cache_get_content_memory[$cache_id_apc] = false;
        return false;
    }


}
