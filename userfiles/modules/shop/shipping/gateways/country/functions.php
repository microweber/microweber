<?
if (!defined("MODULE_DB_SHOP_SHIPPING_TO_COUNTRY")) {
	define('MODULE_DB_SHOP_SHIPPING_TO_COUNTRY', MW_TABLE_PREFIX . 'cart_shipping');
}

action_hook('mw_db_init', 'mw_shop_module_init_shipping_to_country_db');

function mw_shop_module_init_shipping_to_country_db() {
	$function_cache_id = false;

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$cache_content = cache_get_content($function_cache_id, 'db');

	if (($cache_content) != false) {

		return $cache_content;
	}

	$table_name = MODULE_DB_SHOP_SHIPPING_TO_COUNTRY;

	$fields_to_add = array();
	$fields_to_add[] = array('updated_on', 'datetime default NULL');
	$fields_to_add[] = array('created_on', 'datetime default NULL');
	$fields_to_add[] = array('is_active', "char(1) default 'y'");

	$fields_to_add[] = array('shiping_cost', 'float default NULL');
	$fields_to_add[] = array('shiping_cost_max', 'float default NULL');
	$fields_to_add[] = array('shiping_cost_above', 'float default NULL');

	$fields_to_add[] = array('shiping_country', 'TEXT default NULL');
		$fields_to_add[] = array('position', 'int(11) default NULL');


	set_db_table($table_name, $fields_to_add);

	//db_add_table_index('shiping_country', $table_name, array('shiping_country'));

	cache_save(true, $function_cache_id, $cache_group = 'db');
	return true;

	//print '<li'.$cls.'><a href="'.admin_url().'view:settings">newsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl eter</a></li>';
}
