<?php

namespace MicroweberPackages\Admin\Filament;

/*
 * Custom Filament color palette — kept distinct from Filament's bundled
 * Color::Blue (which maps to Tailwind blue-500 #3b82f6) so the admin
 * + checkout panels paint the SAME blue as the public Bootstrap
 * storefront. AI-209 (task-2026-05-13-e8ebcf): the canonical primary
 * blue is `--color-primary: #0d6efd` (Bootstrap blue-500) defined in
 * design-system.css line 35. Filament panels resolve the primary class
 * via the 500-weight slot below, so anchoring 500 = (13, 110, 253) =
 * #0d6efd makes every Filament primary button (Save, Next, Place
 * Order — when not semantic-success) visually match the public CTAs.
 *
 * The full 50→950 scale matches Bootstrap's blue token ladder; values
 * are RGB triplets so Filament can compose `rgb(var(--primary-500))`
 * + tint operations without re-deriving them.
 */
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
