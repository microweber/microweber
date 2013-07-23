<?php

/**
 *
 * Shop module api
 *
 * @package		modules
 * @subpackage		shop
 * @since		Version 0.1
 */

// ------------------------------------------------------------------------

if (!defined("MODULE_DB_SHOP")) {
	define('MODULE_DB_SHOP', MW_TABLE_PREFIX . 'cart');
}

if (!defined("MODULE_DB_SHOP_ORDERS")) {
	define('MODULE_DB_SHOP_ORDERS', MW_TABLE_PREFIX . 'cart_orders');
}

if (!defined("MODULE_DB_SHOP_SHIPPING_TO_COUNTRY")) {
	define('MODULE_DB_SHOP_SHIPPING_TO_COUNTRY', MW_TABLE_PREFIX . 'cart_shipping');
}

action_hook('mw_db_init', 'mw_shop_module_init_db');
//action_hook('mw_db_init_default', 'mw_shop_module_init_db');

function mw_shop_module_init_db() {
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

	$table_name = MODULE_DB_SHOP;

	$fields_to_add = array();
	$fields_to_add[] = array('title', 'TEXT default NULL');
	$fields_to_add[] = array('is_active', "char(1) default 'y'");
	$fields_to_add[] = array('rel_id', 'int(11) default NULL');
	$fields_to_add[] = array('rel', 'varchar(350)  default NULL ');
	$fields_to_add[] = array('updated_on', 'datetime default NULL');
	$fields_to_add[] = array('created_on', 'datetime default NULL');
	$fields_to_add[] = array('price', 'float default NULL');
	$fields_to_add[] = array('currency', 'varchar(33)  default NULL ');
	$fields_to_add[] = array('session_id', 'varchar(255)  default NULL ');
	$fields_to_add[] = array('qty', 'int(11) default NULL');
	$fields_to_add[] = array('other_info', 'TEXT default NULL');
	$fields_to_add[] = array('order_completed', "char(1) default 'n'");
	$fields_to_add[] = array('order_id', 'varchar(255)  default NULL ');
	$fields_to_add[] = array('skip_promo_code', "char(1) default 'n'");
	$fields_to_add[] = array('created_by', 'int(11) default NULL');
	$fields_to_add[] = array('custom_fields_data', 'TEXT default NULL');

	set_db_table($table_name, $fields_to_add);

	// db_add_table_index ( 'title', $table_name, array ('title' ), "FULLTEXT" );
	db_add_table_index('rel', $table_name, array('rel'));
	db_add_table_index('rel_id', $table_name, array('rel_id'));

	db_add_table_index('session_id', $table_name, array('session_id'));

	$table_name = MODULE_DB_SHOP_ORDERS;

	$fields_to_add = array();

	$fields_to_add[] = array('updated_on', 'datetime default NULL');
	$fields_to_add[] = array('created_on', 'datetime default NULL');
	$fields_to_add[] = array('country', 'varchar(255)  default NULL ');
	$fields_to_add[] = array('promo_code', 'TEXT default NULL');
	$fields_to_add[] = array('amount', 'float default NULL');
	$fields_to_add[] = array('transaction_id', 'TEXT default NULL');
	$fields_to_add[] = array('shipping_service', 'TEXT default NULL');
	$fields_to_add[] = array('shipping', 'float default NULL');
	$fields_to_add[] = array('currency', 'varchar(33)  default NULL ');

	$fields_to_add[] = array('currency_code', 'varchar(33)  default NULL ');

	$fields_to_add[] = array('first_name', 'TEXT default NULL');

	$fields_to_add[] = array('last_name', 'TEXT default NULL');

	$fields_to_add[] = array('email', 'TEXT default NULL');

	$fields_to_add[] = array('city', 'TEXT default NULL');

	$fields_to_add[] = array('state', 'TEXT default NULL');

	$fields_to_add[] = array('zip', 'TEXT default NULL');
	$fields_to_add[] = array('address', 'TEXT default NULL');
	$fields_to_add[] = array('address2', 'TEXT default NULL');
	$fields_to_add[] = array('phone', 'TEXT default NULL');

	$fields_to_add[] = array('created_by', 'int(11) default NULL');
	$fields_to_add[] = array('edited_by', 'int(11) default NULL');
	$fields_to_add[] = array('session_id', 'varchar(255)  default NULL ');
	$fields_to_add[] = array('order_completed', "char(1) default 'n'");
	$fields_to_add[] = array('is_paid', "char(1) default 'n'");
	$fields_to_add[] = array('url', 'TEXT default NULL');
	$fields_to_add[] = array('user_ip', 'varchar(255)  default NULL ');
	$fields_to_add[] = array('items_count', 'int(11) default NULL');
	$fields_to_add[] = array('payment_gw', 'TEXT  default NULL ');
	$fields_to_add[] = array('payment_verify_token', 'TEXT  default NULL ');
	$fields_to_add[] = array('payment_amount', 'float default NULL');
	$fields_to_add[] = array('payment_currency', 'varchar(255)  default NULL ');

	$fields_to_add[] = array('payment_status', 'varchar(255)  default NULL ');

	$fields_to_add[] = array('payment_email', 'TEXT default NULL');
	$fields_to_add[] = array('payment_receiver_email', 'TEXT default NULL');

	$fields_to_add[] = array('payment_name', 'TEXT default NULL');

	$fields_to_add[] = array('payment_country', 'TEXT default NULL');

	$fields_to_add[] = array('payment_address', 'TEXT default NULL');

	$fields_to_add[] = array('payment_city', 'TEXT default NULL');
	$fields_to_add[] = array('payment_state', 'TEXT default NULL');
	$fields_to_add[] = array('payment_zip', 'TEXT default NULL');

	$fields_to_add[] = array('payer_id', 'TEXT default NULL');

	$fields_to_add[] = array('payer_status', 'TEXT default NULL');
	$fields_to_add[] = array('payment_type', 'TEXT default NULL');
	$fields_to_add[] = array('order_status', 'varchar(255) default "pending" ');

	$fields_to_add[] = array('payment_shipping', 'float default NULL');

	$fields_to_add[] = array('is_active', "char(1) default 'y'");
	$fields_to_add[] = array('rel_id', 'int(11) default NULL');
	$fields_to_add[] = array('rel', 'varchar(350)  default NULL ');
	$fields_to_add[] = array('price', 'float default NULL');
	$fields_to_add[] = array('other_info', 'TEXT default NULL');
	$fields_to_add[] = array('order_id', 'varchar(255)  default NULL ');
	$fields_to_add[] = array('skip_promo_code', "char(1) default 'n'");

	set_db_table($table_name, $fields_to_add);

	// db_add_table_index ( 'title', $table_name, array ('title' ), "FULLTEXT" );
	db_add_table_index('rel', $table_name, array('rel'));
	db_add_table_index('rel_id', $table_name, array('rel_id'));

	db_add_table_index('session_id', $table_name, array('session_id'));



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










	cache_save(true, $function_cache_id, $cache_group = 'db');

	return true;

	//print '<li'.$cls.'><a href="'.admin_url().'view:settings">newsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl eter</a></li>';
}

action_hook('mw_db_init_options', 'create_mw_shop_default_options');
function create_mw_shop_default_options() {

	$function_cache_id = __FUNCTION__;

	$cache_content = cache_get_content($function_cache_id, $cache_group = 'db');
	if (($cache_content) == '--true--') {
		return true;
	}

	$table = MW_DB_TABLE_OPTIONS;

	mw_var('FORCE_SAVE', $table);
	$datas = array();

	$data = array();

	$data['name'] = 'Currency';
	$data['help'] = 'The website currency';
	$data['option_group'] = 'payments';
	$data['option_key'] = 'currency';
	$data['option_value'] = 'USD';
	$data['field_type'] = 'currency';

	$data['position'] = '1';
	$datas[] = $data;

	$data = array();

	$data['name'] = 'Payment currency';
	$data['help'] = 'Payment process in currency';
	$data['option_group'] = 'payments';
	$data['option_key'] = 'payment_currency';
	$data['option_value'] = 'USD';
	$data['field_type'] = 'currency';

	$data['position'] = '2';
	$datas[] = $data;

	$data['name'] = 'Payment currency rate';
	$data['help'] = 'Payment currency convert rate to site currency';
	$data['option_group'] = 'payments';
	$data['option_key'] = 'payment_currency_rate';
	$data['option_value'] = '1.2';
	$data['field_type'] = 'currency';

	$data['position'] = '3';
	$datas[] = $data;
	//

	$changes = false;
	foreach ($datas as $val) {

		$ch = set_default_option($val);
		if ($ch == true) {

			$changes = true;
		}
	}
	if ($changes == true) {

		cache_clean_group('options/global');
	}
	cache_save('--true--', $function_cache_id, $cache_group = 'db');

	return true;
}

action_hook('mw_admin_header_menu_start', 'mw_print_admin_menu_shop_btn');

function mw_print_admin_menu_shop_btn() {
	$active = url_param('view');
	$cls = '';
	if ($active == 'shop') {
		$cls = ' class="active" '; 
	}
	print '<li' . $cls . '><a href="' . admin_url() . 'view:shop">'._e('Online Shop',true).'</a></li>';
}

action_hook('mw_admin_dashboard_quick_link', 'mw_print_admin_dashboard_orders_btn');

function mw_print_admin_dashboard_orders_btn() {
	$active = url_param('view');
	$cls = '';
	if ($active == 'shop') {
		$cls = ' class="active" ';
	}
	$notif_html = '';
	$notif_count = \mw\Notifications::get('module=shop&rel=cart_orders&is_read=n&count=1');
	if ($notif_count > 0) {
		$notif_html = '<sup class="mw-notif-bubble">' . $notif_count . '</sup>';
	}

	$ord_pending = get_orders('count=1&order_status=[null]&is_completed=y');
	$neword = '';
	if ($ord_pending > 0) {
		$neword = '<span class="icounter">' . $ord_pending . ' new</span>';
	}
	print '<li' . $cls . '><a href="' . admin_url() . 'view:shop/action:orders"><span class="ico iorder">' . $notif_html . '</span>' . $neword . '<span>View Orders</span></a></li>';
}

api_expose('update_order');
/**
 * update_order
 *
 * updates order by parameters
 *
 * @package		modules
 * @subpackage	shop
 * @subpackage	shop\orders
 * @category	shop module api
 */
function update_order($params = false) {

	$params2 = array();
	if ($params == false) {
		$params = array();
	}
	if (is_string($params)) {
		$params = parse_str($params, $params2);
		$params = $params2;
	}
	if (is_admin() == false) {

		error("You must be admin");
	}

	$table = MODULE_DB_SHOP_ORDERS;
	$params['table'] = $table;

	//  d($params);
	return save_data($table, $params);

}

api_expose('delete_client');

function delete_client($data) {

	$adm = is_admin();
	if ($adm == false) {
		error('Error: not logged in as admin.'.__FILE__.__LINE__);
	}
	$table = MODULE_DB_SHOP_ORDERS;

	if (isset($data['email'])) {
		$c_id = db_escape_string($data['email']);
		$q = "DELETE from $table where email='$c_id' ";
		$res = db_q($q);
		//db_delete_by_id($table, $c_id, 'email');
		cache_clean_group('cart_orders/global');
		return $res;
		//d($c_id);
	}
}

api_expose('delete_order');

function delete_order($data) {

	$adm = is_admin();
	if ($adm == false) {
		error('Error: not logged in as admin.'.__FILE__.__LINE__);
	}

	$table = MODULE_DB_SHOP_ORDERS;

	if (isset($data['id'])) {
		$c_id = intval($data['id']);
		db_delete_by_id($table, $c_id);
		$table2 = MODULE_DB_SHOP;
		$q = "DELETE from $table2 where order_id=$c_id ";
		$res = db_q($q);
		return $c_id;
		//d($c_id);
	}

}

function get_orders($params = false) {

	$params2 = array();
	if ($params == false) {
		$params = array();
	}
	if (is_string($params)) {
		$params = parse_str($params, $params2);
		$params = $params2;
	}
	if (is_admin() == false) {
		$params['session_id'] = session_id();
		if (!isset($params['payment_verify_token'])) {
			//error("get_orders? You must be admin");
		}
	}

	$table = MODULE_DB_SHOP_ORDERS;
	$params['table'] = $table;

	//  d($params);
	return get($params);

}

function cart_sum($return_amount = true) {
	if (!session_id() and !headers_sent()) {
		session_start();
	}

	$sid = session_id();
	$diferent_items = 0;
	$amount = floatval(0.00);
	$cart = MODULE_DB_SHOP;
	$sumq = " SELECT  price, qty FROM $cart where order_completed='n'  and session_id='{$sid}'  ";
	$sumq = db_query($sumq);
	if (isarr($sumq)) {
		foreach ($sumq as $value) {
			$diferent_items = $diferent_items + $value['qty'];
			$amount = $amount + (intval($value['qty']) * floatval($value['price']));
		}
	}
	if ($return_amount == false) {
		return $diferent_items;
	}
	return $amount;
}

api_expose('checkout_ipn1');

function checkout_ipn1() {

	$cache_gr = 'ipn';
	$cache_id = '1';
	$c = cache_get_content($cache_id, $cache_gr);
	//d($c);
	$c = checkout_ipn($c);
	//d($c);
}

api_expose('checkout_ipn');

function checkout_ipn($data) {
	if (!session_id() and !headers_sent()) {
		//	session_start();
	}
	//$sid = session_id();

	// if (isset($_GET) and !empty($_GET) and !empty($_POST)) {
	// $data = $_REQUEST;
	// } else

	//if (isset($_POST) and !empty($_POST)) {
	//$data = $_POST;
	//}
	if (isset($data['payment_verify_token'])) {
		$payment_verify_token = ($data['payment_verify_token']);
	}

	if (!isset($data['payment_gw'])) {
		return array('error' => 'You must provide a payment gateway parameter!');
	}

	$data['payment_gw'] = str_replace('..', '', $data['payment_gw']);

	$hostname = get_domain_from_str($_SERVER['REMOTE_ADDR']);
	$cache_gr = 'ipn';
	$cache_id = $hostname . md5(serialize($data));

	cache_save($data, $cache_id, $cache_gr);

	//$data = cache_get_content($cache_id, $cache_gr);

	//d($payment_verify_token);
	$ord_data = get_orders('no_cache=1&limit=1&tansaction_id=[is]NULL&payment_verify_token=' . $payment_verify_token . '');
	// d($ord_data);.
	$payment_verify_token = db_escape_string($payment_verify_token);
	$table = MODULE_DB_SHOP_ORDERS;
	$q = " SELECT  * FROM $table where payment_verify_token='{$payment_verify_token}'  and transaction_id is NULL  limit 1";

	$ord_data = db_query($q);

	if (!isset($ord_data[0]) or !isarr($ord_data[0])) {
		return array('error' => 'Order is completed or expired.');
	} else {

		$ord_data = $ord_data[0];
		$ord = $ord_data['id'];
	}

	$cart_table = MODULE_DB_SHOP;
	$table_orders = MODULE_DB_SHOP_ORDERS;

	//$shop_dir = module_dir('shop');
	//$shop_dir = $shop_dir . DS . 'payments' . DS . 'gateways' . DS;
	$gw_process = MODULES_DIR . $data['payment_gw'] . '_checkout_ipn.php';
	$update_order = array();
	//$update_order['id'] = $ord;
	if (is_file($gw_process)) {
		include $gw_process;

	} else {
		return array('error' => 'The payment gateway is not found!');

	}
	if (!empty($update_order) and isset($update_order['order_completed']) and trim($update_order['order_completed']) == 'y') {
		$update_order['id'] = $ord;

		$update_order['payment_gw'] = $data['payment_gw'];
		mw_var('FORCE_SAVE', $table_orders);
		mw_var('FORCE_ANON_UPDATE', $table_orders);
		//$update_order['debug'] = 1;
		//d($update_order);
		//d($data);
		$ord = save_data($table_orders, $update_order);
		checkout_confirm_email_send($ord);
		if ($ord > 0) {

			$q = " UPDATE $cart_table set
			order_completed='y', order_id='{$ord}'
			where order_completed='n'   ";
			//d($q);
			db_q($q);

			$q = " UPDATE $table_orders set
			order_completed='y'
			where order_completed='n' and
			id='{$ord}'  ";
			//	 d($q);
			db_q($q);
			cache_clean_group('cart/global');
			cache_clean_group('cart_orders/global');
			return true;
		}
	}
	//
	return false;
}

api_expose('checkout');

function checkout($data) {
	if (!session_id() and !headers_sent()) {
		session_start();
	}
	$sid = session_id();
	$cart = array();
	$cart_table = MODULE_DB_SHOP;
	$table_orders = MODULE_DB_SHOP_ORDERS;
	$cart['session_id'] = $sid;
	$cart['order_completed'] = 'n';
	$cart['limit'] = 1;
	$mw_process_payment = true;
	if (isset($_GET['mw_payment_success'])) {
		$mw_process_payment = false;
	}
	mw_var("FORCE_SAVE", $table_orders);
	if (isset($_REQUEST['mw_payment_success']) and intval($_REQUEST['mw_payment_success']) == 1 and isset($_SESSION['order_id'])) {

		$_SESSION['mw_payment_success'] = true;
		$ord = $_SESSION['order_id'];
		if ($ord > 0) {
			$q = " UPDATE $cart_table set
			order_completed='y', order_id='{$ord}'
			where order_completed='n'   and session_id='{$sid}'  ";
			//d($q);
			db_q($q);
			checkout_confirm_email_send($ord);
			$q = " UPDATE $table_orders set
			order_completed='y'
			where order_completed='n' and
			id='{$ord}' and
			session_id='{$sid}'  ";
			//d($q);
			db_q($q);

			checkout_confirm_email_send($ord);

		}

		cache_clean_group('cart/global');
		cache_clean_group('cart_orders/global');
		if (isset($_GET['return_to'])) {
			$return_to = urldecode($_GET['return_to']);
			safe_redirect($return_to);
		}
	}
	$checkout_errors = array();
	$check_cart = get_cart($cart);
	if (!isarr($check_cart)) {

		if (isAjax()) {
			//json_error('Your cart is empty');

		} else {//	error('Your cart is empty');

		}
		$checkout_errors['cart_empty'] = 'Your cart is empty';
	} else {

		if (!isset($data['payment_gw']) and $mw_process_payment == true) {

			$data['payment_gw'] = 'none';
			//error('No payment method is specified');
			//
		} else {
			if ($mw_process_payment == true) {
				$gw_check = payment_options('payment_gw_' . $data['payment_gw']);
				if (isarr($gw_check[0])) {
					$gateway = $gw_check[0];
				} else {
					//error('No such payment gateway is activated');
					$checkout_errors['payment_gw'] = 'No such payment gateway is activated';
				}

			}
		}

		$shiping_country = false;
		$shiping_cost_max = false;
		$shiping_cost = false;
		$shiping_cost_above = false;
		if (isset($_SESSION['shiping_country'])) {
			$shiping_country = $_SESSION['shiping_country'];
		}
		if (isset($_SESSION['shiping_cost_max'])) {
			$shiping_cost_max = $_SESSION['shiping_cost_max'];
		}
		if (isset($_SESSION['shiping_cost'])) {
			$shiping_cost = $_SESSION['shiping_cost'];
		}
		if (isset($_SESSION['shiping_cost_above'])) {
			$shiping_cost_above = $_SESSION['shiping_cost_above'];
		}

		//post any of those on the form
		$flds_from_data = array('first_name', 'last_name', 'email', 'country', 'city', 'state', 'zip', 'address', 'address2', 'payment_email', 'payment_name', 'payment_country', 'payment_address', 'payment_city', 'payment_state', 'payment_zip', 'phone', 'promo_code', 'payment_gw');

		if (!isset($data['email']) or $data['email'] == '') {
			$checkout_errors['email'] = 'Email is required';
		}
		if (!isset($data['first_name']) or $data['first_name'] == '') {
			$checkout_errors['first_name'] = 'First name is required';
		}

		if (!isset($data['last_name']) or $data['last_name'] == '') {
			$checkout_errors['last_name'] = 'Last name is required';
		}

		$posted_fields = array();
		$place_order = array();
		//$place_order['order_id'] = "ORD-" . date("YmdHis") . '-' . $cart['session_id'];

		$return_url_after = '';
		if (isAjax()) {
			$place_order['url'] = curent_url(true);
			$return_url_after = '&return_to=' . urlencode($_SERVER['HTTP_REFERER']);
		} elseif (isset($_SERVER['HTTP_REFERER'])) {
			$place_order['url'] = $_SERVER['HTTP_REFERER'];
			$return_url_after = '&return_to=' . urlencode($_SERVER['HTTP_REFERER']);
		} else {
			$place_order['url'] = curent_url();

		}

		$place_order['session_id'] = $sid;

		$place_order['order_completed'] = 'n';
		$items_count = 0;
		foreach ($flds_from_data as $value) {
			if (isset($data[$value]) and ($data[$value]) != false) {
				$place_order[$value] = $data[$value];
				$posted_fields[$value] = $data[$value];

			}
		}
		$amount = cart_sum();
		if ($amount == 0) {
			$checkout_errors['cart_sum'] = 'Cart sum is 0?';
		}

		if (!empty($checkout_errors)) {

			return array('error' => $checkout_errors);
		}

		$place_order['amount'] = $amount;
		$place_order['currency'] = get_option('currency', 'payments');

		if (isset($data['shipping_gw'])) {
			$place_order['shipping_service'] = $data['shipping_gw'];
		}

		if (intval($shiping_cost_above) > 0 and intval($shiping_cost_max) > 0) {
			if ($amount > $shiping_cost_above) {
				$shiping_cost = $shiping_cost_max;
			}
		}

		$place_order['shipping'] = $shiping_cost;

		$items_count = cart_sum(false);
		$place_order['items_count'] = $items_count;

		$cart_checksum = md5($sid . serialize($check_cart));

		$place_order['payment_verify_token'] = $cart_checksum;

		define('FORCE_SAVE', $table_orders);

		$temp_order = save_data($table_orders, $place_order);
		if ($temp_order != false) {
			$place_order['id'] = $temp_order;
		} else {
			$place_order['id'] = 0;
		}

		$place_order['item_name'] = 'Order id:' . ' ' . $place_order['id'];

		if ($mw_process_payment == true) {
			$shop_dir = module_dir('shop');
			$shop_dir = $shop_dir . DS . 'payments' . DS . 'gateways' . DS;

			if ($data['payment_gw'] != 'none') {

				$gw_process = MODULES_DIR . $data['payment_gw'] . '_process.php';

				$mw_return_url = api_url('checkout') . '?mw_payment_success=1' . $return_url_after;
				$mw_cancel_url = api_url('checkout') . '?mw_payment_failure=1' . $return_url_after;
				$mw_ipn_url = api_url('checkout_ipn') . '?payment_gw=' . $data['payment_gw'] . '&payment_verify_token=' . $place_order['payment_verify_token'];

				if (is_file($gw_process)) {
					require_once $gw_process;
				} else {
					//error('Payment gateway\'s process file not found.');
					$checkout_errors['payment_gw'] = 'Payment gateway\'s process file not found.';

				}
			} else {
				$place_order['order_completed'] = 'y';
				$place_order['is_paid'] = 'n';

				$place_order['success'] = "Your order has been placed successfully!";

			}
			// $q = " DELETE FROM $table_orders  	where order_completed='n'  and session_id='{$sid}' and is_paid='n' ";

			// db_q($q);
			if (!empty($checkout_errors)) {

				return array('error' => $checkout_errors);
			}

			$ord = save_data($table_orders, $place_order);

			$q = " UPDATE $cart_table set
		order_id='{$ord}'
		where order_completed='n'  and session_id='{$sid}'  ";

			db_q($q);

			if (isset($place_order['order_completed']) and $place_order['order_completed'] == 'y') {
				$q = " UPDATE $cart_table set
			order_completed='y', order_id='{$ord}'

			where order_completed='n'  and session_id='{$sid}' ";

				db_q($q);

				if (isset($place_order['is_paid']) and $place_order['is_paid'] == 'y') {
					$q = " UPDATE $table_orders set
				order_completed='y'
				where order_completed='n' and
				id='{$ord}' and session_id='{$sid}' ";
					db_q($q);
				}

				cache_clean_group('cart/global');
				cache_clean_group('cart_orders/global');
				after_checkout($ord);
				//$_SESSION['mw_payment_success'] = true;
			}

			$_SESSION['order_id'] = $ord;
		}

		//	exit();
		if (isset($place_order)) {
			return ($place_order);
		}

	}

	if (!empty($checkout_errors)) {

		return array('error' => $checkout_errors);
	}

	//d($check_cart);
}

function after_checkout($order_id, $suppress_output = true) {
	if ($suppress_output == true) {
		ob_start();
	}
	if ($order_id == false or trim($order_id) == '') {
		return array('error' => 'Invalid order ID');
	}

	$ord_data = get_orders('one=1&id=' . $order_id);
	if (isarr($ord_data)) {

		$ord = $order_id;
		$notif = array();
		$notif['module'] = "shop";
		$notif['rel'] = 'cart_orders';
		$notif['rel_id'] = $ord;
		$notif['title'] = "You have new order";
		$notif['description'] = "New order is placed from " . curent_url(1);
		$notif['content'] = "New order in the online shop. Order id: " . $ord;
		\mw\Notifications::save($notif);
		checkout_confirm_email_send($order_id);

	}
	if ($suppress_output == true) {
		ob_end_clean();
	}
}

function checkout_confirm_email_send($order_id, $to = false, $no_cache = false) {

	$ord_data = get_orders('one=1&id=' . $order_id);
	if (isarr($ord_data)) {

		$order_email_enabled = get_option('order_email_enabled', 'orders');

		if ($order_email_enabled == true) {
			$order_email_subject = get_option('order_email_subject', 'orders');
			$order_email_content = get_option('order_email_content', 'orders');
			$order_email_cc = get_option('order_email_cc', 'orders');

			if ($order_email_subject == false or trim($order_email_subject) == '') {
				$order_email_subject = "Thank you for your order!";
			}

			if ($to == false) {

				$to = $ord_data['email'];
			}
			if ($order_email_content != false and trim($order_email_subject) != '') {

				if (!empty($ord_data)) {
					$cart_items = get_cart('fields=title,qty,price,custom_fields_data&order_id=' . $ord_data['id'] . '&session_id=' . session_id());
				$order_items_html = array_pp($cart_items);

				$order_email_content = str_replace('{cart_items}', $order_items_html, $order_email_content);


					foreach ($ord_data as $key => $value) {
						$order_email_content = str_ireplace('{' . $key . '}', $value, $order_email_content);

					}
				}
				if (!defined('MW_ORDERS_SKIP_SID')) {
			//		define('MW_ORDERS_SKIP_SID', 1);
				}

				$cc = false;
				if (isset($order_email_cc) and (filter_var($order_email_cc, FILTER_VALIDATE_EMAIL))) {
					$cc = $order_email_cc;

				}
				if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {

					$scheduler = new \mw\utils\Events();
					// schedule a global scope function:
					$scheduler -> registerShutdownEvent("\mw\email\Sender::send", $to, $order_email_subject, $order_email_content, true, $no_cache, $cc);

					//\mw\email\Sender::send($to, $order_email_subject, $order_email_content, true, $no_cache, $cc);
				}

			}
		}
	}
}

api_expose('checkout_confirm_email_test');
function checkout_confirm_email_test($params) {

	if (!isset($params['to'])) {
		$email_from = get_option('email_from', 'email');
		if ($email_from == false) {
			return array('error' => 'You must set up your email');
		}
	} else {
		$email_from = $params['to'];

	}
	$ord_data = get_orders('order_completed=y&limit=50');
	if (isarr($ord_data[0])) {
		shuffle($ord_data);
		$ord_test = $ord_data[0];
		checkout_confirm_email_send($ord_test['id'], $to = $email_from, true);
	}

}

api_expose('update_cart');

function update_cart($data) {

	if (!session_id() and !headers_sent()) {
		session_start();
	}

	if (isset($data['content_id'])) {
		$data['for'] = 'content';
		$for_id = $data['for_id'] = $data['content_id'];
	}

	if (!isset($data['for'])) {
		$data['for'] = 'content';
	}

	if (!isset($data['for']) or !isset($data['for_id'])) {

		error('Invalid data');
	}

	$data['for'] = db_get_assoc_table_name($data['for']);

	$for = $data['for'];
	$for_id = intval($data['for_id']);

	$update_qty = 0;

	if ($for_id == 0) {

		error('Invalid data');
	}

	if ($data['for'] == 'content') {
		$cont = get_content_by_id($for_id);

		if ($cont == false) {
			error('Invalid product?');
		} else {
			if (isarr($cont) and isset($cont['title'])) {
				$data['title'] = $cont['title'];
			}
		}
	}

	if (isset($data['qty'])) {
		$update_qty = intval($data['qty']);
		unset($data['qty']);
	}

	$cfs = array();
	$cfs = get_custom_fields($for, $for_id, 1);
	if ($cfs == false) {

		error('Invalid data');
	}

	$add = array();
	$prices = array();
	$found_price = false;
	$skip_keys = array();
	foreach ($data as $k => $item) {

		if ($k != 'for' and $k != 'for_id' and $k != 'title') {

			$found = false;

			foreach ($cfs as $cf) {

				if (isset($cf['custom_field_type']) and $cf['custom_field_type'] != 'price') {
					$key1 = str_replace('_', ' ', $cf['custom_field_name']);
					$key2 = str_replace('_', ' ', $k);

					if (isset($cf['custom_field_name']) and ($cf['custom_field_name'] == $k or $key1 == $key2)) {
						$k = str_replace('_', ' ', $k);
						$found = true;
						/*
						 if ($item== 'ala' and is_array($item)) {

						 if (isarr($cf['custom_field_values'])) {

						 $vi = 0;
						 foreach ($item as $ik => $item_value) {

						 if (in_array($item_value, $cf['custom_field_values'])) {

						 //	$cf1 = $cf;
						 //$cf1['custom_field_values'] = $item_value;
						 $item[$ik] = $item_value;

						 } else {
						 unset($item[$ik]);
						 }

						 $vi++;
						 }
						 }
						 // d($item);
						 } else {*/

						//if($cf['custom_field_type'] != 'price'){
						if (isarr($cf['custom_field_values'])) {
							if (in_array($item, $cf['custom_field_values'])) {
								$found = true;
							}

						}

						if ($found == false and $cf['custom_field_value'] != $item) {
							unset($item);
						}
						//}
					} else {
						//	$skip_keys[] = $k;
						//break(1);
						//	unset($item );
					}

					//   d($k);
					//
					//}
				} elseif (isset($cf['custom_field_type']) and $cf['custom_field_type'] == 'price') {
					if ($cf['custom_field_value'] != '') {

						$prices[$cf['custom_field_name']] = $cf['custom_field_value'];

					}
					//$item[$cf['custom_field_name']] = $cf['custom_field_value'];
					// unset($item[$k]);
				} else {
					//unset($item);
				}

			}
			if ($found == false) {
				$skip_keys[] = $k;
			}
			if (isarr($prices)) {

				foreach ($prices as $price_key => $price) {

					if (isset($data['price'])) {

						if ($price == $data['price']) {
							$found = true;
							$found_price = $price;

						}
					} else if ($price == $item) {
						$found = true;
						if ($found_price == false) {
							$found_price = $item;
						}
						// d($item);
					} else {
						// unset($item);
					}
				}
				if ($found_price == false) {
					$found_price = $prices[0];

				}

			}

			if (isset($item)) {
				if ($found == true) {
					if ($k != 'price' and !in_array($k, $skip_keys)) {
						$add[$k] = ($item);
					}
				}
			}

		}
		// }
	}
	if ($found_price == false) {
		// $found_price = 0;
		error('Invalid data: Please post a "price" field with <input name="price"> ');
	}

	if (isarr($prices)) {
		ksort($add);
		asort($add);
		$table = MODULE_DB_SHOP;
		$cart = array();
		$cart['rel'] = ($data['for']);
		$cart['rel_id'] = intval($data['for_id']);
		$cart['title'] = ($data['title']);
		$cart['price'] = floatval($found_price);
		$cart['custom_fields_data'] = encode_var($add);
		$cart['order_completed'] = 'n';
		$cart['session_id'] = session_id();
		//$cart['one'] = 1;
		$cart['limit'] = 1;
		//  $cart['no_cache'] = 1;
		$checkz = get_cart($cart);
		// d($checkz);
		if ($checkz != false and isarr($checkz) and isset($checkz[0])) {
			//    d($check);
			$cart['id'] = $checkz[0]['id'];
			if ($update_qty > 0) {
				$cart['qty'] = $checkz[0]['qty'] + $update_qty;
			} else {
				$cart['qty'] = $checkz[0]['qty'] + 1;
			}

			//
		} else {

			if ($update_qty > 0) {
				$cart['qty'] = $update_qty;
			} else {
				$cart['qty'] = 1;
			}
		}
		//
		mw_var('FORCE_SAVE', $table);

		$cart_s = save_data($table, $cart);
		return ($cart_s);
	} else {
		error('Invalid cart items');
	}

	//  d($data);
	exit ;
}

api_expose('update_cart_item_qty');

function update_cart_item_qty($data) {

	if (!isset($data['id'])) {
		error('Invalid data');
	}

	if (!isset($data['qty'])) {
		error('Invalid data');
	}
	if (!session_id() and !headers_sent()) {
		session_start();
	}
	$cart = array();
	$cart['id'] = intval($data['id']);

	//if (is_admin() == false) {
	$cart['session_id'] = session_id();
	//}
	$cart['order_completed'] = 'n';

	$cart['one'] = 1;
	$cart['limit'] = 1;
	$checkz = get_cart($cart);

	if ($checkz != false and isarr($checkz)) {
		// d($checkz);
		$cart['qty'] = intval($data['qty']);
		$table = MODULE_DB_SHOP;
		mw_var('FORCE_SAVE', $table);

		$cart_s = save_data($table, $cart);
		return ($cart_s);
		//   db_delete_by_id($table, $id = $cart['id'], $field_name = 'id');
	} else {

	}
}

api_expose('remove_cart_item');

function remove_cart_item($data) {

	if (!isset($data['id'])) {
		error('Invalid data');
	}
	if (!session_id() and !headers_sent()) {
		session_start();
	}
	$cart = array();
	$cart['id'] = intval($data['id']);

	if (is_admin() == false) {
		$cart['session_id'] = session_id();
	}
	$cart['order_completed'] = 'n';

	$cart['one'] = 1;
	$cart['limit'] = 1;
	$checkz = get_cart($cart);

	if ($checkz != false and isarr($checkz)) {
		// d($checkz);
		$table = MODULE_DB_SHOP;
		db_delete_by_id($table, $id = $cart['id'], $field_name = 'id');
	} else {

	}
}

function get_cart($params) {

	$params2 = array();
	if (!isset($_SESSION)) {
		return false;
	}

	if (is_string($params)) {
		$params = parse_str($params, $params2);
		$params = $params2;
	}
	$table = MODULE_DB_SHOP;
	$params['table'] = $table;

	if (!defined('MW_ORDERS_SKIP_SID')) {

		if (is_admin() == false) {
			$params['session_id'] = session_id();
		} else {
			if (isset($params['session_id']) and is_admin() == true) {

			} else {
				$params['session_id'] = session_id();

			}
		}

		if (isset($params['no_session_id']) and is_admin() == true) {
			unset($params['session_id']);
			//	$params['session_id'] = session_id();
		} else {

		}
	}

	$get = get($params);
	//return $get;

	$return = array();
	if (isarr($get)) {
		foreach ($get as $item) {
			if (isset($item['custom_fields_data']) and $item['custom_fields_data'] != '') {
				$item['custom_fields_data'] = decode_var($item['custom_fields_data']);

				$tmp_val = '';
				if (isset($item['custom_fields_data']) and isarr($item['custom_fields_data'])) {
					$tmp_val .= '<ul class="mw-custom-fields-cart-item">';
					foreach ($item['custom_fields_data'] as $cfk => $cfv) {
						if (isarr($cfv)) {
							$tmp_val .= '<li><span class="mw-custom-fields-cart-item-key-array-key">' . $cfk . '</span>';
							$tmp_val .= '<ul class="mw-custom-fields-cart-item-array">';
							foreach ($cfv as $cfk1 => $cfv1) {
								$tmp_val .= '<li class="mw-custom-fields-elem"><span class="mw-custom-fields-cart-item-key">' . $cfk1 . ': </span><span class="mw-custom-fields-cart-item-value">' . $cfv1 . '</span></li>';
							}
							$tmp_val .= '</ul>';
							$tmp_val .= '</li>';
						} else {
							$tmp_val .= '<li class="mw-custom-fields-elem"><span class="mw-custom-fields-cart-item-key">' . $cfk . ': </span><span class="mw-custom-fields-cart-item-value">' . $cfv . '</span></li>';
						}
					}
					$tmp_val .= '</ul>';
					$item['custom_fields'] = $tmp_val;
				}

			}
			$return[] = $item;
		}

	}
	if (empty($return)) {
		$return = false;
	}

	return $return;
	//  d($params);

}

function payment_options($option_key = false) {

	$option_key_q = '';
	if (is_string($option_key)) {
		$option_key_q = "&limit=1&option_key={$option_key}";

	}

	$providers = get_options('option_group=payments' . $option_key_q);
	$str = 'payment_gw_';
	$l = strlen($str);

	$valid = array();
	foreach ($providers as $value) {
		if ($value['option_value'] == 'y') {
			if (substr($value['option_key'], 0, $l) == $str) {
				$title = substr($value['option_key'], $l);
				$string = preg_replace('/(\w+)([A-Z])/U', '\\1 \\2', $title);
				$value['gw_file'] = $title;

				$mod_infp = get_modules_from_db('ui=any&one=1&module=' . $title);

				if (!empty($mod_infp)) {
					$value = $mod_infp;
					$value['gw_file'] = $title;
				} else {
					$value['name'] = $title;
				}
				//
				$valid[] = $value;

			}
		}
	}
	return $valid;
}
