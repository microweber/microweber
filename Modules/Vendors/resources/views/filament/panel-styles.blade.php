{{--
    Soften Filament table dividers in the vendor panel. The Microweber theme's
    default border color is ink and leaks onto Filament's structural table
    borders. Unlayered so it beats Filament's @layer rules. See
    Modules/Profile for the same fix on the profile panel.
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
