// JavaScript Document
mw.require('forms.js');
mw.require('tools.js');

mw.admin_backup = {
  create : function(selector){
	 //  data = mw.$(selector+' input').serialize();
	 
	   mw.notification.success("Backup started...");
	  $.post(mw.settings.api_url+'admin/backup/api/create', false ,
     function(msg) {
		mw.reload_module('admin/backup/manage');
		 mw.notification.success("Backup is created");
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
		 
		 
		 mw.notification.warning("Backup is deleted");
     $.post(mw.settings.api_url+'admin/backup/api/delete', data ,
     function(msg) {
		 
		
     });
  },
  
 

 
}











