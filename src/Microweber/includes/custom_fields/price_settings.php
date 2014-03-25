<?php include('settings_header.php'); ?>
<div class="custom-field-col-left">

  <div class="mw-custom-field-group ">
    <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
      <?php _e('Title'); ?>
    </label>

    <input type="text" class="mw-ui-field mw-ui-invisible-field" value="<?php print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<?php print $rand; ?>">

  </div>
</div>

<?php 
if($data['custom_field_value'] == ''){
	
$data['custom_field_value'] = 0;	
}

?>

<div class="custom-field-col-right">
  <div class="mw-custom-field-group">
    <label class="mw-ui-label" for="custom_field_value<?php print $rand; ?>">Value   <b><?php print mw('shop')->currency_symbol($curr=false,$key=3); ?> </b></label>

    <input type="text"
    class="mw-ui-field"
    name="custom_field_value"
    value="<?php print ($data['custom_field_value']) ?>" />

 
<?php /*
 <label class="mw-ui-check"><input type="checkbox"  class="mw-custom-field-option" name="options[on_sale]"  <?php if(isset($data['options']) == true and isset($data['options']["on_sale"]) == true): ?> checked="checked" <?php endif; ?> value="on_sale"><span></span><span>On sale?</span></label>
 
 
 
 
 
 <input type="text"
    class="mw-ui-field mw-custom-field-option"
    name="options[sale_price]"
    value="<?php if(isset($data['options']) == true and isset($data['options']["sale_price"]) == true): ?><?php print $data['options']["sale_price"][0] ?><?php endif; ?>" />
    
    
    */  ?>
    
    

<?php print $savebtn; ?>



  </div>
</div>
<?php include('settings_footer.php'); ?>
