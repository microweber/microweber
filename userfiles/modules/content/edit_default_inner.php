<?php if (!isset($data)) {
    $data = $params;
}

?>
<script>
    mw.load_editor_internal = function (element_id) {
        var element_id = element_id || 'mw-admin-content-iframe-editor';
        var area = mwd.getElementById(element_id);


        if (area !== null) {
            var params = {};
            <?php if(isset($data['content_id'])): ?>
            params.content_id = '<?php print $data['content_id'] ?>';
            <?php endif; ?>
            <?php if(isset($data['content_type'])): ?>
            params.content_type = '<?php print $data['content_type'] ?>';
            <?php endif; ?>
            <?php if(isset($data['subtype'])): ?>
            params.subtype = '<?php print $data['subtype'] ?>';
            <?php endif; ?>
            <?php if(isset($data['parent_page'])): ?>
            params.parent_page = '<?php print $data['parent_page'] ?>';
            <?php endif; ?>
            <?php if(isset($data['parent_page'])): ?>
            params.inherit_template_from = '<?php print $data['parent_page'] ?>';
            <?php endif; ?>
            params.live_edit = true;
            <?php if(isset($data['active_site_template'])): ?>
            params.preview_template = '<?php print $data['active_site_template'] ?>'
            <?php endif; ?>
            <?php if(isset($data['active_site_layout'])): ?>
            params.preview_layout = '<?php print $data['active_site_layout'] ?>'
            <?php endif; ?>
            if (typeof window.mweditor !== 'undefined') {
                $(mweditor).remove();
                delete window.mweditor;
            }
            mweditor = mw.admin.editor.init(area, params);
			
 //
//			 mweditor.onbeforeunload = function(e) {
//			//  alert( 'Dialog text here.');
//			};
			

        }


    }
</script>
<script>
    $(mwd).ready(function () {




    mw.load_editor_internal();
    });

</script>
<?php $content_edit_modules = mw('ui')->admin_content_edit_text(); ?>
<?php $modules = array(); ?>
<?php if (!empty($content_edit_modules) and !empty($data)) {
   	
    foreach ($content_edit_modules as $k1=>$content_edit_module) {
		foreach ($data as $k=>$v) {
			if(isset($content_edit_module[$k])){
				$v1 = $content_edit_module[$k];
				$v2 = $v;
				if(trim($v1) == trim($v2)){
				 $modules[] = $content_edit_module['module'];
				}
			}
			
		}
    }
	$modules = array_unique($modules);
}

?>
<div class="mw-ui-field-holder" id="mw-edit-page-editor-holder">
  <?php if(empty($modules)): ?>
  <div id="mw-admin-content-iframe-editor"></div>
  <?php else:  ?>
  <?php foreach($modules as $module) : ?>
    <?php print load_module($module,$data); ?>
  <?php endforeach; ?>
  <?php endif; ?>
</div>
