<?php only_admin_access(); ?>

<?php
$captcha_name = get_option('captcha_name', $params['id']);

if (empty($captcha_name)) {
    $url_segment = url_segment();
    $captcha_name = $url_segment[0];
}
?>
<div class="module-live-edit-settings module-captcha-settings">

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php print _e('Captcha Name'); ?></label>
        <input class="mw-ui-field mw_option_field mw-full-width" name="captcha_name" value="<?php echo $captcha_name; ?>" id="captcha_name" placeholder="<?php _e('Enter captcha name..'); ?>">
    </div>

</div>
