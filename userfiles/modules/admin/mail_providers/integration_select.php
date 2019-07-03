<?php
$option_group = $params['id'];

if (isset($params['option_group'])) {
	$option_group = $params['option_group'];	
}

$mailProviders = get_modules('type=mail_provider');
?>
<?php if(!empty($mailProviders)): ?>
<div class="mw-ui-field-holder">
    <label class="mw-ui-check">
        <span><?php _e("Save contacts information on selected mail providers"); ?></span> 
   </label>
   <br /> <br />
   
   <?php foreach($mailProviders as $mailProvider): ?>
   <label class="mw-ui-check" style="border:1px solid #0000001a;border-radius:4px;padding:5px;padding-right:10px;">
        <input type="checkbox" parent-reload="true" value="y" name="use_integration_with_<?php echo strtolower($mailProvider['name']); ?>" class="mw_option_field" option-group="<?php echo $option_group; ?>" <?php if(get_option('use_integration_with_' . strtolower($mailProvider['name']), $option_group)=='y'): ?> checked="checked"  <?php endif; ?>>
        <span></span><span><?php echo $mailProvider['name']; ?></span>
   </label> 
   <?php endforeach; ?>
</div>
<?php endif; ?>