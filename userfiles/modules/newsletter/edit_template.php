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

<script>
    mw.require('editor.js');
    function edit_template(id = false) {
        var data = {};
        data.id = id;

        mw.notification.success('<?php _ejs('Loading...'); ?>');

        if (data.id > 0) {
            $.ajax({
                url: mw.settings.api_url + 'newsletter_get_template',
                type: 'POST',
                data: data,
                success: function (result) {

                    $('.js-edit-template-id').val(result.id);
                    $('.js-edit-template-title').val(result.title);
                    $('.js-edit-template-text').val(result.text);

                    initEditor(result.text);
                }
            });
        } else {
            $('.js-edit-template-id').val('0');
            $('.js-edit-template-title').val('');
            $('.js-edit-template-text').val('');

            initEditor('');
        }

        $('.js-templates-list-wrapper').slideUp();
        $('.js-edit-template-wrapper').slideDown();
    }
    edit_template(<?php echo $params['template-id']; ?>);
</script>



<script>

    MWEditor.controllers.mailTemplateFormDropdownVariables = function (scope, api, rootScope, data) {
        this.checkSelection = function (opt) {
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: [
                    { label: 'name', value:'{{name}}' },
                    { label: 'first_name', value:'{{first_name}}' },
                    { label: 'last_name', value:'{{last_name}}' },
                    { label: 'email', value:'{{email}}' },
                    { label: 'siteUrl', value:'{{site_url}}' },
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

                <small class="text-muted d-flex justify-content-between align-items-center mt-2 mb-2">
                    <span>Variables:  {{name}}, {{first_name}} , {{last_name}} , {{email}, {{unsubscribe}}, {{site_url}}</span>
                </small>

                <textarea id="js-editor-template" name="text" class="js-edit-template-text"></textarea>

                <div class="js-template-design"></div>
                <div class="js-field-message"></div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a class="btn btn-outline-danger btn-sm" href="javascript:;" onclick="delete_template($('.js-edit-template-id').val())">Delete</a>
                <input type="hidden" value="0" class="js-edit-template-id" name="id"/>

                <div>
                    <a
                        class="btn btn-outline-primary btn-sm"
                        target="_blank"
                        href="<?php echo route('admin.newsletter.templates.preview', $params['template-id']); ?>">
                        Preview template
                    </a>
                    <button type="submit" class="btn btn-success btn-sm"><?php _e('Save'); ?></button>
                </div>

            </div>
        </form>
    </div>
</div>
