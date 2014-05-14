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

<div class="<?php print $config['module_class'] ?>"> <a class="mw-ui-btn" href="javascript:mw.clear_cache()">
  <?php _e("Clear cache"); ?>
  </a> <a class="mw-ui-btn" href="javascript:api('mw_post_update'); void(0);">
  <?php _e("Reload Database"); ?>
  </a> <a class="mw-ui-btn" href="javascript:mw.load_module('admin/notifications/system_log','#mw-advanced-settings-module-load-holder')">
  <?php _e("Show system log"); ?>
  </a> <a class="mw-ui-btn" href="javascript:$('.mw_adm_cont_head_change_holder').toggle(); void(0);">
  <?php _e("Custom head tags"); ?>
  </a> <a class="mw-ui-btn" href="javascript:$('.mw_adm_robots_txt_change_holder').toggle(); void(0);"> robots.txt </a> <a class="mw-ui-btn" href="javascript:mw.load_module('settings/group/internal','#mw-advanced-settings-module-load-holder')"> Internal settings </a> 
  
  <a class="mw-ui-btn" href="javascript:mw.load_module('admin/modules/packages','#mw-advanced-settings-module-load-holder')"> Packages </a>
  
  </div>
<div id="mw-advanced-settings-module-load-holder"></div>
<div class="mw_adm_cont_head_change_holder" style="display:none">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Custom head tags"); ?>
      <br>
      <small>
      <?php _e("Advanced functionality"); ?>
      !
      <?php _e("You can put custom html in the site head-tags. Please put only valid meta tags or you can break your site."); ?>
      </small> </label>
    <textarea name="website_head" class="mw_option_field mw-ui-field"   type="text" option-group="website"><?php print get_option('website_head','website'); ?></textarea>
  </div>
</div>
<div class="mw_adm_robots_txt_change_holder" style="display:none">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">Robots.txt
      <?php _e("file"); ?>
    </label>
    <textarea name="robots_txt" class="mw_option_field mw-ui-field"   type="text" option-group="website"><?php print get_option('robots_txt','website'); ?></textarea>
  </div>
</div>
