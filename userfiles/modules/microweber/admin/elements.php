<?php

  $rand = 'layouts_'.rand(); ?>
<script  type="text/javascript">




$(document).ready(function(){
















mw.$('#modules_admin_categories_<?php print $rand; ?> .category_tree a[data-category-id]').bind('click',function(e) {

	$p_id = $(this).parent().attr('data-category-id');

	mw.$('#modules_admin_<?php print $rand; ?>').attr('data-category', $p_id);

 mw.reload_module('#modules_admin_<?php print $rand; ?>');

return false;




 });

});


function mw_reload_all_modules(){
	mw.$('#modules_admin_<?php print $rand; ?>').attr('reload_modules',1);
	mw.$('#modules_admin_<?php print $rand; ?>').attr('cleanup_db',1);
  	mw.load_module('admin/modules/elements','#modules_admin_<?php print $rand; ?>');
}


</script>
<button onclick="mw_reload_all_modules()" ><?php _e('Reload elements'); ?></button>

<table width=" 100%" border="1">
  <tr>
    <td><module type="categories/selector" rel="elements" id="modules_admin_categories_<?php print $rand; ?>" /></td>
    <td><module type="admin/modules/elements" id="modules_admin_<?php print $rand; ?>"    /></td>
  </tr>
</table>

