// JavaScript Document
mw.require('forms.js');


mw.cart = {
  add : function(selector){
	  
	  
	 //  data = mw.$(selector+' input').serialize();
	  
	   data = mw.form.serialize(selector);
     $.post(mw.settings.api_url+'update_cart', data ,
     function(data) {
		 mw.reload_module('shop/cart');
      //   mw.$('#tagline').html(data);
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
	  
	   data = mw.form.serialize(selector);
     $.post(mw.settings.api_url+'checkout', data ,
     function(data) {
		 
		 if(data != undefined){
			 if(parseInt(data) > 0){
			 
			 
			 mw.$(selector).parents('[data-type="shop/checkout"]').attr('view', 'completed');
			 mw.reload_module('shop/checkout');
			 mw.reload_module('shop/cart');
			 
			 } else {
				alert(data); 
				 
			 }
		 }
		 
		// mw.reload_module('shop/cart');
      // mw.$('#sidebar').html(data);
     });
  
  return false;
  
  }
}






