
<div class="control-group">
  <label class="control-label">Form Title</label>
  <div class="controls">
    <input name="form_title" class="mw_option_field"   type="text"    value="<?php print get_option('form_title', $params['id']) ?>" />
  </div>
</div>
<div class="control-group">
  <label class="control-label">Regiter button title</label>
  <div class="controls">
    <input name="form_btn_title" class="mw_option_field"   type="text"   value="<?php print get_option('form_btn_title', $params['id']) ?>" />
  </div>
</div>

  <?   $curent_val = get_option('enable_user_fb_registration',  $params['id']); ?>
  enable_user_fb_registration
  <select name="enable_user_fb_registration" class="mw_option_field mw-ui-field"   type="text">
    <option value="n" <? if($curent_val == 'n'): ?> selected="selected" <? endif; ?>>No</option>

    <option value="y" <? if($curent_val == 'y'): ?> selected="selected" <? endif; ?>>Yes</option>
  </select>