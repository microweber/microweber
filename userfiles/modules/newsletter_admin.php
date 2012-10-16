 
<fieldset>
  <legend>Form settings</legend>
  <div class="control-group">
    <label class="control-label">Form Title</label>
    <div class="controls">
    <textarea  name="form_title" class="mw_option_field"   type="text" data-refresh="contact_form"><?php print option_get('form_title', $params['id']) ?></textarea>
       
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">form_sub_title</label>
    <div class="controls">
        <textarea  name="form_sub_title" class="mw_option_field"   type="text" data-refresh="contact_form"><?php print option_get('form_sub_title', $params['id']) ?></textarea>

     </div>
  </div>
</fieldset>
