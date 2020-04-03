<?php
/*

type: layout

name: Default

description: Default comments template

*/
?>

<div class="mw-ui-row">
    <?php
    $captcha_provider = get_option('provider', 'captcha');
    if ($captcha_provider == 'google_recaptcha_v2'):
    ?>
    <script src="//www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
    </script>

    <script type="text/javascript">
        var onloadCallback = function() {
            if (typeof(grecaptcha) !== 'undefined') {
                grecaptcha.render('js-mw-google-recaptcha-v2-<?php print $params['id'] ?>', {
                    'sitekey': '<?php echo get_option('recaptcha_v2_site_key', 'captcha'); ?>'
                });
            }
        };
        onloadCallback();
    </script>
    <div class="mw-captcha">
        <div id="js-mw-google-recaptcha-v2-<?php print $params['id'] ?>"></div>
        <input name="captcha" type="hidden" value="<?php print $params['id'] ?>" class="mw-captcha-input" />
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
                             src="<?php print api_link('captcha') ?>?w=100&h=60&uid=<?php print uniqid($form_id) ?>&rand=<?php print rand(1, 10000) ?>&id=<?php print $params['id'] ?>" />
                    </a>
                </div>
                <div class="mw-ui-col">
                    <input name="captcha" type="text" required class="mw-captcha-input form-control" placeholder="<?php _e("Security code"); ?>"/>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
