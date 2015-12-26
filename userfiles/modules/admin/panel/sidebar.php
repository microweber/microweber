<?php 
//dd($config);
?>
<?php $view = url_param('view'); ?>
<?php $action = url_param('action'); ?>

<div class="mw-admin-sidebar">
  <div class="mw-admin-main-panel-top-bar">
    <?php if(mw()->ui->admin_logo != false) : ?>
    <img src="<?php print mw()->ui->admin_logo ?>" style="max-width:100%" />
    <?php else: ?>
    <span class="mw-icon-mw"></span> Microweber
    <?php endif;  ?>
  </div>
  <div class="mw-admin-big-spacer"></div>
  <ul>
    <li class="<?php if ($action == 'dashboard'): ?> active<?php endif; ?>"><a href="<?php print admin_url(); ?>view:panel/action:dashboard"><span class="mw-admin-sidebar-icon mw-icon-bars"></span>Dashboard</a></li>
    <li  class="<?php if ($action == 'orders'): ?> active<?php endif; ?>"><a
            href="<?php print admin_url(); ?>view:panel/action:orders"> <span class="mw-admin-sidebar-icon mw-icon-money-outline "></span>
      <?php _e("Orders"); ?>
      </a></li>
    <li class="<?php if ($action == 'customers'): ?> active<?php endif; ?>"><a href="<?php print admin_url(); ?>view:panel/action:customers"><span class="mw-admin-sidebar-icon mw-icon-customer"></span>Customers</li>
    
    
    
       <li class="<?php if ($action == 'settings'): ?> active<?php endif; ?>"><a href="<?php print admin_url(); ?>view:panel/action:settings"><span class="mw-admin-sidebar-icon  mw-icon-wrench-outline"></span>Settings</li>
    
    
     <li <?php if ($action == 'posts'): ?> class="active" <?php endif; ?>><a
            href="<?php print admin_url(); ?>view:panel/action:posts">
      <?php _e("Posts"); ?>
      </a></li>
  </ul>
</div>
