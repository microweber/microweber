{{--
    task-2026-05-31-ico900 — AI-1204: Filament v5 mw-icon-picker clear-button
    accessible-name gap.

    Pre-fix state probed via Playwright at /admin/btn-module-settings:
      The clear-button rendered by the icon-picker (the small button next to
      "Pick icon", visible only when an icon is selected) carried NO aria-label,
      NO title, NO visible text content. It contained only a heroicon-o-x-circle
      SVG via the @svg directive. AT users heard "button" with no indication
      that it removes the chosen icon. WCAG 4.1.2 Level A regression.

    Sister-fix to AI-1199 (Tabs aria-selected), AI-1200 (Toggle aria-checked),
    AI-1201 (Toggle accessible-name), AI-1202 (Section collapse-btn accessible-name),
    AI-1203 (file-upload clear-X accessible-name). Same Filament v5
    native-component accessibility-gap family.

    Fix: inject aria-label + title on the clear button (semantic "Remove icon"
         action) + aria-hidden + focusable=false on the decorative SVG.
         Translated via Microweber __() helper.

    Carries to every mw-icon-picker consumer project-wide (Btn module-settings,
    every Filament resource form with iconPicker() field).
--}}
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
