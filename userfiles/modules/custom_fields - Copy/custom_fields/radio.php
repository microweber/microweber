<?

$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>
<?


//print $data["custom_field_value"]; ?>
<? if(!empty($data['custom_field_values'])) : ?>

<div class="mw-custom-field-group mw-custom-field-group-checkbox custom-field-preview"> <span class="mw-custom-field-label" for="custom_field_help_text<? print $rand ?>"><? print $data["custom_field_name"]; ?></span>
  <? foreach($data['custom_field_values'] as $k => $v): ?>
  <? if(is_string( $k)){
	$kv =  $k;	
	} else {
	$kv =  $v;	
	}
	?>
  <div class="mw-custom-field-form-controls">
    <input type="radio" name="<? print $data["custom_field_name"]; ?>"  <? if (isset($data['input_class'])): ?> class="<? print $data['input_class'] ?>"  <? endif; ?>    data-custom-field-id="<? print $data["id"]; ?>" value="<? print $kv; ?>" <? if(isset($data['custom_field_value']) == true and $data['custom_field_value'] == $kv): ?> checked="checked" <? endif; ?> >
    <label for="field-<? print $data["id"]; ?>"><? print ($v); ?></label>
  </div>
  <? endforeach; ?>
</div>
<? endif; ?>
