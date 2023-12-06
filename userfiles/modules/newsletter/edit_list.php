<?php must_have_access(); ?>

<?php
if (isset($params['id'])) {
    $list = newsletter_get_list($params['id']);
}

$templates = newsletter_get_templates();
$senders = newsletter_get_senders();
?>

<style>
    .js-danger-text {
        padding-top: 5px;
        color: #c21f1f;
    }
    .js-template-select-table {
        border:0px;
        width:100%;
    }
    .js-template-select-table tr {
        height:50px;
    }
    .js-template-select-table td {
        height:50px;
    }
</style>

<script>
    mw.require("<?php print $config['url_to_module']; ?>/js/js-helper.js");

    $(document).ready(function () {

        $(document).on("change", ".js-validation", function () {
            $('.js-edit-list-form :input').each(function () {
                if ($(this).hasClass('js-validation')) {
                    runFieldsValidation(this);
                }
            });
        });

        $(".js-edit-list-form").submit(function (e) {

            e.preventDefault(e);

            var errors = {};
            var data = mw.serializeFields(this);

            $('.js-edit-list-form :input').each(function (k, v) {
                if ($(this).hasClass('js-validation')) {
                    if (runFieldsValidation(this) == false) {
                        errors[k] = true;
                    }
                }
            });

            if (isEmpty(errors)) {

                $.ajax({
                    url: mw.settings.api_url + 'newsletter_save_list',
                    type: 'POST',
                    data: data,
                    success: function (result) {

                        mw.notification.success('<?php _ejs('List saved'); ?>');

                        // Remove modal
                        if (typeof (edit_list_modal) != 'undefined' && edit_list_modal.modal) {
                            edit_list_modal.modal.remove();
                        }

                        // Reload the modules
                        mw.reload_module('newsletter/lists_list')
                        mw.reload_module('newsletter/edit_campaign')
                        mw.reload_module_parent('newsletter');

                    },
                    error: function (e) {
                        alert('Error processing your request: ' + e.responseText);
                    }
                });
            } else {
                mw.notification.error('<?php _ejs('Please fill correct data.'); ?>');
            }
        });

    });

    function runFieldsValidation(instance) {

        var ok = true;
        var inputValue = $(instance).val().trim();

        $(instance).removeAttr("style");
        $(instance).parent().find(".js-field-message").html('');

        if (inputValue == "") {
            $(instance).css("border", "1px solid #b93636");
            $(instance).parent().find('.js-field-message').html(errorText('<?php _e('The field cannot be empty'); ?>'));
            ok = false;
        }

        return ok;
    }
</script>
<script>mw.lib.require('mwui');</script>
<script>mw.lib.require('mwui_init');</script>

<form class="js-edit-list-form">
    <div class="form-group">
        <label class="control-label"><?php _e('List name'); ?></label>
        <small class="text-muted d-block mb-2">Enter the name of the list</small>
        <input name="name" value="<?php if (isset($list['name'])): ?><?php echo $list['name']; ?><?php endif; ?>" type="text" class="form-control js-validation" />
        <div class="js-field-message"></div>
    </div>

    <?php if (empty($templates)): ?>
        <div style="color:#b93636;">First you need to create templates.</div>
    <?php endif; ?>

    <?php if (!empty($templates)): ?>
        <div class="js-template-select-table">
            <div class="form-group">
                <label class="control-label"><?php _e('Success E-mail Template'); ?></label>
                <small class="text-muted d-block mb-2">Select from your e-mail templates or <a href="javascript:;">create a new one</a></small>

                <select name="success_email_template_id"  class="form-control">
                    <?php foreach ($templates as $template) : ?>
                        <option <?php if (isset($list['success_email_template_id']) AND $list['success_email_template_id'] == $template['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $template['id']; ?>"><?php echo $template['title']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="js-field-message"></div>
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e('Success E-mail Sender'); ?></label>
                <small class="text-muted d-block mb-2">Select from your e-mail templates or <a href="javascript:;">create a new one</a></small>

                <?php if (!empty($senders) and is_array($senders)): ?>
                    <select name="success_sender_account_id" class="form-control">
                        <?php foreach ($senders as $sender) : ?>
                            <option <?php if (isset($list['success_sender_account_id']) AND $list['success_sender_account_id'] == $sender['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $sender['id']; ?>"><?php echo $sender['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <div style="color:#b93636;">First you need to add senders.</div>
                <?php endif; ?>
                <div class="js-field-message"></div>
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e('Unsubscription E-mail Template'); ?></label>
                <small class="text-muted d-block mb-2">Select from your e-mail templates or <a href="javascript:;">create a new one</a></small>

                <select name="unsubscription_email_template_id" class="form-control">
                    <?php foreach ($templates as $template) : ?>
                        <option <?php if (isset($list['unsubscription_email_template_id']) AND $list['unsubscription_email_template_id'] == $template['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $template['id']; ?>"><?php echo $template['title']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="js-field-message"></div>
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e('Unsubscription E-mail Sender'); ?></label>
                <small class="text-muted d-block mb-2">Select from your e-mail templates or <a href="javascript:;">create a new one</a></small>

                <?php if (!empty($senders)): ?>
                    <select name="unsubscription_sender_account_id" class="form-control">
                        <?php foreach ($senders as $sender) : ?>
                            <option <?php if (isset($list['unsubscription_sender_account_id']) AND $list['unsubscription_sender_account_id'] == $sender['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $sender['id']; ?>"><?php echo $sender['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <div style="color:#b93636;">First you need to add senders.</div>
                <?php endif; ?>
                <div class="js-field-message"></div>
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e('Confirmation E-mail Template'); ?></label>
                <small class="text-muted d-block mb-2">Select from your e-mail templates or <a href="javascript:;">create a new one</a></small>

                <select name="confirmation_email_template_id" class="form-control">
                    <?php foreach ($templates as $template) : ?>
                        <option <?php if (isset($list['confirmation_email_template_id']) AND $list['confirmation_email_template_id'] == $template['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $template['id']; ?>"><?php echo $template['title']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="js-field-message"></div>
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e('Confirmation E-mail Sender'); ?></label>
                <small class="text-muted d-block mb-2">Select from your e-mail templates or <a href="javascript:;">create a new one</a></small>

                <?php if (!empty($senders)): ?>
                    <select name="confirmation_sender_account_id" class="form-control">
                        <?php foreach ($senders as $sender) : ?>
                            <option <?php if (isset($list['confirmation_sender_account_id']) AND $list['confirmation_sender_account_id'] == $sender['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $sender['id']; ?>"><?php echo $sender['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <div style="color:#b93636;">First you need to add senders.</div>
                <?php endif; ?>
                <div class="js-field-message"></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between">
        <div>
            <?php if (isset($list['id'])): ?>
                <a class="btn btn-outline-danger btn-sm" href="javascript:;" onclick="delete_list('<?php print $list['id']; ?>')">Delete</a>
                <input type="hidden" value="<?php echo $list['id']; ?>" name="id" />
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-success btn-sm"><?php _e('Save'); ?></button>
    </div>
</form>
