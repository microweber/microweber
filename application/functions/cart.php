<?php

function get_items_qty() {
	
	$data = $this->cart_model->itemsGetQty ();
	return intval ( $data );
}

function get_cart_items($params) {
	
	$sid =  get_instance()->session->userdata ( 'session_id' );
	$cart_item = $params;
	if ($cart_item ['order_completed'] == false) {
		$cart_item ['order_completed'] = 'n';
	}
	$cart_item ['sid'] = $sid;
	
	$cart_items = $this->cart_model->itemsGet ( $cart_item );
	return $cart_items;
}

function get_cart_total() {
	
	$promo_code =  get_instance()->session->userdata ( 'promo_code' );
	if (strval ( $promo_code ) == '') {
		
		$promo_item = array ();
		$promo_item ['auto_apply_to_all'] = 'y';
		
		$promo_item = $this->cart_model->itemsGet ( $promo_item );
		if (! empty ( $promo_item )) {
			$promo_item = $promo_item [0];
			 get_instance()->session->set_userdata ( 'promo_code', $promo_item ['promo_code'] );
			 get_instance()->session->set_userdata ( 'cart_promo_code', $promo_item ['promo_code'] );
			$promo_code = $promo_item ['promo_code'];
		}
	
	}
	
	$total = ($this->cart_model->itemsGetTotal ( $promo_code ));
	return $total;
}