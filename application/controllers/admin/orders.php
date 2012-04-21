<?php

class Orders extends CI_Controller {
	
	function __construct() {
		parent :: __construct();
		require_once (APPPATH . 'controllers/default_constructor.php');
		require_once (APPPATH . 'controllers/admin/default_constructor.php');
	
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		$this->load->vars ( $this->template );
		
		$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';
		
		$primarycontent =$this->load->view ( 'admin/orders/index', true, true );
		
		$nav =$this->load->view ( 'admin/orders/subnav', true, true );
		$primarycontent = $nav . $primarycontent;
		
		//$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}
	function ajax_json_edit_orders() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$this->cart_model->orderSave ( $_POST );
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		exit ();
	}
	
	function ajax_json_edit_order_item() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$this->cart_model->itemAdd ( $_POST );
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		exit ();
	}
	
	function ajax_json_get_orders() {
		global $cms_db_tables;
		if ($_POST ['oper'] == 'del') {
		
		} else {
			$table = $cms_db_tables ['table_cart_orders'];
			$page = $_REQUEST ['page']; // get the requested page
			

			$limit = $_REQUEST ['rows']; // get how many rows we want to have into the grid
			$sidx = $_REQUEST ['sidx']; // get index row - i.e. user click to sort
			$sord = $_REQUEST ['sord']; // get the direction
			

			$start = $limit * ($page - 1); // do not put $limit*($page - 1)
			$end = $limit * $page; // do not put $limit*($page - 1)
			if ($start < 0) {
				$start = 0;
			}
			
			if (! $sidx) {
				$sidx = 1;
			}
			$wh = "";
			$searchOn = Strip ( $_REQUEST ['_search'] );
			$the_item_ids_from_search_array = array ();
			if ($searchOn == 'true') {
				$search_array = CI::model('core')->mapArrayToDatabaseTable ( $table, $_REQUEST );
				if (is_array ( $search_array )) {
					$qwery = '';
					$i = 0;
					foreach ( $search_array as $key => $val ) {
						$qwery .= "  AND " . $key . "  LIKE  '%" . $val . "%'  ";
					}
					if (strval ( $qwery ) != '') {
						$q = " select id from $table where id is not null  $qwery";
						//var_Dump($q);
						$q = CI::model('core')->dbQuery ( $q );
						if (! empty ( $q )) {
							foreach ( $q as $sresult ) {
								$some_id = $sresult ['id'];
								$the_item_ids_from_search_array [] = $some_id;
							}
						}
					}
				}
			
			}
			
			$limits_array = array ();
			$limits_array [0] = $start;
			$limits_array [1] = $end;
			
			if ($sidx != false and $sord != false) {
				$order_by_array = array ();
				$order_by_array [0] = $sidx;
				$order_by_array [1] = $sord;
			} else {
				$order_by_array = false;
			}
			
			$this->template ['functionName'] = strtolower ( __FUNCTION__ );
			
			$items_conf = array ();
			//$items_conf ['order_completed'] = 'y';
			$items = $this->cart_model->itemsOrders ( $items_conf, $limits_array, false, $order_by_array, false, false, $ids = $the_item_ids_from_search_array );
			$items_count = $this->cart_model->itemsOrders ( $items_conf, $limits_array = false, false, $order_by_array, false, false, $ids = $the_item_ids_from_search_array, $count_only = true );
			
			header ( "Content-type: text/xml;charset=utf-8" );
			
			$s = "<?xml version='1.0' encoding='utf-8'?>";
			$s .= "<rows>";
			$s .= "<page>" . $page . "</page>";
			$s .= "<total>" . ceil ( $items_count / $_REQUEST ['rows'] ) . "</total>";
			$s .= "<records>" . $items_count . "</records>";
			
			$i = 0;
			foreach ( $items as $item ) {
				$order_items = array ();
				$order_items ['order_id'] = $item ['order_id'];
				$order_items ['order_completed'] = 'y';
				//$amount = $this->cart_model->cartSumByParams ( 'price', $order_items );
				//$qty = $this->cart_model->cartSumByParams ( 'qty', $order_items );
				//$amount = $amount*$qty;
				

				$items_conf1 = array ();
				$items_conf1 ['order_completed'] = 'y';
				$items_conf1 ['order_id'] = $item ['order_id'];
				$items1 = $this->cart_model->itemsGet ( $items_conf1 );
				$amount = 0;
				foreach ( $items1 as $i1 ) {
					$amount = $amount + (($i1 ['qty'] * $i1 ['price']));
				}
				
				if ($item ['currency_code'] == '') {
					$item ['currency_code'] = 'EUR';
				}
				
				$amount_in_curency = $this->cart_model->currencyConvertPrice ( $amount, $item ['currency_code'] );
				if (strval ( trim ( $item ['promo_code'] ) != '' )) {
					$amount_in_curency = $this->cart_model->promoCodeApplyToAmount ( $amount_in_curency, $item ['promo_code'] );
				} else {
				
				}
				
				$item ['id'] = $item ['id'];
				$s .= "<row id='" . $item ['id'] . "'>";
				$s .= "<cell>" . $item ['order_id'] . "</cell>";
				$s .= "<cell>" . $item ['created_on'] . "</cell>";
				$s .= "<cell>" . $item ['sid'] . "</cell>";
				$s .= "<cell>" . $amount_in_curency . "</cell>";
				$s .= "<cell>" . $item ['shipping'] . "</cell>";
				$s .= "<cell>" . $item ['currency_code'] . "</cell>";
				
				$s .= "<cell>" . $item ['promo_code'] . "</cell>";
				$s .= "<cell>" . $item ['created_by'] . $item ['first_name'] . " " . $item ['last_name'] . "</cell>";
				/*$s .= "<cell>" . $item ['last_name'] . "</cell>";*/
				$s .= "<cell>" . $item ['email'] . "</cell>";
				$s .= "<cell>" . $item ['country'] . "</cell>";
				$s .= "<cell>City: " . $item ['city'] . "\n";
				$s .= "State: " . $item ['state'] . "\n";
				$s .= "ZIP: " . $item ['zip'] . "\n";
				$s .= "Phone: " . $item ['night_phone_a'] . "\n";
				$s .= "Address: " . $item ['address1'] . "\n";
				$s .= "Address2:" . $item ['address2'] . "</cell>";
				
				/*$s .= "<cell>" . $item ['city'] . "</cell>";
				$s .= "<cell>" . $item ['state'] . "</cell>";
				$s .= "<cell>" . $item ['zip'] . "</cell>";
				$s .= "<cell>" . $item ['address1'] . "</cell>";
				$s .= "<cell>" . $item ['address2'] . "</cell>";*/
				$s .= "<cell><![CDATA[" . $item ['id'] . "]]></cell>";
				$s .= "</row>";
				
				$i ++;
			}
			
			$s .= "</rows>";
			echo $s;
		}
		exit ();
	
	}
	
	function ajax_json_get_items_for_order_id() {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_cart'];
		
		$page = $_REQUEST ['page']; // get the requested page
		$limit = $_REQUEST ['rows']; // get how many rows we want to have into the grid
		$sidx = $_REQUEST ['sidx']; // get index row - i.e. user click to sort
		$sord = $_REQUEST ['sord']; // get the direction
		if (! $sidx) {
			$sidx = 1;
		}
		
		$wh = "";
		$searchOn = Strip ( $_REQUEST ['_search'] );
		$the_item_ids_from_search_array = array ();
		if ($searchOn == 'true') {
			$searchstr = Strip ( $_REQUEST ['filters'] );
			$wh = constructWhere ( $searchstr );
			
			if (strval ( $wh ) != '') {
				$q = " select id from $table where id is not null  $wh";
				//var_Dump($q);
				$q = CI::model('core')->dbQuery ( $q );
				if (! empty ( $q )) {
					foreach ( $q as $sresult ) {
						$some_id = $sresult ['id'];
						$the_item_ids_from_search_array [] = $some_id;
					}
				}
			}
			//var_dump($the_item_ids_from_search_array);
		}
		
		//ORDER BY ".$sidx." ".$sord. " LIMIT ".$start." , ".$limit;
		

		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$id = CI::model('core')->getParamFromURL ( 'id' );
		$items_conf = array ();
		$items_conf ['id'] = intval ( $id );
		//$items_conf ['order_completed'] = 'y';
		$order = $this->cart_model->itemsOrders ( $items_conf );
		$order = $order [0];
		
		$order_currency_code = $order ['currency_code'];
		$order_promo_code = $order ['promo_code'];
		
		$order_id = $order ['order_id'];
		
		$items_conf = array ();
		$items_conf ['order_completed'] = 'y';
		$items_conf ['order_id'] = $order_id;
		//$limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false
		

		if ($start != false and $limit != false) {
			$limits_array = array ();
			$limits_array [0] = $start;
			$limits_array [1] = $limit;
		} else {
			$limits_array = false;
		}
		
		if ($sidx != false and $sord != false) {
			$order_by_array = array ();
			$order_by_array [0] = $sidx;
			$order_by_array [1] = $sord;
		} else {
			$order_by_array = false;
		}
		//$start." , ".$limit
		

		$items = $this->cart_model->itemsGet ( $items_conf, $limits_array, false, $order_by_array, false, false, $ids = $the_item_ids_from_search_array );
		
		header ( "Content-type: text/xml;charset=utf-8" );
		
		$s = "<?xml version='1.0' encoding='utf-8'?>";
		$s .= "<rows>";
		$s .= "<page>" . $page . "</page>";
		$s .= "<total>" . count ( $items ) . "</total>";
		$s .= "<records>" . count ( $items ) . "</records>";
		
		$i = 0;
		foreach ( $items as $item ) {
			$item ['id'] = $item ['id'];
			$price_in_curency = $this->cart_model->currencyConvertPrice ( $item ['price'], $order_currency_code );
			
			$s .= "<row id='" . $item ['id'] . "'>";
			$s .= "<cell>" . $item ['sku'] . "</cell>";
			$s .= "<cell>" . $item ['created_on'] . "</cell>";
			$s .= "<cell>" . $item ['item_name'] . "</cell>";
			$s .= "<cell>" . $item ['qty'] . "</cell>";
			$s .= "<cell>" . $this->cart_model->currencyConvertPrice ( $item ['price'], $order_currency_code ) . "</cell>";
			$s .= "<cell>" . $this->cart_model->currencyConvertPrice ( ($item ['qty'] * $item ['price']), $order_currency_code ) . "</cell>";
			
			if (trim ( $item ['skip_promo_code'] ) != 'y') {
				$s .= "<cell>" . $this->cart_model->promoCodeApplyToAmount ( $price_in_curency, $order_promo_code ) . "</cell>";
			
			} else {
				$s .= "<cell>" . $price_in_curency . "</cell>";
			
			}
			
			//
			$s .= "<cell>" . floatval ( $item ['weight'] ) . "</cell>";
			$s .= "<cell>" . floatval ( $item ['qty'] * $item ['weight'] ) . "</cell>";
			$s .= "<cell>" . $item ['size'] . "</cell>";
			$s .= "<cell>" . trim ( $item ['colors'] ) . "</cell>";
			$s .= "<cell>" . $item ['sid'] . "</cell>";
			
			$s .= "<cell>" . $item ['id'] . "</cell>";
			
			$s .= "</row>";
			
			$i ++;
		}
		
		$s .= "</rows>";
		echo $s;
		
		exit ();
	
	}
	
	function ajax_json_get() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		$items_conf = array ();
		$items_conf ['order_completed'] = 'y';
		$items = $this->cart_model->itemsGet ();
		
		header ( "Content-type: text/xml;charset=utf-8" );
		
		$s = "<?xml version='1.0' encoding='utf-8'?>";
		$s .= "<rows>";
		$s .= "<page>" . 1 . "</page>";
		$s .= "<total>" . count ( $items ) . "</total>";
		$s .= "<records>" . count ( $items ) . "</records>";
		
		$i = 0;
		foreach ( $items as $item ) {
			$item ['id'] = rand ();
			$s .= "<row id='" . $item ['id'] . "'>";
			$s .= "<cell>" . $item ['id'] . "</cell>";
			$s .= "<cell>" . $item ['id'] . "</cell>";
			$s .= "<cell>" . $item ['id'] . "</cell>";
			$s .= "<cell>" . $item ['id'] . "</cell>";
			$s .= "<cell>" . $item ['id'] . "</cell>";
			$s .= "<cell><![CDATA[" . $item ['id'] . "]]></cell>";
			$s .= "</row>";
			
			$i ++;
		}
		
		$s .= "</rows>";
		echo $s;
		
		exit ();
	}
	
	function ajax_delete_item_from_order() {
		$id = intval ( $_POST ['id'] );
		if ($id == 0) {
			exit ( 'id cannot be zero of cource' );
		} else {
			$this->cart_model->itemDeleteById ( $id, $check_session = false, $only_uncompleted_orders = false );
			exit ( 'deleted' );
		}
	
	}
	
	function ajax_delete_order() {
		$id = intval ( $_POST ['id'] );
		if ($id == 0) {
			exit ( 'id cannot be zero of cource' );
		} else {
			$this->cart_model->orderDeleteById ( $id );
			exit ( 'deleted' );
		}
	
	}
	
	function promo_codes() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		$this->load->vars ( $this->template );
		
		$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';
		
		$primarycontent =$this->load->view ( 'admin/orders/promo_codes', true, true );
		
		$nav =$this->load->view ( 'admin/orders/subnav', true, true );
		$primarycontent = $nav . $primarycontent;
		
		//$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}
	
	function ajax_json_get_promo_codes() {
		global $cms_db_tables;
		if ($_POST ['oper'] == 'del') {
		
		} else {
			$table = $cms_db_tables ['table_cart_promo_codes'];
			$page = $_REQUEST ['page']; // get the requested page
			

			$limit = $_REQUEST ['rows']; // get how many rows we want to have into the grid
			$sidx = $_REQUEST ['sidx']; // get index row - i.e. user click to sort
			$sord = $_REQUEST ['sord']; // get the direction
			

			$start = $limit * ($page - 1); // do not put $limit*($page - 1)
			$end = $limit * $page; // do not put $limit*($page - 1)
			if ($start < 0) {
				$start = 0;
			}
			
			if (! $sidx) {
				$sidx = 1;
			}
			$wh = "";
			$searchOn = Strip ( $_REQUEST ['_search'] );
			$the_item_ids_from_search_array = array ();
			if ($searchOn == 'true') {
				$search_array = CI::model('core')->mapArrayToDatabaseTable ( $table, $_REQUEST );
				if (is_array ( $search_array )) {
					$qwery = '';
					$i = 0;
					foreach ( $search_array as $key => $val ) {
						$qwery .= "  AND " . $key . "  LIKE  '%" . $val . "%'  ";
					}
					if (strval ( $qwery ) != '') {
						$q = " select id from $table where id is not null  $qwery";
						//var_Dump($q);
						$q = CI::model('core')->dbQuery ( $q );
						if (! empty ( $q )) {
							foreach ( $q as $sresult ) {
								$some_id = $sresult ['id'];
								$the_item_ids_from_search_array [] = $some_id;
							}
						}
					}
				}
			
			}
			
			$limits_array = array ();
			$limits_array [0] = $start;
			$limits_array [1] = $end;
			
			if ($sidx != false and $sord != false) {
				$order_by_array = array ();
				$order_by_array [0] = $sidx;
				$order_by_array [1] = $sord;
			} else {
				$order_by_array = false;
			}
			
			$this->template ['functionName'] = strtolower ( __FUNCTION__ );
			
			$items_conf = array ();
			//$items_conf ['order_completed'] = 'y';
			$items = $this->cart_model->promoCodesGet ( $items_conf, $limits_array, false, $order_by_array, false, false, $ids = $the_item_ids_from_search_array );
			$items_count = $this->cart_model->promoCodesGet ( $items_conf, $limits_array = false, false, $order_by_array, false, false, $ids = $the_item_ids_from_search_array, $count_only = true );
			
			header ( "Content-type: text/xml;charset=utf-8" );
			
			$s = "<?xml version='1.0' encoding='utf-8'?>";
			$s .= "<rows>";
			$s .= "<page>" . $page . "</page>";
			$s .= "<total>" . ceil ( $items_count / $_REQUEST ['rows'] ) . "</total>";
			$s .= "<records>" . $items_count . "</records>";
			
			$i = 0;
			foreach ( $items as $item ) {
				$item ['id'] = $item ['id'];
				$s .= "<row id='" . $item ['id'] . "'>";
				$s .= "<cell>" . $item ['promo_code'] . "</cell>";
				$s .= "<cell>" . $item ['created_on'] . "</cell>";
				$s .= "<cell>" . $item ['amount_modifier'] . "</cell>";
				$s .= "<cell>" . $item ['amount_modifier_type'] . "</cell>";
				$s .= "<cell>" . $item ['description'] . "</cell>";
				$s .= "<cell><![CDATA[" . $item ['id'] . "]]></cell>";
				$s .= "</row>";
				$i ++;
			}
			
			$s .= "</rows>";
			echo $s;
		}
		exit ();
	
	}
	
	function ajax_json_edit_promo_code() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$this->cart_model->promoCodeSave ( $_POST );
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		exit ();
	}
	function ajax_delete_promo_code() {
		$id = intval ( $_POST ['id'] );
		if ($id == 0) {
			exit ( 'id cannot be zero of cource' );
		} else {
			$this->cart_model->promoCodeDeleteById ( $id );
			exit ( 'deleted' );
		}
	
	}
	
	function shipping_cost() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		$this->load->vars ( $this->template );
		
		$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';
		
		$primarycontent =$this->load->view ( 'admin/orders/shipping_cost', true, true );
		
		$nav =$this->load->view ( 'admin/orders/subnav', true, true );
		$primarycontent = $nav . $primarycontent;
		
		//$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}
	
	function ajax_json_get_shipping_costs() {
		global $cms_db_tables;
		if ($_POST ['oper'] == 'del') {
		
		} else {
			$table = $cms_db_tables ['table_cart_orders_shipping_cost'];
			$page = $_REQUEST ['page']; // get the requested page
			

			$limit = $_REQUEST ['rows']; // get how many rows we want to have into the grid
			$sidx = $_REQUEST ['sidx']; // get index row - i.e. user click to sort
			$sord = $_REQUEST ['sord']; // get the direction
			

			$start = $limit * ($page - 1); // do not put $limit*($page - 1)
			$end = $limit * $page; // do not put $limit*($page - 1)
			if ($start < 0) {
				$start = 0;
			}
			
			if (! $sidx) {
				$sidx = 1;
			}
			$wh = "";
			$searchOn = Strip ( $_REQUEST ['_search'] );
			$the_item_ids_from_search_array = array ();
			if ($searchOn == 'true') {
				$search_array = CI::model('core')->mapArrayToDatabaseTable ( $table, $_REQUEST );
				if (is_array ( $search_array )) {
					$qwery = '';
					$i = 0;
					foreach ( $search_array as $key => $val ) {
						$qwery .= "  AND " . $key . "  LIKE  '%" . $val . "%'  ";
					}
					if (strval ( $qwery ) != '') {
						$q = " select id from $table where id is not null  $qwery";
						//var_Dump($q);
						$q = CI::model('core')->dbQuery ( $q );
						if (! empty ( $q )) {
							foreach ( $q as $sresult ) {
								$some_id = $sresult ['id'];
								$the_item_ids_from_search_array [] = $some_id;
							}
						}
					}
				}
			
			}
			
			$limits_array = array ();
			$limits_array [0] = $start;
			$limits_array [1] = $end;
			
			if ($sidx != false and $sord != false) {
				$order_by_array = array ();
				$order_by_array [0] = $sidx;
				$order_by_array [1] = $sord;
			} else {
				$order_by_array = false;
			}
			
			$this->template ['functionName'] = strtolower ( __FUNCTION__ );
			
			$items_conf = array ();
			//$items_conf ['order_completed'] = 'y';
			$items = $this->cart_model->shippingCostsGet ( $items_conf, $limits_array, false, $order_by_array, false, false, $ids = $the_item_ids_from_search_array );
			$items_count = $this->cart_model->shippingCostsGet ( $items_conf, $limits_array = false, false, $order_by_array, false, false, $ids = $the_item_ids_from_search_array, $count_only = true );
			
			header ( "Content-type: text/xml;charset=utf-8" );
			
			$s = "<?xml version='1.0' encoding='utf-8'?>";
			$s .= "<rows>";
			$s .= "<page>" . $page . "</page>";
			$s .= "<total>" . ceil ( $items_count / $_REQUEST ['rows'] ) . "</total>";
			$s .= "<records>" . $items_count . "</records>";
			
			$i = 0;
			foreach ( $items as $item ) {
				$item ['id'] = $item ['id'];
				$s .= "<row id='" . $item ['id'] . "'>";
				$s .= "<cell>" . $item ['created_on'] . "</cell>";
				$s .= "<cell>" . $item ['is_active'] . "</cell>";
				$s .= "<cell>" . $item ['ship_to_continent'] . "</cell>";
				$s .= "<cell>" . $item ['shiping_cost_per_item'] . "</cell>";
				$s .= "<cell><![CDATA[" . $item ['id'] . "]]></cell>";
				$s .= "</row>";
				$i ++;
			}
			
			$s .= "</rows>";
			echo $s;
		}
		exit ();
	
	}
	function ajax_json_edit_shipping_costs() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$this->cart_model->shippingCostsSave ( $_POST );
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		exit ();
	}
	
	function ajax_delete_shipping_costs() {
		$id = intval ( $_POST ['id'] );
		if ($id == 0) {
			exit ( 'id cannot be zero of cource' );
		} else {
			$this->cart_model->shippingCostsDeleteById ( $id );
			exit ( 'deleted' );
		}
	
	}
	
	function currencies() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		
		$this->load->vars ( $this->template );
		
		$layout =$this->load->view ( 'admin/layout', true, true );
		$primarycontent = '';
		$secondarycontent = '';
		
		$primarycontent =$this->load->view ( 'admin/orders/currency', true, true );
		
		$nav =$this->load->view ( 'admin/orders/subnav', true, true );
		$primarycontent = $nav . $primarycontent;
		
		//$secondarycontent =$this->load->view ( 'admin/content/sidebar', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$layout = str_ireplace ( '{secondarycontent}', $secondarycontent, $layout );
		//CI::view('welcome_message');
		CI::library('output')->set_output ( $layout );
	}
	
	function ajax_json_get_currencies() {
		global $cms_db_tables;
		if ($_POST ['oper'] == 'del') {
		
		} else {
			$table = $cms_db_tables ['table_cart_currency'];
			$page = $_REQUEST ['page']; // get the requested page
			

			$limit = $_REQUEST ['rows']; // get how many rows we want to have into the grid
			$sidx = $_REQUEST ['sidx']; // get index row - i.e. user click to sort
			$sord = $_REQUEST ['sord']; // get the direction
			

			$start = $limit * ($page - 1); // do not put $limit*($page - 1)
			$end = $limit * $page; // do not put $limit*($page - 1)
			if ($start < 0) {
				$start = 0;
			}
			
			if (! $sidx) {
				$sidx = 1;
			}
			$wh = "";
			$searchOn = Strip ( $_REQUEST ['_search'] );
			$the_item_ids_from_search_array = array ();
			if ($searchOn == 'true') {
				$search_array = CI::model('core')->mapArrayToDatabaseTable ( $table, $_REQUEST );
				if (is_array ( $search_array )) {
					$qwery = '';
					$i = 0;
					foreach ( $search_array as $key => $val ) {
						$qwery .= "  AND " . $key . "  LIKE  '%" . $val . "%'  ";
					}
					if (strval ( $qwery ) != '') {
						$q = " select id from $table where id is not null  $qwery";
						//var_Dump($q);
						$q = CI::model('core')->dbQuery ( $q );
						if (! empty ( $q )) {
							foreach ( $q as $sresult ) {
								$some_id = $sresult ['id'];
								$the_item_ids_from_search_array [] = $some_id;
							}
						}
					}
				}
			
			}
			
			$limits_array = array ();
			$limits_array [0] = $start;
			$limits_array [1] = $end;
			
			if ($sidx != false and $sord != false) {
				$order_by_array = array ();
				$order_by_array [0] = $sidx;
				$order_by_array [1] = $sord;
			} else {
				$order_by_array = false;
			}
			
			$this->template ['functionName'] = strtolower ( __FUNCTION__ );
			
			$items_conf = array ();
			//$items_conf ['order_completed'] = 'y';
			$items = $this->cart_model->currenciesGet ( $items_conf, $limits_array, false, $order_by_array, false, false, $ids = $the_item_ids_from_search_array );
			$items_count = $this->cart_model->currenciesGet ( $items_conf, $limits_array = false, false, $order_by_array, false, false, $ids = $the_item_ids_from_search_array, $count_only = true );
			
			header ( "Content-type: text/xml;charset=utf-8" );
			
			$s = "<?xml version='1.0' encoding='utf-8'?>";
			$s .= "<rows>";
			$s .= "<page>" . $page . "</page>";
			$s .= "<total>" . ceil ( $items_count / $_REQUEST ['rows'] ) . "</total>";
			$s .= "<records>" . $items_count . "</records>";
			
			$i = 0;
			foreach ( $items as $item ) {
				$item ['id'] = $item ['id'];
				$s .= "<row id='" . $item ['id'] . "'>";
				
				$s .= "<cell>" . $item ['currency_from'] . "</cell>";
				$s .= "<cell>" . $item ['currency_to'] . "</cell>";
				$s .= "<cell>" . $item ['currency_rate'] . "</cell>";
				$s .= "<cell><![CDATA[" . $item ['id'] . "]]></cell>";
				$s .= "</row>";
				$i ++;
			}
			
			$s .= "</rows>";
			echo $s;
		}
		exit ();
	
	}
	function ajax_json_edit_currency() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		$this->cart_model->currencySave ( $_POST );
		CI::model('core')->cacheDelete ( 'cache_group', 'cart' );
		exit ();
	}
	
	function ajax_delete_currency() {
		$id = intval ( $_POST ['id'] );
		if ($id == 0) {
			exit ( 'id cannot be zero of cource' );
		} else {
			$this->cart_model->currencyDeleteById ( $id );
			exit ( 'deleted' );
		}
	
	}

}

