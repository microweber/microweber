@php
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
@endphp

@if ($captcha_provider == 'google_recaptcha_v2')
    <script type="text/javascript">
        if (typeof (grecaptcha) === 'undefined') {
            mw.require('https://www.google.com/recaptcha/api.js?hl={{ app()->getLocale() }}', true, 'recaptcha');
        }
    </script>

    <script type="text/javascript">
        window.runRecaptchaV2<?php print $js_function_hash ?> = function () {
            if (typeof (grecaptcha) !== 'undefined') {
                try {
                    grecaptcha.render('js-mw-google-recaptcha-v2-{{ $params['id'] }}', {
                        'sitekey': '{{ get_option('recaptcha_v2_site_key', 'captcha') }}',
                        'action': '{{ $captcha_name }}',
                        'callback': function (response) {
                            $('#{{ $input_id }}').val(response);
                            @if($callback)
                                {{ $callback }}(response);
                            @endif

                        },
                    });
                } catch (error) {

                }
            }
        }


        $(document).ready(function () {

            if ($('#js-mw-google-recaptcha-v2-{{ $params['id'] }}').find('iframe').length > 0) {
                $('#js-mw-google-recaptcha-v2-{{ $params['id'] }}').first().remove();
            }

            mw.on('refreshCaptchaToken', function () {
                window.runRecaptchaV3{{ $js_function_hash }}();
            })


            setTimeout(function () {
                window.runRecaptchaV2{{ $js_function_hash }}();
            }, 1000);
        });
    </script>

    <div class="form-group">
        <div id="js-mw-google-recaptcha-v2-{{ $params['id'] }}"></div>
        <input name="captcha" type="hidden" value="" id="js-mw-google-recaptcha-v2-{{ $params['id'] }}-input"
               class="mw-captcha-input"/>
    </div>
@elseif ($captcha_provider == 'google_recaptcha_v3')

    <input type="hidden" name="captcha" data-captcha-version="v3" id="{{ $input_id }}">

    <script>

        window.runRecaptchaV3Attach{{ $js_function_hash }} = function () {
            var captcha_el = $('#{{ $input_id }}')
            if (captcha_el) {
                var parent_form = mw.tools.firstParentWithTag(captcha_el[0], 'form')
                //   window.runRecaptchaV3<?php print $js_function_hash ?>();
                if (parent_form) {

                    parent_form.$beforepost = window.runRecaptchaV3{{ $js_function_hash }}
                }
            }


        }

        mw.on('refreshCaptchaToken', function () {
            window.runRecaptchaV3{{ $js_function_hash }}();
        })


        window.runRecaptchaV3{{ $js_function_hash }} = function () {

            return new Promise(function (resolve) {
                grecaptcha.ready(function () {

                    grecaptcha.execute('{{ get_option('recaptcha_v3_site_key', 'captcha') }}', {
                        action: '{{ $captcha_name }}'
                    }).then(function (token) {

                        var recaptchaResponse = document.getElementById('{{ $input_id }}');

                        if (recaptchaResponse) {
                            recaptchaResponse.value = token;
                        }

                        @if($callback)
                            {{ $callback }}(token);
                        @endif


                        resolve(token)
                    });
                });

            })


        };
    </script>


    <script>
        $(document).ready(function () {

            if (typeof (window.grecaptcha) === 'undefined') {

                $.getScript("//www.google.com/recaptcha/api.js?render={{ get_option('recaptcha_v3_site_key', 'captcha') }}&hl={{ app()->getLocale() }}", function (data, textStatus, jqxhr) {
                    window.runRecaptchaV3Attach{{ $js_function_hash }}();
                    window.runRecaptchaV3{{ $js_function_hash }}();
                });
            } else {
                window.runRecaptchaV3Attach{{ $js_function_hash }}();
                window.runRecaptchaV3{{ $js_function_hash }}();
            }
        });
    </script>

    @if (isset($params['_confirm']))
        <h6>@lang('Please confirm form submit')</h6>
    @endif

@endif
