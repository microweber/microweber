// JavaScript Document
mw.require('forms.js');


mw.admin_backup = {
  create : function(selector){
	 //  data = mw.$(selector+' input').serialize();
	 
	 
	  $.post(mw.settings.api_url+'admin/backup/api/create', false ,
     function(msg) {
		mw.reload_module('admin/backup/manage');
     });
	 
	 
	 
	 
	// var ifr = "<iframe src='"+	 mw.settings.api_url+'admin/backup/api/create'+"'></iframe>"
	 
	//  mw.$(selector).append(ifr);
	 
	 
	 
  },
  
   remove : function($id, $selector_to_hide){
	  
	  data = {}
	  data.id=$id;
	  
	  if($selector_to_hide != undefined){
		  $($selector_to_hide).fadeOut().remove();
		 }
		 
		 
		 
     $.post(mw.settings.api_url+'admin/backup/api/delete', data ,
     function(msg) {
		 
		
     });
  },
  
 

 
}











