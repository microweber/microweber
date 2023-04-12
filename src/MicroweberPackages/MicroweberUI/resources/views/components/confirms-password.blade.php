@props(['title' => __('Confirm Password'), 'content' => __('For your security, please confirm your password to continue.'), 'button' => __('Confirm')])

@php
    $confirmableId = md5($attributes->wire('then'));
@endphp

<span
    {{ $attributes->wire('then') }}
    x-data
    x-ref="span"
    x-on:click="$wire.startConfirmingPassword('{{ $confirmableId }}')"
    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ $confirmableId }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);"
>
    {{ $slot }}
</span>

@once
<x-mw-ui::dialog-modal wire:model="confirmingPassword">
    <x-slot name="title">
        {{ $title }}
    </x-slot>

    <x-slot name="content">
        {{ $content }}

        <div class="mt-4" x-data="{}" x-on:confirming-password.window="setTimeout(() => $refs.confirmable_password.focus(), 250)">
            <x-mw-ui::input type="password" class="{{ $errors->has('confirmable_password') ? 'is-invalid' : '' }}" placeholder="{{ __('Password') }}"
                         x-ref="confirmable_password"
                         wire:model.defer="confirmablePassword"
                         wire:keydown.enter="confirmPassword" />

            <x-mw-ui::input-error for="confirmable_password" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-mw-ui::secondary-button wire:click="stopConfirmingPassword" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-mw-ui::secondary-button>

        <x-mw-ui::button class="ms-2" wire:click="confirmPassword" wire:loading.attr="disabled">
            <div wire:loading wire:target="confirmPassword" class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>

            {{ $button }}
        </x-mw-ui::button>
    </x-slot>
</x-mw-ui::dialog-modal>
@endonce
