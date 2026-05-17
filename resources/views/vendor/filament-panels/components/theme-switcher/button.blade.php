{{--
  task-2026-05-17-551f7e / AI-773 — admin topbar user-menu theme switcher.
  Jira: https://microweber.atlassian.net/browse/AI-773

  Override of vendor/filament/filament/resources/views/components/
  theme-switcher/button.blade.php. Two designer-flagged defects:

  1. Missing `aria-pressed` on the 3 toggle buttons — screen readers
     hear the button name ("Light", "Dark", "System") but cannot
     announce which one is currently active. Fix: bind aria-pressed
     to the same `theme === @js($theme)` expression that drives the
     `fi-active` visual class. Identical truth condition; one binding
     for sighted + AT users.

  2. Active-theme visual indicator was `oklch(0.985)` ≈ white on the
     light background (invisible). Fix lives in the companion CSS
     rule in `general-styles.css` — gives `.fi-theme-switcher-btn.
     fi-active` a visible accent ring + accent-soft bg. The Blade
     override here is the a11y half; the CSS rule is the visual half.

  The override is otherwise byte-identical to the vendor template
  (props, label generation, click handler, tooltip, x-bind:class,
  icon rendering). Audit-side: when upstream Filament adds new
  capabilities to this button, re-sync this override + verify
  aria-pressed stays bound to the same expression.
--}}
@props([
    'icon',
    'theme',
])

@php
    $label = __("filament-panels::layout.actions.theme_switcher.{$theme}.label");
@endphp

<button
    aria-label="{{ $label }}"
    type="button"
    x-on:click="(theme = @js($theme)) && close()"
    x-bind:aria-pressed="theme === @js($theme) ? 'true' : 'false'"
    x-tooltip="{
        content: @js($label),
        theme: $store.theme,
    }"
    x-bind:class="{ 'fi-active': theme === @js($theme) }"
    class="fi-theme-switcher-btn"
>
    {{
        \Filament\Support\generate_icon_html($icon, alias: match ($theme) {
            'light' => \Filament\View\PanelsIconAlias::THEME_SWITCHER_LIGHT_BUTTON,
            'dark' => \Filament\View\PanelsIconAlias::THEME_SWITCHER_DARK_BUTTON,
            'system' => \Filament\View\PanelsIconAlias::THEME_SWITCHER_SYSTEM_BUTTON,
        })
    }}
</button>
