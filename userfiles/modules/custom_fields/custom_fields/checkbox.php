<?

$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>




<? 


//print $data["custom_field_value"]; ?>



   <? if(!empty($data['custom_field_values'])) : ?>
   
   <div class="mw-custom-field-group">
  <span class="mw-custom-field-label" for="custom_field_help_text<? print $rand ?>"><? print $data["custom_field_name"]; ?></span>
  <? foreach($data['custom_field_values'] as $v): ?>
      <div class="mw-custom-field-form-controls">
      <label>
        <input type="checkbox" name="<? print $data["custom_field_name"]; ?>[]"  data-custom-field-id="<? print $data["id"]; ?>" value="<? print $v; ?>"><? print ($v); ?>
         </label>
      </div>
  <? endforeach; ?>
</div>




   
  

<? endif; ?>