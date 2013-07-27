<?php $this_field_name = (!isset($field_name) or !$field_name) ? "in_table":$field_name;  ?>
 <?php $db_in_table =db_get_tables_list(); ?>
<?php if(is_array($db_in_table )): ?>
<select name="<?php print $this_field_name ?>"    class="mw-exec-option"  >
  <option  selected="selected" value="" >false</option>
  <?php foreach($db_in_table  as $item): ?>
  <option  value="<?php print $item ?>" ><?php print mw('db')->assoc_table_name($item)  ?></option>
  <?php endforeach ; ?>
  <?php endif; ?>
</select>
 