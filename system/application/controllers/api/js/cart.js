mw.cart = {};

mw.cart.add = function($form_selector, $callback) {
	$data = ($($form_selector).serialize());

	$.ajax( {
		type : "POST",
		url : "{SITE_URL}api/cart/add",
		data : $data,
		success : function(msg) {
			//alert("Data: " + msg);
		 // $('.cart_items_qty').html(msg);
		mw.cart.update_info();
		
		
		if (typeof  $callback == 'function') {
			$callback.call(this);
		} else {
			$($callback).fadeOut();
		}
		
		
		
		
			
		}
	});

}

mw.cart.remove_item = function($item_id_in_cart) {

	var answer = confirm("Are you sure you want to remove this item from your cart?")
	if (answer) {

		$.ajax( {
					type : "POST",
					async : false,
					url : "{SITE_URL}api/cart/remove_item",
					data : "id=" + $item_id_in_cart,
					success : function(data) {
						$.post(
										"<? print site_url('ajax_helpers/cart_itemsGetQty'); ?>",
										{
											name : "John",
											time : "2pm"
										},
										function(data) {
											data = parseInt(data);
											if (data == 0) {

											} else {
												$(
														'#cart_item_row_' + $item_id_in_cart)
														.fadeOut();
											}
										});
						mw.cart.update_info();
					}
				});

	} else {

	}

}

mw.cart.modify_item_properties = function($item_id_in_cart, $propery_name,$new_value) {

	$.ajax( {
		type : "POST",
		url : "<? print site_url('api/cart/modify_item_properties'); ?>",
		data : "id=" + $item_id_in_cart + "&propery_name=" + $propery_name
				+ "&new_value=" + $new_value,
		async : false,
		success : function(data) {
		mw.cart.update_info();
		}
	});
}


mw.cart.update_info = function() {
	
	$.ajax( {
		type : "POST",
		dataType: 'json',
		url : "{SITE_URL}api/cart/order_info",
		//data : $data,
		success : function(msg) {
			//alert("Data: " + msg.qty);
		  $('.cart_items_qty').html(msg.qty);
		  $('.cart_items_total').html(msg.total);
			
		}
	});
	
	
	
}


mw.cart.complete_order = function($form_selector, $hide_selector, $show_selector) {
	$data = ($($form_selector).serialize());

	// / alert($data);

	$.ajax( {
		type : "POST",
		url : "<? print site_url('api/cart/complete_order'); ?>",
		data : $data,
		async : false,
		success : function(data) {
			//alert(data);
		  if($hide_selector != undefined ){
			  
			  $($hide_selector).fadeOut();
			   
		  }
		  
		  
  if($show_selector != undefined ){
			  
			  $($show_selector).fadeIn();
			   
		  }
  
  mw.cart.update_info();
		  
		  
		  
		  
		}
	});
}



mw.cart.delete_order = function($id, $selector_to_hide) {
 
	var answer = confirm("WARNING: There is no turning back! Are you sure you want to delete this order? ")
	if (answer) {
		// / alert($data);
	
		$.ajax( {
			type : "POST",
			url : "<? print site_url('api/cart/delete_order'); ?>",
			data : "id=" + $id ,
			async : true,
			success : function(data) {
			 
			  if($selector_to_hide != undefined ){
				  
				  $($selector_to_hide).fadeOut();
				   
			  }
			
			
			}
		});
	}
}







mw.cart.check_promo_code = function($code) {
	
	
	if($code == undefined){
		var code_check = $('#the_promo_code_input').val();
		
	} else {
		var code_check = $code
			
	}
	
	
	$.ajax({
		   type: "POST",
		   url: "<?php print site_url('api/cart/get_promo_code'); ?>",
		   data: "code=" + code_check,
		   dataType: 'json',
		   success: function(msg){
		  if(msg.promo_code != undefined){
		 $('#the_promo_code_input').val(msg.promo_code);
			  $('#the_promo_code_status').show();
			  $('#the_promo_code_status').html(msg.description);
			  mw.cart.update_info();
		  }
		     
		     
		   }
		 });

 
	
}


















