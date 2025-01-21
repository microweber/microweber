<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :statePath="$getStatePath()"
    :valid="! $errors->has('captcha')"
>
    <div class="fi-input-wrapper" wire:ignore>
        <div class="flex items-center space-x-4">
            <div class="w-32">
                <module type="captcha" id="captcha-{{ uniqid() }}" data-callback="captchaCallbackProfilePage"/>
            </div>
        </div>
    </div>

    @if ($errors->has('captcha'))
        <p class="fi-fo-field-wrp-error-message text-sm text-danger-600 dark:text-danger-400">
            {{ $errors->first('captcha') }}
        </p>
    @endif


    <script>
        window.captchaCallbackProfilePage = function (value) {
        @this.set('captcha', value)
        }
    </script>

</x-dynamic-component>
