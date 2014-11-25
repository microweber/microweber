<style type="text/css">
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

<div class="mw_simple_tabs mw_tabs_layout_simple"> <a href="<?php print admin_url('view:').$params['module']  ?>" class="mw-ui-btn right relative" style="z-index: 2;margin:13px 13px 0 0;" target="_blank"><?php _e("See form entires"); ?></a>
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active"><?php _e("Fields"); ?></a></li>
    <li><a href="javascript:;"><?php _e("Skin/Template"); ?></a></li>
    <li><a href="javascript:;"><?php _e("Options"); ?></a></li>
  </ul>
  <div class="tab">
    <module type="forms/assign_list_to_module"  data-for-module="<?php print $config['module_name'] ?>"  data-for-module-id="<?php print $params['id'] ?>" />
    <label class="mw-ui-label"><small><?php _e("Contact Form Fields"); ?></small></label>
    <module type="custom_fields"  view="admin" data-for="module" data-id="<?php print $params['id'] ?>" />
  </div>
  <div class="tab">
    <module type="admin/modules/templates"  />
  </div>
  <div class="tab">
    <microweber module="settings/list"     for_module="<?php print $config['module'] ?>" for_module_id="<?php print $params['id'] ?>" >
    
    <hr>
    <div id="form_email_options">
      <label class="mw-ui-label" style="padding-bottom: 0;"><span class="ico ismall_warn"></span><small><?php _e("Type your e-mail where you will receive the email from this form"); ?></small></label>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Email To"); ?></label>
        <input placeholder="Your Email"  name="email_to"     value="<?php print mw('option')->get('email_to', $params['id']); ?>"     class="mw-ui-field mw_option_field" type="text" />
      </div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("BCC Email To"); ?></label>
        <input placeholder="Your Email"  name="email_bcc"    value="<?php print mw('option')->get('email_bcc', $params['id']); ?>"     class="mw-ui-field mw_option_field"  type="text" />
      </div>
      
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Autorespond Subject"); ?></label>
        <input placeholder="Thank you for your message!"  name="email_autorespond_subject"    value="<?php print mw('option')->get('email_autorespond_subject', $params['id']); ?>"     class="mw-ui-field mw_option_field"  type="text" />
      </div>
      
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Autorespond Message"); ?></label>
		<textarea id="editorDEMO" name="email_autorespond" class="mw_option_field">
			<?php print mw('option')->get('email_autorespond', $params['id']); ?>     
		</textarea>
        <script>var editor = mw.tools.iframe_editor(mwd.getElementById('editorDEMO')); editor.style.width = '100%';</script>
        <label class="mw-ui-label"><span class="ico ismall_warn"></span><small><?php _e("Autorespond e-mail sent back to the user"); ?></small></label>
      </div>
    </div>
    <hr>
    <label class="mw-ui-check">
      <input
            type="checkbox"
            name="disable_captcha"
            value="y"
            class="mw_option_field"
            <?php if(mw('option')->get('disable_captcha', $params['id']) =='y'): ?>   checked="checked"  <?php endif; ?>
      />
      <span></span> <span><?php _e("Disable Code Verification ex"); ?>.:</span> </label>
    <img src="<?php print mw_includes_url(); ?>img/code_verification_example.jpg" class="relative" style="top: 7px;left:10px;" alt="" />
    
    <hr>
    
    <button class="mw-ui-btn mw-ui-btn-blue right" style="width: 90px;"><?php _e("Save"); ?></button>
    
  </div>
</div>
