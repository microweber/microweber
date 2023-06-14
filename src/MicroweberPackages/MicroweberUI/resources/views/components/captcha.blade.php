<div>
    <module type="captcha" data-callback="captchaResponse" />
    <input type="hidden" id="js-captcha-data-mw-ui" {!! $attributes->merge([]) !!} />
    <script>
        function captchaResponse() {
            let captcha = document.getElementsByName("captcha")[0];
            let captchaDataElement = document.getElementById('js-captcha-data-mw-ui');
            captchaDataElement.value = captcha.value;
            captchaDataElement.dispatchEvent(new Event('input'));
        }
    </script>
</div>
