
@php

    $id = $getId();
    $statePath = $getStatePath();
    $iconSets = $getIconSets();

    $mwIconPickRemoveIconLabel = __('Remove icon');

@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
<div

    x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
        }"

    x-cloak

    wire:ignore
>
    <script>
        addEventListener('DOMContentLoaded', e => {
            let iconLoader = mw.iconLoader();

            @if(!empty($iconSets))
                @foreach($iconSets as $iconSet)
                    iconLoader.addIconSet('{{ $iconSet }}');
                @endforeach
            @endif

        })
    </script>

    <div class="">
        <x-filament::button

            x-on:click="async ()=> {
            const picker = mw.app.iconPicker.pickIcon(document.querySelector('.icon-example'));
            await picker.promise().then((icon) => {
                state = icon.icon;
            });
        }"

            color="gray"

        >
        <span class="icon-example"

              :class="state"

        ></span>
            Pick icon
        </x-filament::button>

        <x-filament::button
            x-show="state"
            x-on:click="state = ''"
            color="gray"
            style="margin-left: 1px;"
            :aria-label="$mwIconPickRemoveIconLabel"
            :title="$mwIconPickRemoveIconLabel"
        >

            @svg('heroicon-o-x-circle', ['class' => 'h-4 w-4', 'aria-hidden' => 'true', 'focusable' => 'false'])

        </x-filament::button>
    </div>

</div>
</x-dynamic-component>
