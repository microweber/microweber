<? include('settings_header.php'); ?>
   <? // p ($data['custom_field_value']) ?>
   
   <?
   if(empty($data['custom_field_values'])){
	//$data['custom_field_values'] = array(0);   
   }
   
  // d($data);
   // p ($data['custom_field_values']) 
   
   ?>
   
    <div class="mw-custom-field-group">
      <label class="mw-custom-field-label">Values</label>
     
     <? if(isarr($data['custom_field_values'])) : ?>
     <? foreach($data['custom_field_values'] as $v): ?>
      <div class="mw-custom-field-form-controls">
        <input type="text" name="custom_field_value[]"  value="<? print $v; ?>">
      </div>
  <? endforeach; ?>
  <? endif; ?>
  
  
  
   <div class="mw-custom-field-form-controls">
        <input type="text" name="custom_field_value[]"  value="">
      </div>
  
  
  
  
    </div>
 
<? include('settings_footer.php'); ?>
