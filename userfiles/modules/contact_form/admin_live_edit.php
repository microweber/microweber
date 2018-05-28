<?php  only_admin_access(); ?>

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
        <module type="contact_form/manager/assign_list_to_module"  data-for-module="<?php print $config['module_name'] ?>" data-for-module-id="<?php print $params['id'] ?>" />
        <label class="mw-ui-label"><small><?php _e("Contact Form Fields"); ?></small></label>
        <module type="custom_fields"  view="admin" data-for="module" data-id="<?php print $params['id'] ?>" />
      </div>
      <div class="tab">
        <module type="admin/modules/templates"  />
      </div>
      <div class="tab">
        <module type="settings/list" for_module="<?php print $config['module'] ?>" for_module_id="<?php print $params['id'] ?>" >

            <module type="contact_form/settings"  for_module_id="<?php print $params['id'] ?>"  />

      </div>
  </div>

</div>
