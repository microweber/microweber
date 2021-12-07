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
  },


   rename_form_list : function(list_id, new_title){
      data = {}
      data.id = list_id;
	  data.title = new_title;

       $.post(mw.settings.api_url+'save_form_list', data ,
         function(resp) {
            mw.notification.msg(resp);
         });

  },



   delete_list:function(id){
   mw.tools.confirm(mw.msg.del, function(){


	    data = {}
      data.id = id;

       $.post(mw.settings.api_url+'delete_forms_list', data ,
         function(resp) {
            mw.notification.msg(resp);

         });

       mw.reload_module('settings/list');
   	});
  },

  export_to_excel:function(id){

	  data = {}
      data.id = id;

       $.post(mw.settings.api_url+'forms_list_export_to_excel', data ,
         function(resp) {
            mw.notification.msg(resp);
			if(resp.download != undefined){
			    window.location= resp.download;
			}
         });

  }


}
