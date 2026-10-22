{{--
    Soften Filament table dividers in the profile panel.

    The Microweber theme's default border color is ink (#182433 / rgb(24,36,51)),
    and it leaks onto Filament's structural table borders (header toolbar, column
    header row, pagination), drawing heavy near-black horizontal lines. Filament
    scopes those borders inside @layer, so this override is intentionally
    UNLAYERED (a plain <style> in <head>) — unlayered CSS beats any @layer rule
    regardless of specificity, which a layered/!important theme rule cannot.
--}}
<style>
    .fi-ta thead,
    .fi-ta-header-ctn,
    .fi-ta-header-toolbar,
    .fi-pagination,
    .fi-input-wrp-prefix,
    .fi-input-wrp-suffix {
        border-color: rgba(17, 24, 39, 0.08) !important;
    }

    :is(.dark) .fi-ta thead,
    :is(.dark) .fi-ta-header-ctn,
    :is(.dark) .fi-ta-header-toolbar,
    :is(.dark) .fi-pagination,
    :is(.dark) .fi-input-wrp-prefix,
    :is(.dark) .fi-input-wrp-suffix {
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
</style>
