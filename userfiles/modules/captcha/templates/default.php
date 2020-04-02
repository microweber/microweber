<?php

/*

type: layout

name: Default

description: Default comments template

*/;


?>
<!--<script>
    mw.lib.require('bootstrap3ns');
</script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>-->
<!--
<div class="bootstrap3ns">
    <div class="row">
        <div class="mw-captcha" style="max-width: 400px; margin: 15px;">
            <div class="form-group">
                <div class="captcha-holder">
                    <div class="col-xs-4">
                        <a href="javascript:;" class="tip" data-tip="Refresh captcha" data-tipposition="top-center">
                            <img
                                onclick="mw.tools.refresh_image(this);"
                                class="mw-captcha-img"
                                id="captcha-<?php /*print $form_id; */ ?>"
                                src="<?php /*print api_link('captcha') */ ?>?w=100&h=60&uid=<?php /*print uniqid($form_id) */ ?>&rand=<?php /*print rand(1,10000) */ ?>&id=<?php /*print $params['id'] */ ?> ?>"  /></a>
                    </div>
                    <div class="col-xs-6">
                        <input name="captcha" type="text" required class="mw-captcha-input form-control" placeholder="<?php /*_e("Security code"); */ ?>"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>
-->
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
</script>

<script type="text/javascript">
    var onloadCallback = function() {
        grecaptcha.render('js-mw-google-recaptcha-v2-<?php print $params['id'] ?> ?>', {
            'sitekey' : '<?php echo get_option('recaptcha_v2_site_key', 'captcha'); ?>'
        });
    };
</script>

<div class="mw-ui-row">
    <?php
    $captcha_provider = get_option('provider', 'captcha');
    if ($captcha_provider == 'google_recaptcha_v2'):
    ?>
    <div class="mw-captcha">
        <div id="js-mw-google-recaptcha-v2-<?php print $params['id'] ?> ?>"></div>
    </div>
    <?php else: ?>
    <div class="mw-captcha" style="max-width: 400px; margin: 15px;">
        <div class="form-group">
            <div class="captcha-holder">
                <div class="mw-ui-col" style="width: 100px;">
                    <a href="javascript:;" class="tip" data-tip="Refresh captcha" data-tipposition="top-center">
                        <img onclick="mw.tools.refresh_image(this);"
                             class="mw-captcha-img"
                             id="captcha-<?php print $form_id; ?>"
                             src="<?php print api_link('captcha') ?>?w=100&h=60&uid=<?php print uniqid($form_id) ?>&rand=<?php print rand(1, 10000) ?>&id=<?php print $params['id'] ?> ?>"/></a>
                </div>
                <div class="mw-ui-col">
                    <input name="captcha" type="text" required class="mw-captcha-input form-control" placeholder="<?php _e("Security code"); ?>"/>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
