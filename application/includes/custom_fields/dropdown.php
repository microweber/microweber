<?
//$rand = rand();
if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>
<?

   //d($data);

//print $data["custom_field_value"]; ?>
<? if(!empty($data['custom_field_values'])) : ?>



<div class="control-group">
  <label>
    <? if(isset($data['name']) == true and $data['name'] != ''): ?>
    <? print $data['name'] ?>
    <? elseif(isset($data['custom_field_name']) == true and $data['custom_field_name'] != ''): ?>
    <? print $data['custom_field_name'] ?>
    <? else : ?>
    <? endif; ?>
  </label>
  <? if(isset($data['help']) == true and $data['help'] != ''): ?>
  <small  class="mw-custom-field-help"><? print $data['help'] ?></small>
  <? endif; ?>



 <? if(isarr($data['custom_field_values'])): ?>
  <select <? if(isset($data['options']) and isarr($data['options']) == true and  in_array('multiple', $data['options'])): ?> multiple="multiple"<? endif; ?> <? if (isset($data['input_class'])): ?> class="<? print $data['input_class'] ?>"  <? endif; ?>  name="<? print $data["custom_field_name"]; ?>"  data-custom-field-id="<? print $data["id"]; ?>">
    <?
	foreach($data['custom_field_values'] as $k=>$v): ?>
    <? if(is_string( $k)){
	$kv =  $k;
	} else {
	$kv =  $v;
	}
	?>
    <option  data-custom-field-id="<? print $data["id"]; ?>" value="<? print $kv; ?>" <? if(isset($data['custom_field_value']) == true and $data['custom_field_value'] == $kv): ?> selected="selected" <? endif; ?> ><? print ($v); ?></option>
    <? endforeach; ?>
  </select>
  
  <? endif; ?>
</div>
<? endif; ?>
