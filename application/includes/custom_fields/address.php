<?
//$rand = rand();
if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>
<?


//print $data["custom_field_value"]; ?>
<? if(isarr($data['custom_field_values'])) : ?>

<div class="control-group">
  <label class="mw-ui-label mw-custom-field-label">
    <? if(isset($data['name']) == true and $data['name'] != ''): ?>
    <? print $data['name'] ?>
    <? elseif(isset($data['custom_field_name']) == true and $data['custom_field_name'] != ''): ?>
    <? print $data['custom_field_name'] ?>
    <? else : ?>
    <? endif; ?>
  </label>
  <hr style="margin-top: 7px;" />
  <? if(isset($data['help']) == true and $data['help'] != ''): ?>
  <small  class="mw-custom-field-help"><? print $data['help'] ?></small>
  <? endif; ?>


   
   
    <? foreach($data['custom_field_values'] as $k=>$v): ?>
    <? if(is_string( $v)){
	$kv =  $v;
	} else {
	$kv =  $v[0];	
	}
	?>
     <label class="label"><? print ($kv); ?></label>

     <input type="text" name="<? print $data['custom_field_name'] ?>[<? print ($k); ?>]"  data-custom-field-id="<? print $data["id"]; ?>"  />

    <? endforeach; ?>

</div>
<hr />
<? endif; ?>
