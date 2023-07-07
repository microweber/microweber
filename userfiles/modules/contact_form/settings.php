<?php
if (!user_can_access('module.contact_form.index')) {
    return;
}
?>

<?php
include(__DIR__ . '/settings_javascript.php');
?>

<?php
$mod_id = $params['id'];
if (isset($params['for_module_id'])) {
    $mod_id = $params['for_module_id'];
}
?>

<div id="form_email_options">
    <div class="row px-0 d-flex align-items-center w-100">

        <?php  if ($mod_id == 'contact_form_default'): ?>
            <div class="col">
                <label class="form-label"><?php _e("Global contact form settings") ?></label>
            </div>
        <?php else: ?>

            <div class="col">
                <h5 class="font-weight-bold settings-title-inside"><?php _e("Current contact form settings") ?></h5>
            </div>
        <?php endif; ?>

        <div class="col text-right">
            <a class="btn btn-outline-primary btn-sm" href="<?php print admin_url('settings?group=email') ?>" target="_blank"> <?php _e("E-mail sending options"); ?></a>
        </div>
    </div>

    <div class="mt-2">

        <h5 class="font-weight-bold settings-title-inside"><?php _e("Global sender") ?></h5>

        <div class="form-group mb-3">
            <label class="form-label"><?php _e("Use custom sender settings"); ?></label>
            <small class="text-muted d-block mb-2">
                <?php _e('Use custom sender settings for the global contact forms.'); ?>
                <br />
                <?php _e('By default we will use website system e-mail settings.'); ?>
                <a href="<?php print admin_url('settings?group=email'); ?>" target="_blank"><?php _e('You can change the system e-mail settings here.'); ?></a>
            </small>
        </div>

        <div class="form-group mb-4">
            <?php  $emailCustomSender = \MicroweberPackages\Option\Facades\Option::getValue('email_custom_sender', $mod_id); ?>
            <div class="form-check form-switch pl-0">
                <input type="checkbox" onchange="window.toggleCustomSender(event)" data-value-checked="y" data-value-unchecked="n"   class="mw_option_field form-check-input" name="email_custom_sender" option-group="<?php print $mod_id ?>" id="email_custom_sender" value="y" <?php if ($emailCustomSender): ?>checked<?php endif; ?>>
            </div>
        </div>

        <script type="text/javascript">
            window.toggleCustomSender = function (e) {
                var el = e.target;
                if ($(el).is(":checked")) {
                    $('.js-custom-sender').fadeIn();
                } else {
                    $('.js-custom-sender').fadeOut();
                }

            };
        </script>

        <div class="js-custom-sender d-flex flex-wrap" <?php if (!$emailCustomSender): ?> style="display:none"<?php endif; ?>>
            <div class="form-group col-lg-6 col-12 pe-lg-2">
                <label class="form-label"><?php _e("From e-mail address"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e("The e-mail address which will send the message"); ?></small>
                <input name="email_from" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_from', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
            </div>

            <div class="form-group col-lg-6 col-12 ps-lg-2">
                <label class="form-label"><?php _e("From name"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e("e.x. your name, company or brand name"); ?></small>
                <input name="email_from_name" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_from_name', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
            </div>
        </div>

        <h5 class="font-weight-bold settings-title-inside"><?php _e("Global Receivers") ?></h5>
        <?php _e("Send contact forms data to global receivers when is submited"); ?>

        <div class="form-group mt-3 col-lg-6 col-12 pe-lg-2">
            <label class="form-label"><?php _e("To e-mail addresses"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("E-mail address of the receivers separated with coma."); ?></small>
            <input name="email_to" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_to', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
        </div>

    </div>

</div>
