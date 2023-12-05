<style>
    .js-danger-text {
        padding-top: 5px;
        color: #c21f1f;
    }
</style>

<div class="mb-4 mt-4">
    <a href="javascript:;" class="mw-admin-action-links mw-adm-liveedit-tabs mw-liveedit-button-animation-component" onclick="list_templates();">
        <?php _e('Back to List of templates'); ?>
    </a>
</div>

<script>mw.require('editor.js')</script>

<script>

    MWEditor.controllers.mailTemplateFormDropdownVariables = function (scope, api, rootScope, data) {
        this.checkSelection = function (opt) {
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: [
                    { label: 'firstName', value:'{first_name}' },
                    { label: 'lastName', value:'{last_name}' },
                    { label: 'email', value:'{email}' },
                    { label: 'unsubscribe', value:'{unsubscribe}' },
                    { label: 'siteUrl', value:'{site_url}' },
                ],
                placeholder: rootScope.lang('<?php _ejs("E-mail Values"); ?>')
            });
            dropdown.select.on('change', function (e, val) {
                if(val) {
                    api.insertHTML(val.value);
                }
            });
            return dropdown.root;
        };
        this.element = this.render();
    };

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
                    '|', 'link', 'unlink', 'wordPaste', 'table', 'mailTemplateFormDropdownVariables'
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

<div class="card mt-2">
    <div class="card-body">


<form class="js-edit-template-form">
    <div class="form-group">
        <label class="control-label"><?php _e('Template title'); ?></label>
        <input name="title" type="text" value="" class="form-control js-validation js-edit-template-title"/>
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e('Template design'); ?></label>

        <small class="text-muted d-flex justify-content-between align-items-center mb-2">
            <span>Variables: {first_name} , {Last_name} , {email} , {unsubscribe} {site_url}</span>
        </small>

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
    </div>
</div>
