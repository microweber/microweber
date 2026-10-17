<?php

namespace MicroweberPackages\Admin\Filament;

class MwColors
{
    /**
     * Same ladder as MicroweberFilamentTheme\Palette::Blue — kept here so
     * existing panel providers and contract tests that reference MwColors::Blue
     * stay valid. The Filament plugin registers Palette::colors() on every panel.
     */
    public const Blue = [
        50  => '231, 241, 255',   // #e7f1ff — pre-100 tint
        100 => '207, 226, 255',   // #cfe2ff — Bootstrap blue-100
        200 => '158, 197, 254',   // #9ec5fe — Bootstrap blue-200
        300 => '110, 168, 254',   // #6ea8fe — Bootstrap blue-300
        400 => '61, 139, 253',    // #3d8bfd — Bootstrap blue-400
        500 => '13, 110, 253',    // #0d6efd — Bootstrap blue-500 = --color-primary
        600 => '10, 88, 202',     // #0a58ca — Bootstrap blue-600
        700 => '8, 66, 152',      // #084298 — Bootstrap blue-700
        800 => '5, 44, 101',      // #052c65 — Bootstrap blue-800
        900 => '3, 22, 51',       // #031633 — Bootstrap blue-900
        950 => '1, 10, 25',       // ~#010a19 — post-900 shade
    ];
}
