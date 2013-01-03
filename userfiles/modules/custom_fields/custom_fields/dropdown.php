<?

$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>
<?


//print $data["custom_field_value"]; ?>
<? if(!empty($data['custom_field_values'])) : ?>
<div class="mw-custom-field-group mw-custom-field-group-checkbox custom-field-preview">
<label class="mw-ui-label mw-custom-field-label" for="custom_field_help_text<? print $rand ?>"><? print $data["custom_field_name"]; ?></label>
<select <? if(option_get('multiple_choices_'.$data['id'], 'custom_fields') == 'y'): ?>multiple="multiple"<? endif; ?> name="<? print $data["custom_field_name"]; ?>">


  <? foreach($data['custom_field_values'] as $v): ?>


      <option  data-custom-field-id="<? print $data["id"]; ?>" value="<? print $v; ?>"><? print ($v); ?></option>


  <? endforeach; ?>
</select>
</div>
<? endif; ?>




<? #print html_entity_decode(option_get('embed_code_'.$data['id'], 'custom_fields'));   ?>