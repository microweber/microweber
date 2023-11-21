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

<?php
$isGoogleMesurementEnabled = get_option('google-measurement-enabled', 'website') == "y";
$isGoogleEnhancedConversions = get_option('google-enhanced-conversions-enabled', 'website') == "y";
?>


<div x-data="{
        showGoogleMeasurement: <?php if($isGoogleMesurementEnabled == 'y'): ?>true<?php else: ?>false<?php endif; ?>,
        showGoogleEnhancedConversions: <?php if($isGoogleMesurementEnabled == 'y'): ?>true<?php else: ?>false<?php endif; ?>
        }">


    <div class="form-group">
        <label class="form-label"><?php _e("Google Analytics ID"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Google Analytics' property ID is the identifier associated with your account and used by Google Analytics to collect the data."); ?> <a href="https://support.google.com/analytics/answer/9539598?hl=en" target="_blank"><?php _e("How to find it read here."); ?></a></small>
        <?php $key_name = 'google-analytics-id'; ?>
        <input name="<?php print $key_name ?>" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>" placeholder="UA-12345678-9"/>
    </div>

    <div>
        <label class="form-check-label d-block"><?php _e("Google Analytics Server Side Tracking"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Enable Server Side Google Analytics Tracking"); ?></small>
        <label class="form-check form-switch">
            <input x-model="showGoogleMeasurement" name="google-measurement-enabled" class="form-check-input mw_option_field " data-option-group="website" data-value-checked="y" data-value-unchecked="n" type="checkbox" <?php if (!$isGoogleMesurementEnabled): ?> checked="checked" <?php endif; ?>>
        </label>
    </div>

    <div x-show="showGoogleMeasurement">

        <div class="form-group">
            <label class="form-label"><?php _e("Google Measurement Api Secret"); ?></label>
            <small class="text-muted d-block mb-2">
                <?php _e("Google measurement api secret."); ?>
                <a href="https://support.google.com/analytics/answer/9539598?hl=en" target="_blank"><?php _e("How to find it read here."); ?></a>
                <a href="https://developers.google.com/analytics/devguides/collection/protocol/ga4/reference/" target="_blank"><?php _e("Protocol reference"); ?></a>
                <?php _e("To create a new secret, navigate in the Google Analytics UI to:"); ?>
                <i>Admin > Data Streams > choose your stream > Measurement Protocol > Create</i>
            </small>
            <?php $key_name = 'google-measurement-api-secret'; ?>
            <input name="<?php print $key_name ?>" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>" placeholder="Your Api Secret"/>
        </div>

         <div class="form-group">
            <label class="form-label"><?php _e("Google Measurement ID"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("Google measurement' property ID is the identifier associated with your account and used by Google Analytics to collect the data."); ?> <a href="https://support.google.com/analytics/answer/9539598?hl=en" target="_blank"><?php _e("How to find it read here."); ?></a></small>
            <?php $key_name = 'google-measurement-id'; ?>
            <input name="<?php print $key_name ?>" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>" placeholder="G-12345678"/>
        </div>


        <div>
            <label class="form-check-label d-block"><?php _e("Google Enhanced conversions"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("Enable Server Side Google Enhanced Conversions"); ?></small>
            <label class="form-check form-switch">
                <input x-model="showGoogleEnhancedConversions" name="google-enhanced-conversions-enabled" class="form-check-input mw_option_field " data-option-group="website" data-value-checked="y" data-value-unchecked="n" type="checkbox" <?php if (!$isGoogleEnhancedConversions): ?> checked="checked" <?php endif; ?>>
            </label>
        </div>

        <div x-show="showGoogleEnhancedConversions">

            <div class="form-group">
                <label class="form-label"><?php _e("Conversion ID"); ?></label>
                <?php $key_name = 'google-enhanced-conversion-id'; ?>
                <input name="<?php print $key_name ?>" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>" placeholder=""/>
            </div>

            <div class="form-group">
                <label class="form-label"><?php _e("Conversion Label"); ?></label>
                <?php $key_name = 'google-enhanced-conversion-label'; ?>
                <input name="<?php print $key_name ?>" class="mw_option_field form-control" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>" placeholder=""/>
            </div>

        </div>

    </div>

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
