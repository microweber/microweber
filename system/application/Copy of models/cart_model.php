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
		
		$country_name = CI::library ( 'session' )->userdata ( 'country_name' );
		
		if (strval ( trim ( $country_name ) ) == '') {
			if (! defined ( 'USER_COUNTRY_NAME' )) {
				if (is_file ( BASEPATH . 'libraries/maxmind/geoip.inc' ) == true) {
					include (BASEPATH . 'libraries/maxmind/geoip.inc');
					$handle = geoip_open ( BASEPATH . "libraries/maxmind/GeoIP.dat", GEOIP_STANDARD );
					
					//var_Dump($_SERVER ["REMOTE_ADDR"]);
					if ($_SERVER ["REMOTE_ADDR"] == '::1') {
						$ip = '77.70.8.202';
					} else {
						$ip = $_SERVER ["REMOTE_ADDR"];
					}
					
					$the_user_coutry = geoip_country_code_by_addr ( $handle, $ip );
					$the_user_coutry_name = geoip_country_name_by_addr ( $handle, $ip );
					//var_dump( $the_user_coutry);
					define ( "USER_COUNTRY", $the_user_coutry );
					define ( "USER_COUNTRY_NAME", $the_user_coutry_name );
					geoip_close ( $handle );
				} else {
					//exit('need geo ip');
				}
			}
			//var_dump(USER_COUNTRY);
			

			CI::library ( 'session' )->set_userdata ( 'country_name', USER_COUNTRY_NAME );
		
		}
		//print(USER_COUNTRY);
		//print(USER_COUNTRY_NAME);
		

		$shop_currency = CI::library ( 'session' )->userdata ( 'shop_currency' );
		
		$country_name = CI::library ( 'session' )->userdata ( 'country_name' );
		
		if (strval ( $country_name ) == '') {
			CI::library ( 'session' )->set_userdata ( 'country_name', USER_COUNTRY_NAME );
		}
		
		$shop_currency = CI::library ( 'session' )->userdata ( 'shop_currency' );
		//print $shop_currency;
		if (strval ( $shop_currency ) == '') {
			switch (strtolower ( USER_COUNTRY )) {
				case 'uk' :
					$this->currencyChangeSessionData ( "GBP" );
					break;
				case 'us' :
				case 'usa' :
					$this->currencyChangeSessionData ( "USD" );
					break;
				default :
					$this->currencyChangeSessionData ( "EUR" );
					break;
			
			}
		}
		$shop_currency = CI::library ( 'session' )->userdata ( 'shop_currency' );
		//	print $shop_currency;
	

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
		
		//CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		

		if (empty ( $data )) {
			
			return false;
		
		}
		
		//CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		

		if ($data ['sid'] == false) {
			
			$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
		
		} else {
			$adm = is_admin ();
			if ($adm == true) {
				$session_id = $data ['sid'];
			}
		
		}
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		if (! empty ( $data ['other_info'] )) {
			
			$data ['other_info'] = base64_encode ( serialize ( $data ['other_info'] ) );
		
		}
		
		$data ['sid'] = $session_id;
		if (intval ( $data ['page_id'] ) != 0) {
			$data ['to_table'] = 'table_content';
			$data ['to_table_id'] = intval ( $data ['page_id'] );
		
		}
		
		if (intval ( $data ['content_id'] ) != 0) {
			$data ['to_table'] = 'table_content';
			$data ['to_table_id'] = intval ( $data ['content_id'] );
		}
		
		if (intval ( $data ['post_id'] ) != 0) {
			$data ['to_table'] = 'table_content';
			$data ['to_table_id'] = intval ( $data ['post_id'] );
		}
		
		if (($data ['price']) == false) {
			if (($data ['custom_field_price']) != false) {
				$data ['price'] = $data ['custom_field_price'];
			}
		}
		
		if (($data ['qty']) == false) {
			$data ['qty'] = 1;
		}
		
		if (($data ['item_name']) == false) {
			if ($data ['to_table'] == 'table_content' and $data ['to_table_id'] != false) {
				$content_data = CI::model ( 'content' )->contentGetById ( $data ['to_table_id'] );
				$data ['item_name'] = $content_data ['content_title'];
			}
		}
		
		$data_to_save_options ['delete_cache_groups'] = array ('cart' );
		
		//p ( $data );
		

		$id = CI::model ( 'core' )->saveData ( $table, $data, $data_to_save_options );
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
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
		
		$get = CI::model ( 'core' )->getDbData ( $table, $data, $limit = $limit, $offset = $offset, $orderby = $orderby, $cache_group = $cache_group, $debug = $debug, $ids = $ids, $count_only = $count_only, $only_those_fields = $only_those_fields, $exclude_ids = $exclude_ids, $force_cache_id = $force_cache_id, $get_only_whats_requested_without_additional_stuff = $get_only_whats_requested_without_additional_stuff );
		
		$get2 = array ();
		if (! empty ( $get )) {
			foreach ( $get as $g ) {
				$more = false;
				$more = CI::model ( 'core' )->getCustomFields ( 'table_cart', $g ['id'], true );
				$g ['custom_fields'] = $more;
				$get2 [] = $g;
			}
		} else {
			return false;
		}
		
		return $get2;
	
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
			
			$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
			
			$sid_where = " and sid='$session_id' ";
		
		}
		
		if ($only_uncompleted_orders == true) {
			
			$only_uncompleted_orders_q = " order_completed='n' ";
		
		} else {
			
			$only_uncompleted_orders_q = " order_completed is not null ";
		
		}
		
		$q = "delete from $table where $only_uncompleted_orders_q  $sid_where and id=$id";
		
		$q = CI::model ( 'core' )->dbQ ( $q );
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
		return true;
	
	}
	
	/**

	 * @desc Get Qty

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function itemsGetQty() {
		
		$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		$q = "select sum(qty) as qty_sum from $table where sid='$session_id'  and order_completed='n'";
		//p($q);
		$q = CI::model ( 'core' )->dbQuery ( $q );
		
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
		
		$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
		
		global $cms_db_tables;
		
		$to_return = false;
		
		$table = $cms_db_tables ['table_cart'];
		
		$q = "select * from $table where sid='$session_id'  and order_completed='n'";
		
		$q = CI::model ( 'core' )->dbQuery ( $q );
		
		if (empty ( $q )) {
			
			return 0;
		
		} else {
			
			$total = 0;
			$total_no_promo = 0;
			
			foreach ( $q as $item ) {
				
				//p($item);
				

				if (intval ( $item ['to_table_id'] ) != 0) {
					$do_math = intval ( $item ['qty'] ) * floatval ( $item ['price'] );
					if (trim ( $item ['skip_promo_code'] ) != 'y') {
						$total = $total + $do_math;
					} else {
						$total_no_promo = $total_no_promo + $do_math;
					}
				
				} else {
					
					$do_math = intval ( $item ['qty'] ) * floatval ( $item ['price'] );
					
					$total = $total + $do_math;
				
				}
			
			}
		
		}
		
		if (trim ( strval ( $with_promo_code ) ) == '') {
			
			$to_return = $total;
		
		} else {
			
			$promo_code = $with_promo_code;
			
			$get_promo = array ();
			
			$get_promo ['promo_code'] = $with_promo_code;
			
			$codes = $this->promoCodesGet ( $get_promo );
			
			$code = $codes [0];
			
			if (! empty ( $code )) {
				$final_price = $total - $code ['amount_modifier'];
				
				$to_return = $this->promoCodeApplyToAmount ( $total, $with_promo_code );
				
			/*if ($code ['amount_modifier'] != 0) {
					
					switch ($code ['amount_modifier_type']) {
						
						case 'percent' :
							
							$percent = $code ['amount_modifier']; // without %
							

							$discount_value = ($total / 100) * $percent;
							
							$final_price = $total - $discount_value;
							
							$to_return = $final_price;
							
							break;
						
						default :
							
							$final_price = $total - $code ['amount_modifier'];
							
							$to_return = $final_price;
							
							//var_Dump ( $amount );
							

							break;
					
					}
				
				}*/
			
			} else {
				
				$to_return = $total;
			
			}
		
		}
		
		//CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		

		//return $id;
		//	var_dump( $total_no_promo);
		//	var_dump( $total); 
		

		$to_return = $to_return + $total_no_promo;
		
		if ($convert_to_currency == "EUR") {
			return $to_return;
		} else {
			$convert_to_currency_price = $this->currencyConvertPrice ( $to_return, $convert_to_currency );
			return ($convert_to_currency_price);
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
		
		$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		$q = "delete from $table where sid='$session_id' and order_completed='n'";
		
		//print $q;
		

		$q = CI::model ( 'core' )->dbQ ( $q );
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
	//CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
	

	//return $id;
	

	}
	
	/**
	 * @desc billingCheckCreditCard
	 * @author		Peter Ivanov
	 * @version 1.0
	 * @since Version 1.0
	 */
	
	function billing_borica_ProcessCreditCard() {
		
		$formdata ['orderno'] = CI::library ( 'session' )->userdata ( 'order_id' );
		$formdata ['shipping_total_charges'] = CI::library ( 'session' )->userdata ( 'shipping' );
		$formdata ['amount'] = floatval ( $this->itemsGetTotal ( CI::library ( 'session' )->userdata ( 'cart_promo_code' ), CI::library ( 'session' )->userdata ( 'shop_currency' ) ) );
		$formdata ['amount'] = $formdata ['amount'] + $formdata ['shipping_total_charges'];
		$formdata ['first_name'] = CI::library ( 'session' )->userdata ( 'first_name' );
		$formdata ['last_name'] = CI::library ( 'session' )->userdata ( 'last_name' );
		$formdata ['country'] = CI::library ( 'session' )->userdata ( 'country' );
		$formdata ['phone'] = CI::library ( 'session' )->userdata ( 'night_phone_a' );
		$formdata ['email'] = CI::library ( 'session' )->userdata ( 'email' );
		//	$formdata ['country'] = CI::library('session')->userdata ( 'country' );
		$formdata ['city'] = CI::library ( 'session' )->userdata ( 'city' );
		
		$formdata ['state'] = CI::library ( 'session' )->userdata ( 'state' );
		$formdata ['zip'] = CI::library ( 'session' )->userdata ( 'zip' );
		
		/*$ord_desc = $formdata ['orderno']. " ";
		$ord_desc .= $formdata ['amount']. " ";
		$ord_desc .= $formdata ['first_name']. " ";
		$ord_desc .= $formdata ['last_name']. " ";
		$ord_desc .= $formdata ['country']. " ";
		$ord_desc .= $formdata ['phone']. " ";
		$ord_desc .= $formdata ['email']. " ";
		$ord_desc .= $formdata ['state']. " ";
		$ord_desc .= $formdata ['zip']. " ";*/
		//print CI::library('session')->userdata ( 'shop_currency' );
		$formdata ['amount'] = $this->currencyExchange ( $from = CI::library ( 'session' )->userdata ( 'shop_currency' ), $to = "bgn", $formdata ['amount'] );
		//$formdata ['amount'] = $formdata ['amount']+floatval('0.'.rand());
		$formdata ['amount'] = floatval ( $formdata ['amount'] );
		$formdata ['amount'] = ($formdata ['amount'] * 100);
		//exit($formdata ['amount']);
		define ( "TRANS_CODE", 10 );
		define ( "TRANS_TIME", date ( "YmdHis" ) );
		define ( "AMOUNT", $formdata ['amount'] );
		define ( "TERMID", 62160252 );
		define ( "ORDERID", $formdata ['orderno'] );
		
		$desc = false;
		//foreach ( $formdata as $k => $v ) { 
		$desc = $desc . "email: " . $formdata ['email'] . "     ";
		$desc = $desc . "ip: " . $_SERVER ['REMOTE_ADDR'] . "     ";
		$desc = $desc . "order id: " . $formdata ['orderno'] . "     ";
		
		//}
		

		define ( "ORDERDESC", $desc );
		
		define ( "LANG", 'EN' );
		define ( "PROT_VER", '1.0' );
		
		$message = TRANS_CODE;
		$message .= TRANS_TIME;
		$amount = AMOUNT;
		$message .= str_pad ( $amount, 12, "0", STR_PAD_LEFT );
		$message .= 62160252; //term id
		

		$message .= str_pad ( ORDERID, 15 );
		$message .= str_pad ( ORDERDESC, 125 );
		$message .= LANG;
		$message .= PROT_VER;
		//var_Dump ( $message );
		

		$filename = ROOTPATH . "/eborica/prod_requests.key";
		$fp = fopen ( $filename, "r" );
		$priv_key = fread ( $fp, 8192 );
		fclose ( $fp );
		$pkeyid = openssl_get_privatekey ( $priv_key );
		openssl_sign ( $message, $signature, $pkeyid );
		openssl_free_key ( $pkeyid );
		$message .= $signature;
		
		$action = "registerTransaction?eBorica=";
		$gateBoricaURL = 'https://gate.borica.bg/boreps/';
		
		$url = $gateBoricaURL . $action . urlencode ( base64_encode ( $message ) );
		//echo ($url);
		//$result = file_get_contents ( $url );
		

		return $url;
	
	}
	
	function billing_borica_getBOResp($message) {
		// manipulation of the $_GET["eBorica"] parameter 
		$message = base64_decode ( $message );
		$response [TRANS_CODE] = substr ( $message, 0, 2 );
		$response [TRANS_TIME] = substr ( $message, 2, 14 );
		$response [AMOUNT] = substr ( $message, 16, 12 );
		$response [TERMID] = substr ( $message, 28, 8 );
		$response [ORDERID] = substr ( $message, 36, 15 );
		$response [RESP_CODE] = substr ( $message, 51, 2 );
		$response [PROT_VER] = substr ( $message, 53, 3 );
		$response [SIGN] = substr ( $message, 56, 128 );
		$filename = ROOTPATH . "/eborica/prod_requests.key";
		$fp = fopen ( $filename, "r" );
		$cert = fread ( $fp, 8192 );
		fclose ( $fp );
		$pubkeyid = openssl_get_publickey ( $cert );
		$response [SIGNOK] = openssl_verify ( substr ( $message, 0, strlen ( $message ) - 128 ), $response [SIGN], $pubkeyid );
		openssl_free_key ( $pubkeyid );
		return $response;
	}
	function billing_borica_generateBOReq($transCode, $transStatusFlag, $amount, $termID, $orderID, $orderDescr, $lang, $protVer, $filename, $password = false, $gateBoricaURL = false) {
		$message = $transCode;
		$message .= date ( "YmdHis", mktime () );
		$amount *= 100;
		$message .= str_pad ( $amount, 12, "0", STR_PAD_LEFT );
		$message .= $termID;
		$message .= str_pad ( $orderID, 15 );
		$message .= str_pad ( $orderDescr, 125 );
		$message .= $lang;
		//$message .= str_pad ( $protVer, 3, "0", STR_PAD_LEFT );;
		$message .= ($protVer);
		var_Dump ( $message );
		
		$fp = fopen ( $filename, "r" );
		$priv_key = fread ( $fp, 8192 );
		fclose ( $fp );
		if ($password != false) {
			$pkeyid = openssl_get_privatekey ( $priv_key, $password );
		} else {
			$pkeyid = openssl_get_privatekey ( $priv_key );
		
		}
		openssl_sign ( $message, $signature, $pkeyid );
		openssl_free_key ( $pkeyid );
		$message .= $signature;
		
		if ($transCode == '10' && $transStatusFlag == "0") {
			$action = "registerTransaction?eBorica=";
		} else {
			if ($transCode == '10' && $transStatusFlag == "1") {
				$action = "transactionStatusReport?eBorica=";
			} else {
				$action = "manageTransaction?eBorica=";
			}
		}
		$action = "registerTransaction?eBorica=";
		if ($gateBoricaURL == false) {
			$gateBoricaURL = 'https://gatet.borica.bg/boreps/';
		
		}
		
		//echo('<br>'); 
		$url = $gateBoricaURL . $action . urlencode ( base64_encode ( $message ) );
		echo ($url);
		$result = file_get_contents ( $url );
		echo ($result);
		return $url;
	}
	
	/**
	 * @desc billingCheckCreditCard
	 * @author		Peter Ivanov
	 * @version 1.0
	 * @since Version 1.0
	 */
	
	function billingProcessCreditCard($make_transaction = false) {
		global $cms_db_tables;
		
		return array ('success' => 'ok' );
		$formdata = array ();
		
		$formdata ['clientid'] = $shop_transaction_method_user_id;
		$formdata ['merchantaccountid'] = $shop_transaction_method_merchantaccountid;
		$formdata ['transactiontype'] = 'SALE';
		$formdata ['INDUSTRYTYPE'] = '013'; //ELECTRONIC COMMERCE
		

		$formdata ['customercode'] = CI::library ( 'session' )->userdata ( 'session_id' ) . date ( 'ymdHis' );
		
		//			$formdata ['amount'] = CI::library('session')->userdata ( 'amount' ) + (float)(rand(1, 20) / 100);
		$formdata ['amount'] = floatval ( $this->itemsGetTotal ( CI::library ( 'session' )->userdata ( 'cart_promo_code' ), CI::library ( 'session' )->userdata ( 'shop_currency' ) ) ) + ( float ) (rand ( 1, 20 ) / 100);
		$paidAmount = $formdata ['amount'];
		
		$formdata ['orderno'] = CI::library ( 'session' )->userdata ( 'order_id' );
		//			$formdata ['order_id'] = CI::library('session')->userdata ( 'order_id' );
		$formdata ['cardholdernumber'] = CI::library ( 'session' )->userdata ( 'billing_cardholdernumber' );
		
		///!Important AUTHORIZATIONNUMBER go only with FORCE/TICKET
		//$formdata['AUTHORIZATIONNUMBER'] = $formdata ['customercode'];
		

		$formdata ['expiresmonth'] = CI::library ( 'session' )->userdata ( 'billing_expiresmonth' );
		$formdata ['expiresyear'] = CI::library ( 'session' )->userdata ( 'billing_expiresyear' );
		//			$formdata ['ccv2'] = false;
		$formdata ['bname'] = CI::library ( 'session' )->userdata ( 'billing_first_name' ) . ' ' . CI::library ( 'session' )->userdata ( 'billing_last_name' );
		$formdata ['bemailaddress'] = CI::library ( 'session' )->userdata ( 'billing_user_email' );
		$formdata ['baddress1'] = CI::library ( 'session' )->userdata ( 'billing_address' );
		$formdata ['bcity'] = CI::library ( 'session' )->userdata ( 'billing_city' );
		$formdata ['bstate'] = CI::library ( 'session' )->userdata ( 'billing_state' );
		$formdata ['bzipcode'] = CI::library ( 'session' )->userdata ( 'billing_zip' );
		$formdata ['bcountry'] = CI::library ( 'session' )->userdata ( 'billing_country' );
		$formdata ['bphone'] = CI::library ( 'session' )->userdata ( 'billing_user_phone' );
		
		$formdata ['sname'] = CI::library ( 'session' )->userdata ( 'shipping_first_name' ) . ' ' . CI::library ( 'session' )->userdata ( 'shipping_last_name' );
		$formdata ['scompany'] = CI::library ( 'session' )->userdata ( 'shipping_company_name' );
		$formdata ['saddress1'] = CI::library ( 'session' )->userdata ( 'shipping_address' );
		$formdata ['saddress2'] = CI::library ( 'session' )->userdata ( 'shipping_city' ) . ', ' . CI::library ( 'session' )->userdata ( 'shipping_zip' );
		$formdata ['scity'] = CI::library ( 'session' )->userdata ( 'shipping_city' );
		$formdata ['sstate'] = CI::library ( 'session' )->userdata ( 'shipping_state' );
		$formdata ['szipcode'] = CI::library ( 'session' )->userdata ( 'shipping_zip' );
		$formdata ['sphone'] = CI::library ( 'session' )->userdata ( 'shipping_user_phone' );
		$formdata ['scountry'] = CI::library ( 'session' )->userdata ( 'shipping_state' );
		$formdata ['promo_code'] = CI::library ( 'session' )->userdata ( 'cart_promo_code' );
		$formdata ['shipping_total_charges'] = CI::library ( 'session' )->userdata ( 'shipping_total_charges' );
		$formdata ['shipping_service'] = CI::library ( 'session' )->userdata ( 'shipping_service' );
		$formdata ['semailaddress'] = CI::library ( 'session' )->userdata ( 'shipping_user_email' );
		
		//			file_put_contents('stest.txt', serialize($formdata));
		//	p($formdata, 1);
		

		if (function_exists ( "curl_init" )) {
			//$ckfile = tempnam ("/tmp", "CURLCOOKIE");
			/*~~~~~~~~~ Pay pal payment ~~~~~~~~~~*/
			$userData = array ();
			$userData ['payment_type'] = 'Sale';
			$userData ['first_name'] = CI::library ( 'session' )->userdata ( 'billing_first_name' );
			$userData ['last_name'] = CI::library ( 'session' )->userdata ( 'billing_last_name' );
			$userData ['credit_card_type'] = CI::library ( 'session' )->userdata ( 'credit_card_type' );
			$userData ['credit_card_number'] = str_replace ( ' ', '', $formdata ['cardholdernumber'] );
			$userData ['exp_date_month'] = str_pad ( $formdata ['expiresmonth'], 2, '0', STR_PAD_LEFT ); // Month must be padded with leading zero
			$userData ['exp_date_year'] = $formdata ['expiresyear'];
			
			//$userData ['from_date_month'] = str_pad ( CI::library('session')->userdata ( 'billing_validfrommonth' ), 2, '0', STR_PAD_LEFT ); // Month must be padded with leading zero
			//$userData ['from_date_year'] = CI::library('session')->userdata ( 'billing_validfromyear' );
			

			$userData ['cvv2'] = CI::library ( 'session' )->userdata ( 'billing_cvv2' );
			$userData ['address'] = $formdata ['baddress1'];
			$userData ['city'] = $formdata ['bcity'];
			$userData ['state'] = $formdata ['bstate'];
			$userData ['zip'] = $formdata ['bzipcode'];
			$userData ['country'] = $formdata ['bcountry'];
			$userData ['amount'] = floatval ( $formdata ['amount'] ) + floatval ( $formdata ['shipping_total_charges'] );
			$userData ['currency_id'] = 'USD';
			
			//XXX: This is debug case. Remove it on production only from OOYES IP
			if ($_SERVER ['REMOTE_ADDR'] == '77.70.8.202') {
				$userData ['amount'] = '0.01';
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
				CI::library ( 'session' )->set_userdata ( 'billing_cvv2', '' );
				CI::library ( 'session' )->set_userdata ( 'billing_cardholdernumber', '' );
				
				//Set Affiliate
				//$countries = $this->paymentmethods_tpro_get_countries_list_as_array ();
				//$formdata ['country'] = $countries [$formdata ['bcountry']];
				$formdata ['country_id'] = $formdata ['bcountry'];
				$formdata ['payment_response'] = serialize ( $httpParsedResponseAr );
				
				$formdata ['transactionid'] = $httpParsedResponseAr ['TRANSACTIONID'];
				
				if (isset ( $formdata ['transactionid'] )) {
					//$this->orderPlace ( $formdata );
					$formdata ['from_log'] = $_COOKIE ['from_log'];
					
					CI::library ( 'session' )->set_userdata ( 'billing_cvv2', '' );
					CI::library ( 'session' )->set_userdata ( 'billing_cardholdernumber', '' );
					
					CI::library ( 'session' )->set_userdata ( 'order_id', '' . date ( 'ymdHis' ) . rand () );
					
					return true;
				} else {
					return array ('error' => $httpParsedResponseAr ["L_LONGMESSAGE0"] );
				
				}
			
			} else {
				//Unset Card Data
				return array ('error' => $httpParsedResponseAr ["L_LONGMESSAGE0"] );
			}
		
		} else {
			exit ( 'Error: You need the CURL library on your server in order to run payment processing functions' );
		
		}
	
	}
	/**

	 * @desc Sum various things

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function cartSumByFields($fld) {
		
		$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		$q = "select sum($fld) as qty_sum from $table where sid='$session_id' and order_completed='n'";
		
		$q = CI::model ( 'core' )->dbQuery ( $q );
		
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
		
		$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
		
		global $cms_db_tables;
		
		$items = $this->itemsGet ( $params );
		
		$sum = 0;
		
		foreach ( $items as $itm ) {
			
			$sum = floatval ( $sum ) + floatval ( $itm [$fld] );
		
		}
		
		$sum = floatval ( $sum );
		
		return $sum;
	
	}
	function orderPaid($ord_id) {
		global $cms_db_tables;
		$table_cart_orders = $cms_db_tables ['table_cart_orders'];
		$ord_id = codeClean ( $ord_id );
		$q = "Update $table_cart_orders set is_paid='y'  where order_id='$ord_id'";
		
		$q = CI::model ( 'core' )->dbQ ( $q );
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		return true;
	}
	
	/**

	 * @desc confirm the items in the order by making order_completed field = y

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function orderConfirm($sid = false, $order_id = false) {
		
		if ($sid == false) {
			
			$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
		
		} else {
			
			$session_id = $sid;
		
		}
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_cart'];
		
		if ($order_id != false) {
			
			$order_id = strval ( $order_id );
			
			$order_id_q = ", order_id='$order_id'";
		
		}
		
		$q = "Update $table set order_completed='y' $order_id_q where sid='$session_id' and order_completed='n'";
		
		$q = CI::model ( 'core' )->dbQ ( $q );
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
		CI::library ( 'session' )->set_userdata ( 'cart_promo_code', false );
		
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
			
			$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
			
			$data ['sid'] = $session_id;
		
		} else {
			
			$session_id = $data ['sid'];
		
		}
		
		global $cms_db_tables;
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
		$table_cart_orders = $cms_db_tables ['table_cart_orders'];
		
		$table_cart = $cms_db_tables ['table_cart'];
		
		$data_to_save_options ['delete_cache_groups'] = array ('cart' );
		
		$id = CI::model ( 'core' )->saveData ( $table_cart_orders, $data, $data_to_save_options );
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
	
	}
	
	/**

	 * @desc place the order by making order_placed = y

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function orderPlace($data) {
		
		if ($data ['sid'] == false) {
			
			$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
			
			$data ['sid'] = $session_id;
		
		} else {
			
			$session_id = $data ['sid'];
		
		}
		
		global $cms_db_tables;
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
		$table_cart_orders = $cms_db_tables ['table_cart_orders'];
		
		$table_cart = $cms_db_tables ['table_cart'];
		
		$data_to_save_options ['delete_cache_groups'] = array ('cart' );
		//	p ( $data );
		$id = CI::model ( 'core' )->saveData ( $table_cart_orders, $data, $data_to_save_options );
		
		$this->orderConfirm ( $data ['sid'], $order_id = $data ['order_id'] );
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
		$get_option = array ();
		$get_option ['option_key'] = 'order_complete_email';
		$get_option ['option_group'] = 'orders';
		$get_option1 = CI::model ( 'core' )->optionsGetByKey ( $get_option, true );
		$subj = $get_option1 ['option_value'];
		
		$get_option = array ();
		$get_option ['option_key'] = 'order_complete_email_body';
		$get_option ['option_group'] = 'orders';
		$get_option1 = CI::model ( 'core' )->optionsGetByKey ( $get_option, true );
		$body = $get_option1 ['option_value'];
		
		if ($subj and $body) {
			$get_option = array ();
			$get_option ['option_key'] = 'mailform_to';
			//$get_option ['option_group'] = 'orders';
			$get_option1 = CI::model ( 'core' )->optionsGetByKey ( $get_option, true );
			$from = $get_option1 ['option_value'];
			$headers = 'From: ' . $from . "\r\n" . 'X-Mailer: Microweber - PHP/' . phpversion ();
			// To send HTML mail, the Content-type header must be set
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	
			$headers .= 'Cc: ' .$from. "\r\n";
		
			
			mail ( $data ['email'], $subj, $body, $additional_headers = $headers, $additional_parameters = null );
		
		}
		
		//p($get_option1);
		return $id;
	
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
		
		$get = CI::model ( 'core' )->getDbData ( $table, $data, $limit = $limit, $offset = $offset, $orderby = $orderby, $cache_group = $cache_group, $debug = $debug, $ids = $ids, $count_only = $count_only, $only_those_fields = $only_those_fields, $exclude_ids = $exclude_ids, $force_cache_id = $force_cache_id, $get_only_whats_requested_without_additional_stuff = $get_only_whats_requested_without_additional_stuff );
		
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
		
		$q = CI::model ( 'core' )->dbQ ( $q );
		
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
			
			$q = " delete from $table_cart where order_id = '$order_id'   ";
			
			CI::model ( 'core' )->dbQ ( $q );
			
			$q = " delete from $table_orders where id = $id ";
			
			CI::model ( 'core' )->dbQ ( $q );
		
		}
		
		//var_dump ( $order_data );
		

		//order_id
		

		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
		return true;
	
	}
	
	function promoCodeApplyToCart($with_promo_code) {
		
		$promo_code = $with_promo_code;
		
		$get_promo = array ();
		
		$get_promo ['promo_code'] = $with_promo_code;
		
		$codes = $this->promoCodesGet ( $get_promo );
		
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
						
						$to_return = $final_price;
						
						//var_Dump ( $amount );
						

						break;
				
				}
			
			}
		}
		return $to_return;
	}
	
	function promoCodeApplyToAmount($total, $with_promo_code) {
		
		$promo_code = $with_promo_code;
		
		$get_promo = array ();
		
		$get_promo ['promo_code'] = $with_promo_code;
		
		$codes = $this->promoCodesGet ( $get_promo );
		
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
						
						$to_return = $final_price;
						
						//var_Dump ( $amount );
						

						break;
				
				}
			
			}
		}
		return $to_return;
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
		
		if ($cache_group == false) {
			$cache_group = 'cart/promo';
		}
		
		$get = CI::model ( 'core' )->getDbData ( $table, $data, $limit = $limit, $offset = $offset, $orderby = $orderby, $cache_group = $cache_group, $debug = $debug, $ids = $ids, $count_only = $count_only, $only_those_fields = $only_those_fields, $exclude_ids = $exclude_ids, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = $get_only_whats_requested_without_additional_stuff );
		
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
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
		$table_cart_orders = $cms_db_tables ['table_cart_promo_codes'];
		
		$table_cart = $cms_db_tables ['table_cart'];
		
		$data_to_save_options ['delete_cache_groups'] = array ('cart' );
		
		$id = CI::model ( 'core' )->saveData ( $table_cart_orders, $data, $data_to_save_options );
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
	
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
		
		CI::model ( 'core' )->dbQ ( $q );
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
		return true;
	
	}
	
	/**

	 * @desc calculates shipping to destination county name like: Bulgaria, Unined

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function shippingCalculateToCountryName($country_name = false, $convert_to_currency = 'EUR') {
		
		$countries = CI::model ( 'core' )->geoGetAllCountries ();
		
		if ($country_name == false) {
			
			$country_name = CI::library ( 'session' )->userdata ( 'country_name' );
		
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
		
		//	
		

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
				//v//ar_dump($num);
				$total = ($ship_price * $num);
				//var_dump($total);
				$sid = CI::library ( 'session' )->userdata ( 'session_id' );
				$cart_item = array ();
				$cart_item ['sid'] = $sid;
				$cart_item ['order_completed'] = 'n';
				
				$cart_items = $this->cart_model->itemsGet ( $cart_item );
				if (! empty ( $cart_items )) {
					foreach ( $cart_items as $variable ) {
						if (intval ( $variable ['added_shipping_price'] ) > 0) {
							$total = $total + ((intval ( $variable ['added_shipping_price'] )) * intval ( $variable ['qty'] ));
						}
					}
				}
				
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
		
		$get = CI::model ( 'core' )->getDbData ( $table, $data, $limit = $limit, $offset = $offset, $orderby = $orderby, $cache_group = $cache_group, $debug = $debug, $ids = $ids, $count_only = $count_only, $only_those_fields = $only_those_fields, $exclude_ids = $exclude_ids, $force_cache_id = $force_cache_id, $get_only_whats_requested_without_additional_stuff = $get_only_whats_requested_without_additional_stuff );
		
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
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
		$table = $cms_db_tables ['table_cart_orders_shipping_cost'];
		
		if (strval ( trim ( $data ['ship_to_continent'] ) ) != '') {
			
			$q = "delete from $table where ship_to_continent like '{$data['ship_to_continent']}' ";
			
			CI::model ( 'core' )->dbQ ( $q );
			
			$data ['id'] = 0;
		
		}
		
		$data_to_save_options ['delete_cache_groups'] = array ('cart' );
		
		$id = CI::model ( 'core' )->saveData ( $table, $data, $data_to_save_options );
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
	
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
		
		CI::model ( 'core' )->dbQ ( $q );
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
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
		
		$q = CI::model ( 'core' )->dbQuery ( $q );
		
		$q1 = array ();
		
		foreach ( $q as $item ) {
			
			$q1 [] = $item ['ship_to_continent'];
		
		}
		
		return $q1;
	
	}
	function payPalCardTypes() {
		return array ('Visa' => 'Visa', 'MasterCard' => 'MasterCard', 'Discover' => 'Discover', 'Amex' => 'Amex', 'Maestro' => 'Maestro' ); //'Solo' => 'Solo',
	

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
		
		$get = CI::model ( 'core' )->getDbData ( $table, $data, $limit = $limit, $offset = $offset, $orderby = $orderby, $cache_group = $cache_group, $debug = $debug, $ids = $ids, $count_only = $count_only, $only_those_fields = $only_those_fields, $exclude_ids = $exclude_ids, $force_cache_id = $force_cache_id, $get_only_whats_requested_without_additional_stuff = $get_only_whats_requested_without_additional_stuff );
		
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
			CI::model ( 'core' )->dbQ ( $q );
			
			$data ['id'] = 0;
		
		}
		//var_dump($data);
		$data_to_save_options ['delete_cache_groups'] = array ('cart' );
		$id = CI::model ( 'core' )->saveData ( $table, $data, $data_to_save_options );
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
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
		
		CI::model ( 'core' )->dbQ ( $q );
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
		
		return true;
	
	}
	
	/**

	 * @desc lets do the exchange

	 * @author		Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function currencyExchange($from = "EUR", $to = "EUR", $amount = false) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_cart_currency'];
		
		if (strtolower ( $from ) == strtolower ( $to )) {
			return 1;
		} else {
			
			$q = "select  currency_rate from $table where currency_from like '$from' and currency_to like '$to' limit 0,1 ";
			//	var_dump($q);
			$q = CI::model ( 'core' )->dbQuery ( $q );
			if (empty ( $q )) {
				$rate = 1;
			} else {
				$q = $q [0] ['currency_rate'];
				$rate = $q;
			}
		
		}
		//var_Dump($amount, $rate);
		if ($amount != false) {
			$rate = $amount * $rate;
			//print $rate;
			$rate = number_format ( ($rate), 2, '.', '' );
			//print $rate;
		

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
				CI::library ( 'session' )->set_userdata ( 'shop_currency', $to );
				CI::library ( 'session' )->set_userdata ( 'shop_currency_sign', '&euro;' );
				CI::library ( 'session' )->set_userdata ( 'shop_currency_code', 'EUR' );
				break;
			
			case "USD" :
				CI::library ( 'session' )->set_userdata ( 'shop_currency', $to );
				CI::library ( 'session' )->set_userdata ( 'shop_currency_sign', '$' );
				CI::library ( 'session' )->set_userdata ( 'shop_currency_code', 'USD' );
				break;
			
			case "GBP" :
				
				CI::library ( 'session' )->set_userdata ( 'shop_currency', $to );
				CI::library ( 'session' )->set_userdata ( 'shop_currency_sign', '&pound;' );
				CI::library ( 'session' )->set_userdata ( 'shop_currency_code', 'GBP' );
				break;
			
			default :
				CI::library ( 'session' )->set_userdata ( 'shop_currency', $to );
				CI::library ( 'session' )->set_userdata ( 'shop_currency_sign', $sign );
				CI::library ( 'session' )->set_userdata ( 'shop_currency_code', $code );
				break;
		
		}
		
	//print CI::library('session')->userdata ( 'shop_currency' ); 
	

	}
	
	function currencyConvertPrice($price, $currency = 'EUR') {
		global $cms_db;
		if ($currency == 'EUR') {
			
			return $price;
		
		} else {
			
			$rate_please_work = $this->currencyExchange ( 'EUR', $currency );
			//	var_dump($rate_please_work);
			$rate = $rate_please_work;
			
			$rate = (($rate));
			//	var_dump ( $rate );
			$price = ($price);
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

}

?>