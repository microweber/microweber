
<h2>Tab 1 Fields</h2>
<module type="custom_fields" id="<? print $params['id'] ?>" view="admin" />
<h2>Tab 2 - settings</h2>
<fieldset>
  <legend>Form settings</legend>
  <div class="control-group">
    <label class="control-label">Form Title</label>
    <div class="controls">
      <input name="form_title" class="mw_option_field"   type="text" data-refresh="contact_form"  value="<?php print option_get('form_title', $params['id']) ?>" />
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">form_save_as</label>
    <div class="controls">
      <input name="form_save_as" class="mw_option_field"   type="text" data-refresh="contact_form"  value="<?php print option_get('form_save_as', $params['id']) ?>" />
    </div>
  </div>
</fieldset>
