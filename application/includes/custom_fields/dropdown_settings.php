<? include('settings_header.php'); ?>




   <div class="custom-field-col-left">

  <div class="mw-custom-field-group ">
  <label class="mw-ui-label" for="input_field_label<? print $rand; ?>">
    <?php _e('Define Title'); ?>
  </label>

    <input type="text"  class="mw-ui-field" value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand; ?>">

  <div class="vSpace"></div>
    <label class="mw-ui-check left" style="margin-right: 7px;">
    <input type="checkbox"
        data-option-group="custom_fields"
        name="options[]"
        value="multiple"
        <? if(isset($data['options']) == true and isset($data['options']["multiple"]) == true and $data['options']["multiple"] == '1'): ?> checked="checked" <? endif; ?>
    />

    <span></span>
    <span>Allow Multiple Choices</span>
  </label>

</div>
</div>



   <div class="custom-field-col-right">

      <label class="mw-ui-label">Values</label>
      <div class="mw-custom-field-group" style="padding-top: 0;" id="fields<? print $rand; ?>">
        <? if(isarr($data['custom_field_values'])) : ?>
        <? foreach($data['custom_field_values'] as $v): ?>
        <div class="mw-custom-field-form-controls">
          <input type="text" class="mw-ui-field" name="custom_field_value[]"  value="<? print $v; ?>">
          <?php print $add_remove_controls; ?> </div>
        <? endforeach; ?>
        <? else: ?>
        <div class="mw-custom-field-form-controls">
          <input type="text" name="custom_field_value[]" class="mw-ui-field"  value="" />
          <?php print $add_remove_controls; ?> </div>
        <? endif; ?>
        <script type="text/javascript">
            mw.custom_fields.sort("fields<? print $rand; ?>");
        </script>
      </div>
  <?php print $savebtn; ?>
  </div>
  <? include('settings_footer.php'); ?>
