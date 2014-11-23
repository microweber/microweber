<?php


namespace Microweber\Utils;

$mw_static_option_groups = array();
class StaticOption
{

    public function get($key, $option_group = "global")
    {
        $option_group_disabled = mw_var('static_option_disabled_' . $option_group);
        if ($option_group_disabled == true) {
            return false;
        }
        global $mw_static_option_groups;
        $option_group = trim($option_group);
        $option_group = str_replace('..', '', $option_group);

        $fname = $option_group . '.php';

        $dir_name = MW_STORAGE_DIR . 'options' . DS;
        $dir_name_and_file = $dir_name . $fname;
        $key = trim($key);


        if (isset($mw_static_option_groups[$option_group]) and isset($mw_static_option_groups[$option_group][$key])) {
            return ($mw_static_option_groups[$option_group][$key]);
        }


        if (is_file($dir_name_and_file)) {
            $ops_array = file_get_contents($dir_name_and_file);
            if ($ops_array != false) {
                $ops_array = str_replace(MW_CACHE_CONTENT_PREPEND, '', $ops_array);
                if ($ops_array != '') {
                    $ops_array = unserialize($ops_array);
                    if (is_array($ops_array)) {
                        $all_options = $ops_array;
                        $mw_static_option_groups[$option_group] = $all_options;
                        //mw_var('option_disabled_' . $option_group);
                        if (isset($mw_static_option_groups[$option_group]) and isset($mw_static_option_groups[$option_group][$key])) {
                            return ($mw_static_option_groups[$option_group][$key]);
                        } else {
                            $mw_static_option_groups[$option_group][$key] = false;
                        }

                    }
                }
            }
        } else {
            mw_var('static_option_disabled_' . $option_group, true);
        }

    }

    public function save($data)
    {

        if (MW_IS_INSTALLED == true) {
            // only_admin_access();
        }
        $data = parse_params($data);

        if (!isset($data['option_key']) or !isset($data['option_value'])) {
            exit("Error: no option_key or option_value");
        }
        if (!isset($data['option_group'])) {
            $data['option_group'] = 'global';
        }
        $data['option_group'] = trim($data['option_group']);
        $data['option_key'] = trim($data['option_key']);
        $data['option_value'] = (htmlentities($data['option_value']));
        //d($data);

        $data['option_group'] = str_replace('..', '', $data['option_group']);

        $fname = $data['option_group'] . '.php';

        //	$dir_name = MW_STORAGE_DIR . 'options' . DS . $data['option_group'] . DS;

        $dir_name = MW_STORAGE_DIR . 'options' . DS;
        $dir_name_and_file = $dir_name . $fname;
        if (is_dir($dir_name) == false) {
            mkdir_recursive($dir_name);
        }
        $data_to_serialize = array();
        if (is_file($dir_name_and_file)) {
            $ops_array = file_get_contents($dir_name_and_file);
            if ($ops_array != false) {
                $ops_array = str_replace(MW_CACHE_CONTENT_PREPEND, '', $ops_array);
                if ($ops_array != '') {
                    $ops_array = unserialize($ops_array);
                    if (is_array($ops_array)) {
                        $data_to_serialize = $ops_array;
                    }
                }
            }
            //d($ops_array);
        }

        $data_to_serialize[$data['option_key']] = $data['option_value'];
        //	d($data_to_serialize);
        $data_to_serialize = serialize($data_to_serialize);

        $option_save_string = MW_CACHE_CONTENT_PREPEND . $data_to_serialize;

        $cache = file_put_contents($dir_name_and_file, $option_save_string);
        return $cache;
    }
}





