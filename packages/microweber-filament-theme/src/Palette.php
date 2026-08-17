<?php

namespace MicroweberPackages\MicroweberFilamentTheme;

use Filament\Support\Colors\Color;

/**
 * Filament color palette for every panel that registers MicroweberFilamentTheme.
 *
 * RGB-triplet shades (not OKLCH) so Tailwind `rgba(var(--primary-500), 1)`
 * utilities resolve. Slot 500 of Blue is Bootstrap #0d6efd (AI-209 / AI-819).
 */
class Palette
{
    /** Brand primary — same ladder as MwColors::Blue. */
    public const Blue = [
        50 => '231, 241, 255',
        100 => '207, 226, 255',
        200 => '158, 197, 254',
        300 => '110, 168, 254',
        400 => '61, 139, 253',
        500 => '13, 110, 253',
        600 => '10, 88, 202',
        700 => '8, 66, 152',
        800 => '5, 44, 101',
        900 => '3, 22, 51',
        950 => '1, 10, 25',
    ];

    /** Neutral gray — same ladder Filament Color::Neutral emits as RGB. */
    public const Gray = [
        50 => '250, 250, 250',
        100 => '245, 245, 245',
        200 => '229, 229, 229',
        300 => '212, 212, 212',
        400 => '163, 163, 163',
        500 => '115, 115, 115',
        600 => '82, 82, 82',
        700 => '64, 64, 64',
        800 => '38, 38, 38',
        900 => '23, 23, 23',
        950 => '10, 10, 10',
    ];

    /** MW v2 ink / secondary (#182433). */
    public const Secondary = [
        50 => '232, 235, 238',
        100 => '209, 215, 221',
        200 => '163, 175, 187',
        300 => '117, 135, 153',
        400 => '71, 95, 119',
        500 => '24, 36, 51',
        600 => '20, 30, 43',
        700 => '16, 24, 34',
        800 => '12, 18, 26',
        900 => '8, 12, 17',
        950 => '4, 6, 9',
    ];

    /**
     * Full palette passed to Panel::colors() and FilamentColor::register().
     *
     * @return array<string, array<int, string>|\Filament\Support\Colors\ColorPalette>
     */
    public static function colors(): array
    {
        return [
            'primary' => self::Blue,
            'gray' => self::Gray,
            'danger' => Color::Rose,
            'info' => Color::Sky,
            'success' => Color::Emerald,
            'warning' => Color::Amber,
            'mw-secondary' => self::Secondary,
            'mw-primary' => self::Blue,
            'mw-accent' => Color::hex('#4299e1'),
            'mw-sky-blue' => Color::hex('#ffbf00'),
            'mw-light-green' => Color::hex('#e2f9e6'),
        ];
    }
}
