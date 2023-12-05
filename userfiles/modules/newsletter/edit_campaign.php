<?php must_have_access(); ?>

<?php
if (isset($params['id'])) {
    $campaign = newsletter_get_campaign($params['id']);
}

$senders_params = array();
$senders_params['no_limit'] = true;
$senders_params['order_by'] = "created_at desc";
$senders = newsletter_get_senders($senders_params);

$templates_params = array();
$templates_params['no_limit'] = true;
$templates_params['order_by'] = "created_at desc";
$templates = newsletter_get_templates($templates_params);

?>

<style>
    .js-danger-text {
        padding-top: 5px;
        color: #c21f1f;
    }
</style>

<script>
    mw.require("<?php print $config['url_to_module']; ?>/js/js-helper.js");

    $(document).ready(function () {

        $(document).on("change", ".js-validation", function () {
            $('.js-edit-campaign-form :input').each(function () {
                if ($(this).hasClass('js-validation')) {
                    runFieldsValidation(this);
                }
            });
        });

        $(".js-edit-campaign-form").submit(function (e) {

            e.preventDefault(e);

            var errors = {};
            var data = mw.serializeFields(this);

            $('.js-edit-campaign-form :input').each(function (k, v) {
                if ($(this).hasClass('js-validation')) {
                    if (runFieldsValidation(this) == false) {
                        errors[k] = true;
                    }
                }
            });

            if (isEmpty(errors)) {

                $.ajax({
                    url: mw.settings.api_url + 'newsletter_save_campaign',
                    type: 'POST',
                    data: data,
                    success: function (result) {

                        mw.notification.success('<?php _ejs('Campaign saved'); ?>');

                        // Remove modal
                        if (typeof (edit_campaign_modal) != 'undefined' && edit_campaign_modal.modal) {
                            edit_campaign_modal.modal.remove();
                        }

                        // Reload the modules
                        mw.reload_module('newsletter/campaigns_list')
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

        if ($(instance).hasClass('js-validation-email')) {
            if (validateEmail(inputValue) == false) {
                $(instance).css("border", "1px solid #b93636");
                $(instance).parent().find('.js-field-message').html(errorText('<?php _e('The email address is not valid.'); ?>'));
                ok = false;
            }
        }

        return ok;
    }
</script>
<script>mw.lib.require('mwui_init');</script>
<form class="js-edit-campaign-form">

    <?php
    $lists = newsletter_get_lists();
    ?>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="control-label"><?php _e('Campaign Name'); ?></label>
                <small class="text-muted d-block mb-2">Give a name of your campaign</small>
                <input name="from_name" value="<?php if (isset($campaign['from_name'])): ?><?php echo $campaign['from_name']; ?><?php endif; ?>" type="text" class="form-control js-validation" />
                <div class="js-field-message"></div>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="control-label"><?php _e('Subject'); ?></label>
                <small class="text-muted d-block mb-2">What the campaign is about</small>
                <input name="subject" value="<?php if (isset($campaign['subject'])): ?><?php echo $campaign['subject']; ?><?php endif; ?>" type="text" class="form-control js-validation" />
                <div class="js-field-message"></div>
            </div>
        </div>
    </div>


    <!--
    <div class="form-group">
            <label class="control-label"><?php // _e('Campaign Email');              ?></label>
            <input name="from_email" value="<?php // echo $campaign['from_email'];              ?>" type="text" class="form-control js-validation js-validation-email" />
            <div class="js-field-message"></div>
    </div>
    !-->

    <div class="row">
        <div class="col-6">
         <div class="form-group">
        <label class="control-label"><?php _e('Campaign Email Sender'); ?></label>
        <small class="text-muted d-block mb-2">Select mail sender</small>
        <?php if (!empty($senders)): ?>
            <select name="sender_account_id" class="form-control">
                <?php foreach ($senders as $sender) : ?>
                    <option

                        <?php if ($sender['id'] == $campaign['sender_account_id']): ?> selected="selected" <?php endif; ?>

                        value="<?php echo $sender['id']; ?>"><?php echo $sender['name']; ?></option>
                <?php endforeach; ?>
            </select>
        <?php else: ?>
            <div style="color:#b93636;">First you need to add senders.</div>
        <?php endif; ?>
        <div class="js-field-message"></div>
    </div>
        </div>
        <div class="col-6">
         <div class="form-group">
        <label class="control-label"><?php _e('Campaign E-mail Template'); ?></label>
        <small class="text-muted d-block mb-2">Select from your campaign e-mail templates or <a href="javascript:;">create a new one</a></small>

        <select name="email_template_id"  class="form-control">
            <?php foreach ($templates as $template) : ?>
                <option <?php if (isset($list['success_email_template_id']) AND $list['success_email_template_id'] == $template['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $template['id']; ?>"><?php echo $template['title']; ?></option>
            <?php endforeach; ?>
        </select>
        <div class="js-field-message"></div>
    </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
        <label class="control-label"><?php _e('List'); ?></label>
        <small class="text-muted d-block mb-2">Choose from your lists or <a href="javascript:;" onclick="edit_list();">create a new one</a></small>
        <?php if (!empty($lists)): ?>

            <select name="list_id" class="form-control">
                <?php foreach ($lists as $list) : ?>
                    <option
                        <?php if ($list['id'] == $campaign['list_id']): ?> selected="selected" <?php endif; ?>
                        value="<?php echo $list['id']; ?>"><?php echo $list['name']; ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <div class="js-field-message"></div>

        <?php if (empty($lists)): ?>
            <div style="color:#b93636;">First you need to create lists.</div>
        <?php endif; ?>
    </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="control-label"><?php _e('Sending Limit'); ?></label>
                <small class="text-muted d-block mb-2">
                    <?php _e('Set the maximum number of emails to be sent per day'); ?>
                </small>
                <select name="sending_limit_per_day" class="form-control">
                    <option>100</option>
                    <option>300</option>
                    <option>500</option>
                    <option>1000</option>
                </select>
                <div class="js-field-message"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="control-label"><?php _e('Scheduled At'); ?></label>
                <small class="text-muted d-block mb-2">
                    <?php _e('Set the date and time when the campaign will be sent'); ?>
                </small>
                <?php
                $scheduled_at = date('Y-m-d\TH:i', strtotime('+1 hour'));
                ?>
                <input type="datetime-local" value="<?php echo $scheduled_at;?>" name="scheduled_at" class="form-control" />
                <div class="js-field-message"></div>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="control-label"><?php _e('Start Campaign'); ?></label>
                <small class="text-muted d-block mb-2">
                    <?php _e('Enable or disable this campaign'); ?>
                </small>
                <select name="is_scheduled" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
                <div class="js-field-message"></div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label">
            <?php _e('Campaign finished'); ?>: <?php if (isset($campaign['is_done']) and $campaign['is_done']): ?> Yes <?php else: ?> No <?php endif; ?>
        </label>
    </div>

    <div class="d-flex justify-content-between">
        <div>
            <?php if (isset($campaign['id'])): ?>
                <a class="btn btn-outline-danger btn-sm" href="javascript:;" onclick="delete_campaign('<?php print $campaign['id']; ?>')">Delete</a>
                <input type="hidden" value="<?php echo $campaign['id']; ?>" name="id" />
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-success btn-sm"><?php _e('Save'); ?></button>
    </div>

</form>
