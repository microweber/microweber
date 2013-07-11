<? $this_field_name = (!isset($field_name) or !$field_name) ? "in_table":$field_name;  ?>
 <? $db_in_table =db_get_tables_list(); ?>
<? if(isarr($db_in_table )): ?>
<select name="<? print $this_field_name ?>"    class="mw-exec-option"  >
  <option  selected="selected" value="" >false</option>
  <? foreach($db_in_table  as $item): ?>
  <option  value="<? print $item ?>" ><? print db_get_assoc_table_name($item)  ?></option>
  <? endforeach ; ?>
  <? endif; ?>
</select>
 