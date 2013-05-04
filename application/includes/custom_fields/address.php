<?
//$rand = rand();
if (!isset($data['id'])) {
include('empty_field_vals.php');
}
?>
<?


//print $data["custom_field_value"]; ?>
<?php if(isarr($data['custom_field_values'])) : ?>

<div class="control-group">
  <label class="mw-ui-label mw-custom-field-label">
    <?php if(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print $data['name'] ?>
    <?php elseif(isset($data['custom_field_name']) == true and $data['custom_field_name'] != ''): ?>
    <?php print $data['custom_field_name'] ?>
    <?php else : ?>
    <?php endif; ?>
  </label>
  <hr style="margin-top: 7px;" />
  <?php if(isset($data['help']) == true and $data['help'] != ''): ?>
  <small  class="mw-custom-field-help"><?php print $data['help'] ?></small>
  <?php endif; ?>


   
   
    <?php foreach($data['custom_field_values'] as $k=>$v): ?>
    <?php if(is_string( $v)){
	$kv =  $v;
	} else {
	$kv =  $v[0];	
	}
	?>
     <label><?php print ($kv); ?></label>

     <input type="text" name="<?php print $data['custom_field_name'] ?>[<?php print ($k); ?>]"  data-custom-field-id="<?php print $data["id"]; ?>"  />

    <?php endforeach; ?>

</div>
<hr />
<?php endif; ?>
