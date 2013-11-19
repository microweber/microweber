<?php
only_admin_access();

$rand_id = md5(serialize($params)); ?>

<div class="module-live-edit">
  <div id="mw_email_source_code_editor<?php print $rand_id ?>">
      <label class="mw-ui-label"><?php _e("Insert Embed Code"); ?></label>
      <textarea name="source_code" class="mw_option_field mw-ui-field" style="height:100px; width:370px;" data-refresh="embed"><?php print get_option('source_code', $params['id']) ?></textarea>
   </div>
 </div>
