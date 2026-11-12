
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

    {{-- Register icon sets on Alpine init, not DOMContentLoaded. These fields
         are rendered inside Livewire-loaded modals/slideovers where
         DOMContentLoaded has already fired (and morphdom skips inline
         <script>), so the old handler never ran and the picker opened with an
         empty set list — crashing on "Cannot read properties of undefined
         (reading '_value')". x-init runs whenever Alpine initialises the
         element, including DOM injected after page load. --}}
    x-init="(() => {
            if (!window.mw || !mw.iconLoader) { return; }
            const sets = @js(array_values($getIconSets() ?: []));
            if (!sets.length) { return; }
            const loader = mw.iconLoader();
            sets.forEach((s) => loader.addIconSet(s));
        })()"

    x-cloak

    wire:ignore
>

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
