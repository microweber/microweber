@props(['title' => __('Confirm your action'), 'content' => __('For your security, please confirm your action to continue.'), 'button' => __('Confirm')])

<span {{ $attributes->wire('then') }} >
    {{ $slot }}
</span>

@once
    <x-microweber-ui::dialog-modal>
        <x-slot name="title">
            {{ $title }}
        </x-slot>

        <x-slot name="content">
            {{ $content }}
        </x-slot>

        <x-slot name="footer">
            {{ $footer }}
        </x-slot>

    </x-microweber-ui::dialog-modal>
@endonce
