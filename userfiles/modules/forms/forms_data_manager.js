// JavaScript Document
mw.require('forms.js');


mw.forms_data_manager = {
  delete : function(id, selector){
data = {}
data.id = id;
    mw.tools.confirm(mw.msg.del, function(){
       $.post(mw.settings.api_url+'delete_form_entry', data ,
         function(data) {
            if(selector != undefined){
            	$(selector).fadeOut(function(){
            	  $(this).remove();
            	});
            }
         });
     });
  }
}