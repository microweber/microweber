<?php


only_admin_access();

$terms_label = get_option('terms_label', 'users');
$terms_url = get_option('terms_url', 'users');
$mod_id = 'users';
?>


<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
    });
</script>

<label class="mw-ui-label"><?php _e("Terms and conditions text"); ?></label>

<div class="mw-ui-field-holder">
    <input placeholder="I agree with the Terms and Conditions" name="terms_label"   option-group="<?php print $mod_id ?>"    value="<?php print $terms_label; ?>"     class="mw-ui-field w100 mw_option_field"  type="text" />
</div>



<label class="mw-ui-label"><?php _e("Url of terms and conditions"); ?></label>

<div class="mw-ui-field-holder">
    <input placeholder="<?php print site_url('terms') ?>" name="terms_url"   option-group="<?php print $mod_id ?>"    value="<?php print $terms_url; ?>"     class="mw-ui-field w100 mw_option_field"  type="text" />
</div>
