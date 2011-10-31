<? 
$rand = rand();
 // p($params);
if($params['cf_id']){
	
	$arr = array();
		$arr['id'] =$params['cf_id'];
$cf_conf = CI::model ( 'core' )->getCustomFieldsConfig($arr) 	;
$cf_conf = $cf_conf[0];
// p($cf_conf);
	
}



$check_val = $cf_conf['param_default'];

$check_val  = explode('_', $check_val );


if($check_val[0]){
$price = 	$check_val[0];
	
}


if($check_val[1]){
	if(CI::model( 'core' )->userId() > 0){
		$price = 	$check_val[1];
	}
}

?>
<? if(!empty($cf_conf) ): ?>
<? if(intval($check_val) > 0): ?>

<span>
<? if(($cf_conf['help']) != ''): ?>
<? print $cf_conf['help'] ?>:
<? else: ?>
<? print $cf_conf['name'] ?>:
<? endif; ?>
<input name="custom_field_<? print $cf_conf['param'] ?>" value="<? print  $price   ?>" type="hidden">
</span> <? print  $price  ?> <?php print option_get('shop_currency_sign') ; ?>
<? endif; ?>
<? endif; ?>
