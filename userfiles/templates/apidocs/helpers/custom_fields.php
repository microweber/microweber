<? $this_field_name = (!isset($field_name) or !$field_name) ? "content":$field_name;  ?>
<? $cfs = custom_field_names_for_table($this_field_name); ?>
                <? if(isarr($cfs )): ?>
                <? foreach($cfs  as $item): ?>
                <label> <? print $item['custom_field_name'] ?>
                  <input name="custom_field_<? print $item['custom_field_name'] ?>"    type="text" class="mw-exec-option"  >
                </label>
                <? endforeach ; ?>
                <? endif; ?>
 
