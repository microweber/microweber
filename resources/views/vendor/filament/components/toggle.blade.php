{{--
    task-2026-05-31-tog811 — AI-1200: Filament v5 native Toggle aria-checked initial-state gap.

    Pre-fix state probed via Playwright at /admin/pictures-module-settings (Enable Lightbox
    toggle, sibling Toggle::make('options.lightbox') in Modules/Pictures/Filament/...):

      Stock vendor x-filament::toggle (vendor/filament/support/resources/views/components/
      toggle.blade.php:16) binds aria-checked via:

          x-bind:aria-checked="state?.toString()"

      The forms wrapper at vendor/filament/forms/resources/views/components/toggle.blade.php:9
      DOES merge a static aria-checked => 'false' default, but Alpine overrides that static
      attribute at init with the entangled-state expression. When $wire.$entangle('options.X')
      resolves undefined (record has no saved value), state?.toString() returns undefined and
      Alpine writes EMPTY STRING to aria-checked. ARIA 1.2 requires role="switch" carry
      aria-checked="true|false|mixed" — empty string is invalid, AT cannot announce the
      switch's state. WCAG 4.1.2 Level A regression.

      Toggle ON click → state = true → 'true' string ✓
      Toggle OFF click → state = false → 'false' string ✓
      Initial load with no saved value → state = undefined → '' ❌

    Fix: project-side vendor override at resources/views/vendor/filament/components/
         toggle.blade.php — coerce falsy (undefined/null/false) to literal 'false' string,
         truthy to literal 'true' string, eliminating the undefined?.toString() empty-write.

         x-bind:aria-checked="state ? 'true' : 'false'"

    Namespace path note: Spatie PackageServiceProvider->name('filament') at
    vendor/filament/support/src/SupportServiceProvider.php:51 registers the view namespace
    as 'filament' (NOT 'filament-support'), so an override at vendor/filament-support/
    silently does NOT load. This file lives at vendor/filament/ per the AI-1199 lesson.

    Carries to every Filament v5 Toggle surface project-wide (Btn options.urlBlank,
    Pictures options.lightbox, every Filament Toggle::make() in the codebase).
--}}
@php
    use Filament\Support\View\Components\ToggleComponent;
    use Illuminate\Support\Arr;
@endphp

@props([
    'state',
    'offColor' => 'gray',
    'offIcon' => null,
    'onColor' => 'primary',
    'onIcon' => null,
])

<button
    x-data="{ state: {{ $state }} }"
    x-bind:aria-checked="state ? 'true' : 'false'"
    x-on:click="state = ! state"
    x-bind:class="
        state ? @js(Arr::toCssClasses([
                    'fi-toggle-on',
                    ...\Filament\Support\get_component_color_classes(ToggleComponent::class, $onColor),
                ])) : @js(Arr::toCssClasses([
                            'fi-toggle-off',
                            ...\Filament\Support\get_component_color_classes(ToggleComponent::class, $offColor),
                        ]))
    "
    @if ($state)
        x-cloak
    @endif
    {{
        $attributes
            ->merge([
                'role' => 'switch',
                'type' => 'button',
            ], escape: false)
            ->class(['fi-toggle'])
    }}
>
    <div>
        <div aria-hidden="true">
            {{ \Filament\Support\generate_icon_html($offIcon, size: \Filament\Support\Enums\IconSize::ExtraSmall) }}
        </div>

        <div aria-hidden="true">
            {{ \Filament\Support\generate_icon_html($onIcon, size: \Filament\Support\Enums\IconSize::ExtraSmall) }}
        </div>
    </div>
</button>

@if ($state)
    <div
        x-cloak="inline-flex"
        wire:ignore
        @class([
            'fi-toggle fi-toggle-on fi-hidden',
            ...\Filament\Support\get_component_color_classes(ToggleComponent::class, $onColor),
        ])
    >
        <div>
            <div aria-hidden="true"></div>

            <div aria-hidden="true">
                {{ \Filament\Support\generate_icon_html($onIcon, size: \Filament\Support\Enums\IconSize::ExtraSmall) }}
            </div>
        </div>
    </div>
@endif
