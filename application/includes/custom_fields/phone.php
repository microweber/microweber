<?

//$rand = rand();

if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>
<?


//print $data["custom_field_value"]; ?>
<div class="control-group">
<label class="custom-field-title">
    <? if(isset($data['name']) == true and $data['name'] != ''): ?>
    <? print ucwords(str_replace('_', ' ', $data['name'])); ?>
    <? elseif(isset($data['custom_field_name']) == true and $data['custom_field_name'] != ''): ?>
    <? print $data['custom_field_name'] ?>
    <? else : ?>
    <? endif; ?>
  </label>
<input type="text"
        <? if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <? endif; ?>
        <? if (isset($data['input_class'])): ?> class="<? print $data['input_class'] ?>"  <? endif; ?>
        data-custom-field-id="<? print $data["id"]; ?>"
        name="<? print $data["custom_field_name"]; ?>"
        placeholder="<? print $data["custom_field_value"]; ?>" />
        </div>
