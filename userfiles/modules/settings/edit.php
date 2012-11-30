<?

if(isset($params['option_key']) and isset($params['option_group']) ){
	$data =  option_get($key = $params['option_key'], $option_group = $params['option_group'], $return_full = true, $orderby = false);
	
} else {
	 d($params);
}

if(!is_arr($data)){
	$data = array();
}



if(!isset($data['name'])){
	
}

 
 ?>
<?php $rand = uniqid().rand(); ?>
<script  type="text/javascript">
$(document).ready(function(){
mw.options.form('#opt_form_<? print $rand ?>');
 
});
</script>

<div class="option-item" id="opt_form_<? print $rand ?>">
  <div class="controls">
    <input type="hidden" name="id" value="<? print $data['id'] ?>" />
    <? if(isset($data['field_type']) == true and $data['field_type'] != '' and function_exists('make_custom_field')): ?>
    <? 
  $data['save_in'] = 'table_options';
  $data['custom_field_name'] = $data['option_key'];
  $data['custom_field_value'] = $data['option_value'];
   $data['custom_field_values'] = $data['field_values'];
 
  
  print make_field($data); ?>
    <? else : ?>
    <label class="control-label-title">
      <? if(isset($data['name']) == true and $data['name'] != ''): ?>
      <? print $data['name'] ?>
      <? else : ?>
      <? print $data['option_key'] ?>
      <? endif; ?>
    </label>
    <? if(isset($data['help']) == true and $data['help'] != ''): ?>
    <small  class="control-label-help"><? print $data['help'] ?></small>
    <? endif; ?>
    <input name="<? print $data['option_key'] ?>" class="mw_option_field mw-ui-field"   type="text" option-group="<? print $data['option_group'] ?>"  value="<? print $data['option_value'] ?>" />
    <? endif; ?>
  </div>
</div>
