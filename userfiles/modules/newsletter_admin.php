 
<fieldset>
  <legend>Form settings</legend>
  <div class="control-group">
    <label class="control-label">Form Title</label>
    <div class="controls">
      <input name="form_title" class="mw_option_field"   type="text" data-refresh="contact_form"  value="<?php print option_get('form_title', $params['id']) ?>" />
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">form_sub_title</label>
    <div class="controls">
      <input name="form_sub_title" class="mw_option_field"   type="text" data-refresh="contact_form"  value="<?php print option_get('form_sub_title', $params['id']) ?>" />
    </div>
  </div>
</fieldset>
