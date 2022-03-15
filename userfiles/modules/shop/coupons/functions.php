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
include __DIR__ . DS . 'src/CouponClass.php';

autoload_add_namespace(__DIR__ . '/src/', 'MicroweberPackages\\Modules\\Shop\\Coupons\\');

function coupon_apply($params = array())
{
    $json = array();
    $ok = false;
    $errorMessage = '';

    $coupon_code = $params['coupon_code'];

    $coupon = coupon_get_by_code($coupon_code);
    if (empty($coupon)) {
        $json['error_message'] = _e('The coupon code is not valid.', true);
        return $json;
    }

    $customer_ip = user_ip();

//    $checkout = new MicroweberPackages\Checkout\CheckoutManager();
//    $getCart = $checkout->app->shop_manager->get_cart(array(
//        'session_id' => $checkout->app->user_manager->session_id()
//
//    ));

    $getCart = false;
    $coupon['total_amount'] = floatval($coupon['total_amount']);
    $cartTotal = floatval( \DB::table('cart')->where('session_id', app()->user_manager->session_id())->sum('price'));
    $getCartItems =   \DB::table('cart')->where('session_id', app()->user_manager->session_id())->get();

    if($getCartItems){
        $getCart = $getCartItems->toArray();
    }

    // Check rules
     if ($coupon and isset($coupon['uses_per_customer']) and $coupon['uses_per_customer'] > 0) {
        $getLog = coupon_log_get_by_code_and_customer_ip($coupon_code, $customer_ip);

        if (is_array($getLog) and $getLog['uses_count'] !== false && $getLog['uses_count'] >= $coupon['uses_per_customer']) {
            $errorMessage .= _e('The coupon cannot be applied cause maximum uses exceeded.', true) . "<br />";
        }
    }

    if ($coupon['uses_per_coupon'] > 0) {
        $getLogs = coupon_logs_get_by_code($coupon_code);

        if (count($getLogs) >= $coupon['uses_per_coupon']) {
            $errorMessage .= _e('The coupon code is expired', true) . '.<br />';
        }
    }

    if ($coupon and isset($coupon['total_amount']) and  $cartTotal < $coupon['total_amount']) {
        $errorMessage .= _e('The coupon can\'t be applied because the minimum total amount is ', true) . currency_format($coupon['total_amount']) . "<br />";
    }

    if (!is_array($getCart)) {
        $errorMessage .= _e('The coupon can\'t be applied. The shopping cart is empty.', true);
    }

    if (empty($errorMessage)) {
        $ok = true;
    }

    if(isset( $params['coupon_check_if_valid'])){
       return $ok;
    }


    if ($ok) {

        mw()->user_manager->session_set('coupon_code', $coupon['coupon_code']);
        mw()->user_manager->session_set('coupon_id', $coupon['id']);
        mw()->user_manager->session_set('discount_value', $coupon['discount_value']);
        mw()->user_manager->session_set('discount_type', $coupon['discount_type']);

        mw()->user_manager->session_set('applied_coupon_data', $coupon);

        $json['success_message'] = _e('Coupon code applied.', true);
        $json['success_apply'] = true;
    } else {

        coupons_delete_session();

        $json['error_message'] = $errorMessage;
    }

    return $json;
}

function coupons_save_coupon($couponData = array())
{
    $json = array();
    $ok = false;
    $errorMessage = '';
    $table = 'cart_coupons';


    if (!isset($couponData['is_active'])) {
        $couponData['is_active'] = 0;
    }

    // check if coupon code exists
    $check = coupon_get_by_code($couponData['coupon_code']);
    if (!empty($check)) {
        if ($check['id'] != $couponData['id']) {
            $errorMessage .= _e('This coupon code allready exists. Please, try with another', true) . '.<br />';
        }
    }

    if (!is_numeric($couponData['uses_per_coupon'])) {
        $errorMessage .= _e('Uses per coupon must be number', true) . '.<br />';
    }

    if (!is_numeric($couponData['uses_per_customer'])) {
        $errorMessage .= _e('Uses per customer must be number', true) . '.<br />';
    }

    if (!is_numeric($couponData['discount_value'])) {
        $errorMessage .= _e('Discount value must be number', true) . '.<br />';
    }

    if (!is_numeric($couponData['total_amount'])) {
        $errorMessage .= _e('Total amount must be number', true) . '.<br />';
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


function coupon_log_customer($coupon_code, $customer_email, $customer_ip)
{
    $coupon = coupon_get_by_code($coupon_code);
    if (empty($coupon)) {
        return false;
    }

    $checkLog = coupon_log_get_by_code_and_customer_email_and_ip($coupon_code, $customer_email, $customer_ip);

    $couponLogData = array();
    $table = 'cart_coupon_logs';

    if (!empty($checkLog)) {
        $couponLogData['id'] = $checkLog['id'];
        $couponLogData['uses_count'] = $checkLog['uses_count'] + 1;
    } else {
        $couponLogData['uses_count'] = 1;
    }

    $couponLogData['coupon_id'] = $coupon['id'];
    $couponLogData['coupon_code'] = $coupon_code;
    $couponLogData['customer_email'] = $customer_email;
    $couponLogData['customer_ip'] = $customer_ip;
    $couponLogData['use_date'] = date("Y-m-d H:i:s");

    $couponLogId = db_save($table, $couponLogData);
}


function coupon_log_get_by_code_and_customer_email_and_ip($coupon_code, $customer_email, $customer_ip)
{
    $table = "cart_coupon_logs";

    return db_get($table, array(
        'coupon_code' => $coupon_code,
        'customer_email' => $customer_email,
        'customer_ip' => $customer_ip,
        'single' => true,
        'no_cache' => true
    ));
}

function coupon_log_get_by_code_and_customer_ip($coupon_code, $customer_ip)
{
    $table = "cart_coupon_logs";

    return db_get($table, array(
        'coupon_code' => $coupon_code,
        'customer_ip' => $customer_ip,
        'single' => true,
        'no_cache' => true
    ));
}

function coupon_logs_get_by_code($coupon_code)
{
    $table = "cart_coupon_logs";

    return DB::table($table)->select('*')
        ->where('coupon_code', $coupon_code)
        ->get()
        ->toArray();
}

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


function coupon_logs()
{
    $table = 'cart_coupon_logs';
    $coupons = DB::table($table)->select('*')
        ->get()
        ->toArray();

    $readyCoupons = array();
    foreach ($coupons as $coupon) {
        $readyCoupons[] = get_object_vars($coupon);
    }

    return $readyCoupons;
}

function coupon_get_by_id($coupon_id)
{
    $table = "cart_coupons";

    return db_get($table, array(
        'id' => $coupon_id,
        'single' => true,
        'no_cache' => true
    ));
}

function coupon_get_by_code($coupon_code)
{
    $table = "cart_coupons";

    $get = db_get($table, array(
        'is_active' => 1,
        'coupon_code' => $coupon_code,
        'single' => true,
        'no_cache' => true
    ));

    return $get;
}

function coupon_delete($data)
{
    if (!is_admin())
        return;

    $table = "cart_coupons";

    $couponId = (int) $data['coupon_id'];
    if ($couponId == 0) {
        return array(
            'status' => 'failed'
        );
    }

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

function coupons_delete_session()
{
    mw()->user_manager->session_del('coupon_code');
    mw()->user_manager->session_del('coupon_id');
    mw()->user_manager->session_del('discount_value');
    mw()->user_manager->session_del('discount_type');
    mw()->user_manager->session_del('applied_coupon_data');
}


event_bind('mw.admin.shop.settings.menu', function ($data) {
    print '<div class="col-12 col-sm-6 col-lg-4">
                <a href="#option_group=shop/coupons/admin" class="d-flex my-3">
                    <div class="icon-holder"><i class="mdi mdi-scissors-cutting mdi-20px"></i></div>
                    <div class="info-holder">
                        <span class="text-outline-primary font-weight-bold">' . _e('Coupons', true) . '</span><br/>
                        <small class="text-muted">' . _e('Creating and managing coupon codes', true) . '</small>
                    </div>
                </a>
            </div>';
});

event_bind('mw.admin.shop.settings.coupons', function ($data) {
    print '<module type="shop/coupons" view="admin_block" />';
});
