<? 
$rand = rand();
 // p($params);
if($params['cf_id']){
	
	$arr = array();
		$arr['id'] =$params['cf_id'];
$cf_conf = CI::model ( 'core' )->getCustomFieldsConfig($arr) 	;
$cf_conf = $cf_conf[0];
 //p($cf_conf);
	
}



$check_val = $cf_conf['param_default'];

 
 
?>
<? if(($check_val) != false and ($check_val != 'undefined')  and ($check_val != NULL)): ?>

 


<textarea name="custom_field_<? print $cf_conf['param'] ?>"><? print  $check_val   ?></textarea>
 
<? endif; ?>