<?

if(isset($params['option_key']) and isset($params['option_group']) ){
	$data =  get_option($key = $params['option_key'], $option_group = $params['option_group'], $return_full = true, $orderby = false);
	
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
<?
 

 ?>
<div class="option-item" id="opt_form_<? print $rand ?>">
  <label class="control-label"><? print $data['option_key'] ?></label>
  <div class="controls">
    <input name="<? print $data['option_key'] ?>" class="mw_option_field"   type="text" data-refresh="<? print $data['option_group'] ?>"  value="<? print $data['option_value'] ?>" />
  </div>
</div>
