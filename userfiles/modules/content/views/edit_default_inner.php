<?php if (!isset($data)) {
    $data = $params;
}
if (isset($data['content-id'])) {
    $data['content_id'] = $data['content-id'];
} else if (isset($data['page-id'])) {
    $data['content_id'] = $data['page-id'];
}

?>

<script>
    mw.require('editor.js')
</script>
<script>

    mw.load_editor_internal = function (element_id) {
        element_id = element_id || 'mw-admin-content-iframe-editor';
        var area = document.getElementById(element_id);

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

        }
    }
</script>

<script>
    $(mwd).ready(function () {
        mw.load_editor_internal();
    });
</script>
