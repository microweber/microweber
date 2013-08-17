<?php $this_field_name = (!isset($field_name) or !$field_name) ? "content":$field_name;  ?>
<?php $cfs = mw('fields')->names_for_table($this_field_name); ?>
                <?php if(is_array($cfs )): ?>
                <?php foreach($cfs  as $item): ?>
                <label> <?php print $item['custom_field_name'] ?>
                  <input name="custom_field_<?php print $item['custom_field_name'] ?>"    type="text" class="mw-exec-option"  >
                </label>
                <?php endforeach ; ?>
                <?php endif; ?>
 
