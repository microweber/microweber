<?php
if (!is_admin()) {
    return;
}

$isGoogleMesurementEnabled = get_option('google-measurement-enabled', 'website') == "y";
$isGoogleEnhancedConversions = get_option('google-enhanced-conversions-enabled', 'website') == "y";
?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('#js-google-analytics-settings-form', function () {
            mw.notification.success("<?php _ejs("Google analytics settings are saved"); ?>.");
        });
    });
</script>


<div
    id="js-google-analytics-settings-form"
    x-data="{
        showGoogleMeasurement: <?php if($isGoogleMesurementEnabled == 'y'): ?>true<?php else: ?>false<?php endif; ?>,
        showGoogleEnhancedConversions: <?php if($isGoogleEnhancedConversions == 'y'): ?>true<?php else: ?>false<?php endif; ?>
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
