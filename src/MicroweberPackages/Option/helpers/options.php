<?php
function get_module_options($optionGroup, $module = false)
{
    return app()->option_manager->getModuleOptions($optionGroup, $module = false);
}

function get_module_option($optionKey, $optionGroup = false, $returnFull = false, $module = false)
{
    return app()->option_manager->getModuleOption($optionKey, $optionGroup, $returnFull, $module);
}

function save_module_option($optionKey, $value = false, $group = false, $module = false, $lang = null)
{
    $option = array();
    if(is_array($optionKey)) {
        $option = $optionKey;
    } else {
        $option['option_value'] = $value;
        $option['option_key'] = $optionKey;
        $option['option_group'] = $group;
        $option['module'] = $module;
        if ($lang) {
            $option['lang'] = $lang;
        }
    }


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

/**
 * Getting multiple options from the database by params.
 *
 * @param $params array - query params, e.g. ['option_group' => 'my_group']
 * @return array
 * Example usage:
 * get_options(['option_group' => 'my_group']);
 */
function get_options($params = [])
{
    $options = app()->option_repository->getByParams($params);
    if (!$options) {
        return [];
    }
    return $options;
}

/**
 * Whether a boolean-style option's value represents an enabled ("yes") state.
 *
 * Options that back a checkbox/toggle have two on-disk conventions: legacy data
 * stores 'y', while Filament Toggle/Checkbox fields persist '1' (a dehydrated
 * boolean). Both mean "on"; 'n', '0', '', null mean "off". Prefer this over a
 * bare `get_option(...) == 'y'`, which silently misses the '1' the Filament
 * admin actually writes. Mirrors AdminSettingsPage's own load rule (== 'y' || == 1).
 *
 * @param string      $key
 * @param string|bool $group
 * @return bool
 */
function option_is_yes($key, $group = false): bool
{
    return in_array((string) get_option($key, $group), ['y', '1'], true);
}
