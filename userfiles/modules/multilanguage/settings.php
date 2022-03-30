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
                <label class="control-label"><?php _e('Multilanguage is active'); ?>?</label>
                <div class="custom-control custom-switch pl-0">
                    <label class="d-inline-block mr-5" for="is_active"><?php _e('No'); ?></label>
                    <input class="mw_option_field custom-control-input" id="is_active" type="checkbox" autocomplete="off" name="is_active" <?php if (get_option('is_active', 'multilanguage_settings') == 'y'): ?>checked<?php endif; ?> option-group="multilanguage_settings" data-value-checked="y" data-value-unchecked="n">
                    <label class="custom-control-label" for="is_active"><?php _e('Yes'); ?></label>
                </div>
            </div>

            <?php if ($langs): ?>
            <!--    <div class="form-group">
                    <label class="control-label d-block"><?php /*_e("Default website language"); */?></label>
                    <small class="text-muted d-block mb-2"><?php /*_e("You can set the default language for your website."); */?></small>
                    <?php
/*                    $def_language = get_option('language', 'website');

                    if ($def_language == false) {
                        $def_language = 'en';
                    }
                    */?>
                    <select id="user_lang" name="language" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true" option-group="website">
                        <option disabled="disabled"><?php /*_e('Select Language'); */?></option>
                        <?php /*foreach ($langs as $key => $lang): */?>
                            <option <?php /*if ($def_language == $key): */?> selected="" <?php /*endif; */?> value="<?php /*print $key */?>"><?php /*print $lang */?></option>
                        <?php /*endforeach; */?>
                    </select>
                </div>-->
            <?php endif; ?>

            <?php if ($langs): ?>
                <div class="form-group">
                    <label class="control-label d-block"><?php _e("Homepage language"); ?></label>
                    <small class="text-muted d-block mb-2"></small>
                    <?php
                    $def_language = get_option('homepage_language', 'website');

                    if ($def_language == false) {
                        $def_language = 'en';
                    }
                    ?>
                    <?php if ($langs) : ?>
                        <select id="user_homepage_lang" name="homepage_language" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true" option-group="website">
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
                <label class="control-label"><?php _e("Add prefix for all languages"); ?></label>
                <div class="custom-control custom-switch pl-0">
                    <label class="d-inline-block mr-5" for="add_prefix_for_all_languages"><?php _e("No"); ?></label>
                    <input class="mw_option_field custom-control-input" id="add_prefix_for_all_languages" type="checkbox" autocomplete="off" name="add_prefix_for_all_languages" <?php if (get_option('add_prefix_for_all_languages', 'multilanguage_settings') == 'y'): ?>checked<?php endif; ?> option-group="multilanguage_settings" data-value-checked="y" data-value-unchecked="n">
                    <label class="custom-control-label" for="add_prefix_for_all_languages"><?php _e("Yes"); ?></label>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label"><?php _e("Switch language by IP Geolocation"); ?></label>
                <div class="custom-control custom-switch pl-0">
                    <label class="d-inline-block mr-5" for="use_geolocation"><?php _e("No"); ?></label>
                    <input class="mw_option_field custom-control-input" type="checkbox" id="use_geolocation" autocomplete="off" name="use_geolocation" <?php if (get_option('use_geolocation', 'multilanguage_settings') == 'y'): ?>checked="checked"<?php endif; ?> option-group="multilanguage_settings" data-value-checked="y" data-value-unchecked="n">
                    <label class="custom-control-label" for="use_geolocation"><?php _e("Yes"); ?></label>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e("Geolocation Provider"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e("Choose your preferred geolocation IP detector"); ?></small>
                <select name="geolocation_provider" class="mw_option_field js-geolocation-provider selectpicker" data-size="5" option-group="multilanguage_settings">
                    <option value="browser_detection"><?php _e("Browser Detection"); ?></option>
                    <option value="domain_detection"><?php _e("Domain Detection"); ?></option>
                    <option value="geoip_browser_detection"><?php _e("GEO-IP + Browser Detection"); ?></option>
                    <option value="microweber"><?php _e("Microweber Geo Api"); ?></option>
                    <option value="ipstack_com"><?php _e("IpStack.com"); ?></option>
                </select>

                <a href="javascript:;" class="btn btn-outline-primary" onclick="testGeoApi();"><span class="mw-icon-beaker"></span> <?php _e("Test Geo Api"); ?></a>
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
                    <label class="control-label"><?php _e("IpStack.com API Access Key"); ?></label>
                    <input name="ipstack_api_access_key" option-group="multilanguage_settings" value="<?php echo get_option('ipstack_api_access_key', 'multilanguage_settings'); ?>" class="mw_option_field form-control mw-options-form-binded" type="text">
                </div>
            </div>
        </div>
    </div>
</div>
