<?php only_admin_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
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


            </div>
        </div>
    </div>
</div>
</div>
