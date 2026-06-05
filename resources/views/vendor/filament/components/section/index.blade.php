{{--
    task-2026-05-31-sec813 — AI-1202: Filament v5 native Section collapse-button accessible-name gap.

    Pre-fix state probed via Playwright at /admin/contact-form-module-settings:
      Every <button class="fi-icon-btn fi-section-collapse-btn"> rendered by Filament's
      collapsible Section header carries NO aria-label, NO title, NO visible text content.
      Stock upstream template at vendor/filament/support/resources/views/components/
      section/index.blade.php:103-110 instantiates <x-filament::icon-button> WITHOUT the
      label prop. AT users hear "button" with no indication of which section the toggle
      controls. WCAG 4.1.2 Level A regression.

    Sister-fix to AI-1199 (Tabs aria-selected), AI-1200 (Toggle aria-checked), AI-1201
    (Toggle accessible-name) — same Filament v5 vendor-override accessibility-gap
    family. Lives at the filament namespace path because Spatie
    PackageServiceProvider->name('filament') in
    vendor/filament/support/src/SupportServiceProvider.php registers the view namespace
    as 'filament', NOT 'filament-support'.

    Fix: inject :label="..." into the <x-filament::icon-button> invocation. Filament's
    icon-button component (vendor/filament/support/resources/views/components/
    icon-button.blade.php:22, :81) accepts 'label' => null and merges it as
    'aria-label' => $label into the rendered button attribute bag, also setting title
    when no tooltip is present.

    Label shape: when $heading is filled, "Toggle section: <stripped-heading>" so the
    screen-reader user hears which section the toggle controls. When no $heading,
    fallback to "Toggle section" so the button still has a non-empty accessible name.
    State is conveyed separately via aria-expanded on the content region (preserved
    from upstream).

    Carries to every Filament v5 collapsible Section surface project-wide (ContactForm
    module-settings: 3 collapse buttons; Pictures, Slider, Btn; every Filament admin
    resource form with Section::make()->collapsible()).
--}}
@php
    use Filament\Support\Enums\Alignment;
    use Filament\Support\Enums\IconSize;
    use Filament\Support\View\Components\SectionComponent\IconComponent;

    use function Filament\Support\is_slot_empty;
@endphp

@props([
    'afterHeader' => null,
    'aside' => false,
    'collapsed' => false,
    'collapseId' => null,
    'collapsible' => false,
    'compact' => false,
    'contained' => true,
    'contentBefore' => false,
    'description' => null,
    'divided' => false,
    'footer' => null,
    'hasContentEl' => true,
    'heading' => null,
    'headingTag' => 'h2',
    'icon' => null,
    'iconColor' => 'gray',
    'iconSize' => null,
    'persistCollapsed' => false,
    'secondary' => false,
])

@php
    if (filled($iconSize) && (! $iconSize instanceof IconSize)) {
        $iconSize = IconSize::tryFrom($iconSize) ?? $iconSize;
    }

    $hasDescription = filled((string) $description);
    $hasHeading = filled($heading);
    $hasIcon = filled($icon);
    $hasHeader = $hasIcon || $hasHeading || $hasDescription || $collapsible || (! is_slot_empty($afterHeader));

    $mwSectionCollapseLabel = $hasHeading
        ? 'Toggle section: ' . trim(strip_tags((string) $heading))
        : 'Toggle section';
@endphp

<section
    {{-- Investigate Livewire bug - upstream link omitted to avoid parser-meaningful-character collision --}}
    x-data="{
        isCollapsed: @if ($persistCollapsed) $persist(@js($collapsed)).as(`section-${@js($collapseId) ?? $el.id}-isCollapsed`) @else @js($collapsed) @endif,
    }"
    @if ($collapsible)
        x-on:collapse-section.window="if ($event.detail.id == @js($collapseId) ?? $el.id) isCollapsed = true"
        x-on:expand="isCollapsed = false"
        x-on:expand-section.window="if ($event.detail.id == @js($collapseId) ?? $el.id) isCollapsed = false"
        x-on:open-section.window="if ($event.detail.id == @js($collapseId) ?? $el.id) isCollapsed = false"
        x-on:toggle-section.window="if ($event.detail.id == @js($collapseId) ?? $el.id) isCollapsed = ! isCollapsed"
        x-bind:class="isCollapsed && 'fi-collapsed'"
    @endif
    {{
        $attributes->class([
            'fi-section',
            'fi-section-not-contained' => ! $contained,
            'fi-section-has-content-before' => $contentBefore,
            'fi-section-has-header' => $hasHeader,
            'fi-aside' => $aside,
            'fi-compact' => $compact,
            'fi-collapsible' => $collapsible,
            'fi-divided' => $divided,
            'fi-secondary' => $secondary,
        ])
    }}
>
    @if ($hasHeader)
        <header
            @if ($collapsible)
                x-on:click="isCollapsed = ! isCollapsed"
            @endif
            class="fi-section-header"
        >
            {{
                \Filament\Support\generate_icon_html($icon, attributes: (new \Illuminate\View\ComponentAttributeBag)
                    ->color(IconComponent::class, $iconColor), size: $iconSize ?? IconSize::Large)
            }}

            @if ($hasHeading || $hasDescription)
                <div class="fi-section-header-text-ctn">
                    @if ($hasHeading)
                        <{{ $headingTag }} class="fi-section-header-heading">
                            {{ $heading }}
                        </{{ $headingTag }}>
                    @endif

                    @if ($hasDescription)
                        <p class="fi-section-header-description">
                            {{ $description }}
                        </p>
                    @endif
                </div>
            @endif

            @if (! is_slot_empty($afterHeader))
                <div x-on:click.stop class="fi-section-header-after-ctn">
                    {{ $afterHeader }}
                </div>
            @endif

            @if ($collapsible)
                <x-filament::icon-button
                    color="gray"
                    :icon="\Filament\Support\Icons\Heroicon::ChevronUp"
                    :icon-alias="\Filament\Support\View\SupportIconAlias::SECTION_COLLAPSE_BUTTON"
                    :label="$mwSectionCollapseLabel"
                    x-on:click.stop="isCollapsed = ! isCollapsed"
                    class="fi-section-collapse-btn"
                />
            @endif
        </header>
    @endif

    @if ((! is_slot_empty($slot)) || (! is_slot_empty($footer)))
        <div
            @if ($collapsible)
                x-bind:aria-expanded="(! isCollapsed).toString()"
                @if ($collapsed || $persistCollapsed)
                    x-cloak
                @endif
            @endif
            class="fi-section-content-ctn"
        >
            @if ($hasContentEl)
                <div class="fi-section-content">
                    {{ $slot }}
                </div>
            @else
                {{ $slot }}
            @endif

            @if (! is_slot_empty($footer))
                <footer class="fi-section-footer">
                    {{ $footer }}
                </footer>
            @endif
        </div>
    @endif
</section>
