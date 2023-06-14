<div>
    @php
        $randId = time().rand(1000,9000);
    @endphp
    <module type="captcha" id="js-captcha-module-{{$randId}}" data-callback="captchaResponse{{$randId}}" />
    <input type="text" id="js-{{$randId}}" {!! $attributes->merge([]) !!} />
    <script>
        function captchaResponse{{$randId}}() {
            let captcha = document.getElementsByName("captcha")[0];
            let captchaDataElement = document.getElementById('js-{{$randId}}');
            captchaDataElement.value = captcha.value;
            captchaDataElement.dispatchEvent(new Event('input'));
        }
    </script>
</div>
