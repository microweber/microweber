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
