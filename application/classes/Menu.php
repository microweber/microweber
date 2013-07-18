<?php
if (!defined("MODULE_DB_MENUS")) {
    define('MODULE_DB_MENUS', MW_TABLE_PREFIX . 'menus');
}
class Menu {

   static  function get_items($params = false)
    {
        $table = MODULE_DB_MENUS;
        $params2 = array();
        if ($params == false) {
            $params = array();
        }
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        $params['table'] = $table;
        $params['item_type'] = 'menu_item';
        return get($params);
    }





}