<?

$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>
<?


//print $data["custom_field_value"]; ?>
<? if(!empty($data['custom_field_values'])) : ?>

<select class="mw-custom-field-group" name="<? print $data["custom_field_name"]; ?>">


  <? foreach($data['custom_field_values'] as $v): ?>


      <option  data-custom-field-id="<? print $data["id"]; ?>" value="<? print $v; ?>"><? print ($v); ?></option>


  <? endforeach; ?>
</select>
<? endif; ?>
