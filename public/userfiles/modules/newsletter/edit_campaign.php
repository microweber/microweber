<?php must_have_access(); ?>

<?php
$campaign = [];
$campaign['from_name'] = '';
$campaign['from_email'] = '';
$campaign['subject'] = '';
$campaign['sender_account_id'] = '';
$campaign['list_id'] = '';
$campaign['template_id'] = '';
$campaign['sending_limit_per_day'] = 100;
$campaign['scheduled_at'] = '';
$campaign['is_scheduled'] = '';
$campaign['id'] = 0;

if (isset($params['id'])) {
    $getCampaign = newsletter_get_campaign($params['id']);
    if ($getCampaign) {
        $campaign = $getCampaign;
    }
}


$senders = newsletter_get_senders();


$templates = newsletter_get_templates();

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

            console.log(errors);

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

    <div>
        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Campaign Name (Internal Name)'); ?></label>
            <small class="text-muted d-block mb-2">Give a name of your campaign</small>
            <input name="name" value="<?php if (isset($campaign['name'])): ?><?php echo $campaign['name']; ?><?php endif; ?>" type="text" class="form-control js-validation" />
            <div class="js-field-message"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="settings-title-inside mb-1"><?php _e('From Name'); ?></label>
                <small class="text-muted d-block mb-2">
                    <?php _e('The name of the email sender'); ?>
                </small>
                <input name="from_name" value="<?php if (isset($campaign['from_name'])): ?><?php echo $campaign['from_name']; ?><?php endif; ?>" type="text" class="form-control js-validation" />
                <div class="js-field-message"></div>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="settings-title-inside mb-1"><?php _e('Email Subject'); ?></label>
                <small class="text-muted d-block mb-2">What the campaign is about</small>
                <input name="subject" value="<?php if (isset($campaign['subject'])): ?><?php echo $campaign['subject']; ?><?php endif; ?>" type="text" class="form-control js-validation" />
                <div class="js-field-message"></div>
            </div>
        </div>
    </div>


    <!--
    <div class="form-group">
            <label class="settings-title-inside mb-1"><?php // _e('Campaign Email');              ?></label>
            <input name="from_email" value="<?php // echo $campaign['from_email'];              ?>" type="text" class="form-control js-validation js-validation-email" />
            <div class="js-field-message"></div>
    </div>
    !-->

    <div class="row">
        <div class="col-6">
         <div class="form-group">
        <label class="settings-title-inside mb-1"><?php _e('Campaign Email Sender'); ?></label>
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
        <label class="settings-title-inside mb-1"><?php _e('Campaign E-mail Template'); ?></label>
        <small class="text-muted d-block mb-2">Select from your campaign e-mail templates or <a href="javascript:;">create a new one</a></small>

        <select name="email_template_id"  class="form-control">
            <?php if (!empty($templates)): ?>
                <?php foreach ($templates as $template) : ?>
                    <option <?php if (isset($list['success_email_template_id']) AND $list['success_email_template_id'] == $template['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $template['id']; ?>"><?php echo $template['title']; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <div class="js-field-message"></div>
    </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
        <label class="settings-title-inside mb-1"><?php _e('E-mail List'); ?></label>
        <small class="text-muted d-block mb-2">Choose from your E-mail Lists or <a href="javascript:;" onclick="edit_list();">create a new one</a></small>

            <select name="list_id" class="form-control">

                <option
                    <?php if (0 == $campaign['list_id']): ?> selected="selected" <?php endif; ?>
                    value="0">Default</option>

                <?php if (!empty($lists)): ?>
                    <?php foreach ($lists as $list) : ?>
                        <option
                            <?php if ($list['id'] == $campaign['list_id']): ?> selected="selected" <?php endif; ?>
                            value="<?php echo $list['id']; ?>"><?php echo $list['name']; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

        <div class="js-field-message"></div>

    </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="settings-title-inside mb-1"><?php _e('Sending Limit'); ?></label>
                <small class="text-muted d-block mb-2">
                    <?php _e('Set the maximum number of emails to be sent per day'); ?>
                </small>
                <select name="sending_limit_per_day" class="form-control">
                    <option <?php if ($campaign['sending_limit_per_day'] == 100): ?> selected="selected" <?php endif; ?> >100</option>
                    <option <?php if ($campaign['sending_limit_per_day'] == 300): ?> selected="selected" <?php endif; ?> >300</option>
                    <option <?php if ($campaign['sending_limit_per_day'] == 500): ?> selected="selected" <?php endif; ?> >500</option>
                    <option <?php if ($campaign['sending_limit_per_day'] == 1000): ?> selected="selected" <?php endif; ?> >1000</option>
                </select>
                <div class="js-field-message"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="settings-title-inside mb-1"><?php _e('Scheduled At'); ?></label>
                <small class="text-muted d-block mb-2">
                    <?php _e('Set the date and time when the campaign will be sent'); ?>
                </small>
                <?php
                $scheduled_at = date('Y-m-d\TH:i', strtotime('+1 hour'));
                if (!empty($campaign['scheduled_at'])) {
                    $scheduled_at = $campaign['scheduled_at'];
                }
                ?>
                <input type="datetime-local" value="<?php echo $scheduled_at;?>" name="scheduled_at" class="form-control" />
                <div class="js-field-message"></div>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label class="settings-title-inside mb-1"><?php _e('Start Campaign'); ?></label>
                <small class="text-muted d-block mb-2">
                    <?php _e('Start campaign Yes/No'); ?>
                </small>
                <select name="is_scheduled" class="form-control">
                    <option value="0" <?php if ($campaign['is_scheduled'] == 0): ?> selected="selected" <?php endif; ?> >No</option>
                    <option value="1" <?php if ($campaign['is_scheduled'] == 1): ?> selected="selected" <?php endif; ?> >Yes</option>
                </select>
                <div class="js-field-message"></div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="settings-title-inside mb-1">
            <?php _e('Campaign finished'); ?>: <?php if (isset($campaign['is_done']) and $campaign['is_done']): ?> Yes <?php else: ?> No <?php endif; ?>
        </label>
    </div>

    <div class="d-flex justify-content-between">
        <div>
            <?php if (isset($campaign['id'])): ?>
                <a class="btn btn-outline-danger" href="javascript:;" onclick="delete_campaign('<?php print $campaign['id']; ?>')">Delete</a>
                <input type="hidden" value="<?php echo $campaign['id']; ?>" name="id" />
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-dark"><?php _e('Save'); ?></button>
    </div>

</form>
