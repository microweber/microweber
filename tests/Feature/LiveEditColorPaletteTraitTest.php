<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\LiveEditColorPaletteTrait;
use Tests\TestCase;

/**
 * Non-browser coverage of {@see LiveEditColorPaletteTrait} — the
 * palette enumerator and the CSS-color normalizer. The Dusk-facing
 * methods (openColorPaletteSidebar / clickPalette / saveLiveEdit /
 * snapshotRootCssVars / assertPaletteApplied) are exercised by the
 * per-palette Dusk tests under Phase 3 of the Color Scheme Coverage
 * Plan in TODO.md.
 */
class LiveEditColorPaletteTraitTest extends TestCase
{
    private object $harness;

    protected function setUp(): void
    {
        parent::setUp();

        $this->harness = new class {
            use LiveEditColorPaletteTrait;

            public function palettes(): array
            {
                return $this->listColorPalettes();
            }

            public function normalize(string $value): string
            {
                return $this->normalizeCssColor($value);
            }
        };
    }

    #[Test]
    public function list_color_palettes_returns_all_seventeen_bootstrap_packs(): void
    {
        $packs = $this->harness->palettes();

        $this->assertCount(17, $packs, 'Bootstrap ships with 17 style-pack color palettes');

        $slugs = array_column($packs, 'slug');
        $expected = [
            'apple-shine',
            'arctic-frost',
            'blueberry-pie',
            'citrus-splash',
            'coral-pop',
            'cyber-mint',
            'forest-haze',
            'golden-hour',
            'lavender-fields',
            'midnight-indigo',
            'minty-fresh',
            'neon-night',
            'pastel-dream',
            'robocop',
            'sakura-bloom',
            'sunset-boulevard',
            'urban-concrete',
        ];

        $this->assertSame($expected, $slugs, 'Palette slugs must be sorted alphabetically and match the shipped set');
    }

    #[Test]
    public function each_palette_exposes_slug_title_and_non_empty_properties_map(): void
    {
        $packs = $this->harness->palettes();

        foreach ($packs as $pack) {
            $this->assertArrayHasKey('slug', $pack);
            $this->assertArrayHasKey('title', $pack);
            $this->assertArrayHasKey('properties', $pack);

            $this->assertIsString($pack['slug']);
            $this->assertNotSame('', $pack['slug']);

            $this->assertIsString($pack['title']);
            $this->assertNotSame('', $pack['title']);

            $this->assertIsArray($pack['properties']);
            $this->assertNotEmpty(
                $pack['properties'],
                "Pack '{$pack['slug']}' must define a non-empty properties map"
            );

            foreach ($pack['properties'] as $prop => $value) {
                $this->assertStringStartsWith(
                    '--mw-',
                    $prop,
                    "Pack '{$pack['slug']}' defines non---mw-* property '{$prop}'"
                );
                $this->assertIsString($value);
                $this->assertNotSame(
                    '',
                    trim((string)$value),
                    "Pack '{$pack['slug']}' property '{$prop}' must have a non-empty value"
                );
            }
        }
    }

    #[Test]
    public function every_palette_defines_the_core_variable_set(): void
    {
        // These six vars are consumed by every Bootstrap skin and must
        // never be missing from a pack — otherwise the previous palette's
        // value bleeds through when a user picks this one.
        $core = [
            '--mw-background-color',
            '--mw-primary-color',
            '--mw-body-color',
            '--mw-heading-color',
            '--mw-paragraph-color',
            '--mw-link-color',
        ];

        foreach ($this->harness->palettes() as $pack) {
            foreach ($core as $var) {
                $this->assertArrayHasKey(
                    $var,
                    $pack['properties'],
                    "Pack '{$pack['slug']}' is missing core variable '{$var}'"
                );
            }
        }
    }

    #[Test]
    public function normalize_css_color_folds_hex_and_rgb_to_the_same_tuple(): void
    {
        $this->assertSame('rgb(255, 255, 255)', $this->harness->normalize('#ffffff'));
        $this->assertSame('rgb(255, 255, 255)', $this->harness->normalize('#FFFFFF'));
        $this->assertSame('rgb(255, 255, 255)', $this->harness->normalize('#fff'));
        $this->assertSame('rgb(255, 255, 255)', $this->harness->normalize('rgb(255,255,255)'));
        $this->assertSame('rgb(255, 255, 255)', $this->harness->normalize('rgb(255, 255, 255)'));
        $this->assertSame('rgb(52, 152, 219)', $this->harness->normalize('#3498db'));
        $this->assertSame('rgb(52, 152, 219)', $this->harness->normalize('rgb(52, 152, 219)'));
    }

    #[Test]
    public function normalize_css_color_preserves_rgba_alpha_channel(): void
    {
        $this->assertSame('rgba(0, 0, 0, 0.5)', $this->harness->normalize('rgba(0,0,0,0.5)'));
        $this->assertSame('rgba(255, 255, 255, 1)', $this->harness->normalize('rgba(255, 255, 255, 1)'));
    }

    #[Test]
    public function normalize_css_color_collapses_whitespace_for_non_color_values(): void
    {
        $this->assertSame('var(--mw-primary-color)', $this->harness->normalize('var(--mw-primary-color)'));
        $this->assertSame('red', $this->harness->normalize('RED'));
        $this->assertSame('1px solid black', $this->harness->normalize('  1px   solid   black  '));
        $this->assertSame('', $this->harness->normalize(''));
    }
}
