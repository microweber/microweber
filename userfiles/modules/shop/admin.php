<?php $mw_notif =  (url_param('mw_notif'));
if( $mw_notif != false){
 $mw_notif = mw('Microweber\Notifications')->read( $mw_notif);

}


  ?>

<?php if(is_array($mw_notif) and isset($mw_notif['rel_id']) and $mw_notif['rel_id'] !=0): ?>
<script type="text/javascript">

$(document).ready(function(){

window.location.href = '<?php print admin_url() ?>view:shop/action:orders/#vieworder=<?php print $mw_notif['rel_id'] ?>'; //Will take you to Google.



});



</script>


<?php else :  ?>

<?php
$here = dirname(__FILE__);
$here = $here.DS.'admin_views'.DS;
  $active_action = url_param('action'); ?>
<?php //mw('content')->create_default_content('shop'); ?>
<?php include($here .'nav.php'); ?>
<?php $is_shop = 'y'; ?>
<module type="content/backend" id="mw-content-backend" is_shop="y" />
 <?php endif; ?>



