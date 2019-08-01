<?php
 
        

//$rand = rand();
if (!isset($data['id'])) {
 include('empty_field_vals.php');
}
?>
<?php

if(!isset($data['input_class'])){
	$data['input_class'] = '';
}

if(isset($data['params']) and isset($data['params']['input_class'])) {
	$data['input_class'] = $data['params']['input_class'];
}


$selected = false;

$is_required = (isset($data['options']) == true and isset($data['options']["required"]) == true);


if(!$data['values']) {
	$data['values'][0] = _e('Please, set dropdown options.', true);
}

$multiple = false;
if (isset($data['options']['multiple'])) {
	$multiple = true;
}
?>

<?php if ($multiple): ?> 
<script type="text/javascript">
mw.lib.require('chosen');
$(document).ready(function () {
	 $(".js-mw-select-<?php echo $data['id']; ?>").chosen({width:'100%'}); 
});
</script>
<?php endif; ?>

<?php if(!empty($data['values'])) : ?> 
<div class="control-group form-group">    
<label class="mw-ui-label"> 
    <?php if(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print $data['name'] ?>
    <?php elseif(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print $data['name'] ?>
    <?php else : ?>
    <?php endif; ?>
    
    <?php if (isset($data['options']) == true and isset($data['options']["required"]) == true): ?>  
	<span style="color:red;">*</span>
	<?php endif; ?>
  </label>


  <?php if(isset($data['help']) == true and $data['help'] != ''): ?>
  <small  class="mw-custom-field-help"><?php print $data['help'] ?></small>
  <?php endif; ?>

 <?php if(is_array($data['values'])): ?>
  <select <?php if ($multiple): ?> multiple="multiple" <?php endif; ?> <?php if($is_required and $is_required==1){ ?> required <?php } ?> class="<?php print $data['input_class']; ?> mw-ui-field js-mw-select-<?php echo $data['id']; ?>"  name="<?php print $data["name"]; ?>"  data-custom-field-id="<?php print $data["id"]; ?>">
    <?php foreach($data['values'] as $k=>$v): ?>
    <?php 
    if(is_string($k)){
		$kv =  $k;
	} else {
		$kv =  $v;
	}
	?>
    <option  data-custom-field-id="<?php print $data["id"]; ?>" value="<?php print $kv; ?>" 
	<?php if(!$selected): ?> selected="selected" <?php $selected=true; endif; ?> >
	<?php print ($v); ?>
	</option>
    <?php endforeach; ?>
  </select>
  
  <?php endif; ?>
</div>
<?php endif; ?>
