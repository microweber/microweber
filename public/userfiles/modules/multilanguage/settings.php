<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.module-settings-group-multilanguage', function () {
            mw.notification.success("All changes are saved.");
        });
    });
</script>

<div class="module-settings-group-multilanguage">

    <?php
    $langs = array();
    foreach (get_supported_languages(1) as $supported_language) {
        $langs[$supported_language['locale']] = $supported_language['language'] . ' ['.$supported_language['locale'].']';
    }
    ?>
    <div class="row">
        <div class="col-md-6">

            <div class="form-group">
                <label class="form-label"><?php _e('Multilanguage is active'); ?>?</label>
                <div class="form-check form-switch pl-0">
                    <input class="mw_option_field form-check-input" id="is_active" type="checkbox" autocomplete="off" name="is_active" <?php if (get_option('is_active', 'multilanguage_settings') == 'y'): ?>checked<?php endif; ?> option-group="multilanguage_settings" data-value-checked="y" data-value-unchecked="n">
                </div>
            </div>

            <?php if ($langs): ?>
                <div class="form-group">
                    <label class="form-label d-block"><?php _e("Homepage language"); ?></label>
                    <small class="text-muted d-block mb-2"></small>
                    <?php
                    $def_language = get_option('homepage_language', 'website');

                    if ($def_language == false) {
                        $def_language = 'en';
                    }
                    ?>
                    <?php if ($langs) : ?>
                        <select id="user_homepage_lang" name="homepage_language" class="mw_option_field form-select" data-width="100%" data-size="5" data-live-search="true" option-group="website">
                            <option disabled="disabled"><?php _e('Select Language'); ?></option>
                            <option value="none">None</option>
                            <?php foreach ($langs as $key => $lang): ?>
                                <option <?php if ($def_language == $key): ?> selected="" <?php endif; ?> value="<?php print $key ?>"><?php print $lang ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label"><?php _e("Add prefix for all languages"); ?></label>
                <div class="form-check form-switch pl-0">
                    <input class="mw_option_field form-check-input" id="add_prefix_for_all_languages" type="checkbox" autocomplete="off" name="add_prefix_for_all_languages" <?php if (get_option('add_prefix_for_all_languages', 'multilanguage_settings') == 'y'): ?>checked<?php endif; ?> option-group="multilanguage_settings" data-value-checked="y" data-value-unchecked="n">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label"><?php _e("Switch language by IP Geolocation"); ?></label>
                <div class="form-check form-switch pl-0">
                    <input class="mw_option_field form-check-input" type="checkbox" id="use_geolocation" autocomplete="off" name="use_geolocation" <?php if (get_option('use_geolocation', 'multilanguage_settings') == 'y'): ?>checked="checked"<?php endif; ?> option-group="multilanguage_settings" data-value-checked="y" data-value-unchecked="n">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label"><?php _e("Geolocation Provider"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e("Choose your preferred geolocation IP detector"); ?></small>
                <select name="geolocation_provider" class="mw_option_field js-geolocation-provider  form-select" data-size="5" option-group="multilanguage_settings">
                    <option value="browser_detection"><?php _e("Browser Detection"); ?></option>
                    <option value="domain_detection"><?php _e("Domain Detection"); ?></option>
                    <option value="geoip_browser_detection"><?php _e("GEO-IP + Browser Detection"); ?></option>
                    <option value="microweber"><?php _e("Microweber Geo Api"); ?></option>
                    <option value="ipstack_com"><?php _e("IpStack.com"); ?></option>
                </select>

                <a href="javascript:;" class="btn btn-outline-dark mt-3" onclick="testGeoApi();"> <?php _e("Test Geo Api"); ?></a>
            </div>

            <script>
                $(document).ready(function () {
                    $('.js-geolocation-provider').change(function () {
                        if ($(this).val() == 'ipstack_com') {
                            $('.js-ipstack-com').fadeIn();
                        } else {
                            $('.js-ipstack-com').fadeOut();
                        }
                    });
                });

                function testGeoApi() {
                    var client_details = {}
                    // client_details.ip = $('#ip').val();

                    $.post("<?php print site_url('api/multilanguage/geolocaiton_test'); ?>", client_details, function (msg) {
                        mw.dialog({
                            html: "<pre>" + msg + "</pre>",
                            title: "<?php _e('Geo API Results...'); ?>"
                        });
                    });
                }
            </script>

            <?php
            $displayIstack = 'display:none;';
            if (get_option('geolocation_provider', 'multilanguage_settings') == 'ipstack_com') {
                $displayIstack = '';
            }
            ?>

            <div class="js-ipstack-com mt-3" style="<?php echo $displayIstack; ?>">
                <div class="form-group">
                    <label class="form-label"><?php _e("IpStack.com API Access Key"); ?></label>
                    <input name="ipstack_api_access_key" option-group="multilanguage_settings" value="<?php echo get_option('ipstack_api_access_key', 'multilanguage_settings'); ?>" class="mw_option_field form-control mw-options-form-binded" type="text">
                </div>
            </div>
        </div>
    </div>
</div>
