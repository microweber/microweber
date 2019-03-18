<?php only_admin_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _e("All changes are saved"); ?>.");
        });
    });
</script>


<h2>
    <?php _e("Meta tags"); ?>
</h2>


<div class="mw-ui-box mw-ui-box-content">
    <div class="mw-flex-row">
        <div class="mw-flex-col-xs-12 ">
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e("Site verification code for"); ?> Google</label>
                <?php $key_name = 'google-site-verification-code'; ?>
                <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field mw-full-width" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
            </div>

            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"> Google Analytics ID</label>
                <?php $key_name = 'google-analytics-id'; ?>
                <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field mw-full-width" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
            </div>
        </div>
    </div>
</div>


<div class="mw-ui-box mw-ui-box-content">
    <div class="mw-flex-row">
        <div class="mw-flex-col-xs-12">
            <a href="javascript:$('.other-site-verification-codes-hidden-toggle').toggle();void(0)" class="mw-ui-btn"><?php _e('Other settings'); ?></a>
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
                <h3><?php _e('Other search engines'); ?></h3>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Site verification code for"); ?> Bing</label>
                    <?php $key_name = 'bing-site-verification-code'; ?>
                    <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field mw-full-width" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Site verification code for"); ?> Alexa</label>
                    <?php $key_name = 'alexa-site-verification-code'; ?>
                    <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field mw-full-width" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Site verification code for"); ?> Pinterest</label>
                    <?php $key_name = 'pinterest-site-verification-code'; ?>
                    <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field mw-full-width" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                        <?php _e("Site verification code for"); ?> Yandex </label>
                    <?php $key_name = 'yandex-site-verification-code'; ?>
                    <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field mw-full-width" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
                </div>

                <div class="mw-ui-field-holder">
                    <h3><?php _e('Cache settings'); ?></h3>
                    <div class="mw-notification">Note: Those features are experimental and not fully tested. Check if your website is working normally after enabling cache settings.</div>
                    <hr>
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Optimize assets loading"); ?></label>
                    <?php $optimize_asset_loading = get_option('optimize_asset_loading', 'website'); ?>

                    <ul class="mw-ui-inline-list">
                        <li>
                            <label class="mw-ui-check">
                                <input class="mw_option_field" type="radio" id="img_resize_choice1" name="optimize_asset_loading" <?php if ($optimize_asset_loading == 'y'): ?> checked <?php endif; ?> value="y" option-group="website">
                                <span></span><span><?php _e("Yes"); ?></span>
                            </label>
                        </li>
                        <li>
                            <label class="mw-ui-check">
                                <input class="mw_option_field" type="radio" id="img_resize_choice2" name="optimize_asset_loading" <?php if (!$optimize_asset_loading or $optimize_asset_loading != 'y'): ?> checked <?php endif; ?> value="n" option-group="website">
                                <span></span><span><?php _e("No"); ?></span>
                            </label>
                        </li>
                    </ul>
                </div>


                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Enable full page cache"); ?></label>
                    <?php $enable_full_page_cache = get_option('enable_full_page_cache', 'website'); ?>
                    <ul class="mw-ui-inline-list">
                        <li>
                            <label class="mw-ui-check">
                                <input class="mw_option_field" type="radio" id="img_resize_choice1" name="enable_full_page_cache" <?php if ($enable_full_page_cache == 'y'): ?> checked <?php endif; ?> value="y" option-group="website">
                                <span></span><span><?php _e("Yes"); ?></span>
                            </label>
                        </li>
                        <li>
                            <label class="mw-ui-check">
                                <input class="mw_option_field" type="radio" id="img_resize_choice2" name="enable_full_page_cache" <?php if (!$enable_full_page_cache or $enable_full_page_cache != 'y'): ?> checked <?php endif; ?> value="n" option-group="website">
                                <span></span><span><?php _e("No"); ?></span>
                            </label>
                        </li>
                    </ul>
                </div>

                <hr>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Static files delivery method"); ?></label>
                    <?php $static_files_delivery_method = get_option('static_files_delivery_method', 'website'); ?>

                    <select id="js-static_files_delivery_method_select" name="static_files_delivery_method" class="mw-ui-field mw_option_field" type="text" option-group="website">
                        <option value="" <?php if (!$static_files_delivery_method): ?> selected="selected" <?php endif; ?>><?php _e("Default"); ?></option>
                        <option value="cdn_domain" <?php if ($static_files_delivery_method == 'cdn_domain'): ?> selected="selected" <?php endif; ?>>CDN Domain</option>
                        <option value="content_proxy" <?php if ($static_files_delivery_method == 'content_proxy'): ?> selected="selected" <?php endif; ?>>Content proxy (experimental)</option>
                    </select>

                    <div class="js-toggle-content-proxy-settings" <?php if (!$static_files_delivery_method): ?> style="display: none" <?php endif; ?> >

                        <div class="mw-ui-box mw-ui-box-content mw-ui-box m-b-20">Warning, this is advanced action and may break your site.
                            Make sure you setup you domain to resolve to your website. <br>
                            After that you can enter your content delivery domain name for example cdn.mydomain.com
                        </div>

                        <div class="mw-ui-field-holder">
                            <label class="mw-ui-label">CDN Domain name</label>
                            <?php $key_name = 'static_files_delivery_method_domain'; ?>
                            <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field mw-full-width" type="text" option-group="website" value="<?php print get_option($key_name, 'website'); ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
