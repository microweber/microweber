<? if (!empty($data)) : ?>
<? $vals = $params['value']; 

if( $vals == false){
	$vals = $data['param_values']; 
	
	
}
if( $vals != false){
	$vals = explode(',',$vals);
}
?>

<label class="custom_field_label custom_field_label_<?  print $data['param'];  ?>"> <span>
  <?  print $data['name'];  ?>
  </span>
 
    <? if(!empty($vals)) :?>
    <? foreach($vals as $val): ?>
    <input type="radio" class="custom_field_radio custom_field_<?  print $data['param'];  ?>" value="<? print $val  ?>"  name="custom_field_<?  print $data['param'];  ?>"      <?  if( $data['param_default'] == $val) : ?> checked="checked"   <? endif; ?>  <?  if( $data['help']) : ?> title="<?  print addslashes($data['help']);  ?>"   <? endif; ?>     ><? print $val  ?> 
    <? endforeach; ?>
    <? endif; ?>
  
</label>
<?  endif; ?>
