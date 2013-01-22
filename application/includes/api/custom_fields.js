mw.custom_fields = {
  add:function(el){
    var parent = $(mw.tools.firstParentWithClass(el, 'mw-custom-field-form-controls'));

    var clone = parent.clone(true);
    parent.after(clone);
    clone.find("input").val("");

  },
  remove:function(el){
    var q = "Are you sure you want to remove this field?";
    $(mw.tools.firstParentWithClass(el, 'mw-custom-field-form-controls')).remove();
  },
  
  edit: function($selector, $id, callback){

      var data = {};
      data.settings = 'y';
      data.field_id = $id;

      mw.$($selector).load(mw.settings.api_html+'make_custom_field',data , function(){
        mw.is.func(callback) ? callback.call($type) : '';
      });
	  
  },
  
  
   create: function($selector, $type, $copy, $for_table, $for_id, callback){
      var copy_str = '';
      if($copy !== undefined && $copy !== false){
        var copy_str = '/copy_from:'+ $copy;
      }
      mw.$($selector).load(mw.settings.api_html+'make_custom_field/settings:y/basic:y/for_module_id:'+ $for_id + '/for:'+ $for_table +'/custom_field_type:'+$type + copy_str , function(){
		mw.is.func(callback) ? callback.call($type) : '';
      });
  },
  
  
  
  sort:function(group){
    var group = mwd.getElementById(group);
    $(group).sortable({
        handle:'.custom-fields-handle-field',
        placeholder:'custom-fields-placeholder',
        //containment: "parent",
        axis:'y',
        items:".mw-custom-field-form-controls",
        start:function(a,ui){
            $(ui.placeholder).height($(ui.item).outerHeight())
        },
        scroll:false,
        update:function(){
          mw.custom_fields.save(this.parentNode.id);
        }
    });
  },
  autoSaveOnWriting:function(el, id){
     mw.on.stopWriting(el, function(){
         mw.custom_fields.save(id);
     });
  }
}




mw.custom_fields.save = function(id){
    var obj = mw.form.serialize(id);
    $.post(mw.settings.api_url+'save_custom_field', obj, function(data) {
       $cfadm_reload = false;
	 
	   
	    if(obj.cf_id === undefined){
             mw.reload_module('.edit [data-parent-module="custom_fields"]');
			 
			//  $('#create-custom-field-table').addClass('semi_hidden');
			// $("#"+id).hide();
		 
			 
			
        }
        else {
			
			if(obj.copy_to_table_id === undefined){
				
           // $(""+id).parents('.custom-field-table-tr').first().find('.custom-field-preview-cell').html(data);
				
			} else {
			 $cfadm_reload = true;   
			//   mw.reload_module('custom_fields/list');
			}
			
			
			
        }
		
		
         mw.$(".mw-live-edit [data-type='custom_fields']").each(function(){
         if(!mw.tools.hasParentsWithClass(this, 'mw_modal') && !mw.tools.hasParentsWithClass(this, 'is_admin')){
			// if(!mw.tools.hasParentsWithClass(this, 'mw_modal') ){
               mw.reload_module(this);
           } else {
			$cfadm_reload = true;   
		   }
        });
		
		
		if($cfadm_reload  == true){
	       // mw.reload_module('custom_fields/admin');
		}
		 mw.reload_module('custom_fields/list');

		
		
    });
}

mw.custom_fields.del = function(id){
    var q = "Are you sure you want to delete this?";
    mw.tools.confirm(q, function(){
      var obj = mw.form.serialize(id);
      $.post(mw.settings.api_url+"remove_field",  obj, function(data){
         mw.reload_module('custom_fields/list');
      });
    });

}