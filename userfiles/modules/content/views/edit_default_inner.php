<?php if (!isset($data)) {
    $data = $params;
}
if(isset($data['content-id'])){
	$data['content_id'] = $data['content-id'];
} else if(isset($data['page-id'])){
	$data['content_id'] = $data['page-id'];
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
            params.preview_layout = '<?php print module_name_encode($data['active_site_layout']) ?>'
            <?php endif; ?>
            if (typeof window.mweditor !== 'undefined') {
                $(mweditor).remove();
                delete window.mweditor;
            }





            mweditor = mw.admin.editor.init(area, params);


            if(mwd.getElementById('content-title-field') !== null){
                 mweditor.onload = function(){
                     if(mweditor.contentWindow){
                        var titleel = mweditor.contentWindow.document.body.querySelector('[field="title"]');
                        if(titleel !== null){
                            var rel = mw.tools.mwattr(titleel, 'rel');
                            if(rel == 'post' || rel == 'page' || rel == 'product'  || rel == 'content'){
                                mw.tools.mapNodeValues(titleel, mwd.getElementById('content-title-field'))
                            }
                        }
                     }


                    mw.admin.postImageUploader();

                 }

            }


		   mw_preview_frame_object = mw.top().win.mw_preview_frame_object = mweditor;





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

