<?
 //p($data);
$rand = rand();
 ?>





<? //print $data["custom_field_value"]; ?>



   <? if(!empty($data['custom_field_values'])) : ?>
   
   <div class="control-group">
  <label class="control-label" for="custom_field_help_text<? print $rand ?>"><? print $data["custom_field_name"]; ?></label>
  <? foreach($data['custom_field_values'] as $v): ?>
      <div class="controls">
        <input type="checkbox" name="<? print $data["custom_field_name"]; ?>[]"  value="<? print $v; ?>"><? print decodeUnicodeString($v); ?>
      </div>
  <? endforeach; ?>
</div>




   
  

<? endif; ?>