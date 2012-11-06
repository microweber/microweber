// JavaScript Document
mw.require('forms.js');


mw.cart = {
  add : function(selector){
	  
	  
	 //  data = mw.$(selector+' input').serialize();
	  
	   data = mw.form.serialize(selector);
     $.post(mw.settings.api_url+'update_cart', data ,
     function(data) {
         mw.$('#tagline').html(data);
     });
  }
}






