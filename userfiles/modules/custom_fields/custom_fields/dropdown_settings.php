<? include('settings_header.php'); ?>

 <script type="text/javascript">

$(document).ready(function(){
mw.options.form('#dropdown_opts_<?php print $rand; ?>',function() {
    // mw.reload_module('custom_fields/admin');

          mw.custom_fields.save('custom_fields_edit<? print $rand ?>');

});

});
</script>


   <div class="custom-field-col left">

  <div class="mw-custom-field-group ">
  <label class="mw-ui-label" for="input_field_label<? print $rand ?>">
    <?php _e('Define Title'); ?>
  </label>
  <div class="mw-custom-field-form-controls">
    <input type="text" class="mw-ui-field" value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand ?>">
  </div>
  <div class="vSpace"></div>
    <label class="mw-ui-check left" style="margin-right: 7px;">
    <input type="checkbox" class="mw_option_field" data-option-group="custom_fields" id="multiple_choices_<? print $data['id']; ?>" name="multiple_choices_<? print $data['id']; ?>" value="y" <? if(get_option('multiple_choices_'.$data['id'], 'custom_fields') == 'y'): ?> checked="checked" <? endif; ?> />
    <span></span>
  </label>
  <label for="multiple_choices_<? print $data['id']; ?>" class="mw-ui-label">Allow Multiple Choices</label>
</div>
</div>



   <div class="custom-field-col right">













  
  <label class="mw-ui-label">Values</label>
  <div class="mw-custom-field-group" style="padding-top: 0;" id="fields<?php print $rand; ?>">
    <? if(isarr($data['custom_field_values'])) : ?>
    <? foreach($data['custom_field_values'] as $v): ?>
    <div class="mw-custom-field-form-controls">
      <input type="text" class="mw-ui-field" onkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit<? print $rand ?>');" name="custom_field_value[]"  value="<? print $v; ?>">
      <?php print $add_remove_controls; ?> </div>
    <? endforeach; ?>
    <? else: ?>
    <div class="mw-custom-field-form-controls">
      <input type="text" name="custom_field_value[]" class="mw-ui-field"  value="" />
      <?php print $add_remove_controls; ?> </div>
    <? endif; ?>
    <script type="text/javascript">
        mw.custom_fields.sort("fields<?php print $rand; ?>");
    </script>
  </div>
  </div>
  <? include('settings_footer.php'); ?>
