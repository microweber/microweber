<div class="mw-ui-col-container" style="padding-left: 35px;">
  <module type="site_stats/admin" subtype="graph" />
  <module type="site_stats/admin" />
  <div class="quick-lists pull-left">
    <h2>
      <?php _e("Quick Links"); ?>
    </h2>
    <div class="mw-ui-navigation pull-left">
      <?php event_trigger('mw.admin.dashboard.links'); ?>
      <?php $dash_menu = mw()->ui->admin_dashboard_menu(); ?>
      <?php if(!empty($dash_menu)): ?>
      <?php foreach($dash_menu as $item): ?>
      <?php $btnurl =  admin_url('view:').$item['view']; ?>
      <?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false);  ?>
      
      <?php $text=  $item['text']; ?>
      <a  href="<?php print $btnurl; ?>"><span class="<?php print $icon; ?>"></span><span><?php print $text; ?></span></a>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="mw-ui-navigation pull-left"> <a  href="<?php print admin_url(); ?>"><span class=""></span><span>
      <?php _e("Upgrades"); ?>
      </span></a>
      <?php $notif_count = mw('Microweber\Notifications')->get('is_read=n&count=1'); ?>
      <a href="<?php print admin_url('view:admin__notifications'); ?>"> <span class="relative">
      <?php _e("Notifications"); ?>
      <?php if( $notif_count > 0): ?>
      <sup class="mw-notification-count"><?php print  $notif_count ?></sup>
      <?php endif; ?>
      </span> </a>
      <?php if(is_module('updates')): ?>
      <?php $notif_count = mw_updates_count() ?>
      <a  href="<?php print admin_url(); ?>view:updates">
      <?php if( $notif_count > 0): ?>
      <span class="relative"> <sup class="mw-notification-count"><?php print  $notif_count ?></sup>
      <?php endif; ?>
      <span>
      <?php _e("Updates"); ?>
      </span></span> </a>
      <?php endif; ?>
      <?php event_trigger('mw.admin.dashboard.links2'); ?>
      <a href="https://microweber.com/contact-us?user=<?php print user_name(); ?>" target="_blank">
      <?php _e("Suggest a feature"); ?>
      </a>
      <?php event_trigger('mw.admin.dashboard.help'); ?>
    </div>
  </div>
  <?php event_trigger('mw.admin.dashboard.main'); ?>
</div>
<?php  show_help('dashboard');  ?>
