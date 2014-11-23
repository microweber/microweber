<?php




/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://microweber.com/license/
 *
 */

namespace Microweber\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
//use Illuminate\Database\;
use Illuminate\Database\Eloquent\Builder as Eloquent;
use Microweber\Utils\Database;


class Modules extends Eloquent
{
    protected $table = 'modules';

    public $tables = array();

    private $_install_mode = false;

    function __construct($app = null)
    {
        $this->set_table_names();
    }

    public function install()
    {
        $this->_install_mode = true;
        $this->db_init();
        $this->scan();

        $this->_install_mode = false;


    }

    public function db_init()
    {

        $table_name = $this->tables['modules'];
        $table_name2 = $this->tables['elements'];
        $table_name3 = $this->tables['module_templates'];
        $table_name4 = $this->tables['system_licenses'];

        $fields_to_add = array();
        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['expires_on'] = 'dateTime';

        $fields_to_add['created_by'] = 'integer';

        $fields_to_add['edited_by'] = 'integer';

        $fields_to_add['name'] = 'longText';
        $fields_to_add['parent_id'] = 'integer';
        $fields_to_add['module_id'] = 'longText';

        $fields_to_add['module'] = 'longText';
        $fields_to_add['description'] = 'longText';
        $fields_to_add['icon'] = 'longText';
        $fields_to_add['author'] = 'longText';
        $fields_to_add['website'] = 'longText';
        $fields_to_add['help'] = 'longText';
        $fields_to_add['type'] = 'longText';

        $fields_to_add['installed'] = 'integer';
        $fields_to_add['ui'] = 'integer';
        $fields_to_add['position'] = 'integer';
        $fields_to_add['as_element'] = 'integer';
        $fields_to_add['ui_admin'] = 'integer';
        $fields_to_add['ui_admin_iframe'] = 'integer';
        $fields_to_add['is_system'] = 'integer';

        $fields_to_add['version'] = 'string';

        $fields_to_add['notifications'] = 'integer';

        $db = new Database();
        $db->build_table($table_name, $fields_to_add);

        $fields_to_add['layout_type'] = 'string';
        $db->build_table($table_name2, $fields_to_add);


        $fields_to_add = array();
        $fields_to_add['updated_on'] = 'dateTime';

        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['created_by'] = 'integer';
        $fields_to_add['edited_by'] = 'integer';
        $fields_to_add['module_id'] = 'longText';
        $fields_to_add['name'] = 'longText';
        $fields_to_add['module'] = 'longText';
        $db->build_table($table_name3, $fields_to_add);


        $fields_to_add = array();
        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['created_by'] = 'integer';
        $fields_to_add['edited_by'] = 'integer';
        $fields_to_add['rel'] = 'longText';
        $fields_to_add['rel_name'] = 'longText';
        $fields_to_add['local_key'] = 'longText';
        $fields_to_add['local_key_hash'] = 'longText';
        $fields_to_add['registered_name'] = 'longText';
        $fields_to_add['company_name'] = 'longText';
        $fields_to_add['domains'] = 'longText';
        $fields_to_add['status'] = 'longText';
        $fields_to_add['product_id'] = 'integer';
        $fields_to_add['service_id'] = 'integer';
        $fields_to_add['billing_cycle'] = 'longText';
        $fields_to_add['reg_on'] = 'dateTime';
        $fields_to_add['due_on'] = 'dateTime';

        $db->build_table($table_name4, $fields_to_add);


        return true;

    }


    public function save($data_to_save)
    {

        if (mw()->user->is_admin() == false and $this->_install_mode == false) {
            return false;
        }
        if (isset($data_to_save['is_element']) and $data_to_save['is_element'] == true) {

        }

        $table = $this->tables['modules'];
        $save = false;


        if (!empty($data_to_save)) {
            $s = $data_to_save;

            if (!isset($s["parent_id"])) {
                $s["parent_id"] = 0;
            }
            if (!isset($s["id"]) and isset($s["module"])) {
                $s["module"] = $data_to_save["module"];
                if (!isset($s["module_id"])) {
                    $save = $this->get_modules('ui=any&limit=1&module=' . $s["module"]);

                    if ($save != false and isset($save[0]) and is_array($save[0])) {
                        $s["id"] = intval($save[0]["id"]);
                        $s["position"] = intval($save[0]["position"]);
                        $save = mw()->database->save($table, $s);
                        $mname_clen = str_replace('\\', '/', $s["module"]);
                        $mname_clen = mw()->database->escape_string($mname_clen);
                        if ($s["id"] > 0) {
                            $delid = $s["id"];
                            $del = "DELETE FROM {$table} WHERE module='{$mname_clen}' AND id!={$delid} ";
                            mw()->database->q($del);
                        }
                    } else {

                        $save = mw()->database->save($table, $s);
                    }
                } else {

                }

            } else {

                $save = mw()->database->save($table, $s);
            }
        }

        return $save;
    }


    public function get_modules($params){

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        $params['table'] = $this->table;
        $params['group_by'] = 'module';
        $params['order_by'] = 'position asc';
        $params['cache_group'] = 'modules/global';
        if (isset($params['id'])) {
            $params['limit'] = 1;
        } else {
            $params['limit'] = 1000;
        }
        if (isset($params['module'])) {
            $params['module'] = str_replace('/admin', '', $params['module']);
        }
        if (isset($params['keyword'])) {
            $params['search_in_fields'] = array('name', 'module', 'description', 'author', 'website', 'version', 'help');
        }

               if (isset($params['ui']) and $params['ui'] == 'any') {
            unset($params['ui']);
        }

        return mw()->database->get($params);
 
    }


    public function set_table_names($tables = false)
    {


        if (!is_array($tables)) {
            $tables = array();
        }
        if (!isset($tables['modules'])) {
            $tables['modules'] = 'modules';
        }
        if (!isset($tables['elements'])) {
            $tables['elements'] = 'elements';
        }
        if (!isset($tables['module_templates'])) {
            $tables['module_templates'] = 'module_templates';
        }
        if (!isset($tables['system_licenses'])) {
            $tables['system_licenses'] = 'system_licenses';
        }
        $this->tables['modules'] = $tables['modules'];
        $this->tables['elements'] = $tables['elements'];
        $this->tables['module_templates'] = $tables['module_templates'];
        $this->tables['system_licenses'] = $tables['system_licenses'];

    }

    public function scan($options = false)
    {

        $params = $options;

        if (isset($options['glob'])) {
            $glob_patern = $options['glob'];
        } else {
            $glob_patern = '*config.php';
        }


        if (defined("INI_SYSTEM_CHECK_DISABLED") == false) {
            define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));
        }


        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {
            ini_set("memory_limit", "160M");
            ini_set("set_time_limit", 0);
        }
        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
            set_time_limit(600);
        }
        if (isset($options['path'])) {
            $dir_name = $options['glob'];
        } else {
            $dir_name = modules_path();
        }
        $skip_save = false;
        if (isset($options['skip_save'])) {
            $skip_save = $options['skip_save'];
        }
        if (isset($options['is_elements']) and $options['is_elements'] != false) {
            $list_as_element = true;

        } else {
            $list_as_element = false;
        }

        $modules_remove_old = false;
        $dir = rglob($glob_patern, 0, $dir_name);

        if (!empty($dir)) {
            $configs = array();

            foreach ($dir as $key => $value) {
                $skip_module = false;
                if (isset($options['skip_admin']) and $options['skip_admin'] == true) {
                    if (strstr($value, 'admin')) {
                        $skip_module = true;
                    }
                }

                if ($skip_module == false) {

                    $config = array();
                    $value = normalize_path($value, false);

                    $value_fn = $mod_name = str_replace('_config.php', '', $value);
                    $value_fn = $mod_name = str_replace('config.php', '', $value_fn);
                    $value_fn = $mod_name = str_replace('index.php', '', $value_fn);


                    $value_fn = $mod_name_dir = str_replace($dir_name, '', $value_fn);
                    $value_fn = $mod_name_dir = str_replace($dir_name, '', $value_fn);


                    $def_icon = modules_path() . 'default.png';

                    ob_start();


                    $is_mw_ignore = dirname($value) . DS . '.mwignore';
                    if (!is_file($is_mw_ignore)) {
                        include($value);
                    }

                    $content = ob_get_contents();
                    ob_end_clean();
                    $value_fn = str_replace(modules_path(), '', $value_fn);


                    $replace_root = modules_path();

                    $value_fn = str_replace($replace_root, '', $value_fn);


                    $value_fn = rtrim($value_fn, '\\');
                    $value_fn = rtrim($value_fn, '/');
                    $value_fn = str_replace('\\', '/', $value_fn);
                    $value_fn = str_replace(modules_path(), '', $value_fn);


                    $config['module'] = $value_fn;
                    $config['module'] = rtrim($config['module'], '\\');
                    $config['module'] = rtrim($config['module'], '/');

                    $config['module_base'] = str_replace('admin/', '', $value_fn);
                    if (is_dir($mod_name)) {
                        $bname = basename($mod_name);
                        $t1 = modules_path() . $config['module'] . DS . $bname;

                        $try_icon = $t1 . '.png';

                    } else {
                        $try_icon = $mod_name . '.png';
                    }

                    $try_icon = normalize_path($try_icon, false);

                    if (is_file($try_icon)) {

                        $config['icon'] = ($try_icon);
                    } else {
                        $config['icon'] = ($def_icon);
                    }

                    $configs[] = $config;

                    if (isset($config['name']) and $skip_save !== true and $skip_module == false) {
                        if (trim($config['module']) != '') {

                            if ($list_as_element == true) {

                                $this->app->layouts->save($config);
                            } else {

                                $this->save($config);
                                $config['installed'] = 'auto';


                            }
                        }
                    }

                }
            }

            if ($skip_save == true) {
                return $configs;
            }

            $cfg_ordered = array();
            $cfg_ordered2 = array();
            $cfg = $configs;
            foreach ($cfg as $k => $item) {
                if (isset($item['position'])) {
                    $cfg_ordered2[$item['position']][] = $item;
                    unset($cfg[$k]);
                }
            }
            ksort($cfg_ordered2);
            foreach ($cfg_ordered2 as $k => $item) {
                foreach ($item as $ite) {
                    $cfg_ordered[] = $ite;
                }
            }

            if ($modules_remove_old == true) {

                $table = MW_DB_TABLE_OPTIONS;
                $uninstall_lock = $this->get('ui=any');
                if (is_array($uninstall_lock) and !empty($uninstall_lock)) {
                    foreach ($uninstall_lock as $value) {
                        $ism = $this->exists($value['module']);
                        if ($ism == false) {
                            $this->delete_module($value['id']);
                            $mn = $value['module'];
                            $q = "DELETE FROM $table WHERE option_group='{$mn}'  ";

                            mw()->database->q($q);
                        }

                    }
                }
            }


            $c2 = array_merge($cfg_ordered, $cfg);


            return $c2;
        }
    }


}