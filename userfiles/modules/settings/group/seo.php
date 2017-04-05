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



<div class="mw-ui-field-holder">



    <label class="mw-ui-label">


        <?php _e("Site verification code for"); ?> Google
        <br>
        <?php $key_name = 'google-site-verification-code'; ?>
        <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field" type="text" option-group="website"
               value="<?php print get_option($key_name, 'website'); ?>"/>


    </label>

    <label class="mw-ui-label">


       Google Analytics ID
        <br>
        <?php $key_name = 'google-analytics-id'; ?>
        <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field" type="text" option-group="website"
               value="<?php print get_option($key_name, 'website'); ?>"/>


    </label>




    <a href="javascript:$('.other-site-verification-codes-hidden-toggle').toggle();void(0)" class="mw-ui-btn"><?php _e('Other search engines'); ?></a>
    <div class="other-site-verification-codes-hidden-toggle" style="display: none">
        <label class="mw-ui-label">

            <?php _e("Site verification code for"); ?> Bing
            <br>
            <?php $key_name = 'bing-site-verification-code'; ?>
            <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field" type="text"
                   option-group="website"
                   value="<?php print get_option($key_name, 'website'); ?>"/>
        </label> <label class="mw-ui-label">

            <?php _e("Site verification code for"); ?> Alexa
            <br>
            <?php $key_name = 'alexa-site-verification-code'; ?>
            <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field" type="text"
                   option-group="website"
                   value="<?php print get_option($key_name, 'website'); ?>"/>
        </label> <label class="mw-ui-label">


            <?php _e("Site verification code for"); ?> Pinterest
            <br>
            <?php $key_name = 'pinterest-site-verification-code'; ?>
            <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field" type="text"
                   option-group="website"
                   value="<?php print get_option($key_name, 'website'); ?>"/>

        </label> <label class="mw-ui-label">

            <?php _e("Site verification code for"); ?> Yandex
            <br>
            <?php $key_name = 'yandex-site-verification-code'; ?>
            <input name="<?php print $key_name ?>" class="mw_option_field mw-ui-field" type="text"
                   option-group="website"
                   value="<?php print get_option($key_name, 'website'); ?>"/>


        </label>


    </div>
</div>
