<div class="mw-ui-col-container" style="padding-left: 35px;">
 <module type="site_stats/admin" subtype="graph" />


 <module type="site_stats/admin" />


 <div class="quick-lists pull-left">
       <h2><?php _e("Quick Links"); ?></h2>

    <div class="mw-ui-navigation pull-left">
        <?php event_trigger('admin_dashboard_quick_link'); ?>
        <a  href="<?php print admin_url('view:settings'); ?>"><span class="mw-icon-website"></span><span><?php _e("Manage Website"); ?></span></a>
        <a  href="<?php print admin_url('view:modules'); ?>"><span class="mw-icon-module"></span><span><?php _e("Manage Modules"); ?></span></a>
        <a  href="<?php print admin_url('view:files'); ?>"><span class="mw-icon-upload"></span><span><?php _e("File Manager"); ?></span></a>
       </div>
       <div class="mw-ui-navigation pull-left">
       <a  href="<?php print admin_url(); ?>"><span class=""></span><span><?php _e("Upgrades"); ?></span></a>
        <?php $notif_count = mw('Microweber\Notifications')->get('is_read=n&count=1'); ?>
        <a href="<?php print admin_url('view:admin__notifications'); ?>">
          <span class="relative">
            <?php _e("Notifications"); ?>
            <?php if( $notif_count > -10): ?>
                <sup class="mw-notification-count"><?php print  $notif_count ?></sup>
            <?php endif; ?>
          </span>
        </a>
        <?php if(is_module('updates')): ?>
        <?php $notif_count = mw_updates_count() ?>
        <a  href="<?php print admin_url(); ?>view:updates"><?php if( $notif_count > 0): ?>
          <sup class="mw-notification-count"><?php print  $notif_count ?></sup>
          <?php endif; ?><span><?php _e("Updates"); ?></span></a>
        <?php endif; ?>
       <?php event_trigger('admin_dashboard_quick_link2'); ?>
            <a href="https://microweber.com/contact-us?user=<?php print user_name(); ?>" target="_blank"><?php _e("Suggest a feature"); ?></a>
        <?php event_trigger('admin_dashboard_help_link'); ?>
       </div>
  </div>


 <?php event_trigger('admin_dashboard_main'); ?>




</div>



<?php  show_help('dashboard');  ?>




