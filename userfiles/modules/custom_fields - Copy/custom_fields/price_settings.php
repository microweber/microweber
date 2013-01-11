<? include('settings_header.php'); ?>
    <div class="mw-custom-field-group">
      <label class="mw-custom-field-label" for="custom_field_value<? print $rand ?>">Value</label>
      <div class="mw-custom-field-form-controls">
        <input type="text"  name="custom_field_value" onkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit<? print $rand ?>');"   value="<? print ($data['custom_field_value']) ?>" id="custom_field_value<? print $rand ?>">
      </div>
    </div>
    <? include('settings_footer.php'); ?>
