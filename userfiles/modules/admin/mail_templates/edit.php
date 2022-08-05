<?php must_have_access(); ?>

<?php
$template_id = (isset($params['data_template_id']) ? $params['data_template_id'] : '');
if (!empty($template_id)) {
    $template = get_mail_template_by_id($template_id);
} else {
    $template = array();
    $template['name'] = '';
    $template['type'] = 'new_comment';
    $template['from_name'] = get_email_from_name();
    $template['from_email'] = get_email_from();
    $template['copy_to'] = '';
    $template['subject'] = '';
    $template['message'] = '';
    $template['id'] = '';
    $template['is_active'] = 1;

    if (isset($params['mail_template_type'])) {
        $template['type'] = $params['mail_template_type'];
    }

}
?>

<script>
    $("#edit-mail-template-form").submit(function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        var url = "<?php print api_url('save_mail_template'); ?>";
        var post = $.post(url, data);
        post.done(function (data) {
            mw.reload_module("admin/mail_templates");
            mw.reload_module("admin/mail_templates/list");

            // Reload popup modal
            mw.load_module('admin/mail_templates/admin', '#mw_admin_mail_templates_manage', null, null);
            mw.reload_module('admin/mail_templates/select_template');

        });
    });

    function cancelTemplateEdit() {
        mw.reload_module('admin/mail_templates');

        // Reload popup modal
        mw.load_module('admin/mail_templates/admin', '#mw_admin_mail_templates_manage', null, null);
        mw.reload_module('admin/mail_templates/select_template');
    }
</script>

<?php // _e("These values will be replaced with the actual content"); ?>
<script>mw.require('editor.js')</script>
<script>

    MWEditor.controllers.mailTemplateFormDropdownVariables = function (scope, api, rootScope, data) {
        this.checkSelection = function (opt) {
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: <?php echo json_encode(get_mail_template_fields($template['type'])); ?>,
                placeholder: rootScope.lang('<?php _e("E-mail Values"); ?>')
            });
            dropdown.select.on('change', function (e, val) {
                api.insertHTML(val.value);
            });
            return dropdown.root;
        };
        this.element = this.render();
    };

    $(document).ready(function () {
        mweditor = mw.Editor({
            selector: '#editorAM',
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

        $(mweditor).bind('change', function () {
            <?php if ($template['id'] == ''): ?>
            <?php endif; ?>
        });
    });
</script>

<form id="edit-mail-template-form">
    <div class="card bg-light style-1 mb-3">
        <div class="card-header">
            <h5><i class="mdi mdi-login text-primary mr-3"></i> <strong><?php _e("Mail Template"); ?></strong></h5>
            <div>

            </div>
        </div>

        <div class="card-body pt-3">
            <h5 class="mb-3"><?php _e("Edit mail template"); ?></h5>
            <div class="row">
                <div class="col-12">
                    <div class="form-group mb-4">
                        <label class="control-label"><?php _e("Template Name"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("Name the email template so you can recognize it more easily"); ?></small>
                        <input type="text" name="name" value="<?php echo $template['name']; ?>" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label class="control-label"><?php _e("Is this mail template Active?"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("Тurn off or turn on auto-reply for this email template"); ?></small>
                    </div>

                    <div class="form-group mb-4">
                        <div class="custom-control custom-switch pl-0">
                            <label class="d-inline-block mr-5" for="is_active">No</label>
                            <input name="is_active" id="is_active" class="custom-control-input" value="1" type="checkbox" <?php if ($template['is_active']): ?> checked="checked" <?php endif; ?>>
                            <label class="custom-control-label" for="is_active">Yes</label>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="control-label"><?php _e("Template type"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("Name the email template so you can recognize it more easily"); ?></small>
                        <div>
                            <select name="type" class="js-template-type selectpicker" data-width="100%">
                                <?php foreach (get_mail_template_types() as $type): ?>
                                    <option value="<?php echo $type; ?>" <?php if ($type == $template['type']): ?>selected="selected"<?php endif; ?>><?php echo $type; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="thin"/>

            <h5 class="mb-3"><?php _e("Sendind the email"); ?></h5>
            <div class="row">
                <div class="col-12">
                    <div class="form-group mb-4">
                        <label class="control-label"><?php _e("From Name"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("On what behalf will the e-mail be sent"); ?>?</small>
                        <input type="text" name="from_name" value="<?php echo $template['from_name']; ?>" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="control-label"><?php _e("From E-mail"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("From which email it will be sent"); ?>?</small>
                        <input type="text" name="from_email" value="<?php echo $template['from_email']; ?>" class="form-control" placeholder="Ex. your@mail.com">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="control-label"><?php _e("Copy To"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("To which email do you want to send a copy"); ?>?</small>
                        <input type="text" name="copy_to" class="form-control" value="<?php echo $template['copy_to']; ?>" placeholder="Ex. your@mail.com">

                    </div>
                </div>
            </div>
            <hr class="thin"/>


            <?php event_trigger('admin_mail_templates_message'); ?>

            <h5 class="mb-3"><?php _e("Message"); ?></h5>
            <div class="row">
                <div class="col-12">
                    <div class="form-group mb-4">
                        <label class="control-label"><?php _e("Subject"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("Subject of your email"); ?></small>
                        <input type="text" name="subject" value="<?php echo $template['subject']; ?>" class="form-control">
                    </div>

                    <div>
                        <?php
                        $template_id_attachment = '';
                        if (is_int($template_id)) {
                            $template_id_attachment = $template_id;
                        }
                        ?>
                        <module type="admin/components/file_append" option_group="mail_template_id_<?php echo $template_id_attachment; ?>"/>
                    </div>

                    <div class="form-group mb-4">
                        <textarea id="editorAM" name="message" class="form-control"><?php echo $template['message']; ?></textarea>
                    </div>
                </div>
            </div>

            <hr class="thin"/>

            <div class="row">
                <div class="col-md-6">
                    <button type="button" onClick="cancelTemplateEdit();" class="btn btn-danger btn-sm"><?php _e("Cancel"); ?></button>
                </div>

                <div class="col-md-6 text-end text-right">
                    <input type="hidden" name="id" value="<?php echo $template['id']; ?>">
                    <button type="submit" name="submit" class="btn btn-success btn-sm"><?php _e("Save changes"); ?></button>
                </div>
            </div>

        </div>
    </div>
</form>
