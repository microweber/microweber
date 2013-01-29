<?

//$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>
<?


//print $data["custom_field_value"]; ?>
<? if(!empty($data['custom_field_values'])) : ?>

<div class="control-group">
  <label class="label"><? print $data["custom_field_name"]; ?></label>
  <? foreach($data['custom_field_values'] as $k => $v): ?>
  <? if(is_string( $k)){
	$kv =  $k;
	} else {
	$kv =  $v;	
	}
	?>
  <label class="radio">
    <input type="radio" name="<? print $data["custom_field_name"]; ?>"  <? if (isset($data['input_class'])): ?> class="<? print $data['input_class'] ?>"  <? endif; ?>   data-custom-field-id="<? print $data["id"]; ?>" value="<? print $kv; ?>" <? if(isset($data['custom_field_value']) == true and $data['custom_field_value'] == $kv): ?> checked="checked" <? endif; ?> />
    <span><? print ($v); ?></span>
  </label>
  <? endforeach; ?>
</div>
<? endif; ?>
