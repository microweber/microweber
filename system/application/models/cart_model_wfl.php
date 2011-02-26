<?php

if (! defined ( 'BASEPATH' ))
	
	exit ( 'No direct script access allowed' );

/**
 
 * Microweber

 *

 * An open source CMS and application development framework for PHP 5.1 or newer

 *

 * @package		Microweber
 * @author		Peter Ivanov
 * @copyright	Copyright (c), Mass Media Group, LTD.
 * @license		http://ooyes.net
 * @link		http://ooyes.net
 * @since		Version 1.0
 * @filesource

 */

// ------------------------------------------------------------------------


/**

 * Cart Class

 *

 * @desc Cart class

 * @access		public

 * @category	Cart API

 * @subpackage		Cart

 * @author		Peter Ivanov

 * @link		http://ooyes.net

 */

class Cart_model extends Model {
	
	function __construct() {
		
		parent::Model ();
	
	}
	
	function itemSave($data) {
		
		$id = $this->itemAdd ( $data );
		
		return $id;
	
	}
	
	/**

	 * @desc add/save item

	 * @param array

	 * @return id

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function itemAdd($data) {
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		if (empty ( $data )) {
			
			return false;
		
		}
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		if ($data ['sid'] == false) {
			
			$session_id = CI::library('session')->userdata ( 'session_id' );
		
		} else {
			
			$session_id = $data ['sid'];
		
		}
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		if (! empty ( $data ['other_info'] )) {
			
			$data ['other_info'] = base64_encode ( serialize ( $data ['other_info'] ) );
		
		}
		
		$data ['sid'] = $session_id;
		
		$data_to_save_options ['delete_cache_groups'] = array ('cart' );
		
		$id = CI::model('core')->saveData ( $table, $data, $data_to_save_options );
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		return $id;
	
	}
	
	/**

	 * @desc get items

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function itemsGet($data = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		if (empty ( $orderby )) {
			
			$orderby [0] = 'updated_on';
			
			$orderby [1] = 'DESC';
		
		}
		
		$get = CI::model('core')->getDbData ( $table, $data, $limit = $limit, $offset = $offset, $orderby = $orderby, $cache_group = $cache_group, $debug = $debug, $ids = $ids, $count_only = $count_only, $only_those_fields = $only_those_fields, $exclude_ids = $exclude_ids, $force_cache_id = $force_cache_id, $get_only_whats_requested_without_additional_stuff = $get_only_whats_requested_without_additional_stuff );
		
		return $get;
	
	}
	
	/**

	 * @desc delete item

	 * @param string

	 * @param array

	 * @return array

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0



function itemDeleteBySku($sku) {

if (strval ( $sku ) == '') {

return false;

}

$session_id = CI::library('session')->userdata ( 'session_id' );

global $cms_db_tables;

$table = $cms_db_tables ['table_cart'];



$data = array ();

$data ['sku'] = $sku;

$data ['sid'] = $session_id;

$del = CI::model('core')->deleteData ( $table, $data, 'cart' );



CI::model('core')->cacheDelete ( 'cache_group', 'cart' );

return $id;

	 */
	
	/**

	 * @desc delete item

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function itemDeleteById($id, $check_session = true, $only_uncompleted_orders = true) {
		
		$this->itemsCleanupOldAddedToCartItemsWhereTheOrderWasNeverCompleted ();
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		$id = intval ( $id );
		
		if ($check_session == true) {
			
			$session_id = CI::library('session')->userdata ( 'session_id' );
			
			$sid_where = " and sid='$session_id' ";
		
		}
		
		if ($only_uncompleted_orders == true) {
			
			$only_uncompleted_orders_q = " order_completed='n' ";
		
		} else {
			
			$only_uncompleted_orders_q = " order_completed is not null ";
		
		}
		
		$q = "delete from $table where $only_uncompleted_orders_q  $sid_where and id=$id";
		
		$q = CI::model('core')->dbQ ( $q );
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		return true;
	
	}
	
	/**

	 * @desc Get Qty

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function itemsGetQty() {
		
		$session_id = CI::library('session')->userdata ( 'session_id' );
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		$q = "select sum(qty) as qty_sum from $table where sid='$session_id'  and order_completed='n'";
		
		$q = CI::model('core')->dbQuery ( $q );
		
		$q = $q [0] ['qty_sum'];
		
		$q = intval ( $q );
		
		//var_dump($q);
		

		return $q;
		
	//CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
	

	//return $id;
	

	}
	
	/**

	 * @desc Get Total

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function itemsGetTotal($with_promo_code = false, $convert_to_currency = 'EUR') {
		
		$session_id = CI::library('session')->userdata ( 'session_id' );
		
		global $cms_db_tables;
		
		$to_return = false;
		
		$table = $cms_db_tables ['table_cart'];
		
		$q = "select qty, price from $table where sid='$session_id'  and order_completed='n'";
		
		$q = CI::model('core')->dbQuery ( $q );
		
		if (empty ( $q )) {
			
			return 0;
		
		} else {
			
			$total = 0;
			
			foreach ( $q as $item ) {
				
				$do_math = $item ['qty'] * $item ['price'];
				
				$total = $total + $do_math;
			
			}
		
		}
		
		if (trim ( strval ( $with_promo_code ) ) == '') {
			
			$to_return = $total;
		
		} else {
			
			$promo_code = $with_promo_code;
			
			$get_promo = array ();
			
			$get_promo ['promo_code'] = $with_promo_code;
			
			$codes = $this->cart_model->promoCodesGet ( $get_promo );
			
			$code = $codes [0];
			
			if (! empty ( $code )) {
				
				if ($code ['amount_modifier'] != 0) {
					
					switch ($code ['amount_modifier_type']) {
						
						case 'percent' :
							
							$percent = $code ['amount_modifier']; // without %
							

							$total = $total; // initial value
							

							$discount_value = ($total / 100) * $percent;
							
							$final_price = $total - $discount_value;
							
							//print $final_price;
							

							$to_return = $final_price;
							
							break;
						
						default :
							
							$final_price = $total - $code ['amount_modifier'];
							
							if ($final_price < 0) {
								$final_price = 0;
							}
							
							$to_return = $final_price;
							
							//var_Dump ( $amount );
							

							break;
					
					}
				
				}
			
			} else {
				
				$to_return = $total;
			
			}
		
		}
		
		//CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		

		//return $id;
		

		if ($convert_to_currency == "EUR") {
			return $to_return;
		} else {
			$convert_to_currency_price = $this->currencyConvertPrice ( $to_return, $convert_to_currency );
			return ceil ( $convert_to_currency_price );
		}
	}
	
	/**

	 * @desc Empties Cart

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function itemsEmptyCart() {
		
		$this->itemsCleanupOldAddedToCartItemsWhereTheOrderWasNeverCompleted ();
		
		$session_id = CI::library('session')->userdata ( 'session_id' );
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		$q = "delete from $table where sid='$session_id' and order_completed='n'";
		
		//print $q;
		

		$q = CI::model('core')->dbQ ( $q );
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		
 
CI::library('session')->set_userdata ( 'billing_cardholdernumber', false );
CI::library('session')->set_userdata ( 'billing_expiresmonth', false );
CI::library('session')->set_userdata ( 'billing_expiresyear', false );
CI::library('session')->set_userdata ( 'billing_cvv2', false );
		
	//CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
	

	//return $id;
	

	}
	
	/**

	 * @desc Sum various things

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function cartSumByFields($fld) {
		
		$session_id = CI::library('session')->userdata ( 'session_id' );
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		$q = "select sum($fld) as qty_sum from $table where sid='$session_id' and order_completed='n'";
		
		$q = CI::model('core')->dbQuery ( $q );
		
		$q = $q [0] ['qty_sum'];
		
		$q = floatval ( $q );
		
		return $q;
	
	}
	
	/**

	 * @desc Sum various things By Params array

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function cartSumByParams($fld, $params = false) {
		
		$session_id = CI::library('session')->userdata ( 'session_id' );
		
		global $cms_db_tables;
		
		$items = $this->itemsGet ( $params );
		
		$sum = 0;
		
		foreach ( $items as $itm ) {
			
			$sum = floatval ( $sum ) + floatval ( $itm [$fld] );
		
		}
		
		$sum = floatval ( $sum );
		
		return $sum;
	
	}
	
	/**

	 * @desc confirm the items in the order by making order_completed field = y

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function orderConfirm($sid = false, $order_id = false) {
		
		if ($sid == false) {
			
			$session_id = CI::library('session')->userdata ( 'session_id' );
		
		} else {
			
			$session_id = $sid;
		
		}
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		if ($order_id != false) {
			
			$order_id = strval ( $order_id );
			
			$order_id_q = ", order_id='$order_id'";
		
		}
		
		$q = "Update $table set order_completed='y' $order_id_q where sid='$session_id' and order_completed='n'";
		
		$q = CI::model('core')->dbQ ( $q );
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		return true;
	
	}
	
	/**

	 * @desc save the order

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function orderSave($data) {
		
		if ($data ['sid'] == false) {
			
			$session_id = CI::library('session')->userdata ( 'session_id' );
			
			$data ['sid'] = $session_id;
		
		} else {
			
			$session_id = $data ['sid'];
		
		}
		
		global $cms_db_tables;
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		$table_cart_orders = $cms_db_tables ['table_cart_orders'];
		
		$table_cart = $cms_db_tables ['table_cart'];
		
		$data_to_save_options ['delete_cache_groups'] = array ('cart' );
		
		$id = CI::model('core')->saveData ( $table_cart_orders, $data, $data_to_save_options );
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
	
	}
	
	/**

	 * @desc place the order by making order_placed = y

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function orderPlace($data) {
		
		if ($data ['sid'] == false) {
			
			$session_id = CI::library('session')->userdata ( 'session_id' );
			
			$data ['sid'] = $session_id;
		
		} else {
			
			$session_id = $data ['sid'];
		
		}
		
		global $cms_db_tables;
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		$table_cart_orders = $cms_db_tables ['table_cart_orders'];
		
		$table_cart = $cms_db_tables ['table_cart'];
		
		$data_to_save_options ['delete_cache_groups'] = array ('cart' );
		
		$id = CI::model('core')->saveData ( $table_cart_orders, $data, $data_to_save_options );
		
		$this->orderConfirm ( $data ['sid'], $order_id = $data ['order_id'] );
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
	
	}
	
	/**

	 * @desc get orders shorthand to the itemsOrders functuion. I dont know why we named it itemsOrders but it must stay that way because of compat issues

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function ordersGet($data = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = true) {
		
		$temp = $this->itemsOrders ( $data, $limit, $offset, $orderby, $cache_group, $debug, $ids, $count_only, $only_those_fields, $exclude_ids, $force_cache_id, $get_only_whats_requested_without_additional_stuff );
		
		return $temp;
	
	}
	
	/**

	 * @desc get orders

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function itemsOrders($data = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = true) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart_orders'];
		
		if (empty ( $orderby )) {
			
			$orderby [0] = 'updated_on';
			
			$orderby [1] = 'DESC';
		
		}
		
		$get = CI::model('core')->getDbData ( $table, $data, $limit = $limit, $offset = $offset, $orderby = $orderby, $cache_group = $cache_group, $debug = $debug, $ids = $ids, $count_only = $count_only, $only_those_fields = $only_those_fields, $exclude_ids = $exclude_ids, $force_cache_id = $force_cache_id, $get_only_whats_requested_without_additional_stuff = $get_only_whats_requested_without_additional_stuff );
		
		return $get;
	
	}
	
	/**

	 * @desc this is a housekeeping function so we dont make too huge DB table with unreal items that were never ordered

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function itemsCleanupOldAddedToCartItemsWhereTheOrderWasNeverCompleted() {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		$the_old_times = date ( "Y-m-d H:i:s", strtotime ( "-2 months" ) );
		
		$q = "delete from $table where created_on<'$the_old_times' and order_completed='n'";
		
		$q = CI::model('core')->dbQ ( $q );
		
		return true;
	
	}
	
	/**

	 * @desc delete order

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function orderDeleteById($id) {
		
		$this->itemsCleanupOldAddedToCartItemsWhereTheOrderWasNeverCompleted ();
		
		global $cms_db_tables;
		
		$table_orders = $cms_db_tables ['table_cart_orders'];
		
		$table_cart = $cms_db_tables ['table_cart'];
		
		$id = intval ( $id );
		
		if ($id == 0) {
			
			return false;
		
		}
		
		$order_data = array ();
		
		$order_data ['id'] = $id;
		
		$order_data = $this->ordersGet ( $order_data );
		
		$order_data = $order_data [0];
		
		if (! empty ( $order_data )) {
			
			$order_id = $order_data ['order_id'];
			
			$q = " delete from $table_cart where order_id = '$order_id' AND sid='{$order_data ['sid']}' ";
			
			CI::model('core')->dbQ ( $q );
			
			$q = " delete from $table_orders where id = $id ";
			
			CI::model('core')->dbQ ( $q );
		
		}
		
		//var_dump ( $order_data );
		

		//order_id
		

		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		return true;
	
	}
	
	/**

	 * @desc get promo codes

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function promoCodesGet($data = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = true) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart_promo_codes'];
		
		if (empty ( $orderby )) {
			
			$orderby [0] = 'updated_on';
			
			$orderby [1] = 'DESC';
		
		}
		
		$get = CI::model('core')->getDbData ( $table, $data, $limit = $limit, $offset = $offset, $orderby = $orderby, $cache_group = $cache_group, $debug = $debug, $ids = $ids, $count_only = $count_only, $only_those_fields = $only_those_fields, $exclude_ids = $exclude_ids, $force_cache_id = $force_cache_id, $get_only_whats_requested_without_additional_stuff = $get_only_whats_requested_without_additional_stuff );
		
		return $get;
	
	}
	
	/**

	 * @desc save the promo code

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function promoCodeSave($data) {
		
		global $cms_db_tables;
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		$table_cart_orders = $cms_db_tables ['table_cart_promo_codes'];
		
		$table_cart = $cms_db_tables ['table_cart'];
		
		$data_to_save_options ['delete_cache_groups'] = array ('cart' );
		
		$id = CI::model('core')->saveData ( $table_cart_orders, $data, $data_to_save_options );
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
	
	}
	
	/**

	 * @desc deletes promo code by id

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function promoCodeDeleteById($id) {
		
		$this->itemsCleanupOldAddedToCartItemsWhereTheOrderWasNeverCompleted ();
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart_promo_codes'];
		
		$id = intval ( $id );
		
		if ($id == 0) {
			
			return false;
		
		}
		
		$q = " delete from $table where id = $id ";
		
		CI::model('core')->dbQ ( $q );
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		return true;
	
	}
	
	/**

	 * @desc calculates shipping to destination county name like: Bulgaria, Unined

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function shippingCalculateToCountryName($country_name = false, $convert_to_currency = 'EUR') {
		
		$countries = CI::model('core')->geoGetAllCountries ();
		
		if ($country_name == false) {
			
			$country_name = CI::library('session')->userdata ( 'country_name' );
		
		}
		
		$the_c = false;
		
		foreach ( $countries as $c ) {
			
			if ($the_c == false) {
				
				$c ['name_lowercase'] = strtolower ( $c ['name'] );
				
				$c ['code_lowercase'] = strtolower ( $c ['code'] );
				
				$country_name_lowercase = strtolower ( $country_name );
				
				if ($c ['name_lowercase'] == $country_name_lowercase) {
					
					$the_c = $c;
				
				}
				
				if ($c ['code_lowercase'] == $country_name_lowercase) {
					
					$the_c = $c;
				
				}
			
			}
		
		}
		
		//	var_dump($the_c);
		

		if (empty ( $the_c )) {
			
			return false;
		
		} else {
			
			$continet = $the_c ['continent'];
			
			$shippingCostsGet_data = array ();
			
			$shippingCostsGet_data ['ship_to_continent'] = $continet;
			
			$shippingCostsGet_data ['is_active'] = 'y';
			
			$shippingCostsGet = $this->shippingCostsGet ( $shippingCostsGet_data );
			
			$shippingCostsGet = $shippingCostsGet [0];
			
			//shippingCostsGet
			

			if (empty ( $shippingCostsGet )) {
				
				return false;
			
			} else {
				
				$ship_price = $shippingCostsGet ['shiping_cost_per_item'];
				
				$num = $this->itemsGetQty ();
				
				$total = ($ship_price * $num);
				//	var_dump($convert_to_currency);
				if ($convert_to_currency == "EUR") {
					return $total;
				} else {
					$convert_to_currency_price = $this->currencyConvertPrice ( $total, $convert_to_currency );
					
					//var_dump($convert_to_currency_price);
					return ($convert_to_currency_price);
				}
			
			}
		
		}
	
	}
	
	/**
	 * 
	 * 
	 * 
	 * 
	 * UPS Service Selection Codes
	 * 01 � UPS Next Day Air
	 * 02 � UPS Second Day Air
	 * 03 � UPS Ground
		07 � UPS Worldwide Express
		08 � UPS Worldwide Expedited
		11 � UPS Standard
		12 � UPS Three-Day Select
		13 - Next Day Air Saver
		14 � UPS Next Day Air Early AM
		54 � UPS Worldwide Express Plus
		59 � UPS Second Day Air AM
		65 � UPS Saver
	 * 
	 * 
	 * 
	 * 

	 * @desc calculate UPS shipping costs

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function shippingUPSGetCost($data = array()) {
		//require_once 'ups/upsRate.php';
		global $cms_db_tables, $CFG;
		
		$cost = $data ['cost'];
		$from_zip = $data ['from_zip'];
		$to_zip = $data ['shipping_to_zip'];
		$service = $data ['shipping_service'];
		$length = $data ['length'];
		$width = $data ['width'];
		$height = $data ['height'];
		$address_type = $data ['shipping_address_type'];
		$weight = $data ['weight'];
		
		/*
		 * 
		 */
		
		$CFG->ups_userid = CI::model('core')->optionsGetByKey ( 'shop_ups_username', false );
		; // Enter your UPS User ID
		$CFG->ups_password = CI::model('core')->optionsGetByKey ( 'shop_ups_password', false );
		; // Enter your UPS Password
		$CFG->ups_xml_access_key = CI::model('core')->optionsGetByKey ( 'shop_ups_xml_key', false );
		; // Enter your UPS Access Key
		$CFG->ups_shipper_number = CI::model('core')->optionsGetByKey ( 'shop_ups_shipper_number', false );
		; // Enter your UPS Shipper Number
		$CFG->ups_testmode = CI::model('core')->optionsGetByKey ( 'shop_ups_testmode', false ); // "TRUE" for test transactions "FALSE" for live transactions
		

		$CFG->companystate = CI::model('core')->optionsGetByKey ( 'shop_producer_state', false ); // Your State
		$CFG->companyzipcode = $from_zip; // Your Zipcode
		

		$CFG->companyname = CI::model('core')->optionsGetByKey ( 'shop_producer_company', false ); // Your Company Name
		$CFG->companystreetaddress1 = CI::model('core')->optionsGetByKey ( 'shop_producer_address', false ); // Your Street Addres
		$CFG->companystreetaddress2 = ""; // Your Street Address
		$CFG->companycity = CI::model('core')->optionsGetByKey ( 'shop_producer_city', false ); // Your City
		//		$CFG->companystate			= "NY";	
		

		//		p($CFG);die;
		

		$ship_to = array ();
		$ship_to ["company_name"] = $data ['shipping_company_name']; // Ship To Company
		$ship_to ["attn_name"] = $data ['shipping_name']; // Ship To Name
		$ship_to ["phone_dial_plan_number"] = trim ( substr ( $data ['shipping_user_phone'], 0, 6 ) ); // Ship To First 6 Of Phone Number
		$ship_to ["phone_line_number"] = trim ( substr ( $data ['shipping_user_phone'], 6 ) ); // Ship To Last 4 Of Phone Number
		$ship_to ["phone_extension"] = "1"; // Ship To Phone Extension
		$ship_to ["address_1"] = $data ['shipping_address']; // Ship To 1st Address Line
		$ship_to ["address_2"] = ""; // Ship To 2nd Address Line
		$ship_to ["address_3"] = ""; // Ship To 3rd Address Line
		$ship_to ["city"] = $data ['shipping_to_city']; // Ship To City
		$ship_to ["state_province_code"] = $data ['shipping_state']; // Ship To State
		$ship_to ["postal_code"] = $to_zip; // Ship To Postal Code
		$ship_to ["country_code"] = CI::model('core')->optionsGetByKey ( "shop_producer_country", false ); // Ship To Country Code
		

		$ship_from = array ();
		$ship_from ["company_name"] = CI::model('core')->optionsGetByKey ( "shop_ups_shipper_company_name", false ); // Ship From Company
		$ship_from ["attn_name"] = CI::model('core')->optionsGetByKey ( "shop_ups_shipper_person_name", false ); // Ship From Name
		$ship_from ["phone_dial_plan_number"] = trim ( substr ( CI::model('core')->optionsGetByKey ( "shop_ups_shipper_company_phone", false ), 0, 6 ) ); // Ship From First 6 Of Phone Number
		$ship_from ["phone_line_number"] = trim ( substr ( CI::model('core')->optionsGetByKey ( "shop_ups_shipper_company_phone", false ), 6 ) );
		; // Ship From Last 4 Of Phone Number
		$ship_from ["phone_extension"] = "1"; // Ship From Phone Extension
		$ship_from ["address_1"] = CI::model('core')->optionsGetByKey ( "shop_ups_shipper_company_address", false ); // Ship From 1st Address Line
		$ship_from ["address_2"] = ""; // Ship From 2nd Address Line
		$ship_from ["address_3"] = ""; // Ship From 3rd Address Line
		$ship_from ["city"] = CI::model('core')->optionsGetByKey ( "shop_ups_shipper_company_city", false ); // Ship From City
		$ship_from ["state_province_code"] = CI::model('core')->optionsGetByKey ( 'shop_producer_state', false );
		; // Ship From State
		$ship_from ["postal_code"] = $from_zip; // Ship From Postal Code
		$ship_from ["country_code"] = CI::model('core')->optionsGetByKey ( "shop_producer_country", false ); // Ship From Country Code
		

		$shipment ["bill_shipper_account_number"] = $CFG->ups_shipper_number; // This will bill the shipper
		$shipment ["service_code"] = $service;
		$shipment ["packaging_type"] = CI::model('core')->optionsGetByKey ( 'shop_shipment_packaging_type', false ); // 02 For "Your Packaging"
		$shipment ["invoice_number"] = date ( 'ymdHis' ); // Invoice Number
		$shipment ["weight"] = $weight; // Total Weight Of Package (Not Less Than 1lb.)
		$shipment ["length"] = $length;
		$shipment ["width"] = $width;
		$shipment ["height"] = $height;
		$shipment ["insured_value"] = $cost; // Insured Value Of Package
		

		//		p($ship_from);
		//		p($shipment);die;
		

		$res = $this->ups_ship_confirm ( $ship_to, $ship_from, $shipment );
		
		//var_dump($res);
		if (intval ( $res ) == 0) {
			return false;
		}
		
		return $res;
		
	/*		
		$Url = join ( "&", array ("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes", "10_action=3", "13_product=" . $service, "14_origCountry=" . "US", "15_origPostal=" . $from_zip, "origCity=" . '', "19_destPostal=" . $to_zip, "20_destCity=" . '', "22_destCountry=US" . '', "23_weight=" . $width, "47_rateChart=" . 'Regular+Daily+Pickup', "48_container=" . '00', "49_residential=" . $address_type, "25_length=" . $length, "26_width=" . $width, "27_height=" . $height ) );
		
		$Resp = fopen ( $Url, "r" );
		while ( ! feof ( $Resp ) ) {
			$Result = fgets ( $Resp, 500 );
			$Result = explode ( "%", $Result );
			$Err = substr ( $Result [0], - 1 );
			
			switch ($Err) {
				case 3 :
					$ResCode = $Result [8];
					break;
				case 4 :
					$ResCode = $Result [8];
					break;
				case 5 :
					$ResCode = $Result [1];
					break;
				case 6 :
					$ResCode = $Result [1];
					break;
			}
		}
		fclose ( $Resp );
		if (! $ResCode) {
			$ResCode = "An error occured.";
		}
		// echo "<h1>The cost is: ".$ResCode . '</h1>';
		
*/
	//return $ResCode;
	}
	
	function ups_ship_confirm($ship_to, $ship_from, $shipment) {
		/////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////| UPS Ship Confirm Function |////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////////////
		// NOTE: The XML request docment contains some static values that can be changed for the
		// requirements of your specific application.  Examples include LabelPrintMethod,
		// LabelImageFormat, and LabelStockSize.  Please refer to the UPS Developer's Guide for
		// allowed values for these fields.
		//
		// ALSO: Characters such as "&" "<" ">" """ "'" have to be replaced in regard of the W3C
		// definition of XML.  These characters will break the XML document if they are not replaced.
		/////////////////////////////////////////////////////////////////////////////////////////////
		

		global $cms_db_tables, $CFG;
		//var_dump($CFG);
		

		include_once BASEPATH . '/libraries/upsRate.php';
		
		// UPS will not allow a weight value of anything less than 0lbs
		if ($shipment ["weight"] < 1) {
			$shipment ["weight"] = 1;
		}
		
		// define some required values
		$access_license_number = $CFG->ups_xml_access_key;
		$user_id = $CFG->ups_userid;
		$password = $CFG->ups_password;
		$label_height = "4";
		$label_width = "6";
		$shipper_name = $CFG->companyname;
		$shipper_attn_name = "Shipping Department";
		$shipper_phone_dial_plan_number = "123456";
		$shipper_phone_line_number = "7890";
		$shipper_phone_extension = "001";
		$shipper_number = $CFG->ups_shipper_number;
		$shipper_address_1 = $CFG->companystreetaddress1;
		$shipper_address_2 = $CFG->companystreetaddress2;
		$shipper_address_3 = "";
		$shipper_city = $CFG->companycity;
		$shipper_state_province_code = $CFG->companystate;
		$shipper_postal_code = $CFG->companyzipcode;
		
		$upsRate = new upsRate ();
		
		$upsRate->setCredentials ( $access_license_number, $user_id, $password, $shipper_number );
		$rate = $upsRate->getRate ( $shipper_postal_code, $ship_to ['postal_code'], $shipment ['service_code'], $shipment ['length'], $shipment ['width'], $shipment ['height'], $shipment ['weight'] );
		//var_dump($rate);
		

		return number_format ( $rate, 2 );
		/*//$ship_from[state_province_code] = $ship_from['from_zip'];
		
		     	<Length>$shipment[length]</Length>
         	<Width>$shipment[width]</Width>
         	<Height>$shipment[height]</Height>
         </Dimensions>
     
         <PackageWeight>
            <UnitOfMeasurement>
               <Code>LBS</Code>
            </UnitOfMeasurement>
            <Weight>$shipment[weight]</Weight>
            
            
            
		$shipper_country_code = "US";
		if ($CFG->ups_testmode == "FALSE") {
			$post_url = "https://www.ups.com/ups.app/xml/ShipConfirm";
		} else {
			$post_url = "https://wwwcie.ups.com/ups.app/xml/ShipConfirm";
		}
		
		// construct the xml query document
		$xml_request = "<?xml version=\"1.0\"?>
<AccessRequest xml:lang=\"en-US\">
	<AccessLicenseNumber>
		$access_license_number
	</AccessLicenseNumber>
	<UserId>
		$user_id
	</UserId>
	<Password>
		$password
	</Password>
</AccessRequest>
<?xml version=\"1.0\"?>
<ShipmentConfirmRequest xml:lang=\"en-US\">
   <Request>
      <TransactionReference>
         <CustomerContext>ShipConfirmUS</CustomerContext>
         <XpciVersion>1.0001</XpciVersion>
      </TransactionReference>
      <RequestAction>ShipConfirm</RequestAction>
      <RequestOption>nonvalidate</RequestOption>
   </Request>
   <LabelSpecification>
      <LabelPrintMethod>
         <Code>EPL</Code>
      </LabelPrintMethod>
      <LabelImageFormat>
      	<Code>EPL</Code>
      </LabelImageFormat>
      <LabelStockSize>
      	<Height>4</Height>
      	<Width>6</Width>
      </LabelStockSize>
   </LabelSpecification>
   <Shipment>
      <Shipper>
         <Name>$shipper_name</Name>
         <AttentionName>$shipper_attn_name</AttentionName>
         <PhoneNumber>
            <StructuredPhoneNumber>
               <PhoneDialPlanNumber>$shipper_phone_dial_plan_number</PhoneDialPlanNumber>
               <PhoneLineNumber>$shipper_phone_line_number</PhoneLineNumber>
               <PhoneExtension>$shipper_phone_extension</PhoneExtension>
            </StructuredPhoneNumber>
         </PhoneNumber>
         <ShipperNumber>$shipper_number</ShipperNumber>
         <Address>
            <AddressLine1>$shipper_address_1</AddressLine1>
            <AddressLine2>$shipper_address_2</AddressLine2>
            <AddressLine3>$shipper_address_3</AddressLine3>
            <City>$shipper_city</City>
            <StateProvinceCode>$shipper_state_province_code</StateProvinceCode>
            <PostalCode>$shipper_postal_code</PostalCode>
            <CountryCode>$shipper_country_code</CountryCode>
         </Address>
      </Shipper>
      <ShipTo>
         <CompanyName>$ship_to[company_name]</CompanyName>
         <AttentionName>$ship_to[attn_name]</AttentionName>
         <PhoneNumber>
            <StructuredPhoneNumber>
               <PhoneDialPlanNumber>$ship_to[phone_dial_plan_number]</PhoneDialPlanNumber>
               <PhoneLineNumber>$ship_to[phone_line_number]</PhoneLineNumber>
               <PhoneExtension>$ship_to[phone_extension]</PhoneExtension>
            </StructuredPhoneNumber>
         </PhoneNumber>
         <Address>
            <AddressLine1>$ship_to[address_1]</AddressLine1>
            <AddressLine2>$ship_to[address_2]</AddressLine2>
            <AddressLine3>$ship_to[address_3]</AddressLine3>
            <City>$ship_to[city]</City>
            <StateProvinceCode>$ship_to[state_province_code]</StateProvinceCode>
            <PostalCode>$ship_to[postal_code]</PostalCode>
            <CountryCode>$ship_to[country_code]</CountryCode>
            <ResidentialAddress/>
         </Address>
      </ShipTo>
      <ShipFrom>
         <CompanyName>$ship_from[company_name]</CompanyName>
         <AttentionName>$ship_from[attn_name]</AttentionName>
         <PhoneNumber>
            <StructuredPhoneNumber>
               <PhoneDialPlanNumber>$ship_from[phone_dial_plan_number]</PhoneDialPlanNumber>
               <PhoneLineNumber>$ship_from[phone_line_number]</PhoneLineNumber>
               <PhoneExtension>$ship_from[phone_extension]</PhoneExtension>
            </StructuredPhoneNumber>
         </PhoneNumber>
         <Address>
            <AddressLine1>$ship_from[address_1]</AddressLine1>
            <AddressLine2>$ship_from[address_2]</AddressLine2>
            <AddressLine3>$ship_from[address_3]</AddressLine3>
            <City>$ship_from[city]</City>
            <StateProvinceCode>$ship_from[state_province_code]</StateProvinceCode>
            <PostalCode>$ship_from[postal_code]</PostalCode>
            <CountryCode>$ship_from[country_code]</CountryCode>
         </Address>
      </ShipFrom>
      <PaymentInformation>
         <Prepaid>
            <BillShipper>
               <AccountNumber>$shipment[bill_shipper_account_number]</AccountNumber>
            </BillShipper>
         </Prepaid>
      </PaymentInformation>
      <Service>
         <Code>$shipment[service_code]</Code>
      </Service>
      <Package>
         <PackagingType>
            <Code>$shipment[packaging_type]</Code>
         </PackagingType>
         <Dimensions>
         	<UnitOfMeasurement>
         		<Code>IN</Code>
         	</UnitOfMeasurement>
         	<Length>$shipment[length]</Length>
         	<Width>$shipment[width]</Width>
         	<Height>$shipment[height]</Height>
         </Dimensions>
     
         <PackageWeight>
            <UnitOfMeasurement>
               <Code>LBS</Code>
            </UnitOfMeasurement>
            <Weight>$shipment[weight]</Weight>
         </PackageWeight>
  
      </Package>
   </Shipment>
</ShipmentConfirmRequest>";
		
		//		p($xml_request);die;
		

		// execute the curl function and return the result document to $result
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $post_url );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, "$xml_request" );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$xml_result = curl_exec ( $ch );
		curl_close ( $ch );
		
		$data = $this->parse_xml ( $xml_result );
		
		$result = array ();
		if ($data ["ShipmentConfirmResponse"] ["#"] ["Response"] [0] ["#"] ["ResponseStatusCode"] [0] ["#"] == 1) {
			$result ["total_charges"] = $data ["ShipmentConfirmResponse"] ["#"] ["ShipmentCharges"] [0] ["#"] ["TotalCharges"] [0] ["#"] ["MonetaryValue"] [0] ["#"];
		} else {
			$result ["error_description"] = $data ["ShipmentConfirmResponse"] ["#"] ["Response"] [0] ["#"] ["Error"] [0] ["#"] ["ErrorDescription"] [0] ["#"];
		
		}
		if (! array_key_exists ( 'error_description', $result )) {
			//echo number_format($result["total_charges"],2);
			return number_format ( $result ["total_charges"], 2 );
		} else {
			echo "<div style='color:red'>{$result["error_description"]}</div>";
			exit ();
		}*/
	
	}
	
	function parse_xml($data) {
		
		$vals = $index = $array = array ();
		$parser = xml_parser_create ();
		xml_parser_set_option ( $parser, XML_OPTION_CASE_FOLDING, 0 );
		xml_parser_set_option ( $parser, XML_OPTION_SKIP_WHITE, 1 );
		xml_parse_into_struct ( $parser, $data, $vals, $index );
		xml_parser_free ( $parser );
		
		$i = 0;
		
		if (isset ( $vals [$i] ['tag'] )) {
			$tagname = $vals [$i] ['tag'];
			if (isset ( $vals [$i] ["attributes"] )) {
				$array [$tagname] ["@"] = $vals [$i] ["attributes"];
			}
			
			$array [$tagname] ["#"] = $this->xml_get_depth ( $vals, $i );
		}
		return $array;
	}
	
	function xml_get_depth($vals, &$i) {
		$children = array ();
		if (isset ( $vals [$i] ['value'] ))
			array_push ( $children, $vals [$i] ['value'] );
		
		while ( ++ $i < count ( $vals ) ) {
			
			switch ($vals [$i] ['type']) {
				
				case 'cdata' :
					array_push ( $children, $vals [$i] ['value'] );
					break;
				
				case 'complete' :
					$tagname = $vals [$i] ['tag'];
					if (isset ( $children ["$tagname"] )) {
						$size = sizeof ( $children ["$tagname"] );
					} else {
						$size = 0;
					}
					
					if (isset ( $vals [$i] ['value'] )) {
						$children [$tagname] [$size] ["#"] = $vals [$i] ['value'];
					}
					if (isset ( $vals [$i] ["attributes"] )) {
						$children [$tagname] [$size] ["@"] = $vals [$i] ["attributes"];
					}
					break;
				
				case 'open' :
					$tagname = $vals [$i] ['tag'];
					if (isset ( $children ["$tagname"] )) {
						$size = sizeof ( $children ["$tagname"] );
					} else {
						$size = 0;
					}
					if (isset ( $vals [$i] ["attributes"] )) {
						$children ["$tagname"] [$size] ["@"] = $vals [$i] ["attributes"];
						$children ["$tagname"] [$size] ["#"] = $this->xml_get_depth ( $vals, $i );
					} else {
						$children ["$tagname"] [$size] ["#"] = $this->xml_get_depth ( $vals, $i );
					}
					break;
				
				case 'close' :
					return $children;
					break;
			}
		
		}
		
		return $children;
	
	}
	
	/**

	 * @desc get shipping dimensions

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function shippingGetOrderPackageSize() {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		$session_id = CI::library('session')->userdata ( 'session_id' );
		$q = "select * from $table where sid='$session_id' and order_completed='n'";
		
		$q = CI::model('core')->dbQuery ( $q );
		
		$weight = false;
		$height = false;
		$width = false;
		$height = false;
		// var_dump(	$q);
		if (! empty ( $q )) {
			foreach ( $q as $item ) {
				$weight = $weight + (floatval ( $item ['weight'] ) * $item ['qty']);
				$height = $height + (floatval ( $item ['height'] ) * $item ['qty']);
				$length = $length + (floatval ( $item ['length'] ) * $item ['qty']);
				$width = $width + (floatval ( $item ['width'] ) * $item ['qty']);
			}
		} else {
			return false;
		}
		
		$temp = array ();
		$temp ['weight'] = $weight;
		$temp ['height'] = $height;
		$temp ['length'] = $length;
		$temp ['width'] = $width;
		
		return $temp;
	
	}
	
	/**

	 * @desc get shipping costs by criteria

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function shippingCostsGet($data = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = true) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart_orders_shipping_cost'];
		
		if (empty ( $orderby )) {
			
			$orderby [0] = 'updated_on';
			
			$orderby [1] = 'DESC';
		
		}
		
		$get = CI::model('core')->getDbData ( $table, $data, $limit = $limit, $offset = $offset, $orderby = $orderby, $cache_group = $cache_group, $debug = $debug, $ids = $ids, $count_only = $count_only, $only_those_fields = $only_those_fields, $exclude_ids = $exclude_ids, $force_cache_id = $force_cache_id, $get_only_whats_requested_without_additional_stuff = $get_only_whats_requested_without_additional_stuff );
		
		return $get;
	
	}
	
	/**

	 * @desc save the promo code

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function shippingCostsSave($data) {
		
		global $cms_db_tables;
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		$table = $cms_db_tables ['table_cart_orders_shipping_cost'];
		
		if (strval ( trim ( $data ['ship_to_continent'] ) ) != '') {
			
			$q = "delete from $table where ship_to_continent like '{$data['ship_to_continent']}' ";
			
			CI::model('core')->dbQ ( $q );
			
			$data ['id'] = 0;
		
		}
		
		$data_to_save_options ['delete_cache_groups'] = array ('cart' );
		
		$id = CI::model('core')->saveData ( $table, $data, $data_to_save_options );
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
	
	}
	
	/**

	 * @desc deletes shipping Costs by id

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function shippingCostsDeleteById($id) {
		
		$this->itemsCleanupOldAddedToCartItemsWhereTheOrderWasNeverCompleted ();
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart_orders_shipping_cost'];
		
		$id = intval ( $id );
		
		if ($id == 0) {
			
			return false;
		
		}
		
		$q = " delete from $table where id = $id ";
		
		CI::model('core')->dbQ ( $q );
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		return true;
	
	}
	
	/**

	 * @desc shipping  Get Active Continents

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function shippingGetActiveContinents() {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart_orders_shipping_cost'];
		
		$q = " select ship_to_continent from $table where is_active='y' ";
		
		$q = CI::model('core')->dbQuery ( $q );
		
		$q1 = array ();
		
		foreach ( $q as $item ) {
			
			$q1 [] = $item ['ship_to_continent'];
		
		}
		
		return $q1;
	
	}
	
	/**
	 * @desc billingCheckCreditCard
	 * @author		Peter Ivanov
	 * @version 1.0
	 * @since Version 1.0
	 */
	
	function billingProcessCreditCard($make_transaction = false) {
		global $cms_db_tables;
		
		$shop_transaction_method = CI::model('core')->optionsGetByKey ( 'shop_transaction_method' );
		if ($shop_transaction_method == false) {
			//	return false;
		}
		
		$shop_transaction_method_user_id = CI::model('core')->optionsGetByKey ( 'shop_transaction_method_user_id' );
		if ($shop_transaction_method_user_id == false) {
			return false;
		}
		
		$shop_transaction_method_username = CI::model('core')->optionsGetByKey ( 'shop_transaction_method_username' );
		if ($shop_transaction_method_username == false) {
			return false;
		}
		
		$shop_transaction_method_password = CI::model('core')->optionsGetByKey ( 'shop_transaction_method_password' );
		if ($shop_transaction_method_password == false) {
			return false;
		}
		
		$shop_transaction_method_merchantaccountid = CI::model('core')->optionsGetByKey ( 'shop_transaction_method_merchantaccountid' );
		if ($shop_transaction_method_merchantaccountid == false) {
			return false;
		}
		
		$billing_cvv2 = CI::library('session')->userdata ( 'billing_cvv2' );
		if ($billing_cvv2 == false) {
			return false;
		}
		$formdata = array ();
		
		//p($this->payPalTest());
		

		$formdata ['clientid'] = $shop_transaction_method_user_id;
		$formdata ['merchantaccountid'] = $shop_transaction_method_merchantaccountid;
		$formdata ['transactiontype'] = 'SALE';
		$formdata ['INDUSTRYTYPE'] = '013'; //ELECTRONIC COMMERCE
		

		$formdata ['customercode'] = CI::library('session')->userdata ( 'session_id' ) . date ( 'ymdHis' );
		
		//			$formdata ['amount'] = CI::library('session')->userdata ( 'amount' ) + (float)(rand(1, 20) / 100);
		$formdata ['amount'] = floatval ( $this->itemsGetTotal ( CI::library('session')->userdata ( 'cart_promo_code' ), CI::library('session')->userdata ( 'shop_currency' ) ) ) + ( float ) (rand ( 1, 20 ) / 100);
		$paidAmount = $formdata ['amount'];
		
		$formdata ['orderno'] = CI::library('session')->userdata ( 'order_id' );
		//			$formdata ['order_id'] = CI::library('session')->userdata ( 'order_id' );
		$formdata ['cardholdernumber'] = CI::library('session')->userdata ( 'billing_cardholdernumber' );
		
		///!Important AUTHORIZATIONNUMBER go only with FORCE/TICKET
		//$formdata['AUTHORIZATIONNUMBER'] = $formdata ['customercode'];
		

		$formdata ['expiresmonth'] = CI::library('session')->userdata ( 'billing_expiresmonth' );
		$formdata ['expiresyear'] = CI::library('session')->userdata ( 'billing_expiresyear' );
		//			$formdata ['ccv2'] = false;
		$formdata ['bname'] = CI::library('session')->userdata ( 'billing_first_name' ) . ' ' . CI::library('session')->userdata ( 'billing_last_name' );
		$formdata ['bemailaddress'] = CI::library('session')->userdata ( 'billing_user_email' );
		$formdata ['baddress1'] = CI::library('session')->userdata ( 'billing_address' );
		$formdata ['bcity'] = CI::library('session')->userdata ( 'billing_city' );
		$formdata ['bstate'] = CI::library('session')->userdata ( 'billing_state' );
		$formdata ['bzipcode'] = CI::library('session')->userdata ( 'billing_zip' );
		$formdata ['bcountry'] = CI::library('session')->userdata ( 'billing_country' );
		$formdata ['bphone'] = CI::library('session')->userdata ( 'billing_user_phone' );
		
		$formdata ['sname'] = CI::library('session')->userdata ( 'shipping_first_name' ) . ' ' . CI::library('session')->userdata ( 'shipping_last_name' );
		$formdata ['scompany'] = CI::library('session')->userdata ( 'shipping_company_name' );
		$formdata ['saddress1'] = CI::library('session')->userdata ( 'shipping_address' );
		$formdata ['saddress2'] = CI::library('session')->userdata ( 'shipping_city' ) . ', ' . CI::library('session')->userdata ( 'shipping_zip' );
		$formdata ['scity'] = CI::library('session')->userdata ( 'shipping_city' );
		$formdata ['sstate'] = CI::library('session')->userdata ( 'shipping_state' );
		$formdata ['szipcode'] = CI::library('session')->userdata ( 'shipping_zip' );
		$formdata ['sphone'] = CI::library('session')->userdata ( 'shipping_user_phone' );
		$formdata ['scountry'] = CI::library('session')->userdata ( 'shipping_state' );
		$formdata ['promo_code'] = CI::library('session')->userdata ( 'cart_promo_code' );
		$formdata ['shipping_total_charges'] = CI::library('session')->userdata ( 'shipping_total_charges' );
		$formdata ['shipping_service'] = CI::library('session')->userdata ( 'shipping_service' );
		$formdata ['semailaddress'] = CI::library('session')->userdata ( 'shipping_user_email' );
		
		//			file_put_contents('stest.txt', serialize($formdata));
		//	p($formdata, 1);
		

		if (function_exists ( "curl_init" )) {
			//$ckfile = tempnam ("/tmp", "CURLCOOKIE");
			/*~~~~~~~~~ Pay pal payment ~~~~~~~~~~*/
			$userData = array ();
			$userData ['payment_type'] = 'Sale';
			$userData ['first_name'] = CI::library('session')->userdata ( 'billing_first_name' );
			$userData ['last_name'] = CI::library('session')->userdata ( 'billing_last_name' );
			$userData ['credit_card_type'] = CI::library('session')->userdata ( 'credit_card_type' );
			$userData ['credit_card_number'] = str_replace ( ' ', '', $formdata ['cardholdernumber'] );
			$userData ['exp_date_month'] = str_pad ( $formdata ['expiresmonth'], 2, '0', STR_PAD_LEFT ); // Month must be padded with leading zero
			$userData ['exp_date_year'] = $formdata ['expiresyear'];
			
			//$userData ['from_date_month'] = str_pad ( CI::library('session')->userdata ( 'billing_validfrommonth' ), 2, '0', STR_PAD_LEFT ); // Month must be padded with leading zero
			//$userData ['from_date_year'] = CI::library('session')->userdata ( 'billing_validfromyear' );
			

			$userData ['cvv2'] = CI::library('session')->userdata ( 'billing_cvv2' );
			$userData ['address'] = $formdata ['baddress1'];
			$userData ['city'] = $formdata ['bcity'];
			$userData ['state'] = $formdata ['bstate'];
			$userData ['zip'] = $formdata ['bzipcode'];
			$userData ['country'] = $formdata ['bcountry'];
			$userData ['amount'] = floatval ( $formdata ['amount'] ) + floatval ( $formdata ['shipping_total_charges'] );
			$userData ['currency_id'] = 'USD';
			
			//XXX: This is debug case. Remove it on production only from OOYES IP
			if ($_SERVER ['REMOTE_ADDR'] == '77.70.8.202') {
				$userData ['amount'] = '1.01';
			}
			
			//var_dump ( $userData  );
			//exit ();
			

			$nvpStr = $this->_payPalFormatNVP ( $userData );
			//var_dump($nvpStr);
			// Execute the API operation; see the PPHttpPost function above.
			$httpParsedResponseAr = $this->_payPalHttpPost ( 'DoDirectPayment', $nvpStr );
			// var_dump($httpParsedResponseAr);
			// exit;
			foreach ( $httpParsedResponseAr as $key => $val ) {
				$httpParsedResponseAr [$key] = urldecode ( $val );
			}
			
			if ("SUCCESS" == strtoupper ( $httpParsedResponseAr ["ACK"] ) || "SUCCESSWITHWARNING" == strtoupper ( $httpParsedResponseAr ["ACK"] )) {
				
				$formdata ['referrer_id'] = $_COOKIE ['referrer_id'];
				//	$formdata ['to_table_id'] = $_COOKIE['group_id'];
				//Delete ccv2			
				$formdata ['ccv2'] = false;
				$formdata ['order_id'] = $formdata ['orderno'];
				
				$formdata ['order_completed'] = 'y';
				$formdata ['payment_completed'] = 'y';
				/////////$this->orderPlace($formdata);
				

				//Unset Card Data
				CI::library('session')->set_userdata ( 'billing_cvv2', '' );
				CI::library('session')->set_userdata ( 'billing_cardholdernumber', '' );
				
				//Set Affiliate
				//$countries = $this->paymentmethods_tpro_get_countries_list_as_array ();
				//$formdata ['country'] = $countries [$formdata ['bcountry']];
				$formdata ['country_id'] = $formdata ['bcountry'];
				$formdata ['payment_response'] = serialize ( $httpParsedResponseAr );
				
				$formdata ['transactionid'] = $httpParsedResponseAr ['TRANSACTIONID'];
				
				if (isset ( $formdata ['transactionid'] )) {
					$this->orderPlace ( $formdata );
					$formdata ['from_log'] = $_COOKIE ['from_log'];
					
					CI::library('session')->set_userdata ( 'billing_cvv2', '' );
					CI::library('session')->set_userdata ( 'billing_cardholdernumber', '' );
					
					/*
						$userdata ['id'] = $formdata ['referrer_id'];
						$parent = CI::model('users')->getUsers ( $userdata );
						
						$opt = array ();
						$opt ['email'] = $formdata ['bemailaddress'];
						$opt ['name'] = $formdata ['bname'];
						
						$opt ['order_id'] = $formdata ['order_id'];
						$opt ['shop'] = $items;
						$opt ['total'] = $paidAmount + $formdata ['shipping_total_charges'];
						$opt ['charge'] = $formdata ['shipping_total_charges'];
						
						$this->sendMailToBilling ( $opt, true );
						if ($formdata ['semailaddress'] && ($formdata ['bemailaddress'] != $formdata ['semailaddress'])) {
							$opt ['email'] = $formdata ['semailaddress'];
							$this->sendMailToBilling ( $opt, true );
						} */
					CI::library('session')->set_userdata ( 'order_id', 'WFL' . date ( 'ymdHis' ) . rand () );
					
					return true;
				} else {
					return array('error' => $httpParsedResponseAr["L_LONGMESSAGE0"]);
					
					#nav li.active a:first
					
					//return false;
				}
				
			////////////////////////
			/////////Call Microweaber
			

			} else {
				//Unset Card Data
				return array('error' => $httpParsedResponseAr["L_LONGMESSAGE0"]);

				//return $httpParsedResponseAr['L_LONGMESSAGE0'];
				//return $httpParsedResponseAr;
			}
			
		/*~~~~~~~~~ Mbs payment ~~~~~~~~~~*/
		
		/*$formdata ['ccv2'] = false;
					
					//build the post string
					$poststring = '';
					foreach ( $formdata as $key => $val ) {
						$poststring .= urlencode ( $key ) . "=" . urlencode ( mb_trim ( $val ) ) . "&";
					}
					//echo "Poststring: ".$poststring."\n";
					// strip off trailing ampersand
					$poststring = substr ( $poststring, 0, - 1 );
					
					$url = "https://gateway.mbs-us.com/ccgateway.asp";
					$ch = curl_init ();
					
					$ckfile = (CACHEDIR . date ( "Y-m-d-h-i-s" ) . rand () . "cookie.txt");
					//				curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
					curl_setopt ( $ch, CURLOPT_COOKIEJAR, $ckfile );
					curl_setopt ( $ch, CURLOPT_COOKIESESSION, TRUE );
					curl_setopt ( $ch, CURLOPT_FRESH_CONNECT, TRUE );
					
					//curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
					

					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to post to 
					curl_setopt ( $ch, CURLOPT_FAILONERROR, 0 );
					curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 ); // allow redirects 
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); // return into a variable 
					curl_setopt ( $ch, CURLOPT_TIMEOUT, 60 ); // times out after Ns 
					curl_setopt ( $ch, CURLOPT_POST, 1 ); // set POST method 
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
					//$params .= "CLIENTID=$clientid&MERCHANTACCOUNTID=$mid&CARDHOLDERNUMBER=$cardholdernumber";
					//$params .= "&EXPIRESMONTH=$expmo&EXPIRESYEAR=$expyear&TRANSACTIONTYPE=$trantype";
					$params = $poststring;
					// add POST fields
					curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params );
					
					curl_setopt ( $ch, CURLOPT_FAILONERROR, 0 );
					curl_setopt ( $ch, CURLOPT_VERBOSE, 0 );
					curl_setopt ( $ch, CURLOPT_HEADER, 0 );
					//curl_setopt ( $ch, CURLOPT_COOKIEFILE, 1 );
					curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
					
					$result = curl_exec ( $ch ); // run the whole process 
					curl_close ( $ch );
					
					if (preg_match ( '/APPROVED=N/i', $result, $res_buf )) {
						//Unset Card Data
						CI::library('session')->set_userdata ( 'billing_cvv2', '' );
						CI::library('session')->set_userdata ( 'billing_cardholdernumber', '' );
						return $result;
					} else {
						$formdata ['referrer_id'] = $_COOKIE ['referrer_id'];
						//	$formdata ['to_table_id'] = $_COOKIE['group_id'];
						//Delete ccv2			
						$formdata ['ccv2'] = false;
						$formdata ['order_id'] = $formdata ['orderno'];
						
						$formdata ['order_completed'] = 'y';
						$formdata ['payment_completed'] = 'y';
						/////////$this->orderPlace($formdata);
						

						//Unset Card Data
						CI::library('session')->set_userdata ( 'billing_cvv2', '' );
						CI::library('session')->set_userdata ( 'billing_cardholdernumber', '' );
						
						//Set Affiliate
						$countries = $this->paymentmethods_tpro_get_countries_list_as_array ();
						$formdata ['country'] = $countries [$formdata ['bcountry']];
						$formdata ['country_id'] = $formdata ['bcountry'];
						
						$res_buf = array ();
						$res_buf = explode ( '&', $result );
						foreach ( $res_buf as $key => $val ) {
							if (($pos = strpos ( $val, 'SEQNO=' )) !== false) {
								$formdata ['transactionid'] = substr ( $val, 6 );
							}
						}
						
						if (isset ( $formdata ['transactionid'] )) {
							$this->orderPlace ( $formdata );
							$formdata ['from_log'] = $_COOKIE ['from_log'];
							
							$sql = "SELECT * FROM {$cms_db_tables['table_cart']} WHERE sid='{CI::library('session')->userdata ( 'session_id' )}' AND order_id='{$formdata ['order_id']}' ";
							$items = CI::model('core')->dbQuery ( $sql );
							
							for($i = 0; $i < count ( $items ); $i ++) {
								
								$formdata ['to_table_id'] = $items [$i] ['to_table_id'];
								$formdata ['amount'] = intval ( $items [$i] ['qty'] ) * intval ( $items [$i] ['price'] );
								$formdata ['sku'] = $items [$i] ['sku'];
								
								$poststring = '';
								foreach ( $formdata as $key => $val ) {
									$poststring .= urlencode ( $key ) . "=" . urlencode ( mb_trim ( $val ) ) . "&";
								}
								reset ( $formdata );
								
								//$ckfile = tempnam ("/tmp", "CURLCOOKIE2"); 
								

								$url = site_url ( '/affiliate_center/callbacks/callback_microweber.php' );
								$ch = curl_init ( $url );
								
								//curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile);
								//curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
								

								curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to post to 
								curl_setopt ( $ch, CURLOPT_FAILONERROR, 0 );
								curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 ); // allow redirects 
								curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); // return into a variable 
								curl_setopt ( $ch, CURLOPT_TIMEOUT, 60 ); // times out after Ns 
								curl_setopt ( $ch, CURLOPT_POST, 1 ); // set POST method 
								curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
								
								curl_setopt ( $ch, CURLOPT_FAILONERROR, 0 );
								curl_setopt ( $ch, CURLOPT_VERBOSE, 0 );
								curl_setopt ( $ch, CURLOPT_HEADER, 0 );
								//curl_setopt ( $ch, CURLOPT_COOKIEFILE, 1 );
								curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
								
								curl_setopt ( $ch, CURLOPT_POSTFIELDS, $poststring );
								$result = curl_exec ( $ch ); // run the whole process 
								

								curl_close ( $ch );
								//echo 'MSG=';
							//p($result);
							///////////////////////	
							

							}
							$userdata ['id'] = $formdata ['referrer_id'];
							$parent = CI::model('users')->getUsers ( $userdata );
							
							$opt = array ();
							$opt ['email'] = $formdata ['bemailaddress'];
							$opt ['name'] = $formdata ['bname'];
							
							$opt ['order_id'] = $formdata ['order_id'];
							$opt ['shop'] = $items;
							$opt ['total'] = $paidAmount + $formdata ['shipping_total_charges'];
							$opt ['charge'] = $formdata ['shipping_total_charges'];
							
							$this->sendMailToBilling ( $opt, true );
							if ($formdata ['semailaddress'] && ($formdata ['bemailaddress'] != $formdata ['semailaddress'])) {
								$opt ['email'] = $formdata ['semailaddress'];
								$this->sendMailToBilling ( $opt, true );
							}
							CI::library('session')->set_userdata ( 'order_id', 'WFL' . date ( 'ymdHis' ) . rand () );
							return true;
						} else {
							
							return false;
						}
						
					////////////////////////
					/////////Call Microweaber
					

					}*/
		
		} else {
			exit ( 'Error: You need the CURL library on your server in order to run payment processing functions' );
		
		}
	
	}
	
	function payPalCardTypes() {
		return array ('Visa' => 'Visa', 'MasterCard' => 'MasterCard', 'Discover' => 'Discover', 'Amex' => 'Amex', 'Maestro' => 'Maestro' ); //'Solo' => 'Solo',
	

	}
	
	 function _payPalFormatNVP($userData) {
		
		if ($userData ['credit_card_number']) {
			$userData ['credit_card_number'] = str_replace ( ' ', '', $userData ['credit_card_number'] );
			$userData ['credit_card_number'] = str_replace ( '-', '', $userData ['credit_card_number'] );
		
		}
		
		foreach ( $userData as $k => $v ) {
			
			$userData [$k] = urlencode ( $v );
		}
		
		// Add request-specific fields to the request string.
		

		//		//full user data
		//		$nvpStr =	"&PAYMENTACTION={$userData['payment_type']}".
		//					"&AMT={$userData['amount']}".
		//					"&CREDITCARDTYPE={$userData['credit_card_type']}".
		//					"&ACCT={$userData['credit_card_number']}".
		//					"&EXPDATE={$userData['exp_date_month']}{$userData['exp_date_year']}".
		//					"&CVV2={$userData['cvv2']}".
		//					"&FIRSTNAME={$userData['first_name']}".
		//					"&LASTNAME={$userData['last_name']}".
		//					"&STREET={$userData['address']}".
		//					"&CITY={$userData['city']}".
		//					"&STATE={$userData['state']}".
		//					"&ZIP={$userData['zip']}".
		//					"&COUNTRYCODE={$userData['country']}".
		//					"&CURRENCYCODE={$userData['currency_id']}";
		

		//less user data
		$nvpStr = "&PAYMENTACTION={$userData['payment_type']}" . "&AMT={$userData['amount']}" . //"&FIRSTNAME={$userData['first_name']}".
//	"&LASTNAME={$userData['last_name']}".
		"&CREDITCARDTYPE={$userData['credit_card_type']}" . "&FIRSTNAME={$userData['first_name']}" . "&LASTNAME={$userData['last_name']}" . "&ACCT={$userData['credit_card_number']}" . "&EXPDATE={$userData['exp_date_month']}{$userData['exp_date_year']}" . "&CVV2={$userData['cvv2']}" . 

		//"&STREET={$userData['address']}".
		//"&CITY={$userData['city']}".
		//"&STATE={$userData['state']}".
		//"&ZIP={$userData['zip']}".
		//"&COUNTRYCODE={$userData['country']}". 
		//"&IPADDRESS={$_SERVER['REMOTE_ADDR']}". 
		

		"&CURRENCYCODE={$userData['currency_id']}";
		
		return $nvpStr;
	
	}
	
	/**
	 * Send HTTP POST Request
	 *
	 * @param	string	The API method name
	 * @param	string	The POST Message fields in &name=value pair format
	 * @return	array	Parsed HTTP Response body
	 */
	 function _payPalHttpPost($methodName_, $nvpStr_) {
		$environment = CI::model('core')->optionsGetByKey ( 'paypal_environment' ); // 'sandbox', 'beta-sandbox' or 'live'
		

		// Set up your API credentials, PayPal end point, and API version.
		$API_UserName = urlencode ( CI::model('core')->optionsGetByKey ( 'paypal_api_username' ) );
		$API_Password = urlencode ( CI::model('core')->optionsGetByKey ( 'paypal_api_password' ) );
		$API_Signature = urlencode ( CI::model('core')->optionsGetByKey ( 'paypal_api_signature' ) );
		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		if ("sandbox" === $environment || "beta-sandbox" === $environment) {
			$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
		}
		$version = urlencode ( '51.0' );
		//var_dump($API_Endpoint);
		// Set the curl parameters.
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $API_Endpoint );
		curl_setopt ( $ch, CURLOPT_VERBOSE, 1 );
		
		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
		curl_setopt ( $ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)" );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		$IPADDRESS = urlencode ( $_SERVER ['REMOTE_ADDR'] );
		// Set the API operation, version, and API signature in the request.
		$nvpreq = "METHOD=$methodName_&IPADDRESS=$IPADDRESS&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
		
		// Set the request as a POST FIELD for curl.
		//var_dump( $nvpreq);
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $nvpreq );
		
		// Get response from the server.
		$httpResponse = curl_exec ( $ch );
		//var_dump($httpResponse);
		if (! $httpResponse) {
			exit ( "$methodName_ failed: " . curl_error ( $ch ) . '(' . curl_errno ( $ch ) . ')' );
		}
		
		curl_close ( $ch );
		
		// Extract the response details.
		$httpResponseAr = explode ( "&", $httpResponse );
		
		$httpParsedResponseAr = array ();
		foreach ( $httpResponseAr as $i => $value ) {
			$tmpAr = explode ( "=", $value );
			if (sizeof ( $tmpAr ) > 1) {
				$httpParsedResponseAr [$tmpAr [0]] = $tmpAr [1];
			}
		}
		
		if ((0 == sizeof ( $httpParsedResponseAr )) || ! array_key_exists ( 'ACK', $httpParsedResponseAr )) {
			exit ( "Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint." );
		}
		
		return $httpParsedResponseAr;
	
	}
	
	public function payPalTest() {
		$userData = array ();
		
		$userData ['payment_type'] = 'Sale';
		$userData ['first_name'] = 'Peter';
		$userData ['last_name'] = 'Ivanov';
		$userData ['credit_card_type'] = 'Visa';
		$userData ['credit_card_number'] = '4116530132589606';
		$userData ['exp_date_month'] = str_pad ( '1', 2, '0', STR_PAD_LEFT ); // Month must be padded with leading zero
		$userData ['exp_date_year'] = '2012';
		
		$userData ['from_date_month'] = str_pad ( '1', 2, '0', STR_PAD_LEFT ); // Month must be padded with leading zero
		$userData ['from_date_year'] = '2010';
		
		$userData ['cvv2'] = '835';
		$userData ['address'] = 'Hristo Botev 07';
		$userData ['city'] = 'Kazanlak';
		$userData ['state'] = 'Kazanlak';
		$userData ['zip'] = '6100';
		$userData ['country'] = 'BG';
		$userData ['amount'] = '00.10';
		$userData ['currency_id'] = 'USD';
		$userData ['country'] = 'BG';
		
		$nvpStr = $this->_payPalFormatNVP ( $userData );
		//p($nvpStr);
		

		// Execute the API operation; see the PPHttpPost function above.
		$httpParsedResponseAr = $this->_payPalHttpPost ( 'DoDirectPayment', $nvpStr );
		
		foreach ( $httpParsedResponseAr as $key => $val ) {
			$httpParsedResponseAr [$key] = urldecode ( $val );
		}
		
		$payPalResponse = serialize ( $httpParsedResponseAr );
		//p($payPalResponse);
		

		if ("SUCCESS" == strtoupper ( $httpParsedResponseAr ["ACK"] ) || "SUCCESSWITHWARNING" == strtoupper ( $httpParsedResponseAr ["ACK"] )) {
			echo '<h2>Direct Payment Completed Successfully:</h2><br />';
			$valid = false;
			if ($httpParsedResponseAr ['CVV2MATCH'] == 'M') {
				$valid = true;
			}
			echo '<h4>CVV2 match: ' . ( int ) ($valid) . '</h4>';
			foreach ( $httpParsedResponseAr as $key => $val ) {
				echo $key . ': <b>' . $val . '</b><br />';
			}
			exit ();
		} else {
			echo '<h2>DoDirectPayment failed:</h2><br />';
			foreach ( $httpParsedResponseAr as $key => $val ) {
				echo $key . ': <b>' . $val . '</b><br />';
			}
			exit ();
		}
	
	}
	
	/**

	 * @desc get currencies by criteria
	 * @author		Peter Ivanov
	 * @version 1.0
	 * @since Version 1.0

	 */
	
	function currenciesGet($data = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = true) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart_currency'];
		
		if (empty ( $orderby )) {
			
			$orderby [0] = 'updated_on';
			
			$orderby [1] = 'DESC';
		
		}
		
		$get = CI::model('core')->getDbData ( $table, $data, $limit = $limit, $offset = $offset, $orderby = $orderby, $cache_group = $cache_group, $debug = $debug, $ids = $ids, $count_only = $count_only, $only_those_fields = $only_those_fields, $exclude_ids = $exclude_ids, $force_cache_id = $force_cache_id, $get_only_whats_requested_without_additional_stuff = $get_only_whats_requested_without_additional_stuff );
		
		return $get;
	
	}
	
	/**
	 * @desc save the currency
	 * @author		Peter Ivanov
	 * @version 1.0
	 * @since Version 1.0
	 */
	
	function currencySave($data) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_cart_currency'];
		
		if ((strval ( trim ( $data ['currency_from'] ) ) != '') and (strval ( trim ( $data ['currency_to'] ) ) != '')) {
			
			$q = "delete from $table where currency_from like '{$data['currency_from']}' and currency_to like '{$data['currency_to']}' ";
			
			//var_dump($q );
			CI::model('core')->dbQ ( $q );
			
			$data ['id'] = 0;
		
		}
		//var_dump($data);
		$data_to_save_options ['delete_cache_groups'] = array ('cart' );
		$id = CI::model('core')->saveData ( $table, $data, $data_to_save_options );
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		return true;
	}
	
	/**

	 * @desc deletes curency Costs by id

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function currencyDeleteById($id) {
		
		$this->itemsCleanupOldAddedToCartItemsWhereTheOrderWasNeverCompleted ();
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart_currency'];
		
		$id = intval ( $id );
		
		if ($id == 0) {
			
			return false;
		
		}
		
		$q = " delete from $table where id = $id ";
		
		CI::model('core')->dbQ ( $q );
		
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		
		return true;
	
	}
	
	/**

	 * @desc lets do the exchange

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function currencyExchange($from = "EUR", $to = "EUR") {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_cart_currency'];
		
		if (strtolower ( $from ) == strtolower ( $to )) {
			return 1;
		} else {
			
			$q = "select  currency_rate from $table where currency_from like '$from' and currency_to like '$to' limit 0,1 ";
			$q = CI::model('core')->dbQuery ( $q );
			if (empty ( $q )) {
				$rate = 10;
			} else {
				$q = $q [0] ['currency_rate'];
				$rate = $q;
			}
		
		}
		return $rate;
		/*$cache_file = CACHEDIR . 'currencyExchange_' . md5 ( $from ) . md5 ( $to );

if (is_file ( $cache_file ) == false) {

$cache_file_time = strtotime ( '1984-01-11 07:15' );

} else {

$cache_file_time = filemtime ( $cache_file );

}

//hadcode exchange cause we dont have suitable real time excange service that is free if youk now some please let me know at peter@ooyes.net and info@microweber.com


// note no no its fine! :)


$now = strtotime ( date ( 'Y-m-d H:i:s' ) );

$api_call = $cache_file_time;

$difference = $now - $api_call;

$api_time_seconds = 1800;

if ($difference >= $api_time_seconds) {

$url = "http://www.webservicex.com/CurrencyConvertor.asmx/ConversionRate?FromCurrency=$from&ToCurrency=$to";

$count = CI::model('core')->url_getPage ( $url, $timeout = 60 );
//$count = strval ( $count );
//	$xml = new SimpleXMLElement($count);
//	$count1 = substr ( $count, 0, 16 );
//$xmlobj = simplexml_load_string($count);
//print_r($xmlobj->xpath("//*[level='1']"));
//var_dump($xmlobj->xpath("//0"));
//print_r($xmlobj->xpath("/0"));
//$catArray = xml2array($xml);
$dom = new domDocument ( );
$dom->loadXML ( $count );
if (! $dom) {
//  echo 'Error while parsing the document';
//exit;
}
$s = makeThisXMLtoArray ( $dom );
$s = $s ['#document'] ['double'];
//echo $s->book[0]->title;


$count = $s;
//var_dump ( 'aaaaa' );
//var_dump ( $s );


//	$what = $xml->double[0];
//$count = explode('>',$count);
//var_dump($count);
//$count = $count[1];
//$count = explode('<',$count);
//var_dump($count);
//$count = $count[0];
//$count


//


//var_dump($what);
//exit;
if (is_file ( $cache_file ) == true) {

@unlink ( $cache_file );

}

touch ( $cache_file );

file_put_contents ( $cache_file, ($count) );

return ($count);

} else {

$count = file_get_contents ( $cache_file );

return ($count);

}*/
	
	}
	
	function currencyChangeSessionData($to = "EUR", $sign = '&euro;', $code = 'EUR') {
		
		switch ($to) {
			
			case "EUR" :
				CI::library('session')->set_userdata ( 'shop_currency', $to );
				CI::library('session')->set_userdata ( 'shop_currency_sign', '&euro;' );
				CI::library('session')->set_userdata ( 'shop_currency_code', 'EUR' );
				break;
			
			case "USD" :
				CI::library('session')->set_userdata ( 'shop_currency', $to );
				CI::library('session')->set_userdata ( 'shop_currency_sign', '$' );
				CI::library('session')->set_userdata ( 'shop_currency_code', 'USD' );
				break;
			
			case "GBP" :
				CI::library('session')->set_userdata ( 'shop_currency', $to );
				CI::library('session')->set_userdata ( 'shop_currency_sign', '&pound;' );
				CI::library('session')->set_userdata ( 'shop_currency_code', 'GBP' );
				break;
			
			default :
				CI::library('session')->set_userdata ( 'shop_currency', $to );
				CI::library('session')->set_userdata ( 'shop_currency_sign', $sign );
				CI::library('session')->set_userdata ( 'shop_currency_code', $code );
				break;
		
		}
	
	}
	
	function currencyConvertPrice($price, $currency = 'EUR') {
		global $cms_db;
		return $price;
		if ($currency == 'EUR') {
			
			return $price;
		
		} else {
			
			$rate_please_work = $this->currencyExchange ( 'EUR', $currency );
			//	var_dump($rate_please_work);
			$rate = $rate_please_work;
			
			$rate = (floatval ( $rate ));
			//	var_dump ( $rate );
			$price = floatval ( $price );
			//	var_dump($price);
			$english_format_number = number_format ( ($rate * $price), 2, '.', '' );
			
			return ($english_format_number);
			
		//fuck this doesnt work????????????????????
		

		//$price1 = (( str2num ( $price)) * ( str2num ( $rate  )));
		

		//$rate = str_replace('.',',',$rate);
		//var_dump ( $rate );
		

		//$price = number_format($price, 2, '.', '');
		

		//$rate = number_format($rate, 2, '.', '');
		//print ($rate * $price);
		////settype ( $price, "double" ); // $foo is now 5   (integer)
		

		//settype ( $rate_please_work, "double" ); // $foo is now 5   (integer)
		

		//var_dump ( $price );
		

		//var_dump ( $rate );
		

		//	array ('host' => DBHOSTNAME, 'username' => DBUSERNAME, 'password' => DBPASSWORD, 'dbname' => DBDATABASE, 'options' => $cms_db_options )
		//$q = "SELECT (CAST('$price' AS signed) * CAST('$rate' AS signed)) as test_pls ";
		//var_dump ( $q );
		//$link = mysql_connect ( DBHOSTNAME, DBUSERNAME, DBPASSWORD );
		//if (! $link) {
		//die ( 'Could not connect: ' . mysql_error () );
		//}
		//echo 'Connected successfully';
		

		//mysql_select_db ( DBDATABASE, $link ) or die ( mysql_error () );
		

		// Retrieve all the data from the "example" table
		//$result = mysql_query ( $q, $link ) or die ( mysql_error () );
		

		// store the record of the "example" table into $row
		//$row = mysql_fetch_array ( $result );
		// Print out the contents of the entry
		

		//var_dump ( $row );
		///?/	echo ": " . $row ['test_pls'];
		

		//mysql_close ( $link );
		

		//print $q;
		//$result = $cms_db->fetchAll($q );
		//	var_dump ( $result );
		//$cms_db
		//$query = CI::db()->query($q);
		//$query = $query->row_array ();
		

		//$q = CI::model('core')->dbQuery ( $q );
		//	$q  = $q [0];
		//var_dump($query);
		//$q = CI::db()->query ( $q );
		//	$q = $q->row_array ();
		//
		//$q = $q [0] ['the_correct_results_that_php_is_bugging_about'];
		

		//	$query = CI::db()->query($q);
		

		//if ($query->num_rows() > 0)
		//{
		//  $row = $query->row();
		//var_dump($row);
		//}
		

		//print $q ;
		

		//	return $q;
		

		}
	
	}
	
	function paymentmethods_tpro_get_countries_list_as_array() {
		$fname = BASEPATH . '/libraries/' . 'tpro_country_codes.txt';
		if (is_file ( $fname )) {
			//$txt = file_get_contents ();
			//var_dump ( $txt );
			

			$filename = $fname;
			$contents = file ( $filename );
			$arr = array ();
			foreach ( $contents as $line ) {
				if ($line != NULL) {
					$line_exploded = explode ( ' ', $line );
					$line_clean = str_ireplace ( $line_exploded [0], '', $line );
					$arr [$line_exploded [0]] = $line_clean;
				}
			}
			return $arr;
		} else {
			return false;
		}
	
	}
	
	function paymentmethods_paypal_get_countries_codes() {
		
		return array ('AF' => 'Afghanistan', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua And Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia And Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Columbia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Cote D\'Ivorie (Ivory Coast)', 'HR' => 'Croatia (Hrvatska)', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'Democratic Republic Of Congo (Zaire)', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TP' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'FX' => 'France, Metropolitan', 'GF' => 'French Guinea', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard And McDonald Islands', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macau', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar (Burma)', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'AN' => 'Netherlands Antilles', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts And Nevis', 'LC' => 'Saint Lucia', 'PM' => 'Saint Pierre And Miquelon', 'VC' => 'Saint Vincent And The Grenadines', 'SM' => 'San Marino', 'ST' => 'Sao Tome And Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovak Republic', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia And South Sandwich Islands', 'KR' => 'South Korea', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard And Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad And Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks And Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'UK' => 'United Kingdom', 'US' => 'United States', 'UM' => 'United States Minor Outlying Islands', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican City (Holy See)', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'VG' => 'Virgin Islands (British)', 'VI' => 'Virgin Islands (US)', 'WF' => 'Wallis And Futuna Islands', 'EH' => 'Western Sahara', 'WS' => 'Western Samoa', 'YE' => 'Yemen', 'YU' => 'Yugoslavia', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe' );
	}
	
	function sendMailToBilling($opt = array(), $return_full = false) {
		if (empty ( $opt )) {
			return false;
		}
		
		$to = $opt ['email'];
		$bccEmail = CI::model('core')->optionsGetByKey ( 'admin_email', false );
		
		$admin_options = CI::model('core')->optionsGetByKey ( 'admin_email', true );
		
		$from = (empty ( $admin_options )) ? 'noreply@ooyes.net' : $admin_options ['option_value'];
		$site = site_url ();
		$object = 'Billing Proccess';
		$total = 0;
		$message = <<<STR
		Hello, <b>{$opt ['name']}</b>!

		 
		Thank you for your order from {$site} . 
		
		Your order id is: <b>{$opt ['order_id']}</b>
		
		Here are your billing proccess details:<br />
		<p>
STR;
		for($i = 0; $i < count ( $opt ['shop'] ); $i ++) {
			$total = $opt ['shop'] [$i] ['price'] * $opt ['shop'] [$i] ['qty'];
			
			$message .= <<<STR
			<p>
			Item name: {$opt['shop'][$i]['item_name']}<br />
			SKU: {$opt['shop'][$i]['sku']}<br /><br /><br />
			Quantity: {$opt['shop'][$i]['qty']}<br />
			Price: {$opt['shop'][$i]['price']}<br />
			<b>Total: $total</b><br /><br />
			
			Size: {$opt['shop'][$i]['size']}<br />
			Height: {$opt['shop'][$i]['height']}<br />
			Width: {$opt['shop'][$i]['width']}<br />
			Length: {$opt['shop'][$i]['length']}<br />
			<p>
STR;
		}
		$message .= <<<STR
		<p> </p>
		<p>
		Shipping Charge:{$opt['charge']}		
		</p>
		<p>
		All Totals:{$opt['total']}		
		</p>
STR;
		@mail ( $to, $object, $message, "From: $from\nReply-To: $from\nContent-Type: text/html;charset=\"windows-1251\"\nContent-Transfer-Encoding: 8bit" );
		@mail ( $bccEmail, $object, $message, "From: $from\nReply-To: $from\nContent-Type: text/html;charset=\"windows-1251\"\nContent-Transfer-Encoding: 8bit" );
	}

}