<?php
if (!user_can_access('module.contact_form.index')) {
    return;
}


$for_module = $config['module'];
$for_module_id = $params['id'];
?>

<script>
    mw.top().dialog.get('.mw_modal_live_edit_settings').resize(500);
</script>

<script>
    mw.require('editor.js')
    initEditor = function () {
        if (!window.editorLaunced) {
            window.editorLaunced = true;

            mw.Editor({
                element: document.getElementById('editorAM'),
            });
        }
    }
    $(document).ready(function () {
        $('#form_options').on('click', function () {
            initEditor();
        });
        initEditor()
    });
</script>
<module type="contact_form/settings_live_edit"  for_module="<?php print($for_module) ?>" for_module_id="<?php print $for_module_id ?>"  />
