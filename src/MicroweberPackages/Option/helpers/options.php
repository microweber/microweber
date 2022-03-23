<?php
function get_module_options($optionGroup) {
    return mw()->option_manager->getModuleOptions($optionGroup);
}

function get_module_option($optionKey, $optionGroup = false, $returnFull = false) {
    return mw()->option_manager->getModuleOption($optionKey, $optionGroup, $returnFull);
}

/**
 * Getting options from the database.
 *
 * @param $key array|string - if array it will replace the db params
 * @param $option_group string - your option group
 * @param $return_full bool - if true it will return the whole db row as array rather then just the value
 * @param $module string - if set it will store option for module
 * Example usage:
 * get_option('my_key', 'my_group');
 */
function get_option($key, $option_group = false, $return_full = false, $orderby = false, $module = false)
{
    return app()->option_manager->get($key, $option_group, $return_full, $orderby, $module);
}

/*
 *
 * You can use this function to store options in the database.
 *
 * @param $data array|string
 * Example usage:
 *
 * $option = array();
 * $option['option_value'] = 'my value';
 * $option['option_key'] = 'my_option';
 * $option['option_group'] = 'my_option_group';
 * save_option($option);
 *
 * Or Eexample:
 * save_option($key, $value, $group);
 *
 */
function save_option($dataOrKey, $value = false, $group = false)
{
    $lang = false;
    if (isset($_POST['lang'])) {
        $lang = $_POST['lang'];
    }

    if ($dataOrKey && $value && $group) {

        $option = array();
        $option['option_value'] = $value;
        $option['option_key'] = $dataOrKey;
        $option['option_group'] = $group;
        if($lang){
            $option['lang'] = $lang;
        }
        return app()->option_manager->save($option);
    } else {
        return app()->option_manager->save($dataOrKey);
    }
}

function delete_option($key, $group = false, $module_id = false) {

    return app()->option_manager->delete($key, $group, $module_id);
}
