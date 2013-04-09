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
<style>
#form_email_options input[type='text'], #form_email_options textarea {
	width: 90%;
	width: calc(100% - 22px);
	width: -webkit-calc(100% - 22px);
}
.mw_tabs_layout_simple .mw_simple_tabs_nav {
	padding-left: 12px;
}
.mw_tabs_layout_simple .mw_simple_tabs_nav li {
	margin:0;
}
</style>

<div class="mw_simple_tabs mw_tabs_layout_simple"> <a href="<? print admin_url('view:').$params['module']  ?>" class="mw-ui-btn right relative" style="z-index: 2;margin:13px 13px 0 0;" target="_blank">See form entires</a>
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active">Fields</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
    <li><a href="javascript:;">Options</a></li>
  </ul>
  <div class="tab">
    <module type="forms/assign_list_to_module"  data-for-module="<? print $config['module_name'] ?>"  data-for-module-id="<? print $params['id'] ?>" />
    <label class="mw-ui-label"><small>Contact Form Fields</small></label>
    <module type="custom_fields"  view="admin" data-for="module" data-id="<? print $params['id'] ?>" />
  </div>
  <div class="tab">
    <module type="admin/modules/templates"  />
  </div>
  <div class="tab">
    <microweber module="settings/list"     for_module="<? print $config['module'] ?>" for_module_id="<? print $params['id'] ?>" >
    <div class="vSpace"></div>
    <hr>
    <div id="form_email_options">
      <label class="mw-ui-label" style="padding-bottom: 0;"><span class="ico ismall_warn"></span><small>Type your e-mail where you will receive the email from this form</small></label>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Email To</label>
        <input placeholder="Your Email"  name="email_to"    value="<? print get_option('email_to', $params['id']); ?>"     class="mw_option_field" type="text" />
      </div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">BCC Email To</label>
        <input placeholder="Your Email"  name="email_bcc"    value="<? print get_option('email_bcc', $params['id']); ?>"     class="mw_option_field"  type="text" />
      </div>
      
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Autorespond Subject</label>
        <input placeholder="Thank you for your message!"  name="email_autorespond_subject"    value="<? print get_option('email_autorespond_subject', $params['id']); ?>"     class="mw_option_field"  type="text" />
      </div>
      
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Autorespond Message</label>
		<textarea id="editorDEMO" name="email_autorespond" class="mw_option_field">
			<? print get_option('email_autorespond', $params['id']); ?>     
		</textarea>
        <script>var editor = mw.tools.iframe_editor(mwd.getElementById('editorDEMO')); editor.style.width = '100%';</script>
        <label class="mw-ui-label"><span class="ico ismall_warn"></span><small>Autorespond e-mail sent back to the user</small></label>
      </div>
    </div>
    <hr>
    <label class="mw-ui-check">
      <input
            type="checkbox"
            name="disable_captcha"
            value="y"
            class="mw_option_field"
            <? if(get_option('disable_captcha', $params['id']) =='y'): ?>   checked="checked"  <? endif; ?>
      />
      <span></span> <span>Disable Code Verification ex.:</span> </label>
    <img src="<?php print INCLUDES_URL; ?>img/code_verification_example.jpg" class="relative" style="top: 7px;left:10px;" alt="" />
    <div class="vSpace"></div>
    <hr>
    <div class="vSpace"></div>
    <button class="mw-ui-btn mw-ui-btn-blue right" style="width: 90px;">Save</button>
    <div class="vSpace"></div>
  </div>
</div>
