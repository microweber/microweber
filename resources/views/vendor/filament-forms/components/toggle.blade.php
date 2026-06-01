{{--
    task-2026-05-31-tog812 — AI-1201: Filament v5 native Toggle accessible-name gap.

    Pre-fix state probed via Playwright at /admin/social-links-module-settings:
      Every <button role="switch"> rendered by Filament's forms Toggle wrapper
      carries aria-label="" AND aria-labelledby="" — DOM-confirmed across all
      13 SocialLinks toggles. Filament's field-wrapper renders
      <label for="{{ $id }}" class="fi-fo-field-label"> sibling-to (not parent-of)
      the button, BUT because the button carries role="switch", ARIA name-
      computation discards the <label for=""> association — only aria-label,
      aria-labelledby, text content, and title count as accessible-name sources
      for role-overridden elements. AT users hear "switch, off" with no
      indication of which switch. WCAG 4.1.2 Level A.

    Sister-fix to AI-1200 — same Filament Toggle vendor-override surface
    family. Lives at the filament-forms namespace path because
    Spatie PackageServiceProvider->name('filament-forms') in
    vendor/filament/forms/src/FormsServiceProvider.php:21 registers the view
    namespace as 'filament-forms', NOT 'filament'.

    Fix: inject 'aria-label' => $getLabel() into the attribute bag passed to
         the <x-filament::toggle> support component. The AI-1200 override at
         resources/views/vendor/filament/components/toggle.blade.php then
         emits the aria-label naturally via its $attributes->merge() bag.

    Carries to every Filament v5 Toggle surface project-wide (Pictures
    options.lightbox, Btn options.urlBlank, all 13 SocialLinks toggles,
    every Toggle::make() in the codebase).
--}}
@php
    use Illuminate\View\ComponentAttributeBag;

    $fieldWrapperView = $getFieldWrapperView();
    $statePath = $getStatePath();

    $attributes = (new ComponentAttributeBag)
        ->merge([
            'aria-checked' => 'false',
            'aria-label' => $getLabel(),
            'autofocus' => $isAutofocused(),
            'disabled' => $isDisabled(),
            'id' => $getId(),
            'offColor' => $getOffColor() ?? 'gray',
            'offIcon' => $getOffIcon(),
            'onColor' => $getOnColor() ?? 'primary',
            'onIcon' => $getOnIcon(),
            'state' => '$wire.' . $applyStateBindingModifiers('$entangle(\'' . $statePath . '\')'),
            'wire:loading.attr' => 'disabled',
            'wire:target' => $statePath,
        ], escape: false)
        ->merge($getExtraAttributes(), escape: false)
        ->merge($getExtraAlpineAttributes(), escape: false)
        ->class(['fi-fo-toggle']);
@endphp

<x-dynamic-component
    :component="$fieldWrapperView"
    :field="$field"
    :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center"
>
    @if ($isInline())
        <x-slot name="labelPrefix">
            <x-filament::toggle
                :attributes="\Filament\Support\prepare_inherited_attributes($attributes)"
            />
        </x-slot>
    @else
        <x-filament::toggle
            :attributes="\Filament\Support\prepare_inherited_attributes($attributes)"
        />
    @endif
</x-dynamic-component>
