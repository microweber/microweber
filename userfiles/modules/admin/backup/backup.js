// JavaScript Document
mw.require('forms.js');
mw.require('tools.js');
 
mw.admin_backup = {
	create : function(selector){
	 //  data = mw.$(selector+' input').serialize();

	 mw.notification.success("Backup started...");
	 $.post(mw.settings.api_url+'Microweber/Utils/Backup/create', false ,
	 	function(msg) {
	 		mw.reload_module('admin/backup/manage');
	 		mw.notification.msg(msg);
	 	});

	},
	
	create_template_export : function(){
	
	 mw.notification.success("Export started...");
	 $.post(mw.settings.api_url+'Microweber/Utils/Backup/create_template_export', false ,
	 	function(msg) {
			mw.reload_module_interval("#mw_backup_log", 5000);

	 		mw.reload_module('admin/backup/manage');
	 		mw.notification.msg(msg);
	 	});

	},

	download : function(filename){
	 //  data = mw.$(selector+' input').serialize();

	 mw.notification.success("Backup started...");
	 $.post(mw.settings.api_url+'Microweber/Utils/Backup/create', false ,
	 	function(msg) {
	 		mw.reload_module('admin/backup/manage');
	 		mw.notification.msg(msg);
	 	});

	},


	restore : function(src,loading_element){
		
 
		var r = confirm("Are you sure you want to restore this backup? All existing content will be replaced!");
		if (r == true) {
			
			if(typeof(loading_element) != 'undefined'){
				$(loading_element).addClass('restoring-backup');
			}
			
			
		   mw.notification.success("Backup restoration started",7000);
				data = {}
				data.id=src;
				$.post(mw.settings.api_url+'Microweber/Utils/Backup/restore', data ,
					function(msg) {
						
						if(typeof(loading_element) != 'undefined'){
								$(loading_element).removeClass('restoring-backup');
						}
						mw.reload_module('admin/backup/manage');
						mw.notification.msg(msg,10000);
					});
		}  



		
		

	},

	move_uploaded_file_to_backup : function(src){

		data = {}
		data.src=src;
		$.post(mw.settings.api_url+'Microweber/Utils/Backup/move_uploaded_file_to_backup', data ,
			function(msg) {
				mw.reload_module('admin/backup/manage');
				mw.notification.msg(msg);
			});

	},



	create_full : function(selector){
		
		mw.load_module( 'admin/backup/log',"#mw_backup_log");
	 
        mw.reload_module_interval("#mw_backup_log", 5000);
            
			
			
		mw.notification.success("Full Backup is started...",7000);
	 
		$.post(mw.settings.api_url+'Microweber/Utils/Backup/create_full', false ,
			function(msg) {
				mw.reload_module('admin/backup/manage');
				mw.notification.msg(msg);
			});

	},



	remove : function($id, $selector_to_hide){

        mw.tools.confirm(mw.msg.del, function(){
      		data = {}
      		data.id=$id;


      		$.post(mw.settings.api_url+'Microweber/Utils/Backup/delete', data ,
      			function(resp) {
      			 //mw.reload_module('admin/backup/manage');
      			 mw.notification.msg(resp);
      			 if($selector_to_hide != undefined){
      			 	$($selector_to_hide).fadeOut().remove();

      			 }                
      			}
      			);
        })
	},
}
