// JavaScript Document
mw.require('forms.js');


mw.admin_backup = {
  create : function(selector){
	 //  data = mw.$(selector+' input').serialize();
	 
	 var ifr = "<iframe src='"+	 mw.settings.api_url+'admin/backup/api/create'+"'></iframe>"
	 
	  mw.$(selector).append(ifr);
	 
	 
	 
  },
  
   remove : function($id, $selector_to_hide){
	  
	  data = {}
	  data.id=$id;
	 
     $.post(mw.settings.api_url+'admin/backup/api/delete', data ,
     function(data) {
		 if($selector_to_hide != undefined){
		 mw.$($selector_to_hide).fadeOut().remove();
		 }
     });
  },

 
}











