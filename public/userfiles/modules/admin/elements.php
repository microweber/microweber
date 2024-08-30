<?php

  $rand = 'layouts_'.rand(); ?>
<script  type="text/javascript">

$(document).ready(function(){

mw.$('#modules_admin_categories_<?php print $rand; ?> .category_tree a[data-category-id]').on('click',function(e) {

	$p_id = $(this).parent().attr('data-category-id');

	mw.$('#modules_admin_<?php print $rand; ?>').attr('data-category', $p_id);

 mw.reload_module('#modules_admin_<?php print $rand; ?>');
 	 //mw.$('#modules_admin_<?php print $rand; ?>').removeAttr('cleanup_db');

 //	 alert($p_id);
return false;

 });

});

function mw_reload_all_modules(){
	mw.$('#modules_admin_<?php print $rand; ?>').attr('reload_modules',1);
	mw.$('#modules_admin_<?php print $rand; ?>').attr('cleanup_db',1);

    mw.tools.loading('#modules_admin_<?php print $rand; ?>', true);

    mw.notification.success('Reloading elements');

  	mw.load_module('admin/modules/elements','#modules_admin_<?php print $rand; ?>',function () {
        mw.tools.loading('#modules_admin_<?php print $rand; ?>', false);
        mw.notification.success('Elements are reloaded');

        $.post(mw.settings.api_url + 'mw_post_update');
        mw.notification.success("The DB was reloaded");



    });
}

</script>

<style>
    #modules_admin_<?php print $rand; ?> ul{
        list-style: none;
    }
</style>

<div class="container">
    <div class="row">
        <button onclick="mw_reload_all_modules()" class="btn btn-primary mb-2 float-right"><i class="mdi mdi-refresh icon-left mr-2"></i><?php _e('Reload elements'); ?></button>
        <module type="admin/modules/elements" id="modules_admin_<?php print $rand; ?>"/>
    </div>
</div>


