

<div class="module-live-edit-settings">

<a href="<?php print admin_url('view:').$params['module']  ?>" class="mw-ui-btn pull-right mw-ui-btn-medium mw-ui-btn-notification" target="_blank"><?php _e("See form entires"); ?></a>

<style type="text/css" scoped="scoped">
    .tab{
      display: none;
    }

</style>

<script>

initEditor = function(){
  if(!window.editorLaunced){
    editorLaunced = true;
      mw.editor({
        element:mwd.getElementById('editorAM'),
        hideControls:['format', 'fontsize', 'justifyfull']
      });
  }
}

$(document).ready(function(){
   mw.tabs({
      nav:".mw-ui-btn-nav-tabs a",
      tabs:".tab",
      onclick:function(){
        if(this.id === 'form_options'){
          initEditor()
        }
      }
   });
});

</script>


 <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
    <a href="javascript:;" class="mw-ui-btn active"><?php _e("Fields"); ?></a>
    <a href="javascript:;" class="mw-ui-btn"><?php _e("Skin/Template"); ?></a>
    <a href="javascript:;" class="mw-ui-btn" id="form_options"><?php _e("Options"); ?></a>
  </div>
  <div class="mw-ui-box mw-ui-box-content">
    <div class="tab" style="display: block">
        <module type="forms/assign_list_to_module"  data-for-module="<?php print $config['module_name'] ?>" data-for-module-id="<?php print $params['id'] ?>" />
        <label class="mw-ui-label"><small><?php _e("Contact Form Fields"); ?></small></label>
        <module type="custom_fields"  view="admin" data-for="module" data-id="<?php print $params['id'] ?>" />
      </div>
      <div class="tab">
        <module type="admin/modules_manager/templates"  />
      </div>
      <div class="tab">
        <module type="settings/list" for_module="<?php print $config['module'] ?>" for_module_id="<?php print $params['id'] ?>" >
        
        <hr>
        <div id="form_email_options">
          <label class="mw-ui-label" style="padding-bottom: 0;"><small><?php _e("Type your e-mail where you will receive the email from this form"); ?></small></label>
          <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e("Email To"); ?></label>
            <input   name="email_to"     value="<?php print get_option('email_to', $params['id']); ?>"     class="mw-ui-field w100 mw_option_field" type="text" />
          </div>
          <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e("BCC Email To"); ?></label>
            <input name="email_bcc"    value="<?php print get_option('email_bcc', $params['id']); ?>"     class="mw-ui-field w100 mw_option_field"  type="text" />
          </div>

          <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e("Autorespond Subject"); ?></label>
            <input name="email_autorespond_subject"    value="<?php print get_option('email_autorespond_subject', $params['id']); ?>"     class="mw-ui-field w100 mw_option_field"  type="text" />
          </div>

          <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e("Autorespond Message"); ?></label>
    		<textarea id="editorAM" name="email_autorespond" class="mw_option_field">
    			<?php print get_option('email_autorespond', $params['id']); ?>
    		</textarea>

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
                <?php if(get_option('disable_captcha', $params['id']) =='y'): ?>   checked="checked"  <?php endif; ?>
          />
          <span></span> <span><?php _e("Disable Code Verification ex"); ?>.:</span> </label>
        <img src="<?php print mw_includes_url(); ?>img/code_verification_example.jpg" class="relative" style="top: 7px;left:10px;" alt="" />

        <hr>

        <button class="mw-ui-btn mw-ui-btn-info pull-right" ><?php _e("Save"); ?></button>

      </div>
  </div>

</div>
