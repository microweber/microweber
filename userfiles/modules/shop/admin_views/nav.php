<? if(is_admin() == false) {error('Must be admin');}  ?>

<div id="mw-shop-menu">
  <? $orders = get_orders('count=1&order_status=pending&order_completed=y');



 ?>
 
 <?
	 $notif_html = '';
	$notif_count = get_notifications('module=shop&to_table=table_cart_orders&is_read=n&count=1');
 	if( $notif_count > 0){
    $notif_html = '<sup class="mw-notif-bubble">'.$notif_count.'</sup>';
    }
 
  ?>
  <a href="<?php print $config['url']; ?>/action:orders" class="new-order-notification"> <strong><?php print intval( $orders); ?></strong>
  <?php _e("New Orders"); ?>
  </a>
  <div id="mw-admin-shop-navigation">
    <ul class="mw-quick-links">
      <li <?php if($active_action == false): ?>class="active"<? endif; ?>><a href="<?php print $config['url']; ?>"><span class="ico iproduct"></span><span>
        <?php _e("Products"); ?>
        </span></a></li>
      <li <?php if($active_action == 'orders'): ?>class="active"<? endif; ?>><a href="<?php print $config['url']; ?>/action:orders"><span class="ico iorder"><? print $notif_html ?></span><span>
        <?php _e("Orders"); ?>
        </span></a></li>
      <li <?php if($active_action == 'clients'): ?>class="active"<? endif; ?>><a href="<?php print $config['url']; ?>/action:clients"><span class="ico iusers"></span><span>
        <?php _e("Clients"); ?>
        </span></a></li>
      <li <?php if($active_action == 'shipping'): ?>class="active"<? endif; ?>><a href="<?php print $config['url']; ?>/action:shipping"><span class="ico itruck"></span><span>
        <?php _e("Shipping"); ?>
        </span></a></li>
      <!--      <li <?php if($active_action == 'promo_codes'): ?>class="active"<? endif; ?>><a href="<?php print $config['url']; ?>/action:promo_codes"><span class="ico ipromo"></span><span><?php _e("Promo codes"); ?></span></a></li>
-->

         <? exec_action('mw_admin_shop_link'); ?>


      <li <?php if($active_action == 'options'): ?>class="active"<? endif; ?>><a href="<?php print $config['url']; ?>/action:options"><span class="ico ioptions"></span><span>
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
