<?php must_have_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
    });
</script>

<?php
$mod_id = 'newsletter';
if (isset($params['for_module_id'])) {
    $mod_id = $params['for_module_id'];
}
?>
<div class="p-4">
    <div class="form-group">
        <label class="control-label"><?php _e("Captcha verification"); ?></label>
        <div class="custom-control custom-checkbox d-flex align-items-center">
            <input type="checkbox" name="enable_captcha" id="enable_captcha" value="y" option-group="<?php print $mod_id ?>" class="mw_option_field custom-control-input"  <?php if (get_option('enable_captcha', $mod_id) == 'y'): ?>checked<?php endif; ?> />
            <label class="custom-control-label" for="enable_captcha"><?php _e("Enable Code Verification ex"); ?>.: <img src="<?php print mw_includes_url(); ?>img/code_verification_example.jpg" style="margin-top: -3px; height: 26px;"  alt=""/></label>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e("Redirect to URL after submit"); ?></label>
        <small class="text-muted d-block mb-2">For example: redirect to thank_you.php page</small>
        <input name="newsletter_redirect_after_submit"   option-group="<?php print $mod_id ?>"    value="<?php print get_option('newsletter_redirect_after_submit', $mod_id); ?>" class="mw_option_field form-control" type="text" />
    </div>
</div>