<div id="main" class="liquid">
  <?php exec_action('mw_admin_dashboard_main'); ?>
  <div class="mw_clear" style="padding-bottom: 20px;">&nbsp;</div>
  <div class="quick-lists">
    <div class="quick-links-case left">
      <h2><?php _e("Quick Add"); ?></h2>
      <ul class="mw-quick-links"> 
        <li><a href="<?php print admin_url('view:content'); ?>#action=new:page"><span class="mw-ui-btn-plus">&nbsp;</span><span class="ico ipage"></span><span><?php _e("New Page"); ?></span></a></li>
        <li><a href="<?php print admin_url('view:content'); ?>#action=new:post"><span class="mw-ui-btn-plus">&nbsp;</span><span class="ico ipost"></span><span><?php _e("New Post"); ?></span></a></li>
        <li><a href="<?php print admin_url('view:content'); ?>#action=new:category"><span class="mw-ui-btn-plus">&nbsp;</span><span class="ico icategory"></span><span><?php _e("New Category"); ?></span></a></li>
        <li><a href="<?php print admin_url('view:content'); ?>#action=new:product"><span class="mw-ui-btn-plus">&nbsp;</span><span class="ico iproduct"></span><span><?php _e("New Product"); ?></span></a></li>
      </ul>
    </div>
    <div class="quick-links-case">
      <h2><?php _e("Quick Links"); ?></h2>
      <ul class="mw-quick-links left">
        <?php exec_action('mw_admin_dashboard_quick_link'); ?>
        <li><a href="<?php print admin_url('view:settings'); ?>"><span class="ico imanage-website"></span><span><?php _e("Manage Website"); ?></span></a></li>
        <li><a href="<?php print admin_url('view:modules'); ?>"><span class="ico imanage-module"></span><span><?php _e("Manage Modules"); ?></span></a></li>
      </ul>
    </div>
    <div class="quick-links-case">
      <ul class="mw-quick-links left">
        <li><a href="<?php print admin_url(); ?>"><span class="ico iupgrade"></span><span><?php _e("Upgrades"); ?></span></a></li>
        <?php $notif_count = \mw\Notifications::get('is_read=n&count=1'); ?>
        <li><a href="<?php print admin_url('view:admin__notifications'); ?>"><span class="ico inotification">
          <?php if( $notif_count > 0): ?>
          <sup class="mw-notif-bubble"><?php print  $notif_count ?></sup>
          <?php endif; ?>
          </span><span><?php _e("Notifications"); ?></span></a></li>
        <li><a href="<?php print admin_url('view:files'); ?>"><span class="ico iupload"></span><span><?php _e("File Manager"); ?></span></a></li>
        <?php if(is_module('updates')): ?>
        <?php $notif_count = mw_updates_count() ?>
        <li><a href="<?php print admin_url(); ?>view:updates"><span class="ico iupdate"> <?php if( $notif_count > 0): ?>
          <sup class="mw-notif-bubble"><?php print  $notif_count ?></sup>
          <?php endif; ?></span><span><?php _e("Updates"); ?></span></a></li>
        <?php endif; ?>
        <?php exec_action('mw_admin_dashboard_quick_link2'); ?>
      </ul>
    </div>
    <div class="quick-links-case">
      <ul class="mw-quick-links left">
        <li><a href="http://api.microweber.net/service/frames/report.php?user=<?php print user_name(); ?>" onclick="mw.contact.report(this.href);return false;"><span class="ico ireport"></span><span><?php _e("Report a Bug"); ?></span></a></li>
        <li><a href="http://api.microweber.net/service/frames/suggest.php?user=<?php print user_name(); ?>" onclick="mw.contact.report(this.href);return false;"><span class="ico isuggest"></span><span><?php _e("Suggest a feature"); ?></span></a></li>
        <?php if(is_module('help')): ?>
        <li><a href="<?php print admin_url(); ?>view:help"><span class="ico ihelp"></span><span><?php _e("Help &amp; Support"); ?></span></a></li>
        <?php endif; ?>
        <?php exec_action('mw_admin_dashboard_help_link'); ?>
      </ul>
    </div>
  </div>
  <br />
  <br />
  <br />
</div>



<?php  show_help('dashboard');  ?>




