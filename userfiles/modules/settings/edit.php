<?   //  d($orig_params); ?>
<?
$data = false;
 if($data == false and isset($orig_params) and isset($orig_params['for_module_id']) and isset($params['id']) ){
 	$chck =   get_options('limit=1&id=' . $params['id']);
	if (isset($chck[0]) and isset($chck[0]['id'])) {
	$data = $chck[0];
	 
	}
	
}


 if($data == false and isset($orig_params) and isset($orig_params['for_module_id']) ){
 	$chck =   get_options('limit=1&module=' . $orig_params['for_module_id']);
	if (isset($chck[0]) and isset($chck[0]['id'])) {
	$data = $chck[0];
	 
	}
	
}
if($data == false and isset($params['option_key']) and isset($params['option_group']) ){
	$data =  get_option($key = $params['option_key'], $option_group = $params['option_group'], $return_full = true, $orderby = false);
	
} else {
	
}

if(!is_arr($data)){
	$data = array();
	$data['id'] = 0;
}



if(!isset($data['name'])){
	
}  

 
 ?>
<?php $rand = uniqid().rand(); ?>

<script  type="text/javascript">
$(document).ready(function(){
    mw.options.form('#opt_form_<? print $rand ?>', function(){
      mw.notification.success("<?php _e("All changes are saved"); ?>.")
    });
});
</script>

<div class="option-item" id="opt_form_<? print $rand ?>">
  <div class="controls">
 <? if(isset($orig_params) and isset($orig_params['for_module_id'])): ?>

      
      <? else : ?> 

      <? endif; ?> 
     <input type="hidden" name="id" value="<? print $data['id'] ?>" />
     <? // d($data); ?>
    <? if( function_exists('make_field')): ?>
    <? 
  $data['save_in'] = 'table_options';
  $data['custom_field_name'] = $data['option_key'];
  $data['custom_field_value'] = $data['option_value'];
  $data['custom_field_values'] = $data['field_values'];
  $data['input_class'] = 'mw-ui-field';  

   //$data['title'] =  $data['name'];  
   
   
  if(isset($orig_params) and isset($orig_params['for_module_id']) ){
  $data['custom_field_name'] = $data['option_key'].'|for_module|'.$orig_params['for_module_id'];
	
}
  
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
