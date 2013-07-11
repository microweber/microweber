<?php 
 
$rand_id = md5(serialize($params)); ?>

<div id="mw_email_source_code_editor<?php print $rand_id ?>" >
          <textarea name="source_code" cols="50"  class="mw_option_field" style="height:600px; width:100%;" data-refresh="embed"     ><?php print get_option('source_code', $params['id']) ?></textarea>
 </div>
