<style>
    .mw-ui-field-full-width {
        width: 100%;
    }

    .js-danger-text {
        padding-top: 5px;
        color: #c21f1f;
    }
</style>


<script type="text/javascript">
    function edit_iframe_template(template_id) {

        var module_type = 'newsletter';
        var module_id = 'edit_template_iframe';

        var src = mw.settings.site_url + 'api/module?template_id=' + template_id + '&id=' + module_id + '&type=' + module_type + '&autosize=true';
        var modal = mw.tools.modal.frame({
            url: src,
            width: 1500,
            height: 1500,
            name: 'mw-module-settings-editor-front',
            title: 'Settings',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        });
    }

    initEditor = function () {
        if (window.editorLaunced) {
            $('.mw-iframe-editor').remove();
        }
        editorLaunced = true;
        var editorTemplate = mw.editor({
            element: mwd.getElementById('js-editor-template'),
            hideControls: ['format', 'fontsize', 'justifyfull']
        });
    };

    /*$(editorTemplate).on('change', function () {
        this.value;
    });*/

    $(document).ready(function () {

        $(".js-edit-template-form").submit(function (e) {

            e.preventDefault(e);

            var errors = {};
            var data = mw.serializeFields(this);

            $.ajax({
                url: mw.settings.api_url + 'newsletter_save_template',
                type: 'POST',
                data: data,
                success: function (result) {

                    mw.notification.success('<?php _ejs('Template saved'); ?>');

                    // Reload the modules
                    mw.reload_module('newsletter/templates_list')
                    mw.reload_module_parent('newsletter');

                    $(".js-edit-template-form")[0].reset();

                    list_templates();

                },
                error: function (e) {
                    alert('Error processing your request: ' + e.responseText);
                }
            });

        });

    });
</script>


<form class="js-edit-template-form">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Template title'); ?></label>
        <input name="title" type="text" value="" class="mw-ui-field mw-ui-field-full-width js-validation js-edit-template-title"/>
        <div class="js-field-message"></div>
    </div>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Template design'); ?></label>
        <br/>
        Variables:
        <br/>
        {first_name} , {Last_name} , {email} , {unsubscribe} {site_url}
        <br/>

        <button onclick="edit_iframe_template($('.js-edit-template-id').val())" type="button" class="mw-ui-btn mw-ui-btn-info" style="float:right;"><?php _e('Use Template Generator'); ?></button>

        <textarea id="js-editor-template" name="text" class="js-edit-template-text" style="border:3px solid #cfcfcf; width:100%;height:500px;margin-top:5px;"></textarea>

        <div class="js-template-design"></div>
        <div class="js-field-message"></div>
    </div>
    <br/>
    <button type="submit" class="mw-ui-btn"><?php _e('Save'); ?></button>

    <a class="mw-ui-btn mw-ui-btn-icon" href="javascript:;" onclick="delete_template($('.js-edit-template-id').val())"> <span class="mw-icon-bin"></span> </a>
    <input type="hidden" value="0" class="js-edit-template-id" name="id"/>

</form>