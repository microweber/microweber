<?php
has_access();

$mod_id = 'users';
if (isset($params['terms-group'])) {
    $mod_id = $params['terms-group'];
}

$terms_label = get_option('terms_label', $mod_id);
$terms_url = get_option('terms_url', $mod_id);
?>


<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
    });
</script>

<div class="form-group mb-3">
    <label class="control-label"><?php _e("Terms and conditions text"); ?></label>
    <small class="text-muted d-block mb-2">The text will appear to the user</small>
    <input type="text" class="mw_option_field form-control" name="terms_label" option-group="<?php print $mod_id ?>" value="<?php print $terms_label; ?>" placeholder="I agree with the Terms and Conditions"/>
</div>

<div class="form-group mb-3">
    <label class="control-label"><?php _e("URL of terms and conditions"); ?></label>
    <small class="text-muted d-block mb-2">Уou need to create this page and type in the address field.</small>
    <input type="text" class="mw_option_field form-control" name="terms_url" option-group="<?php print $mod_id ?>" value="<?php print $terms_url; ?>" placeholder="<?php print site_url('terms') ?>"/>
</div>