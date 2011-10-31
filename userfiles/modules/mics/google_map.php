<?php $h =  option_get('map_h', $params['module_id']); ?>
<?php $w =  option_get('map_w', $params['module_id']) ;


$map_zoom =  option_get('map_zoom', $params['module_id']) ;

$map_address =  option_get('map_address', $params['module_id']) ;



if(intval($h) == 0){
	$h = 300;
}

if(intval($map_zoom) == 0){
	$map_zoom = 13;
}
if(trim(($map_address)) ==''){
	$map_address = 'Mountain View, CA';
}
$map_address = urlencode($map_address);

if(intval($w) == 0){
	$w = 500;
}
?>

<div> <img src="http://maps.google.com/maps/api/staticmap?center=<? print $map_address ?>|<? print $map_address ?>&zoom=<? print $map_zoom ?>&markers=<? print $map_address ?>&size=<? print $w ?>x<? print $h ?>&sensor=true"  width="<? print $w ?>" height="<? print $h ?>" /> </div>
