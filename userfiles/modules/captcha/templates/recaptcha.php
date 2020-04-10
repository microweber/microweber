<?php


if ($captcha_provider == 'google_recaptcha_v2'):
    ?>
    <script type="text/javascript">
        if (typeof(grecaptcha) === 'undefined') {
            mw.require('https://www.google.com/recaptcha/api.js', true, 'recaptcha');
        }
    </script>
    <script type="text/javascript">


        grecaptchaverifyCallback = function (response) {
            $('#js-mw-google-recaptcha-v2-<?php print $params['id'] ?>-input').val(response);

        };

        grecaptchaonloadCallback = function () {

            if (typeof(grecaptcha) !== 'undefined') {
                grecaptcha.render('js-mw-google-recaptcha-v2-<?php print $params['id'] ?>', {
                    'sitekey': '<?php echo get_option('recaptcha_v2_site_key', 'captcha'); ?>',
                    'callback': grecaptchaverifyCallback,
                });
            }
        };
        $(document).ready(function () {
            setTimeout(function () {
                grecaptchaonloadCallback();
            }, 1000);
        });

    </script>

    <div id="js-mw-google-recaptcha-v2-<?php print $params['id'] ?>"></div>
    <input name="captcha" type="hidden" value=""
           id="js-mw-google-recaptcha-v2-<?php print $params['id'] ?>-input" class="mw-captcha-input"/>


<?php elseif ($captcha_provider == 'google_recaptcha_v3'): ?>
    <script type="text/javascript">
         if (typeof(grecaptcha) === 'undefined') {
            mw.require('//www.google.com/recaptcha/api.js?render=<?php echo get_option('recaptcha_v3_site_key', 'captcha'); ?>', true, 'recaptcha');
        }
    </script>

    <script>
        $(document).ready(function () {
            setTimeout(function () {
                if (typeof(grecaptcha) !== 'undefined') {
                    grecaptcha.ready(function () {
                        grecaptcha.execute('<?php echo get_option('recaptcha_v3_site_key', 'captcha'); ?>', {action: 'contact'}).then(function (token) {
                            var recaptchaResponse = document.getElementById('js-mw-google-recaptcha-v3-<?php print $params['id'] ?>-input');
                            recaptchaResponse.value = token;
                        });
                    });
                }
            }, 1000);
        });
    </script>


    <?php if (isset($params['_confirm'])) { ?>
        <h6><?php _e("Please confirm form submit"); ?></h6>
    <?php } ?>


    <input type="hidden" name="captcha" id="js-mw-google-recaptcha-v3-<?php print $params['id'] ?>-input">


<?php else: ?>


<?php endif; ?>
