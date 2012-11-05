<?

if (!defined("MODULE_DB_TABLE_SHOP")) {
    define('MODULE_DB_TABLE_SHOP', TABLE_PREFIX . 'shop');
}
 
api_expose('update_cart');

function update_cart($data) {
    d($data);
    exit;
}