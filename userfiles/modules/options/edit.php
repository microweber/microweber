<?php
if(isset($params['option_key']) and isset($params['option_group']) ){
	$data =  get_option($key = $params['option_key'], $option_group = $params['option_group'], $return_full = true, $orderby = false);

}

if(!is_array($data)){
	$data = array();
}
?>
<?php $rand = uniqid(); ?>
<script  type="text/javascript">
$(document).ready(function(){
mw.options.form('#opt_form_<?php echo $rand;?>');

});
</script>
<div class="option-item" id="opt_form_<?php echo $rand;?>">
  <label class="control-label"><?php print $data['option_key'] ?></label>
  <div class="controls">
    <input name="<?php print $data['option_key'] ?>" class="mw_option_field"   type="text" data-refresh="<?php print $data['option_group'] ?>"  value="<?php print $data['option_value'] ?>" />
  </div>
</div>
