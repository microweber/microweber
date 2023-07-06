<?php
if (!user_can_access('module.contact_form.index')) {
    return;
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
        $("input[name='require_terms']").on('click', function () {
            if ($("input[name='require_terms']").is(':checked')) {
                $("#agree_when").show();
            } else {
                $("#agree_when").hide();
            }
        });
    });
</script>

<?php
$mod_id = 'contact_form_default';
if (isset($params['for_module_id'])) {
    $mod_id = $params['for_module_id'];
}
?>

<?php
$simple = false;
if (isset($params['simple']) AND $params['simple'] == 'true') {
    $simple = true;
}
?>

<div class="<?php if (!$simple): ?>card  mb-3<?php endif; ?> card-settings">
    <div class="card-body <?php if (!$simple): ?> <?php endif; ?>">

        <?php if (!$simple): ?>

        <?php endif; ?>

        <div class="row w-100">
            <div class="col-xl-3 mb-xl-0 mb-3">
                <h5 class="font-weight-bold settings-title-inside"><?php _e("Contact form settings") ?></h5>
                <small class="text-muted"><?php _e("Make settings for your contact form (there may be more than one) related to the conditions for sending data and using the website.") ?></small>
            </div>
            <div class="col-xl-9">
                <div class="<?php if (!$simple): ?>card bg-azure-lt  mb-3<?php endif; ?>">
                    <div class="<?php if (!$simple): ?> <?php endif; ?>">
                        <div class="row">
                            <div class="col-12">

                                <div class="form-group mb-3">
                                    <label class="form-label"><?php _e("Users must agree to the terms and conditions") ?></label>
                                    <small class="text-muted d-block mb-2"><?php _e("If the user does not agree to the terms, he will not be able to use the contact form") ?></small>
                                </div>

                                <module type="users/terms/set_for_module" for_module="<?php echo $params['parent-module-id']; ?>"/>

                                <div class="form-group mb-3">
                                    <label class="form-label"><?php _e("Saving data and emails") ?></label>
                                    <small class="text-muted d-block mb-2"><?php _e("Will you save the information from the emails in your database on the website?") ?></small>
                                </div>

                                <div class="form-group mb-4">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="mw_option_field form-check-input" id="skip_saving_emails_<?php echo $params['id']; ?>" parent-reload="true" name="skip_saving_emails" value="y" data-value-unchecked="n" data-value-checked="y" option-group="<?php print $mod_id ?>" <?php if (get_option('skip_saving_emails', $mod_id) == 'y'): ?>checked<?php endif; ?> />
                                        <label class="custom-control-label" for="skip_saving_emails_<?php echo $params['id']; ?>"><?php _e("Skip saving emails in my website database."); ?></label>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label d-block"><?php _e("Want to view and edit the text and the page?") ?></label>
                                    <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="collapse" data-bs-target="#contact-form-settings"><?php _e("Edit the text and URL") ?></button>
                                </div>

                                <div class="collapse" id="contact-form-settings">
                                    <module type="users/terms/edit" terms-group="contact_form" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


