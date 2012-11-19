<? include('settings_header.php'); ?>
    <div class="mw-custom-field-group">
      <label class="mw-ui-label" for="custom_field_value<? print $rand ?>">Value</label>
      <div class="mw-custom-field-form-controls">
        <input type="text" class="mw-ui-field" onkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit<? print $rand ?>');"  name="custom_field_value"  value="<? print ($data['custom_field_value']) ?>" id="custom_field_value<? print $rand ?>">
      </div>
    </div>
    <? include('settings_footer.php'); ?>
