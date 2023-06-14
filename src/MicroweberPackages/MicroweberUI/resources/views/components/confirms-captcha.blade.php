@props(['title' => __('Confirm Captcha'), 'content' => __('For your security, please confirm captcha to continue.'), 'button' => __('Confirm')])

@php
    $confirmableId = md5($attributes->wire('then'));
@endphp

<span
    {{ $attributes->wire('then') }}
    x-data
    x-ref="span"
    x-on:click="$wire.startConfirmingCaptcha('{{ $confirmableId }}')"
    x-on:captcha-confirmed.window="setTimeout(() => $event.detail.id === '{{ $confirmableId }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);"
>
    {{ $slot }}
</span>

@once
    <x-dialog-modal wire:model="confirmingCaptcha">
        <x-slot name="title">
            {{ $title }}
        </x-slot>

        <x-slot name="content">
            {{ $content }}

            <div class="mt-4" x-data="{}" x-on:confirming-captcha.window="setTimeout(() => $refs.confirmable_captcha.focus(), 250)">
                <x-input type="text" class="mt-1 block w-3/4" placeholder="{{ __('Password') }}" autocomplete="current-captcha"
                         x-ref="confirmable_captcha"
                         wire:model.defer="confirmableCaptcha"
                         wire:keydown.enter="confirmCaptcha" />

                <x-input-error for="confirmable_captcha" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="stopConfirmingCaptcha" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ml-3" dusk="confirm-captcha-button" wire:click="confirmCaptcha" wire:loading.attr="disabled">
                {{ $button }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
@endonce
