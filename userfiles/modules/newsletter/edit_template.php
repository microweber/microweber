<style>
    .js-danger-text {
        padding-top: 5px;
        color: #c21f1f;
    }
</style>

<script>mw.require('editor.js')</script>

<script>
    function edit_iframe_template(template_id) {

        var module_type = 'newsletter';
        var module_id = 'edit_template_iframe';

        var src = mw.settings.site_url + 'api/module?template_id=' + template_id + '&id=' + module_id + '&type=' + module_type + '&autosize=true';
        var modal = mw.dialogIframe({
            url: src,
            width: 1500,
            height: 1500,
            id: 'mw-module-settings-editor-front',
            title: 'Settings',
            template: 'default',
            center: false,
            resize: true,
            draggable: true
        });
    }

    initEditor = function (val) {
        if (window.editorLaunced) {
            editorLaunced.setContent(val, true);
            return;
        }

        window.editorLaunced = new mw.Editor({
            selector: '#js-editor-template',
            mode: 'div',
            smallEditor: false,
            minHeight: 250,
            maxHeight: '70vh',
            controls: [
                [
                    'undoRedo', '|', 'image', '|',
                    {
                        group: {
                            icon: 'mdi mdi-format-bold',
                            controls: ['bold', 'italic', 'underline', 'strikeThrough']
                        }
                    },
                    '|',
                    {
                        group: {
                            icon: 'mdi mdi-format-align-left',
                            controls: ['align']
                        }
                    },
                    '|', 'format',
                    {
                        group: {
                            icon: 'mdi mdi-format-list-bulleted-square',
                            controls: ['ul', 'ol']
                        }
                    },
                    '|', 'link', 'unlink', 'wordPaste', 'table'
                ],
            ]
        });
        initEditor(val)
    };

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
                    mw.reload_module('newsletter/templates_list');
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
    <div class="form-group">
        <label class="control-label"><?php _e('Template title'); ?></label>
        <input name="title" type="text" value="" class="form-control js-validation js-edit-template-title"/>
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e('Template design'); ?></label>
        <small class="text-muted d-flex justify-content-between align-items-center mb-2"><span>Variables: {first_name} , {Last_name} , {email} , {unsubscribe} {site_url}</span> <button onclick="edit_iframe_template($('.js-edit-template-id').val())" type="button" class="btn btn-outline-primary"><?php _e('Template Generator'); ?></button></small>

        <textarea id="js-editor-template" name="text" class="js-edit-template-text"></textarea>

        <div class="js-template-design"></div>
        <div class="js-field-message"></div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <a class="btn btn-outline-danger btn-sm" href="javascript:;" onclick="delete_template($('.js-edit-template-id').val())">Delete</a>
        <input type="hidden" value="0" class="js-edit-template-id" name="id"/>
        <button type="submit" class="btn btn-success btn-sm"><?php _e('Save'); ?></button>
    </div>
</form>
