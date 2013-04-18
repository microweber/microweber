<?

//$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>
<?

  ?>
<? if(!empty($data['custom_field_values'])) : ?>
 <div class="custom-field-title"><? print $data["custom_field_name"]; ?></div>
<div class="control-group custom-fields-type-checkbox">

  <div class="mw-customfields-checkboxes">

  <? foreach($data['custom_field_values'] as $v): ?>

    <label class="checkbox">
        <input type="checkbox" name="<? print $data["custom_field_name"]; ?>[]" id="field-<? print $data["id"]; ?>"  data-custom-field-id="<? print $data["id"]; ?>" value="<? print $v; ?>" />
        <span><? print ($v); ?></span>
    </label>

  <? endforeach; ?>

  </div>
</div>

<? endif; ?>
