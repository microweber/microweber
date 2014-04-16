// JavaScript Document
mw.require('forms.js');
mw.require('tools.js');
 
mw.admin_import = {
	create : function(selector){
	 //  data = mw.$(selector+' input').serialize();

	 mw.notification.success("Import started...");
	 $.post(mw.settings.api_url+'Utils/Import/create', false ,
	 	function(msg) {
	 		mw.reload_module('admin/import/manage');
	 		mw.notification.msg(msg, 5000);
	 	});

	},

	download : function(filename){
	 //  data = mw.$(selector+' input').serialize();

	 mw.notification.success("Import started...");
	 $.post(mw.settings.api_url+'Utils/Import/create', false ,
	 	function(msg) {
	 		mw.reload_module('admin/import/manage');
	 		mw.notification.msg(msg, 5000);
	 	});

	},

	restore_to_page : function(src,page_id){
		data = {}
		data.id=src;
		data.import_to_page_id=page_id;
		$.post(mw.settings.api_url+'Utils/Import/restore', data ,
			function(msg) {
				mw.reload_module('admin/import/manage');
				mw.notification.msg(msg, 5000);
				mw.reload_module('admin/import/process');
			});
	},

	restore : function(src){
		data = {}
		data.id=src;
		$.post(mw.settings.api_url+'Utils/Import/restore', data ,
			function(msg) {
				mw.reload_module('admin/import/manage');
				mw.notification.msg(msg, 5000);
				mw.reload_module('admin/import/process');
			});
	},

	move_uploaded_file_to_import : function(src){

		data = {}
		data.src=src;
		$.post(mw.settings.api_url+'Utils/Import/move_uploaded_file_to_import', data ,
			function(msg) {
				mw.reload_module('admin/import/manage');
				mw.notification.msg(msg, 5000);
			});

	},



	create_full : function(selector){
		
		mw.load_module( 'admin/import/log',"#mw_import_log");
	 
        mw.reload_module_interval("#mw_import_log", 5000);
            
			
			
		mw.notification.success("FULL Import is started...");
	 
		$.post(mw.settings.api_url+'Utils/Import/create_full', false ,
			function(msg) {
				mw.reload_module('admin/import/manage');
				mw.notification.msg(msg);
			});

	},



	remove : function($id, $selector_to_hide){

        mw.tools.confirm(mw.msg.del, function(){
      		data = {}
      		data.id=$id;


      		$.post(mw.settings.api_url+'Utils/Import/delete', data ,
      			function(resp) {
      			 //mw.reload_module('admin/import/manage');
      			 mw.notification.msg(resp);
      			 if($selector_to_hide != undefined){
      			 	$($selector_to_hide).fadeOut().remove();

      			 }                
      			}
      			);
        })
	},
}
