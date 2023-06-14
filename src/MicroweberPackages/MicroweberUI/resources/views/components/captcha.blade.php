<div>
    @php
        $randId = time().rand(1000,9000);
    @endphp
    <module type="captcha" id="js-captcha-module-{{$randId}}" data-callback="captchaResponse{{$randId}}" />
    <input type="hidden" id="js-{{$randId}}" {!! $attributes->merge([]) !!} />
    <script>
        function captchaResponse{{$randId}}(value) {
            let captchaDataElement = document.getElementById('js-{{$randId}}');
            captchaDataElement.value = value;
            captchaDataElement.dispatchEvent(new Event('input'));
        }
    </script>
</div>
