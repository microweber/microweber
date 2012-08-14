
<div class="control-group">
  <label class="control-label" for="custom_field_required<? print $rand ?>">Required</label>
  <div class="controls">
    <label class="checkbox">
      <input type="checkbox"  name="custom_field_required" id="custom_field_required<? print $rand ?>" value="y" <? if(trim($data['custom_field_required']) == 'y'): ?> checked="checked"  <? endif; ?> >
      Is this field Required? </label>
  </div>
</div>
<div class="control-group">
  <label class="control-label">Is active</label>
  <div class="controls">
    <label class="radio">
      <input type="radio" name="custom_field_is_active"   <? if(trim($data['custom_field_is_active']) == 'y'): ?> checked="checked"  <? endif; ?>  value="y">
      Yes </label>
    <label class="radio">
      <input type="radio" name="custom_field_is_active" <? if(trim($data['custom_field_is_active']) == 'n'): ?> checked="checked"  <? endif; ?>   value="n">
      No </label>
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="custom_field_help_text<? print $rand ?>">Help text</label>
  <div class="controls">
    <input type="text"  name="custom_field_help_text"   value="<? print ($data['custom_field_help_text']) ?>"  id="custom_field_help_text<? print $rand ?>">
  </div>
</div>
<div class="form-actions">
  <button type="button" class="btn btn-primary" onclick="save_cf_<? print $rand ?>()">Save changes</button>
  <button class="btn"   onclick="remove_cf_<? print $rand ?>()" >Delete</button>
</div>
</fieldset>
</div>
