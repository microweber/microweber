<?php if(!isset($data)){
$data = $params;	
}
 d($data);
?>


<script>
mw.load_editor_internal  =  function(element_id){
	var element_id =  element_id || 'mw-admin-content-iframe-editor';
	 var area = mwd.getElementById(element_id); 
	 
	 
	if(area !== null){
		var params = {};
		params.content_id='<?php print $data['content_id'] ?>';
		params.content_type='<?php print $data['content_type'] ?>';
		params.subtype='<?php print $data['subtype'] ?>';
		params.parent_page='<?php print $data['parent_page'] ?>';
		params.inherit_template_from='<?php print $data['parent_page'] ?>';
		params.live_edit=true;
		<?php if(isset($data['active_site_template'])): ?>
 			params.preview_template='<?php print $data['active_site_template'] ?>'
		<?php endif; ?>
		 <?php if(isset($data['active_site_layout'])): ?>
 			params.preview_layout='<?php print $data['active_site_layout'] ?>'
		<?php endif; ?>
		if(typeof window.mweditor !== 'undefined'){
			 $(mweditor).remove();
			 delete window.mweditor;
		}
		
		  mweditor = mw.admin.editor.init(area, params);
			
	 }
	
	
}
</script>
<script>
    $(mwd).ready(function(){
        mw.load_editor_internal();
	});
</script>
<div class="mw-ui-field-holder" id="mw-edit-page-editor-holder">






  <div id="mw-admin-content-iframe-editor"></div>
</div>
