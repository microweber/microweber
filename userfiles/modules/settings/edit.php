<?php   //  d($orig_params); ?>
<?php
$data = false;
 if($data == false and isset($orig_params) and isset($orig_params['for_module_id']) and isset($params['id']) ){
 	$chck =   get_option('limit=1&id=' . $params['id']);
	if (isset($chck[0]) and isset($chck[0]['id'])) {
	$data = $chck[0];
	 
	}
	
}


 if($data == false and isset($orig_params) and isset($orig_params['for_module_id']) ){
 	$chck =   get_option('limit=1&module=' . $orig_params['for_module_id']);
	if (isset($chck[0]) and isset($chck[0]['id'])) {
	$data = $chck[0];
	 
	}
	
}
if($data == false and isset($params['option_key']) and isset($params['option_group']) ){
	$data =  get_option($key = $params['option_key'], $option_group = $params['option_group'], $return_full = true, $orderby = false);
	
} else {
	
}

if(!is_array($data)){
	$data = array();
	$data['id'] = 0;
}



if(!isset($data['name'])){
	
}  

 
 ?>
<?php //$rand = uniqid().rand(); ?>

<script  type="text/javascript">
$(document).ready(function(){
	
	<?php if(isset($data['option_key']) and  trim($data['option_key']) == 'current_template'): ?>
    
	
	
	  
      <?php else : ?> 
 mw.options.form('#opt_form_{rand}', function(){
      mw.notification.success("<?php _e("All changes are saved"); ?>.");
    });
      <?php endif; ?> 
});
</script>
 
<div class="option-item" id="opt_form_{rand}">
  <div class="controls">
 <?php if(isset($orig_params) and isset($orig_params['for_module_id'])): ?>
      <?php else : ?>
      <?php endif; ?> 
     <input type="hidden" name="id" value="<?php print $data['id'] ?>" />
     <?php // d($data); ?>
    <?php if( function_exists('make_field')): ?>
    <?php 
  $data['save_in'] = 'table_options';
  $data['name'] = $data['option_key'];
  $data['value'] = $data['option_value'];
  $data['values'] = $data['field_values'];
  $data['input_class'] = 'mw-ui-field';  
  if( $data['name'] == 'current_template'){
	  $data['type'] = 'website_template';
  }

   //$data['title'] =  $data['name'];  
 // d($data);
   
  if(isset($orig_params) and isset($orig_params['for_module_id']) ){
  $data['name'] = $data['option_key'].'|for_module|'.$orig_params['for_module_id'];
	
}

  
  print mw()->fields_manager->make($data); ?>
    <?php else : ?>
 
    <label class="control-label-title">
      <?php if(isset($data['name']) == true and $data['name'] != ''): ?>
      <?php print $data['name'] ?>
      <?php else : ?>
      <?php print $data['option_key'] ?>
      <?php endif; ?>
    </label>
    <?php if(isset($data['help']) == true and $data['help'] != ''): ?>
    <small  class="control-label-help"><?php print $data['help'] ?></small>
    <?php endif; ?>
    <input name="<?php print $data['option_key'] ?>" class="mw_option_field mw-ui-field"   type="text" option-group="<?php print $data['option_group'] ?>"  value="<?php print $data['option_value'] ?>" />
    <?php endif; ?>
  </div>
</div>
