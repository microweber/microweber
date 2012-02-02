<?php

class Cart extends Controller {
	
	function __construct() {
		
		parent::Controller ();
		
		require_once (APPPATH . 'controllers/default_constructor.php');
		//	require_once (APPPATH . 'controllers/api/default_constructor.php');
		

		$order_id = CI::library ( 'session' )->userdata ( 'order_id' );
		if ($order_id == false) {
			
			$order_id = "ORD" . date ( "ymdHis" ) . rand ();
			
			$order_id = CI::library ( 'session' )->set_userdata ( 'order_id', $order_id );
		
		}
	
	}
	
	function add() {
		
		if ($_POST) {
			$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
			$check ['sid'] = $session_id;
			$cart = CI::model ( 'cart' )->itemAdd ( $_POST );
			$cart = CI::model ( 'cart' )->itemsGetQty ();
			print $cart;
		
		}
	
	}
	
	function order_info() {
		
		$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
		$check ['sid'] = $session_id;
		$check ['order_completed'] = 'n';
		
		$info = array ();
		$items = get_cart_items ( $check );
		$info ['items'] = $items;
		
		$total = get_cart_total ();
		
		$info ['total'] = $total;
		$qty = get_items_qty ();
		
		$info ['qty'] = $qty;
		$info = json_encode ( $info );
		exit ( $info );
	
	}
	
	function remove_item() {
		$id = $_POST ['id'];
		
		$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
		$check = array ();
		$check ['id'] = $id;
		$check ['sid'] = $session_id;
		$check ['order_completed'] = 'n';
		
		$check = get_cart_items ( $check );
		if (! empty ( $check )) {
			print CI::model ( 'cart' )->itemDeleteById ( $id );
		}
	
	}
	
	function delete_order() {
		
		$adm = is_admin ();
		if ($adm == true) {
			$id = $_POST ['id'];
			$id = intval ( $id );
			if ($id != 0) {
				print CI::model ( 'cart' )->orderDeleteById ( $id );
			}
		
		} else {
			exit ( 'You must be logged as admin to do that' );
		}
	
	}
	
	function modify_item_properties() {
		$id = $_POST ['id'];
		$property = $_POST ['propery_name'];
		$new_value = $_POST ['new_value'];
		$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
		$check = array ();
		$check ['id'] = $id;
		$check ['sid'] = $session_id;
		
		$check ['order_completed'] = 'n';
		
		$check = get_cart_items ( $check );
		if (! empty ( $check )) {
			if ($property != 'sid') {
				$new = array ();
				$new ['id'] = $id;
				$new [$property] = $new_value;
				CI::model ( 'cart' )->itemSave ( $new );
			}
		} else {
			exit ( 'Error: invalid item in cart' );
		}
	}
	
	function complete_order() {
		if ($_POST) {
			$session_id = CI::library ( 'session' )->userdata ( 'session_id' );
			$order_id = CI::library ( 'session' )->userdata ( 'order_id' );
			$data = $_POST;
			$data ['sid'] = $session_id;
			$data ['order_id'] = $order_id;
			
			$total = get_cart_total ();
			$data ['amount'] = $total;
			
			if (intval ( $data ['shipping'] ) == 0) {
				$data ['shipping'] = option_get ( 'shipping' );
			
			}
			
			if (strval ( $data ['promo_code'] ) == '') {
				$data ['promo_code'] = CI::model ( 'cart' )->promoCodeGetCurent ();
			
			}
			
			//p ( $data );
			$cart = CI::model ( 'cart' )->orderPlace ( $data );
			$cart = json_encode ( $cart );
			exit ();
		}
	}
	
	function checkout_return() {
		//var_dump($_SERVER);
		$get = $_REQUEST ['eBorica'];
		$param = CI::model ( 'core' )->getParamFromURL ( 'eBorica' );
		
		//	var_dump($param);
		

		$msg = $this->cart_model->billing_borica_getBOResp ( $param );
		$msg_resp = $msg ["RESP_CODE"];
		//var_dump($msg_resp);
		//$msg_resp = 00;
		switch ($msg_resp) {
			case '00' :
				
				$msg_to_show = "You have completed the payment. We will contact you shortly for your order.";
				break;
			
			case '94' :
				$msg_to_show = "You have canceled your payment.";
				break;
			
			default :
				$msg_to_show = "There was error with your payment. Yor payment has not been processed.";
				break;
		
		}
		$site = site_url ();
		print "<h1>$msg_to_show</h1><br>";
		print "<b><a href='$site' >Click here to continue</a></b>";
		exit ();
		//billing_borica_getBOResp
	//header("Location: ".site_url('shop'));
	//exit;
	}
	
	function get_promo_code() {
		
		$code = $_POST ['code'];
		$code = trim ( $code );
		if ($code != '') {
			
			$get_promo = array ();
			$get_promo ['promo_code'] = $code;
			
			$codes = $cart = CI::model ( 'cart' )->promoCodesGet ( $get_promo );
		}
		//print json_encode ( $codes );
		

		if (! empty ( $codes )) {
			$codes = $codes [0];
			
			if (! empty ( $codes )) {
				//	$promo_code = CI::library ( 'session' )->userdata ( 'promo_code' );
				CI::library ( 'session' )->set_userdata ( 'promo_code', $codes ['promo_code'] );
				CI::library ( 'session' )->set_userdata ( 'cart_promo_code', $codes ['promo_code'] );
			
			}
			
			print json_encode ( $codes );
			exit ();
		} else {
			
			$promo_item = array ();
			$promo_item ['auto_apply_to_all'] = 'y';
			
			$promo_item = CI::model ( 'cart' )->promoCodesGet ( $promo_item );
			//	p($promo_item);
			if (! empty ( $promo_item )) {
				$promo_item = $promo_item [0];
				CI::library ( 'session' )->set_userdata ( 'promo_code', $promo_item ['promo_code'] );
				CI::library ( 'session' )->set_userdata ( 'cart_promo_code', $promo_item ['promo_code'] );
				$promo_code = $promo_item ['promo_code'];
				
				print json_encode ( $promo_item );
			
			} else {
				
				exit ( '0' );
			}
		}
	
	}

}



