<?php only_admin_access(); ?>
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


<label class="mw-ui-check">
    <input
            type="checkbox"
            name="enable_captcha"
            value="y"
            option-group="<?php print $mod_id ?>"
            class="mw_option_field"
        <?php if (get_option('enable_captcha', $mod_id) == 'y'): ?>   checked="checked"  <?php endif; ?>
    />
    <span></span> <span><?php _e("Enable Code Verification ex"); ?>.:</span> </label>
<img src="<?php print mw_includes_url(); ?>img/code_verification_example.jpg" class="relative" style="top: 7px;left:10px;" alt=""/>

<hr>
<label class="mw-ui-label"><?php _e("Redirect to URL after submit"); ?></label>

<div class="mw-ui-field-holder">
    <input name="newsletter_redirect_after_submit"   option-group="<?php print $mod_id ?>"    value="<?php print get_option('newsletter_redirect_after_submit', $mod_id); ?>"     class="mw-ui-field w100 mw_option_field"  type="text" />
</div>

