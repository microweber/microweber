// JavaScript Document
mw.require('forms.js');
mw.require('tools.js');
 
mw.template_exporter = {

	 

	download : function(filename){
	 //  data = mw.$(selector+' input').serialize();

	 mw.notification.success("Backup started...");
	 $.post(mw.settings.api_url+'admin/developer_tools/template_exporter/Worker/download', false ,
	 	function(msg) {
	 		mw.reload_module('admin/developer_tools/template_exporter/manage');
	 		mw.notification.msg(msg);
	 	});

	},


	 

 

	create : function(selector){
		
		mw.load_module( 'admin/developer_tools/template_exporter/log',"#mw_backup_log");
	 
        mw.reload_module_interval("#mw_backup_log", 3000);
            
			
			
		mw.notification.success("Full Backup is started...",7000);
	 
		$.post(mw.settings.api_url+'admin/developer_tools/template_exporter/Worker/create_full', false ,
			function(msg) {
				mw.reload_module('admin/developer_tools/template_exporter/manage');
				mw.notification.msg(msg);
			});

	},



	remove : function($id, $selector_to_hide){

        mw.tools.confirm(mw.msg.del, function(){
      		data = {}
      		data.id=$id;


      		$.post(mw.settings.api_url+'admin/developer_tools/template_exporter/Worker/delete', data ,
      			function(resp) {
      			 //mw.reload_module('admin/developer_tools/template_exporter/manage');
      			 mw.notification.msg(resp);
      			 if($selector_to_hide != undefined){
      			 	$($selector_to_hide).fadeOut().remove();

      			 }                
      			}
      			);
        })
	} 
}
