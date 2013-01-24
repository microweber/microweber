<? include('settings_header.php'); ?>


 <div class="custom-field-col-left">

  <div class="mw-custom-field-group ">
  <label class="mw-ui-label" for="input_field_label<? print $rand ?>">
    <?php _e('Define Title'); ?>
  </label>

    <input type="text" class="mw-ui-field" value="<? print ($data['custom_field_name']) ?>" name="custom_field_name" id="input_field_label<? print $rand ?>">

</div>
</div>



   <div class="custom-field-col-right">
    <div class="mw-custom-field-group">
      <label class="mw-ui-label" for="custom_field_value<? print $rand ?>">my@email.com</label>

        <input type="text" class="mw-ui-field" onkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit<? print $rand ?>');"  name="custom_field_value"  value="my@email.com" id="custom_field_value<? print $rand ?>">

    </div>
    <button type="button" class="mw-ui-btn mw-ui-btn-blue mw-custom-fields-save" onclick="__save();"><?php _e('Save changes'); ?></button>
    </div>

    <? include('settings_footer.php'); ?>
