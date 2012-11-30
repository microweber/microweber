
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
  
 
  
  <microweber module="settings/list"   id="options_list_<? print  $params['id']  ?>" for_module="<? print $config['module'] ?>" >
  
   
</fieldset>
