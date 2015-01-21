<?php only_admin_access(); ?>
<script  type="text/javascript">
$(document).ready(function(){
  mw.options.form('.<?php print $config['module_class'] ?>,.mw_adm_cont_head_change_holder', function(){
      mw.notification.success("<?php _e("Advanced settings updated"); ?>.");
    });


	  mw.options.form('.<?php print $config['module_class'] ?>,.mw_adm_robots_txt_change_holder', function(){
      mw.notification.success("<?php _e("Advanced settings updated"); ?>.");
    });
});



</script>

<h2><?php _e("Advanced"); ?></h2>

<div class="<?php print $config['module_class'] ?>">



    <div class="mw-ui-btn-vertical-nav">
        <a class="mw-ui-btn" href="javascript:mw.clear_cache()"><?php _e("Clear cache"); ?></a>
        <a class="mw-ui-btn" href="javascript:api('mw_post_update'); void(0);"><?php _e("Reload Database"); ?></a>
                <a class="mw-ui-btn" href="javascript:$('.mw_adm_dev_tools_change_holder').toggle(); void(0);"> Developer tools </a>

        <a class="mw-ui-btn" href="javascript:$('.mw_adm_cont_head_change_holder').toggle(); void(0);"><?php _e("Custom head tags"); ?></a>
        <a class="mw-ui-btn" href="javascript:$('.mw_adm_robots_txt_change_holder').toggle(); void(0);"> robots.txt </a>
       <!-- <a class="mw-ui-btn" href="javascript:mw.load_module('settings/group/internal','#mw-advanced-settings-module-load-holder')"><?php _e("Internal settings"); ?> </a>
       -->
    </div>


  </div>




<div class="mw_adm_cont_head_change_holder mw-ui-box mw-ui-box-content" style="display:none">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <h3><?php _e("Custom head tags"); ?></h3>
      <br>
      <div class="mw-ui-box mw-ui-box-content mw-ui-box-warn">
      <?php _e("Advanced functionality"); ?>
      !
      <?php _e("You can put custom html in the site head-tags. Please put only valid meta tags or you can break your site."); ?>
      </div> </label>
    <textarea name="website_head" class="mw_option_field mw-ui-field w100"   type="text" option-group="website"><?php print get_option('website_head','website'); ?></textarea>
  </div>
</div>
<div class="mw_adm_robots_txt_change_holder mw-ui-box mw-ui-box-content" style="display:none">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">Robots.txt
      <?php _e("file"); ?>
    </label>
    <textarea name="robots_txt" class="mw_option_field mw-ui-field w100" type="text" option-group="website"><?php print get_option('robots_txt','website'); ?></textarea>
  </div>
</div>
<div class="mw_adm_dev_tools_change_holder mw-ui-box mw-ui-box-content" style="display:none">
  <div class="mw-ui-field-holder">
     <a class="mw-ui-btn" href="javascript:mw.load_module('admin/developer_tools/template_exporter','#mw-advanced-settings-dev-tools-output')">Template exporter</a>
     <a class="mw-ui-btn" href="javascript:mw.load_module('admin/developer_tools/media_cleanup','#mw-advanced-settings-dev-tools-output')">Media cleanup</a>
     <a class="mw-ui-btn" href="javascript:mw.load_module('admin/notifications/system_log','#mw-advanced-settings-dev-tools-output')"><?php _e("Show system log"); ?></a>
         <a class="mw-ui-btn" href="javascript:mw.load_module('admin/modules/packages','#mw-advanced-settings-dev-tools-output')"><?php _e("Packages"); ?></a>



  <div class="mw-clear" style="padding-bottom:10px;"></div>

  <div id="mw-advanced-settings-dev-tools-output"></div>

  </div>
</div>