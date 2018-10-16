<?php
/**
 * Microweber Coupon Module
 * Developed by: Bozhidar Slaveykov
 *
 * @category   Modules
 * @package    Functions
 * @author     Bozhidar Slaveykov <selfworksbg@gmail.com>
 * @copyright  2018 Microweber
 */
api_expose('coupon_apply');

function coupon_apply($params = array())
{
	$json = array();
	$ok = false;
	$errorMessage = '';
	
	$coupon_code = $params['coupon_code'];
	
	$coupon = coupon_get_by_code($coupon_code);
	if (empty($coupon)) {
		$errorMessage .= 'The coupon code is not valid.<br />';
		$ok = false;
	}
	
	$checkoutManager = new Microweber\Providers\Shop\CheckoutManager();
	
	$sid = $checkoutManager->app->user_manager->session_id();
	$cart = array();
	$cart['session_id'] = $sid;
	$checkCart = $checkoutManager->app->shop_manager->get_cart($cart);
	
	if (! is_array($checkCart)) {
		$errorMessage .= 'The coupon can\'t be applied. The shopping cart is empty.';
	}
	
	if (empty($errorMessage)) {
		$ok = true;
	}
	
	if ($ok) {
		
		mw()->user_manager->session_set('discount_value', $coupon['discount_value']);
		mw()->user_manager->session_set('discount_type', $coupon['discount_type']);
		
		$json['success_message'] = 'The coupon code applied success.';
		$json['success_apply'] = true;
	} else {
		
		mw()->user_manager->session_set('discount_value', false);
		mw()->user_manager->session_set('discount_type', false);
		
		$json['error_message'] = $errorMessage;
	}
	
	return $json;
}

api_expose_admin('coupons_save_coupon');

function coupons_save_coupon($couponData = array())
{
	$json = array();
	$ok = false;
	$errorMessage = '';
	$table = 'cart_coupons';
	
	// check if coupon code exists
	$check = coupon_get_by_code($couponData['coupon_code']);
	if (! empty($check)) {
		if ($check['id'] != $couponData['id']) {
			$errorMessage .= 'This coupon code allready exists. Please, try with another.<br />';
		}
	}
	
	if (! is_numeric($couponData['uses_per_coupon'])) {
		$errorMessage .= 'Uses per coupon must be number.<br />';
	}
	
	if (! is_numeric($couponData['uses_per_customer'])) {
		$errorMessage .= 'Uses per customer must be number.<br />';
	}
	
	if (! is_numeric($couponData['discount_value'])) {
		$errorMessage .= 'Discount value must be number.<br />';
	}
	
	if (! is_numeric($couponData['total_amount'])) {
		$errorMessage .= 'Total amount must be number.<br />';
	}
	
	if (empty($errorMessage)) {
		$ok = true;
	}
	
	if ($ok) {
		$couponId = db_save($table, $couponData);
		$json['coupon_id'] = $couponId;
		$json['success_edit'] = true;
	} else {
		$json['error_message'] = $errorMessage;
	}
	
	return $json;
}

api_expose_admin('coupon_log_customer');

function coupon_log_customer($coupon_code, $customer_id)
{
	$coupon = coupon_get_by_code($coupon_code);
	$checkLog = coupon_log_get_by_code_and_customer_id($coupon_code, $customer_id);
	
	$couponLogData = array();
	$table = 'cart_coupon_logs';
	
	if (! empty($checkLog)) {
		$couponLogData['id'] = $checkLog['id'];
		$couponLogData['uses_count'] = $checkLog['uses_count'] + 1;
	} else {
		$couponLogData['uses_count'] = 1;
	}
	
	$couponLogData['coupon_id'] = $coupon['id'];
	$couponLogData['customer_id'] = $customer_id;
	$couponLogData['coupon_code'] = $coupon_code;
	$couponLogData['use_date'] = date("Y-m-d H:i:s");
	
	$couponLogId = db_save($table, $couponLogData);
}

api_expose_admin('coupon_log_get_by_code_and_customer_id');

function coupon_log_get_by_code_and_customer_id($coupon_code, $customer_id)
{
	$table = "cart_coupon_logs";
	
	return db_get($table, array(
		'coupon_code' => $coupon_code,
		'customer_id' => $customer_id,
		'single' => true
	));
}

api_expose_admin('coupon_get_all');

function coupon_get_all()
{
	$table = 'cart_coupons';
	$coupons = DB::table($table)->select('*')
		->get()
		->toArray();
	
	$readyCoupons = array();
	foreach ($coupons as $coupon) {
		$readyCoupons[] = get_object_vars($coupon);
	}
	
	return $readyCoupons;
}

api_expose_admin('coupon_get_by_id');

function coupon_get_by_id($coupon_id)
{
	$table = "cart_coupons";
	
	return db_get($table, array(
		'id' => $coupon_id,
		'single' => true
	));
}

api_expose_admin('coupon_get_by_code');

function coupon_get_by_code($coupon_code)
{
	$table = "cart_coupons";
	
	return db_get($table, array(
		'coupon_code' => $coupon_code,
		'single' => true
	));
}

api_expose('coupon_delete');

function coupon_delete()
{
	if (! is_admin())
		return;
	
	$table = "cart_coupons";
	$couponId = (int) $_POST['coupon_id'];
	
	$delete = db_delete($table, $couponId);
	
	if ($delete) {
		return array(
			'status' => 'success'
		);
	} else {
		return array(
			'status' => 'failed'
		);
	}
}