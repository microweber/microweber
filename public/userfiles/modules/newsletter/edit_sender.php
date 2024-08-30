<?php must_have_access(); ?>

<?php
$sender = [];
$sender['account_type'] = '';

if (isset($params['id'])) {
    $getSender = newsletter_get_sender($params);
    if (!empty($getSender)) {
        $sender = $getSender;
    }
}
?>
<script>mw.lib.require('mwui');</script>
<script>mw.lib.require('mwui_init');</script>
<style>
    .js-danger-text {
        padding-top: 5px;
        color: #c21f1f;
    }
    .js-sender-wrapper {
        background: #f6f6f6;
        padding: 10px;
        border-radius:5px;
    }
</style>

<script>
    mw.require("<?php print $config['url_to_module']; ?>/js/js-helper.js");

    $(document).ready(function () {

        $(".js-edit-sender-form").submit(function (e) {

            e.preventDefault(e);

            var data = mw.serializeFields(this);

            $.ajax({
                url: '<?php echo route('admin.newsletter.sender-accounts.save'); ?>',
                type: 'POST',
                data: data,
                success: function (result) {

                    if (result.data.id) {
                        mw.notification.success('<?php _ejs('Sender saved'); ?>');

                        // Remove modal
                        if (typeof (edit_campaign_modal) != 'undefined' && edit_campaign_modal.modal) {
                            edit_campaign_modal.modal.remove();
                        }

                        // Reload the modules
                        mw.reload_module('newsletter/sender_accounts_list')
                        mw.reload_module_parent('newsletter');
                    }

                }
            });

        });

    });

</script>

<form class="js-edit-sender-form">

    <div class="form-group">
        <label class="settings-title-inside mb-1"><?php _e('Name'); ?></label>
        <small class="text-muted d-block mb-2">Enter the full name of the sender</small>
        <input name="name" value="<?php if (isset($sender['name'])): ?><?php echo $sender['name']; ?><?php endif; ?>" type="text" class="form-control js-validation" />
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="settings-title-inside mb-1"><?php _e('From Name'); ?></label>
        <small class="text-muted d-block mb-2">This name will be visible as Sender name in the received e-mail</small>
        <input name="from_name" value="<?php if (isset($sender['from_name'])): ?><?php echo $sender['from_name']; ?><?php endif; ?>" type="text" class="form-control js-validation" />
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="settings-title-inside mb-1"><?php _e('From Email'); ?></label>
        <small class="text-muted d-block mb-2">This e-mail will be visible as Sender e-mail address in the received e-mail</small>
        <input name="from_email" value="<?php if (isset($sender['from_email'])): ?><?php echo $sender['from_email']; ?><?php endif; ?>" type="text" class="form-control js-validation js-validation-email" />
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="settings-title-inside mb-1"><?php _e('Reply To Email'); ?></label>
        <small class="text-muted d-block mb-2">This e-mail will used for reply in the received e-mail</small>
        <input name="reply_email" value="<?php if (isset($sender['reply_email'])): ?><?php echo $sender['reply_email']; ?><?php endif; ?>" type="text" class="form-control js-validation js-validation-email" />
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="settings-title-inside mb-1"><?php _e('Send email function'); ?></label>
        <small class="text-muted d-block mb-2">Choose a method to send the emails</small>
        <select class="form-control js-select-account-type" name="account_type">
            <option value="php_mail">PHP Mail</option>
            <option value="smtp">SMTP Server</option>
            <option value="mailchimp">Mailchimp</option>
            <option value="mailgun">Mailgun</option>
            <option value="mandrill">Mandrill</option>
            <option value="amazon_ses">Amazon SES</option>
            <option value="sparkpost">Sparkpost</option>
        </select>
    </div>


<br />
    <script>
        $(document).ready(function () {

            $(".js-select-account-type").val('<?php echo $sender['account_type']; ?>');
            $(".js-select-account-type").trigger("change");

            $(".js-sender-php-mail").show();

            $(document).on("change", ".js-select-account-type", function () {

                $(".js-sender-wrapper").hide();

                switch ($(this).val()) {
                    case "mailchimp":
                        $(".js-sender-mailchimp").show();
                        break;
                    case "mailgun":
                        $(".js-sender-mailgun").show();
                        break;
                    case "mandrill":
                        $(".js-sender-mandrill").show();
                        break;
                    case "amazon_ses":
                        $(".js-sender-amazon-ses").show();
                        break;
                    case "sparkpost":
                        $(".js-sender-sparkpost").show();
                        break;
                    case "php_mail":
                        $(".js-sender-php-mail").show();
                        break;
                    case "smtp":
                        $(".js-sender-smtp").show();
                        break;
                    default:
                }

            });

        });
    </script>

    <div class="js-sender-wrapper js-sender-mailchimp" style="display:none;">
        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Secret'); ?> </label>
            <input name="mailchimp_secret" class="mw_option_field mw-options-form-binded form-control" type="text" value="<?php if (isset($sender['mailchimp_secret'])): ?><?php echo $sender['mailchimp_secret']; ?><?php endif; ?>">
        </div>
    </div>

    <div class="js-sender-wrapper js-sender-mandrill" style="display:none;">
        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Secret'); ?> </label>
            <input name="mandrill_secret" class="mw_option_field mw-options-form-binded form-control" type="text" value="<?php if (isset($sender['mandrill_secret'])): ?><?php echo $sender['mandrill_secret']; ?><?php endif; ?>">
        </div>
    </div>

    <div class="js-sender-wrapper js-sender-mailgun" style="display:none;">
        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Domain'); ?> </label>
            <input name="mailgun_domain" class="mw_option_field mw-options-form-binded form-control" type="text" value="<?php if (isset($sender['mailgun_domain'])): ?><?php echo $sender['mailgun_domain']; ?><?php endif; ?>">
        </div>
        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Secret'); ?> </label>
            <input name="mailgun_secret" class="mw_option_field mw-options-form-binded form-control" type="text" value="<?php if (isset($sender['mailgun_secret'])): ?><?php echo $sender['mailgun_secret']; ?><?php endif; ?>">
        </div>
    </div>

    <div class="js-sender-wrapper js-sender-amazon-ses" style="display:none;">
        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Key'); ?> </label>
            <input name="amazon_ses_key" class="mw_option_field mw-options-form-binded form-control" type="text" value="<?php if (isset($sender['amazon_ses_key'])): ?><?php echo $sender['amazon_ses_key']; ?><?php endif; ?>">
        </div>

        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Secret'); ?> </label>
            <input name="amazon_ses_secret" class="mw_option_field mw-options-form-binded form-control" type="text" value="<?php if (isset($sender['amazon_ses_secret'])): ?><?php echo $sender['amazon_ses_secret']; ?><?php endif; ?>">
        </div>

        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Region'); ?> </label>
            <input name="amazon_ses_region" class="mw_option_field mw-options-form-binded form-control" type="text" value="<?php if (isset($sender['amazon_ses_region'])): ?><?php echo $sender['amazon_ses_region']; ?><?php endif; ?>">
        </div>
    </div>

    <div class="js-sender-wrapper js-sender-sparkpost" style="display:none;">
        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Secret'); ?> </label>
            <input name="sparkpost_secret" class="mw_option_field mw-options-form-binded form-control" type="text" value="<?php if (isset($sender['sparkpost_secret'])): ?><?php echo $sender['sparkpost_secret']; ?><?php endif; ?>">
        </div>
    </div>


    <div class="js-sender-wrapper js-sender-smtp" style="display:none;">
        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Smtp Username'); ?> </label>
            <input name="smtp_username" class="mw_option_field mw-options-form-binded form-control" type="text" value="<?php if (isset($sender['smtp_username'])): ?><?php echo $sender['smtp_username']; ?><?php endif; ?>">
        </div>
        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Smtp Password'); ?> </label>
            <input name="smtp_password" class="mw_option_field mw-options-form-binded form-control" type="text" value="<?php if (isset($sender['smtp_password'])): ?><?php echo $sender['smtp_password']; ?><?php endif; ?>">
        </div>
        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Smtp Email Host'); ?> </label>
            <input name="smtp_host" class="mw_option_field mw-options-form-binded form-control" type="text" value="<?php if (isset($sender['smtp_host'])): ?><?php echo $sender['smtp_host']; ?><?php endif; ?>">
        </div>
        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Smtp Email Port'); ?> </label>
            <input name="smtp_port" class="mw_option_field mw-options-form-binded form-control" type="text" value="<?php if (isset($sender['smtp_port'])): ?><?php echo $sender['smtp_port']; ?><?php endif; ?>">
        </div>
    </div>

    <script>
        $(document).ready(function () {

            $(document).on("click", ".js-sender-test-method", function () {
                $(".js-sender-test-email-wrapper").toggle();
            });

            $(document).on("click", ".js-sender-send-test-email", function () {
                $(".js-email-send-test-output").html("Sending...");
                $.ajax({
                    url: mw.settings.api_url + 'newsletter_test_sender',
                    type: 'POST',
                    data: $('.js-edit-sender-form').serialize(),
                    success: function (result) {
                        $('.js-email-send-test-output').html(result);
                    },
                    error: function (e) {
                        $('.js-email-send-test-output').html('Error processing your request: ' + e.responseText);
                    }
                });
            });
        });
    </script>

    <div class="js-sender-test-email-wrapper" style="display:none;background: whitesmoke  none repeat scroll 0% 0%;padding:15px;">
        <div class="form-group">
            <label class="settings-title-inside mb-1"><?php _e('Send test email to'); ?> </label>
            <input name="to_email" class="mw_option_field mw-options-form-binded form-control js-sender-test-email-to" type="text" option-group="email">
        </div>

        <button class="btn btn-outline-success js-sender-send-test-email"><?php _e('Send test email'); ?> </button>

        <hr clas="thin"/>

        <pre class="js-email-send-test-output"></pre>
    </div>
    <br />

    <button type="button" class="btn btn-outline-dark  js-sender-test-method"><?php _e('Test Method'); ?></button>

    <div class="d-flex justify-content-between">
        <div>
            <?php if (isset($sender['id'])): ?>
                <a class="btn btn-outline-danger" href="javascript:;" onclick="delete_sender('<?php print $sender['id']; ?>')"><?php _e('Delete'); ?></a>
                <input type="hidden" value="<?php echo $sender['id']; ?>" name="id" />
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-dark"><?php _e('Save'); ?></button>
    </div>
</form>
