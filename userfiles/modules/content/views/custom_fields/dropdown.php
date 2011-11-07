<? if (!empty($data)) : ?>
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

$val = $params['value']; 


if( $val == false){
	$val = $data['param_default']; 
	
}


if( $vals == false){
	$vals = $data['param_values']; 
	
	
}
if( $vals != false){
	$vals = explode(',',$vals);
}
//p($params);
?>
<? //p($vals); ?>
<? //p($data); ?>
 <? if(trim($data['help']) == '') {$data['help'] = $data['name'];}  ?>
<span><? print $data['help'] ?>:</span> 

 


<select  <?  if( $data['help']) : ?> title="<?  print addslashes($data['help']);  ?>"   <? endif; ?> name="custom_field_<?  print $data['param'];  ?>" class="custom_field_<?  print $data['param'];  ?>">
    <? if(!empty($vals)) :?>
    <? foreach($vals as $val): ?>
    <option value="<? print $val  ?>"     <?  if( $data['param_default'] == $val) : ?> selected="selected"   <? endif; ?>      ><? print $val  ?></option>
    <? endforeach; ?>
    <? endif; ?>
  </select>
 
 
 
<?  endif; ?>
