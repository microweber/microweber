// JavaScript Document
mw.require('forms.js');


mw.forms_data_manager = {
  delete : function(id, selector){
data = {}
data.id = id;
     $.post(mw.settings.api_url+'delete_form_entry', data ,
     function(data) {
		 //mw.reload_module('shop/cart');
if(selector != undefined){
	$(selector).fadeOut();
}


     });
  }

}