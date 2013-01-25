<script  type="text/javascript">
  /* $(document).ready(function () {
		
 $(window).bind('customFieldSaved', function(){
	 
	 
			if(window.parent != undefined && window.parent.mw != undefined){
				 window.parent.mw.reload_module('custom_fields');
			 }
		});
});
*/
 

 
</script>

<div class="mw_simple_tabs mw_tabs_layout_simple">
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active">Fields</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
    <li><a href="javascript:;">Options</a></li>
     
  </ul>
    <a href="<? print admin_url('view:').$params['module']  ?>" class="mw-ui-link" target="_blank">See form entires</a>

  <div class="tab">
    <module type="custom_fields"  view="admin" data-for="module" data-id="<? print $params['id'] ?>" />
  </div>
  <div class="tab">
    <module type="admin/modules/templates"  />
  </div>
  <div class="tab">
 <br />
  <br />
  <br />  <br />
  
  
  
  <module type="forms/assign_list_to_module"  data-for-module="<? print $config['module_name'] ?>"  data-for-module-id="<? print $params['id'] ?>" />
  
  
   
      
   <label>
      <input type="checkbox" name="disable_captcha" value="y" class="mw_option_field" <? if(get_option('disable_captcha', $params['id']) =='y'): ?>   checked="checked"  <? endif; ?> />
      Disable captcha</label>
    <microweber module="settings/list"     for_module="<? print $config['module'] ?>" for_module_id="<? print $params['id'] ?>" >
  </div>
</div>
