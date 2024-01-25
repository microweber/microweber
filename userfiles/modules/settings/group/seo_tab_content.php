<?php must_have_access(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>

<div class="form-group">
    <label class="form-label"><?php _e("Google site verification code"); ?> </label>
    <small class="text-muted d-block mb-2"><?php _e("If you have a Google Tag Manager account, you can verify ownership of a site using your Google Tag Manager container snippet code. To verify ownership using Google Tag Manager: Choose Google Tag Manager in the verification details page for your site, and follow the instructions shown."); ?> <a href="https://support.google.com/webmasters/answer/9008080?hl=en#choose_method&zippy=%2Chtml-tag" target="_blank"><?php _e("Read the article here."); ?></a></small>
    <?php $key_name = 'google-site-verification-code'; ?>
    <input name="<?php print $key_name ?>" class="mw_option_field form-control" type="text" option-group="website" title="google-site-verification" value="<?php print get_option($key_name, 'website'); ?>"/>
</div>

<div>
    <module type="google_analytics/settings_form_admin" />
</div>

<?php event_trigger('mw.admin.settings.seo') ?>


<div class="form-group">
    <label class="form-label"><?php _e("Facebook pixel ID"); ?> </label>
    <small class="text-muted d-block mb-2"><?php _e("You can find a tutorials in internet where and how to find the code."); ?></small>
    <?php $key_name = 'facebook-pixel-id'; ?>
    <input name="<?php print $key_name ?>" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
</div>

<a href="javascript:$('.other-site-verification-codes-hidden-toggle').toggle();void(0)" class="btn btn-outline-primary btn-sm my-3"><?php _e('Other search engines'); ?></a>
<script>
    $(document).ready(function () {
        $('#js-static_files_delivery_method_select').on('change', function () {
            if (this.value == 'cdn_domain' || this.value == 'content_proxy') {
                $(".js-toggle-content-proxy-settings").show();
            }
            else {
                $(".js-toggle-content-proxy-settings").hide();
            }
        });
    });
</script>

<div class="other-site-verification-codes-hidden-toggle" style="display: none">

    <div class="form-group">
        <label class="form-label"><?php _e("Bing"); ?> </label>
        <small class="text-muted d-block mb-2"><?php _e("You can find a tutorials in internet where and how to find the code."); ?></small>
        <?php $key_name = 'bing-site-verification-code'; ?>
        <input name="<?php print $key_name ?>" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
    </div>

    <div class="form-group">
        <label class="form-label"><?php _e("Alexa"); ?> </label>
        <small class="text-muted d-block mb-2"><?php _e("You can find a tutorials in internet where and how to find the code."); ?></small>
        <?php $key_name = 'alexa-site-verification-code'; ?>
        <input name="<?php print $key_name ?>" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
    </div>

    <div class="form-group">
        <label class="form-label"><?php _e("Pinterest"); ?> </label>
        <small class="text-muted d-block mb-2"><?php _e("You can find a tutorials in internet where and how to find the code."); ?></small>
        <?php $key_name = 'pinterest-site-verification-code'; ?>
        <input name="<?php print $key_name ?>" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
    </div>

    <div class="form-group">
        <label class="form-label">
            <?php _e("Site verification code for"); ?> <?php _e("Yandex"); ?> </label>
        <small class="text-muted d-block mb-2"><?php _e("You can find a tutorials in internet where and how to find the code."); ?></small>
        <?php $key_name = 'yandex-site-verification-code'; ?>
        <input name="<?php print $key_name ?>" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
    </div>
</div>
