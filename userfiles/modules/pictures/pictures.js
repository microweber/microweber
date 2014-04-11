// JavaScript Document

mw.module_pictures = {
  after_upload : function(data){
     $.post(mw.settings.api_url+'save_media', data ,
     function(data) {
      
     });
  },

  save_title : function(id,title){

  	 var data = {};
	 data.id = id;
	 data.title = title;



     $.post(mw.settings.api_url+'save_media', data ,
     function(data) {
		 
		
		 
		 
	    if(window.parent != undefined && window.parent.mw != undefined){
  	 window.parent.mw.reload_module('pictures');
 }
     });
  },

  del: function($id){
   if(confirm('Are you sure you want to delete this image?')){
     $.post(mw.settings.api_url+'delete_media', { id: $id  }, function(data) {
		 $('.admin-thumb-item-'+$id).fadeOut(); 
        mw.reload_module('pictures');
		
			    if(window.parent != undefined && window.parent.mw != undefined){
					 window.parent.mw.reload_module('pictures');
					 window.parent.mw.reload_module('shop/products');  
				 }
     });
   }
  },
  init:function(selector){
    var el = $(selector);
	//d(el);
    el.sortable({
        items:".admin-thumb-item",
        placeholder:'admin-thumb-item-placeholder',
        update: function(){
          var serial = el.sortable('serialize');
          $.ajax({
            url: mw.settings.api_url+'reorder_media',
            type:"post",
            data:serial
          })
		     
			 
			 
			  
			 
			 
			  if(window.parent != undefined && window.parent.mw != undefined){
				 
				 window.parent.mw.reload_module('pictures');
				   setTimeout(function(){
					 window.parent.mw.reload_module('posts');   
					  window.parent.mw.reload_module('shop/products');     
				  },100)
				
			}
			 setTimeout(function(){
		  mw.reload_module('pictures')
 		      },300)
		
 
	
 
 
        }
    });
  }
}









