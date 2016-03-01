<?php


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
    return mw()->option_manager->get($key, $option_group, $return_full, $orderby, $module);
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
 *
 *
 */
api_expose_admin('save_option');
function save_option($data)
{
    return mw()->option_manager->save($data);
}
