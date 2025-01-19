<x-dynamic-component
    :component="$getFieldWrapperView()"

>
    <div class="fi-input-wrapper">
        <div class="flex items-center space-x-4">
            <div class="w-32">
                <module type="captcha" id="captcha-{{ uniqid() }}" data-callback="captchaCallback"/>
            </div>
        </div>
    </div>

    @script
    <script>
        window.captchaCallback = function (value) {
        @this.set('captcha', value)
        }
    </script>
    @endscript
</x-dynamic-component>
