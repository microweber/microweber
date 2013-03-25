<? $mw_notif =  (url_param('mw_notif'));
if( $mw_notif != false){
 $mw_notif = read_notification( $mw_notif);	
 
}

 
  ?>
 
<? if(isarr($mw_notif) and isset($mw_notif['rel_id']) and $mw_notif['rel_id'] !=0): ?>
<script type="text/javascript">

$(document).ready(function(){

window.location.href = '<? print admin_url() ?>view:shop/action:orders/#vieworder=<? print $mw_notif['rel_id'] ?>'; //Will take you to Google.


 
});



</script>


<? else :  ?>

<? 
$here = dirname(__FILE__);
$here = $here.DS.'admin_views'.DS;
  $active_action = url_param('action'); ?>
<? mw_create_default_content('shop'); ?>
<? include($here .'nav.php'); ?>
<? $is_shop = 'y'; ?>
<? 
 
$display_file = ADMIN_VIEWS_PATH .'content.php';
if($active_action != false){
	$vf = $here.$active_action.'.php' ;
	
	if(is_file($vf)){
	$display_file = ($vf);
	
	}

}

?>
<? include($display_file); ?>
<? endif; ?>



