<? include('settings_header.php'); ?>
    <div class="mw-custom-field-group">
      <label class="mw-custom-field-label" for="custom_field_value{rand}">Value</label>
      <div class="mw-custom-field-form-controls">
        <input type="text"  name="custom_field_value"  value="<? print ($data['custom_field_value']) ?>" id="custom_field_value{rand}">
      </div>
    </div>
    <? include('settings_footer.php'); ?>
