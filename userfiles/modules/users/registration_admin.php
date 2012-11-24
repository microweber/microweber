
<h2>Register form settings</h2>
<fieldset>
  <legend>Title</legend>
  <div class="control-group">
    <label class="control-label">Form Title</label>
    <div class="controls">
      <input name="form_title" class="mw_option_field"   type="text"    value="<?php print option_get('form_title', $params['id']) ?>" />
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Regiter button title</label>
    <div class="controls">
      <input name="form_btn_title" class="mw_option_field"   type="text"   value="<?php print option_get('form_btn_title', $params['id']) ?>" />
    </div>
  </div>
  <h2>Global settings</h2>
  <div class="control-group">
    <label class="control-label">facebook_api</label>
    <div class="controls">
      <input name="facebook_api" class="mw_option_field" data-module-id='api'  type="text"   value="<?php print option_get('facebook_api', 'api') ?>" />
    </div>
  </div>
</fieldset>
