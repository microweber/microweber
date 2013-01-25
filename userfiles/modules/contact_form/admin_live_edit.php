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
  <div class="tab">
    <module type="custom_fields"  view="admin" data-for="module" data-id="<? print $params['id'] ?>" />
  </div>
  <div class="tab">
    <module type="admin/modules/templates"  />
  </div>
  <div class="tab">
 <br />
  <a href="<? print admin_url('view:').$params['module']  ?>" class="mw-ui-link" target="_blank">See form entires</a>
  <br />
  <br />  <br />
    <fieldset>
      <legend>Form settings</legend>
      <div class="control-group">
        <label class="control-label">Form name</label>
        <div class="controls">
          <input name="form_name" class="mw_option_field"   type="text" data-refresh="contact_form"  value="<?php print get_option('form_name', $params['id']) ?>" />
        </div>
      </div>
      <label>
      <input type="checkbox" name="disable_captcha" value="y" class="mw_option_field" <? if(get_option('disable_captcha', $params['id']) =='y'): ?>   checked="checked"  <? endif; ?> />
      Disable captcha</label>
      
    </fieldset>
    <microweber module="settings/list"     for_module="<? print $config['module'] ?>" for_module_id="<? print $params['id'] ?>" >
  </div>
</div>
