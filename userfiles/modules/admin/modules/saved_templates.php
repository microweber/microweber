<? only_admin_access(); ?>
<? if(isset($params['module_name']) and isset($params['module_id'])): ?>
<?


 $module_name = $params['module_name'];
 $module_id = $params['module_id'];

  ?>
<script  type="text/javascript">



$(document).ready(function(){

	 mw.$('.module-templates-action-btn').click(function() {
	
	var temp_form1 = mw.tools.firstParentWithClass(this, 'module-templates-add-new-holder');	 
	var save_module_as_template_url = '<? print site_url('api') ?>/save_module_as_template';
	var is_del = $(this).attr('delete');
	if(is_del != undefined){
		var save_module_as_template_url = '<? print site_url('api') ?>/delete_module_as_template';
	}
	
	
	var is_use = $(this).attr('use');
	if(is_use != undefined){
		 
		 
		/*if (parent.mw != undefined) {
                                if (parent.mw.reload_module !== undefined) {
									
 									parent.mw.$('#<? print $module_id ?>').attr("id",is_use);
 									 parent.mw.reload_module("#"+is_use);
									
                                }
                            }*/
						if (mw.reload_module != undefined) {	
				 mw.$('#<? print $module_id ?>').attr("id",is_use);
				 mw.reload_module("#"+is_use);
				 
				 
				 var menu = mwd.getElementById('module-saved-templates');
				 if(mw.tools.hasParentsWithClass(menu, 'mw_modal')){
					var modal = mw.tools.firstParentWithClass(menu, 'mw_modal');
					var frame = modal.getElementsByTagName('iframe')[0];
					var form = $(frame).contents().find("#mw_reload_this_module_popup_form")[0];
					form.elements['id'].value = is_use;	
						 $(form).submit(); 
				 }
				 
				 
				 
				 
				 
				 
				 
								//	 mw.reload_module("<? print $module_name  ?>");			
						}
							
							
		 
		 
		 
	} else {
		 
		 mw.form.post(temp_form1 ,save_module_as_template_url , function(){
		 mw.reload_module("#<? print $params['id'] ?>");
		 });
	 
	}
	 
	 
	 
     return false;
 	});

  });


	   
 
</script>

<div id="module-saved-templates">
  <? $saved_modules = get_saved_modules_as_template("module={$module_name}"); ?>
  <? if(isarr($saved_modules )): ?>
  <ul>
    <? foreach($saved_modules  as $item): ?>
    <li>
      <div class="module-templates-add-new-holder">
        <input type="hidden" name="id" value="<? print  $item['id'] ?>">
         
        <input type="hidden" name="module" value="<? print  $item['module'] ?>">
       <? print  $item['id'] ?>: name <? print  $item['name'] ?>
        <input  type="hidden" name="name" value="<? print  $item['name'] ?>">
        
        <input  type="hidden" name="module_id" value="<? print  $item['module_id'] ?>">
        <input  type="button" value="Use"   use="<? print  $item['module_id'] ?>" class="module-templates-action-btn"   />

   <!--     <input  type="button" value="Update"  class="module-templates-action-btn"   /> -->
        <input  type="button" value="X"   delete=1 class="module-templates-action-btn"   />
      </div>
    </li>
    <? endforeach ; ?>
  </ul>
  <? endif; ?>
</div>
<div class="module-templates-add-new-holder">
  <input type="hidden" name="module" value="<? print $module_name ?>">
  <input  type="text" name="name" value="">
  <input  type="hidden" name="module_id" value="<? print $module_id ?>">
  <input  type="button" value="Save template"  class="module-templates-action-btn"   />
</div>
<? else : ?>
error $params['module_name'] is not set or $params['module_id'] is not set
<? endif; ?>
