<?php

class Shop extends Controller {
	
	function __construct() {
		
		parent::Controller ();
		
		require_once (APPPATH . 'controllers/default_constructor.php');
		//  require_once (APPPATH . 'controllers/api/default_constructor.php');
	

	}
	
	function ok() {
	
	}
	
	function ipn() {
		
		$email = url_param ( 'ipn_email' );
		$header = "";
		$emailtext = "";
		// Read the post from PayPal and add 'cmd' 
		$req = 'cmd=_notify-validate';
		if (function_exists ( 'get_magic_quotes_gpc' )) {
			$get_magic_quotes_exits = true;
		}
		foreach ( $_POST as $key => $value ) // Handle escape characters, which depends on setting of magic quotes 
{
			if ($get_magic_quotes_exists == true && get_magic_quotes_gpc () == 1) {
				$value = urlencode ( stripslashes ( $value ) );
			} else {
				$value = urlencode ( $value );
			}
			$req .= "&$key=$value";
		}
		// Post back to PayPal to validate 
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen ( $req ) . "\r\n\r\n";
		$fp = fsockopen ( 'ssl://www.paypal.com', 443, $errno, $errstr, 30 );
		
		// Process validation from PayPal 
		// TODO: This sample does not test the HTTP response code. All 
		// HTTP response codes must be handles or you should use an HTTP 
		// library, such as cUrl 
		

		if (! $fp) { // HTTP ERROR 
		} else {
			// NO HTTP ERROR 
			fputs ( $fp, $header . $req );
			while ( ! feof ( $fp ) ) {
				$res = fgets ( $fp, 1024 );
				if (strcmp ( $res, "VERIFIED" ) == 0) {
					// TODO: 
					// Check the payment_status is Completed 
					// Check that txn_id has not been previously processed 
					// Check that receiver_email is your Primary PayPal email 
					// Check that payment_amount/payment_currency are correct 
					// Process payment 
					// If 'VERIFIED', send an email of IPN variables and values to the 
					// specified email address 
					foreach ( $_POST as $key => $value ) {
						$emailtext .= $key . " = " . $value . "\n\n";
					
					}
					
					if ($_POST ['item_number']) {
						$this->cart_model->orderPaid ( $_POST ['item_number'] );
						$data = array ();
						$data ['order_id'] = $_POST ['item_number'];
						
						$ord = $this->cart_model->ordersGet ( $data, $limit = false );
						$ord = $ord [0];
						
						$user_from_order = explode ( ':', $_POST ['item_number'] );
						$user_from_order = $user_from_order [1];
						
						$usr = CI::model ( 'users' )->getUserById ( $user_from_order );
						if ($usr ['expires_on'] == false) {
							$usr ['expires_on'] = date ( "Y-m-d H:i:s" );
						}
						$extend_with = $_POST ['period3'];
						
						$extend_with = str_ireplace ( 'M', '', $extend_with );
						
						$extend_with = intval ( $extend_with );
						
						//$newdate = add_date ( $usr ['expires_on'], 0, $extend_with, 0 );
						$newdate = strtotime ( "+{$extend_with} months", strtotime ( $usr ['expires_on'] ) );
						$newdate = date ( "Y-m-d H:i:s", $newdate );
						
						$to_save = array ();
						$to_save ['id'] = $user_from_order;
						$to_save ['expires_on'] = $newdate;
						$emailtext .= 'Old date:' . $usr ['expires_on'] . "\n\n";
						
						$emailtext .= 'New date:' . $newdate . "\n\n";
						$emailtext .= serialize ( $to_save ) . "\n\n";
						CI::model ( 'users' )->saveUser ( $to_save );
					
					}
					$get_option = array ();
					$get_option ['option_key'] = 'mailform_to';
					//$get_option ['option_group'] = 'orders';
					$get_option1 = CI::model ( 'core' )->optionsGetByKey ( $get_option, true );
					$email = $get_option1 ['option_value'];
					//$email = 'boksiora@gmail.com';
					mail ( $email, "Live-VERIFIED IPN", $emailtext . "\n\n" . $req );
				} else if (strcmp ( $res, "INVALID" ) == 0) {
					// If 'INVALID', send an email. TODO: Log for manual investigation. 
					foreach ( $_POST as $key => $value ) {
						$emailtext .= $key . " = " . $value . "\n\n";
					}
					$get_option = array ();
					$get_option ['option_key'] = 'mailform_to';
					//$get_option ['option_group'] = 'orders';
					$get_option1 = CI::model ( 'core' )->optionsGetByKey ( $get_option, true );
					$email = $get_option1 ['option_value'];
					mail ( $email, "Error: Live-INVALID IPN", $emailtext . "\n\n" . $req );
				}
			}
			fclose ( $fp );
		}
	
	}
	
	function place_order() {
		
		$data = $_POST;
		$to_table = CI::model ( 'core' )->guessDbTable ();
		$to_table_id = CI::model ( 'core' )->guessId ();
		$data ['to_table'] = $to_table;
		$data ['to_table_id'] = $to_table_id;
		$cart = CI::model ( 'cart' )->orderPlace ( $data );
		$cart = json_encode ( $cart );
		exit ();
	
	}
	
	function promo_code_edit() {
		
		$adm = is_admin ();
		if ($adm == true) {
			
			//$this->template ['functionName'] = strtolower ( __FUNCTION__ );
			CI::model ( 'cart' )->promoCodeSave ( $_POST );
			CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
			exit ();
		}
	}
	
	function promo_code_delete() {
		
		$adm = is_admin ();
		if ($adm == true) {
			$id = intval ( $_POST ['id'] );
			
			CI::model ( 'cart' )->promoCodeDeleteById ( $id );
			
			CI::model ( 'core' )->cacheDelete ( 'cache_group', 'cart' );
			exit ();
		}
	}

}


