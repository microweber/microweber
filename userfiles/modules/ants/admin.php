
<fieldset class="inputs">
  <legend><span><?php _e("Number of ants"); ?></span></legend>
  <input name="number_of_ants" data-refresh="ants"  class="mw_option_field"   type="range"  min="3" max="100"  value="<?php print mw('option')->get('number_of_ants', $params['id']) ?>">
</fieldset>
