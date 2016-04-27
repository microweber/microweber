// JavaScript Document
mw.require('forms.js');
mw.require('tools.js');
 
mw.admin_import = {
	create : function(selector){
	 //  data = mw.$(selector+' input').serialize();

	 mw.notification.success("Import started...");
	 $.post(mw.settings.api_url+'Microweber/Utils/Import/create', false ,
	 	function(msg) {
	 		mw.reload_module('admin/import/manage');
	 		mw.notification.msg(msg, 5000);
	 	});

	},

	download : function(filename){
	 //  data = mw.$(selector+' input').serialize();

	 mw.notification.success("Import started...");
	 $.post(mw.settings.api_url+'Microweber/Utils/Import/create', false ,
	 	function(msg) {
	 		mw.reload_module('admin/import/manage');
	 		mw.notification.msg(msg, 15000);
	 	});

	},

	restore_to_page : function(src,page_id){
		data = {}
		data.id=src;
		data.import_to_page_id=page_id;
		$.post(mw.settings.api_url+'Microweber/Utils/Import/restore', data ,
			function(msg) {
				mw.reload_module('admin/import/manage');
				mw.notification.msg(msg, 15000);
				//mw.reload_module('admin/import/process');
			});
	},

	restore : function(src){
		data = {}
		data.id=src;
		$.post(mw.settings.api_url+'Microweber/Utils/Import/restore', data ,
			function(msg) {
				mw.reload_module('admin/import/manage');
				mw.notification.msg(msg, 15000);
				//mw.reload_module('admin/import/process');
			});
	},

	move_uploaded_file_to_import : function(src){

		data = {}
		data.src=src;
		$.post(mw.settings.api_url+'Microweber/Utils/Import/move_uploaded_file_to_import', data ,
			function(msg) {
				mw.reload_module('admin/import/manage');
				mw.notification.msg(msg, 5000);
			});

	},



	create_full : function(selector){
		
		mw.load_module( 'admin/import/log',"#mw_import_log");
	 
        mw.reload_module_interval("#mw_import_log", 5000);
            
			
			
		mw.notification.success("FULL Import is started...");
	 
		$.post(mw.settings.api_url+'Microweber/Utils/Import/create_full', false ,
			function(msg) {
				mw.reload_module('admin/import/manage');
				mw.notification.msg(msg);
			});

	},



	remove : function($id, $selector_to_hide){

        mw.tools.confirm(mw.msg.del, function(){
      		data = {}
      		data.id=$id;


      		$.post(mw.settings.api_url+'Microweber/Utils/Import/delete', data ,
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
	
	
	start_batch_process : function(){
	 
		$.post(mw.settings.api_url+'Microweber/Utils/Import/batch_process', {process:true} ,
		
			function(msg) {
				
				
				if(typeof(msg.percent) != 'undefined'){
				$('#import-progress-log-holder:hidden').show();	
				 if(typeof(msg.total) != 'undefined' && typeof(msg.remaining) != 'undefined'){
					 
				$('#import-progress-log-holder-values').html(msg.remaining+'/'+msg.total);		 
				 }
				 
	 
	 
	 
				$('#import-progress-log-meter').attr('value',msg.percent);	
			 
				} else {
				$('#import-progress-log-holder:visible').hide();	
	
				}
				
				 
				
				//mw.reload_module('admin/import/process');
				setTimeout(function(){ 
				mw.admin_import.start_batch_process();
				
				 }, 500);
 

			
			}).error(function(){
								mw.admin_import.start_batch_process();

				});

	},
	cancel_batch_process : function(){
	 var r = confirm("Are you sure you want to cancel the import?");
		if (r == true) {
		$.post(mw.settings.api_url+'Microweber/Utils/Import/batch_process', {cancel:true} ,
			function(msg) {
				
				
			});
		} 


		

	},
			
	
	
	
	
	
}
