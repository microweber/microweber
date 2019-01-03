<?php only_admin_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _e("Saved"); ?>.");
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
