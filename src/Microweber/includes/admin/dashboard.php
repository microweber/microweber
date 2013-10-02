<div id="main" class="liquid">


 <div class="mw-ui-row">
   <div class="mw-ui-col" style="width: 55%">

          <?php event_trigger('mw_admin_dashboard_main'); ?>

   </div>
   <div class="mw-ui-col" style="width: 45%">

   <div class="quick-add">


      <?php /*  <module type="content/edit_page" live_edit="true" quick_edit="true" id="mw-quick-page" />  */ ?>


        <module type="content/edit_page" live_edit="true"  subtype="post" id="mw-quick-page" />


   </div>




<div class="quick-lists">

    <div class="quick-links-case">
      <h2><?php _e("Quick Links"); ?></h2>
      <ul class="mw-quick-links left">
        <?php event_trigger('mw_admin_dashboard_quick_link'); ?>



        <li><a href="<?php print admin_url('view:settings'); ?>"><span class="ico imanage-website"></span><span><?php _e("Manage Website"); ?></span></a></li>
        <li><a href="<?php print admin_url('view:modules'); ?>"><span class="ico imanage-module"></span><span><?php _e("Manage Modules"); ?></span></a></li>
      </ul>
    </div>
    <div class="quick-links-case">
      <ul class="mw-quick-links left">
        <li><a href="<?php print admin_url(); ?>"><span class="ico iupgrade"></span><span><?php _e("Upgrades"); ?></span></a></li>
        <?php $notif_count = mw('Microweber\Notifications')->get('is_read=n&count=1'); ?>
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
        <?php event_trigger('mw_admin_dashboard_quick_link2'); ?>
      </ul>
    </div>
    <div class="quick-links-case">
      <ul class="mw-quick-links left">
        <li><a href="http://api.microweber.net/service/frames/report.php?user=<?php print user_name(); ?>" onclick="mw.contact.report(this.href);return false;"><span class="ico ireport"></span><span><?php _e("Report a Bug"); ?></span></a></li>
        <li><a href="http://api.microweber.net/service/frames/suggest.php?user=<?php print user_name(); ?>" onclick="mw.contact.report(this.href);return false;"><span class="ico isuggest"></span><span><?php _e("Suggest a feature"); ?></span></a></li>
        <?php if(is_module('help')): ?>
        <li><a href="<?php print admin_url(); ?>view:help"><span class="ico ihelp"></span><span><?php _e("Help &amp; Support"); ?></span></a></li>
        <?php endif; ?>
        <?php event_trigger('mw_admin_dashboard_help_link'); ?>
      </ul>
    </div>
  </div>


   </div>
 </div>







</div>



<?php  show_help('dashboard');  ?>




