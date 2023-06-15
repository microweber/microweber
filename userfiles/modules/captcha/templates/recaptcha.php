<?php
$captcha_name = get_option('captcha_name', $params['id']);

if (empty($captcha_name)) {
    $url_segment = url_segment();
    $captcha_name = $url_segment[0];
}
$form_id = "mw_contact_form_" . $params['id'];
if (isset($params['parent-module-id'])) {
    $form_id = $params['parent-module-id'];
}

if (!$captcha_name) {
    $captcha_name = 'captcha' . crc32($params['id']);
}

$captcha_name = str_replace(['-', '_', '/'], '', $captcha_name);

if (isset($params['captcha_parent_for_id'])) {
    $params['id'] = $params['captcha_parent_for_id'] . '-captcha';
}

$callback = false;
if (isset($params['data-callback'])) {
    $callback = $params['data-callback'];
}

$input_id = 'js-mw-google-recaptcha-v3-' . $params['id'] . '-input';
if (isset($params['_confirm'])) {
    $input_id .= '-confirm';

}


$js_function_hash = md5($params['id']);

if ($captcha_provider == 'google_recaptcha_v2'):
    ?>
    <script type="text/javascript">
        if (typeof (grecaptcha) === 'undefined') {
            mw.require('https://www.google.com/recaptcha/api.js?hl=<?php print app()->getLocale() ?>', true, 'recaptcha');
        }
    </script>

    <script type="text/javascript">
        window.runRecaptchaV2<?php print $js_function_hash ?> = function () {
            if (typeof (grecaptcha) !== 'undefined') {
                try {
                    grecaptcha.render('js-mw-google-recaptcha-v2-<?php print $params['id'] ?>', {
                        'sitekey': '<?php echo get_option('recaptcha_v2_site_key', 'captcha'); ?>',
                        'action': '<?php echo $captcha_name; ?>',
                        'callback': function (response) {
                            $('#<?php print $input_id ?>').val(response);
                            <?php if($callback): ?>
                            <?php print $callback; ?>(response);
                            <?php endif; ?>

                        },
                    });
                } catch (error) {

                }
            }
        }


        $(document).ready(function () {

            if ($('#js-mw-google-recaptcha-v2-<?php print $params['id'] ?>').find('iframe').length > 0) {
                $('#js-mw-google-recaptcha-v2-<?php print $params['id'] ?>').first().remove();
            }

            mw.on('refreshCaptchaToken', function () {
                window.runRecaptchaV3<?php print $js_function_hash ?>();
            })


            setTimeout(function () {
                window.runRecaptchaV2<?php print $js_function_hash ?>();
            }, 1000);
        });
    </script>

    <div class="form-group">
        <div id="js-mw-google-recaptcha-v2-<?php print $params['id'] ?>"></div>
        <input name="captcha" type="hidden" value="" id="js-mw-google-recaptcha-v2-<?php print $params['id'] ?>-input" class="mw-captcha-input"/>
    </div>
<?php elseif ($captcha_provider == 'google_recaptcha_v3'): ?>

    <input type="hidden" name="captcha" data-captcha-version="v3" id="<?php print $input_id ?>">

    <script>


        window.runRecaptchaV3Attach<?php print $js_function_hash ?> = function () {
            var captcha_el = $('#<?php print $input_id ?>')
                if (captcha_el) {
                    var parent_form = mw.tools.firstParentWithTag(captcha_el[0], 'form')
                   //   window.runRecaptchaV3<?php print $js_function_hash ?>();
                     if (parent_form) {

                        parent_form.$beforepost = window.runRecaptchaV3<?php print $js_function_hash ?>
                    }
                }



        }

        mw.on('refreshCaptchaToken', function () {
            window.runRecaptchaV3<?php print $js_function_hash ?>();
        })


         window.runRecaptchaV3<?php print $js_function_hash ?> = function () {

             return  new Promise(function (resolve){
                grecaptcha.ready(function () {

                    grecaptcha.execute('<?php echo get_option('recaptcha_v3_site_key', 'captcha'); ?>', {
                        action: '<?php echo $captcha_name; ?>'
                    }).then(function (token) {

                        var recaptchaResponse = document.getElementById('<?php print $input_id ?>');

                        if (recaptchaResponse) {
                            recaptchaResponse.value = token;
                        }

                        <?php if($callback): ?>
                        <?php print $callback; ?>(token);
                        <?php endif; ?>


                      resolve(token)
                    });
                });

            })


        };
    </script>


<script>
    $(document).ready(function () {

        if (typeof (window.grecaptcha) === 'undefined') {

            $.getScript( "//www.google.com/recaptcha/api.js?render=<?php echo get_option('recaptcha_v3_site_key', 'captcha'); ?>&hl=<?php print app()->getLocale() ?>", function( data, textStatus, jqxhr ) {
                window.runRecaptchaV3Attach<?php print $js_function_hash ?>();
                window.runRecaptchaV3<?php print $js_function_hash ?>();
            });
        } else {
            window.runRecaptchaV3Attach<?php print $js_function_hash ?>();
            window.runRecaptchaV3<?php print $js_function_hash ?>();
        }
    });
</script>

    <?php if (isset($params['_confirm'])) { ?>
        <h6><?php _e("Please confirm form submit"); ?></h6>
    <?php } else { ?>

    <?php } ?>

<?php else: ?>


<?php endif; ?>
