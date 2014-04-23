<?php if(is_admin() == false) {error('Must be admin');}  ?>

<div id="mw-shop-menu">
  <?php $orders = get_orders('count=1&order_status=pending&order_completed=y');



 ?>
 
 <?php
	 $notif_html = '';
	$notif_count = mw('Microweber\Notifications')->get('module=shop&rel=cart_orders&is_read=n&count=1');
 	if( $notif_count > 0){
    $notif_html = '<sup class="mw-notif-bubble">'.$notif_count.'</sup>';  ?>


   <?php }  ?>
 

  <a href="<?php print $config['url']; ?>/action:orders" class="new-order-notification"> <strong><?php print intval( $orders); ?></strong>
  <?php _e("New Orders"); ?>
  </a>
  <div id="mw-admin-shop-navigation">
    <ul class="mw-ui-navigation mw-ui-navigation-horizontal">
      <li <?php if($active_action == false): ?>class="active"<?php endif; ?>><a href="<?php print $config['url']; ?>"><span class=""></span><span>
        <?php _e("Products"); ?>
        </span></a></li>
      <li <?php if($active_action == 'orders'): ?>class="active"<?php endif; ?>><a href="<?php print $config['url']; ?>/action:orders"><span class=""><?php print $notif_html ?></span><span>
        <?php _e("Orders"); ?>
        </span></a></li>
      <li <?php if($active_action == 'clients'): ?>class="active"<?php endif; ?>><a href="<?php print $config['url']; ?>/action:clients"><span class="mw-icon-users"></span><span>
        <?php _e("Clients"); ?>
        </span></a></li>
      <li <?php if($active_action == 'shipping'): ?>class="active"<?php endif; ?>><a href="<?php print $config['url']; ?>/action:shipping"><span class="mw-icon-truck"></span><span>
        <?php _e("Shipping"); ?>
        </span></a></li>
      <!--      <li <?php if($active_action == 'promo_codes'): ?>class="active"<?php endif; ?>><a href="<?php print $config['url']; ?>/action:promo_codes"><span class="ico ipromo"></span><span><?php _e("Promo codes"); ?></span></a></li>
-->

         <?php event_trigger('admin_shop_link'); ?>


      <li <?php if($active_action == 'options'): ?>class="active"<?php endif; ?>><a href="<?php print $config['url']; ?>/action:options"><span class="mw-icon-gear"></span><span>
        <?php _e("Options"); ?>
        </span></a></li>
    </ul>
  </div>
</div>
<script>


$(document).ready(function(){
    // Set the active class immediately
    var nav_lis = mw.$("#mw-admin-shop-navigation li");
    nav_lis.click(function(){
        if(!$(this).hasClass('active')){
           nav_lis.removeClass('active');
           $(this).addClass('active');
        }
    });

});

</script>
