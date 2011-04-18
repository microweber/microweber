<?php

function get_items_qty() {
	
	$data = CI::model ( 'cart' )->itemsGetQty ();
	return intval ( $data );
}

function get_cart_items($params) {
	
	$sid = CI::library ( 'session' )->userdata ( 'session_id' );
	$cart_item = $params;
	if ($cart_item ['order_completed'] == false) {
		$cart_item ['order_completed'] = 'n';
	}
	$cart_item ['sid'] = $sid;
	
	$cart_items = CI::model ( 'cart' )->itemsGet ( $cart_item );
	return $cart_items;
}

function get_cart_total() {
	
	$promo_code = CI::library ( 'session' )->userdata ( 'promo_code' );
	$total = (CI::model ( 'cart' )->itemsGetTotal ( $promo_code ));
	return $total;
}