<div class="mw-ui-col-container" style="padding-left: 35px;">
 <module type="site_stats/admin" subtype="graph" />


 <div class="mw-ui-row" >
 <div class="mw-ui-col" style="width: 75%">
 <div class="mw-ui-col-container">
      <module type="site_stats/admin"  />
 </div>
 </div>
 <div class="mw-ui-col" style="width: 25%">
 <div class="mw-ui-col-container">
 <div class="quick-lists">

    <div class="mw-ui-btn-vertical-nav" id="dashboard-navigation">
      <h2><?php _e("Quick Links"); ?></h2>

        <?php event_trigger('admin_dashboard_quick_link'); ?>



        <a class="mw-ui-btn" href="<?php print admin_url('view:settings'); ?>"><span class="mw-icon-gear"></span><span><?php _e("Manage Website"); ?></span></a>
        <a class="mw-ui-btn" href="<?php print admin_url('view:modules'); ?>"><span class="mw-icon-module"></span><span><?php _e("Manage Modules"); ?></span></a>



        <a class="mw-ui-btn" href="<?php print admin_url(); ?>"><span><?php _e("Upgrades"); ?></span></a>
        <?php $notif_count = mw('Microweber\Notifications')->get('is_read=n&count=1'); ?>
       <a class="mw-ui-btn" href="<?php print admin_url('view:admin__notifications'); ?>"><span class="mw-icon-notification">
          <?php if( $notif_count > 0): ?>
          <sup class="mw-notification-count"><?php print  $notif_count ?></sup>
          <?php endif; ?>
          </span><span><?php _e("Notifications"); ?></span></a>
        <a class="mw-ui-btn" href="<?php print admin_url('view:files'); ?>"><span class="ico iupload"></span><span><?php _e("File Manager"); ?></span></a>
        <?php if(is_module('updates')): ?>
        <?php $notif_count = mw_updates_count() ?>
        <a class="mw-ui-btn" href="<?php print admin_url(); ?>view:updates"><span class="ico iupdate"> <?php if( $notif_count > 0): ?>
          <sup class="mw-notification-count"><?php print  $notif_count ?></sup>
          <?php endif; ?></span><span><?php _e("Updates"); ?></span></a>
        <?php endif; ?>
        <?php event_trigger('admin_dashboard_quick_link2'); ?>


            <a class="mw-ui-btn" href="http://api.microweber.net/service/frames/suggest.php?user=<?php print user_name(); ?>" onclick="mw.contact.report(this.href);return false;"><?php _e("Suggest a feature"); ?></a>


        <?php event_trigger('admin_dashboard_help_link'); ?>
       </div>
  </div>

 </div>
 </div>

 </div>


 <?php event_trigger('admin_dashboard_main'); ?>




</div>



<?php  show_help('dashboard');  ?>




