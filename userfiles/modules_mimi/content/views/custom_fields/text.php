<? 
$rand = rand();
 // p($params);
if($params['cf_id']){
	
	$arr = array();
		$arr['id'] =$params['cf_id'];
		//p($arr);
$cf_conf = CI::model ( 'core' )->getCustomFieldsConfig($arr) 	;
//p($cf_conf);
$cf_conf = $cf_conf[0];

	
}



$check_val = $cf_conf['param_default'];

 
 
?>
<? if(($check_val) != false and ($check_val != 'undefined')  and ($check_val != NULL)): ?>

 

 <? if(trim($cf_conf['help']) == '') {$cf_conf['help'] = $cf_conf['name'];}  ?>
<span><? print $cf_conf['help'] ?>:
<input name="custom_field_<? print $cf_cfg['param'] ?>" value="<? print  $check_val   ?>" type="hidden">
</span> <? print  $check_val   ?>



 
<? endif; ?>