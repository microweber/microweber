<? include('settings_header.php'); ?>
  <div class="mw-custom-field-group">
    <div class="mw-custom-field-form-controls">
      <textarea onkeyup="mw.custom_fields.autoSaveOnWriting(this, 'custom_fields_edit<? print $rand ?>');" name="custom_field_value"   class="mw-ui-field"><? print ($data['custom_field_value']) ?></textarea>
    </div>
  </div>
  <? include('settings_footer.php'); ?>
