<?php
function get_module_options($optionGroup, $module = false)
{
    return mw()->option_manager->getModuleOptions($optionGroup, $module = false);
}

function get_module_option($optionKey, $optionGroup = false, $returnFull = false, $module = false)
{
    return mw()->option_manager->getModuleOption($optionKey, $optionGroup, $returnFull, $module);
}

function save_module_option($optionKey, $value = false, $group = false, $module = false)
{
    $option = array();
    $option['option_value'] = $value;
    $option['option_key'] = $optionKey;
    $option['option_group'] = $group;
    $option['module'] = $module;

    return save_option($option);
}

function module_option($optionGroup = false, $optionKey = false, $default = false)
{
    $get = get_module_option($optionKey, $optionGroup);
    if ($get === null) {
        return $default;
    }
    return $get;
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
 * Or Example:
 * save_option($key, $value, $group);
 *
 */
function save_option($optionKey, $value = false, $group = false, $lang = false)
{
    //  $lang = false;
    if (!$lang) {
        if (isset($_POST['lang'])) {
            $lang = $_POST['lang'];
        }
    }
    if ($optionKey &&
        (is_string($optionKey) || is_numeric($optionKey))
        && $group) {

        $option = array();
        $option['option_value'] = $value;
        $option['option_key'] = $optionKey;
        $option['option_group'] = $group;
        if ($lang) {
            if ($lang != app()->lang_helper->default_lang()) {
                $option['lang'] = $lang;
            }
            //  $option['lang'] = $lang;
        }

        return app()->option_manager->save($option);
    } else {
        return app()->option_manager->save($optionKey);
    }
}

function delete_option($key, $group = false, $module_id = false)
{

    return app()->option_manager->delete($key, $group, $module_id);
}
