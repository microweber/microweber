// JavaScript Document
mw.require('forms.js');


mw.cart = {
  add : function(selector, price){
	 //  data = mw.$(selector+' input').serialize();
	 data = mw.form.serialize(selector);
	 
	 if(price != undefined && data != undefined){
		data.price= price
	 }
	  
	 
	 
     $.post(mw.settings.api_url+'update_cart', data ,
     function(data) {
		 mw.reload_module('shop/cart');
     });
  },
  
   remove : function($id){
	  
	  data = {}
	  data.id=$id;
	 
     $.post(mw.settings.api_url+'remove_cart_item', data ,
     function(data) {
		 
		 
		 mw.$('.mw-cart-item-'+$id).fadeOut().remove();
		 
		 
		// mw.reload_module('shop/cart');
      //   mw.$('#tagline').html(data);
     });
  },


  qty : function($id, $qty){
	  
	  data = {}
	  data.id=$id;
	  data.qty= $qty;
	 
     $.post(mw.settings.api_url+'update_cart_item_qty', data ,
     function(data) {
		 
		 
		// mw.$('.mw-cart-item-'+$id).fadeOut().remove();
		 
		 
		// mw.reload_module('shop/cart');
      //   mw.$('#tagline').html(data);
     });
  },
  
    checkout : function(selector){
	   
	  
	 //  data = mw.$(selector+' input').serialize();

	   var obj = mw.form.serialize(selector);
	  //  alert(obj.payment_gw);
		
					
					
			 
			$.ajax({
			  type: "POST",
			  
			  url: mw.settings.api_url+'checkout',
			  data: obj
			}).done(function( data ) {
					 if(data != undefined){
						 if(parseInt(data) > 0){
							 mw.$('[data-type="shop/checkout"]').attr('view', 'completed');
							  //mw.reload_module('shop/cart');
							 mw.reload_module('shop/checkout');
							 
			
						 } else {
						 					 
							 if(obj.payment_gw != undefined){
								 var callback_func = obj.payment_gw+'_checkout';
								
								 if(typeof window[callback_func] === 'function'){
									window[callback_func](data,selector);
								 }
								 
								 
								 var callback_func = 'checkout_callback';
								 if(typeof window[callback_func] === 'function'){
									window[callback_func](data,selector);
								 }
							 }
						 }
					 }
				 
			  
			  
				return false;
			  
			  
			  
			  
			  
			});



 
		
 
  

  
  }
}













