<div id="main" class="liquid">
 <div class="mw-ui-row dashboard-top-row">
   <div class="mw-ui-col" style="width: 55%">
        <module type="site_stats/admin" subtype="graph" />
   </div>
   <div class="mw-ui-col" style="width: 45%">
       <div class="quick-add">
            <ul class="quick-add-nav" id="quick-add-nav">
                <li class="active"><span title="Create Post"><i class="mw-ui-btn-plus"></i><i class="ico ipost"></i></span></li>
                <li><span title="Create Product"><i class="mw-ui-btn-plus"></i><i class="ico iproduct"></i></span></li>
                <li><span title="Create Page"><i class="mw-ui-btn-plus"></i><i class="ico ipage"></i></span></li>
                <li><span title="Create Category"><i class="mw-ui-btn-plus"></i><i class="ico icategory"></i></span></li>
            </ul><div class="quick-add-module">
                <module type="content/quick" live_edit="true" quick_edit="true" subtype="post" id="mw-quick-page" />
            </div>
       </div>
   </div>
 </div>

 <script>
 $(document).ready(function(){
    mw.$("#quick-add-nav li").click(function(){
       if(!$(this).hasClass("active")){
          mw.$("#quick-add-nav li.active").removeClass("active");
          $(this).addClass("active")
       }
    });
 });
 </script>


 <div class="mw-ui-row">
 <div class="mw-ui-col" style="width: 55%">
      <module type="site_stats/admin"  />

 </div>
 <div class="mw-ui-col" style="width: 45%">

      <div class="quick-lists" style="border: none">

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


 <?php event_trigger('mw_admin_dashboard_main'); ?>




</div>



<?php  show_help('dashboard');  ?>




