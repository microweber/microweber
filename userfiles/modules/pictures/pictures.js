// JavaScript Document

mw.module.pictures = {}



mw.module.pictures.after_upload  = function(data){
 $.post(mw.settings.api_url+'save_media', data ,
   function(data) {
	   mw.reload_module('pictures')
	  // mw.log(data);
     //("Data Loaded: " + data);
	 if(mw.tools != undefined){
	 mw.tools.modal.remove('mw_rte_image');
	 }
   });
}



mw.module.pictures.del  = function($id){
 $.post(mw.settings.api_url+'delete_media', { id: $id   } ,
   function(data) {
	  
	   mw.reload_module('pictures')
 
   });
}


