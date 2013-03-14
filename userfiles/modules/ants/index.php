<script src="<? print $config['url_to_module'] ?>js/jquery.insects.js"></script>
<link rel="stylesheet" type="text/css" href="<? print $config['url_to_module'] ?>css/jquery.insects.css" />
<? $how_many_ants = get_option('number_of_ants', $params['id']);
if($how_many_ants == false or $how_many_ants == '' or intval( $how_many_ants) == 0){
	$how_many_ants = 5;
}
 ?>
<script type="text/javascript">
	$(document).ready(function () {
	 $('#<? print $params['id']; ?>').insectify({chance: 1, squishable:1,"max-speed":10, 'mouse-distance':20, 'max-insects': <? print intval($how_many_ants); ?>});
	});
</script>
<? if(is_admin()): ?>
<? print mw_notif("Click here to edit the Ants"); ?>
<? endif;
