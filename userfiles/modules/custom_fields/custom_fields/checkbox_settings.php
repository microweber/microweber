<? include('settings_header.php'); ?>
   <? // p ($data['custom_field_value']) ?>
   
   <?
   if(empty($data['custom_field_values'])){
	//$data['custom_field_values'] = array(0);   
   }
   
   
   // p ($data['custom_field_values']) 
   
   ?>
   
    <div class="control-group">
      <label class="control-label">Values</label>
     
     <? if(!empty($data['custom_field_values'])) : ?>
     <? foreach($data['custom_field_values'] as $v): ?>
      <div class="controls">
        <input type="text" name="custom_field_value[]"  value="<? print $v; ?>">
      </div>
  <? endforeach; ?>
  <? endif; ?>
  
  
  
   <div class="controls">
        <input type="text" name="custom_field_value[]"  value="">
      </div>
  
  
  
  
    </div>
 
<? include('settings_footer.php'); ?>
