<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Structural contract tests for the 17 Bootstrap color-pack JSON files
 * under `Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/`.
 *
 * These packs are consumed by the Vue live-edit picker at runtime via
 * `setPropertyForSelectorBulk(':root', <properties>, ...)`. If a pack's
 * shape drifts (wrong fieldType, missing `:root` in selectors, empty
 * properties map, non-assoc array, etc.) the picker silently fails to
 * apply the palette and the previous palette bleeds through. These
 * tests fail fast in unit CI so a malformed pack can't reach the
 * browser-level Dusk suite.
 *
 * Drives the same enumeration the Dusk trait uses
 * ({@see \Tests\Browser\Traits\LiveEditColorPaletteTrait::listColorPalettes()})
 * so both layers see the same on-disk set.
 */
class ColorPaletteFilesTest extends TestCase
{
    /**
     * Directory (repo-relative) holding the color pack JSONs.
     */
    private const COLOR_PACK_DIR = 'Templates/Bootstrap/resources/assets/design-styles/style-packs/colors';

    /**
     * Shipped slugs. Kept inline (not discovered from disk) so that a
     * pack being accidentally deleted causes {@see test_seventeen_shipped_color_packs_are_present_on_disk()}
     * to fail rather than the suite silently shrinking with the repo.
     *
     * @var string[]
     */
    private const EXPECTED_SLUGS = [
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

    private static function repoRoot(): string
    {
        return dirname(__DIR__, 3);
    }

    private static function packPath(string $slug): string
    {
        return self::repoRoot() . '/' . self::COLOR_PACK_DIR . '/' . $slug . '.json';
    }

    public static function colorPackProvider(): array
    {
        $out = [];
        foreach (self::EXPECTED_SLUGS as $slug) {
            $out[$slug] = [$slug];
        }
        return $out;
    }

    #[Test]
    public function seventeen_shipped_color_packs_are_present_on_disk(): void
    {
        $dir = self::repoRoot() . '/' . self::COLOR_PACK_DIR;
        $this->assertDirectoryExists(
            $dir,
            "Color-pack directory missing: {$dir}"
        );

        $found = array_map(
            static fn (string $path): string => pathinfo($path, PATHINFO_FILENAME),
            glob($dir . '/*.json') ?: []
        );
        sort($found);

        $expected = self::EXPECTED_SLUGS;
        sort($expected);

        $this->assertSame(
            $expected,
            $found,
            'Shipped color-pack set drifted from the expected 17 slugs'
        );
    }

    #[Test]
    #[DataProvider('colorPackProvider')]
    public function pack_file_exists_and_is_readable(string $slug): void
    {
        $path = self::packPath($slug);

        $this->assertFileExists($path, "Pack '{$slug}' is missing on disk: {$path}");
        $this->assertIsReadable($path, "Pack '{$slug}' is not readable: {$path}");
        $this->assertGreaterThan(
            0,
            (int)@filesize($path),
            "Pack '{$slug}' must not be empty"
        );
    }

    #[Test]
    #[DataProvider('colorPackProvider')]
    public function pack_file_parses_as_valid_json_object(string $slug): void
    {
        $raw = file_get_contents(self::packPath($slug));
        $this->assertNotFalse($raw, "Pack '{$slug}' could not be read");

        $data = json_decode($raw, true);

        $this->assertSame(
            JSON_ERROR_NONE,
            json_last_error(),
            "Pack '{$slug}' is not valid JSON: " . json_last_error_msg()
        );
        $this->assertIsArray($data, "Pack '{$slug}' must decode to an array");
        $this->assertArrayHasKey('settings', $data, "Pack '{$slug}' must have a 'settings' key");
        $this->assertIsArray($data['settings'], "Pack '{$slug}' 'settings' must be an array");
        $this->assertArrayHasKey(0, $data['settings'], "Pack '{$slug}' 'settings' must have index 0");
    }

    #[Test]
    #[DataProvider('colorPackProvider')]
    public function pack_settings_declare_style_pack_field_type(string $slug): void
    {
        $settings = $this->readSettingsZero($slug);

        $this->assertArrayHasKey(
            'fieldType',
            $settings,
            "Pack '{$slug}' settings[0] must declare a fieldType"
        );
        $this->assertSame(
            'stylePack',
            $settings['fieldType'],
            "Pack '{$slug}' settings[0].fieldType must be 'stylePack'"
        );
    }

    #[Test]
    #[DataProvider('colorPackProvider')]
    public function pack_selectors_contain_root(string $slug): void
    {
        $settings = $this->readSettingsZero($slug);

        $this->assertArrayHasKey(
            'selectors',
            $settings,
            "Pack '{$slug}' settings[0] must declare selectors"
        );
        $this->assertIsArray(
            $settings['selectors'],
            "Pack '{$slug}' settings[0].selectors must be a list"
        );
        $this->assertContains(
            ':root',
            $settings['selectors'],
            "Pack '{$slug}' selectors must contain ':root' so variables land on the document root"
        );
    }

    #[Test]
    #[DataProvider('colorPackProvider')]
    public function pack_style_properties_are_non_empty_assoc_array(string $slug): void
    {
        $settings = $this->readSettingsZero($slug);

        $this->assertArrayHasKey(
            'fieldSettings',
            $settings,
            "Pack '{$slug}' settings[0] must declare fieldSettings"
        );
        $this->assertIsArray(
            $settings['fieldSettings'],
            "Pack '{$slug}' fieldSettings must be an array"
        );

        $this->assertArrayHasKey(
            'styleProperties',
            $settings['fieldSettings'],
            "Pack '{$slug}' fieldSettings must declare styleProperties"
        );
        $styleProperties = $settings['fieldSettings']['styleProperties'];

        $this->assertIsArray($styleProperties, "Pack '{$slug}' styleProperties must be a list");
        $this->assertArrayHasKey(0, $styleProperties, "Pack '{$slug}' styleProperties must have index 0");

        $this->assertArrayHasKey(
            'properties',
            $styleProperties[0],
            "Pack '{$slug}' styleProperties[0] must have a 'properties' key"
        );

        $properties = $styleProperties[0]['properties'];

        $this->assertIsArray(
            $properties,
            "Pack '{$slug}' styleProperties[0].properties must be an array"
        );
        $this->assertNotEmpty(
            $properties,
            "Pack '{$slug}' styleProperties[0].properties must not be empty"
        );

        // "assoc array" — every key must be a string (not a sequential int).
        foreach (array_keys($properties) as $key) {
            $this->assertIsString(
                $key,
                "Pack '{$slug}' properties map must be string-keyed, got int key '{$key}'"
            );
            $this->assertNotSame(
                '',
                trim($key),
                "Pack '{$slug}' has an empty string key in properties"
            );
        }
    }

    /**
     * Regression guard: the picker applies the whole `properties` map
     * as CSS custom properties. Anything not prefixed with `--` would
     * be swallowed by `setPropertyForSelectorBulk` without visible
     * effect — catch those at unit-test time, not by squinting at a
     * Dusk screenshot.
     */
    #[Test]
    #[DataProvider('colorPackProvider')]
    public function pack_property_keys_are_all_css_custom_properties(string $slug): void
    {
        $properties = $this->readSettingsZero($slug)['fieldSettings']['styleProperties'][0]['properties'];

        foreach (array_keys($properties) as $prop) {
            $this->assertStringStartsWith(
                '--',
                (string)$prop,
                "Pack '{$slug}' defines non---* property '{$prop}' — all CSS custom properties must begin with '--'"
            );
        }
    }

    /**
     * Load settings[0] for a given slug. Returns an array (never null)
     * or fails the current test with a descriptive message.
     */
    private function readSettingsZero(string $slug): array
    {
        $raw = file_get_contents(self::packPath($slug));
        $this->assertNotFalse($raw, "Pack '{$slug}' could not be read for settings[0]");

        $data = json_decode($raw, true);
        $this->assertIsArray($data, "Pack '{$slug}' did not decode to an array");
        $this->assertArrayHasKey('settings', $data);
        $this->assertArrayHasKey(0, $data['settings']);

        $settings = $data['settings'][0];
        $this->assertIsArray($settings, "Pack '{$slug}' settings[0] must be an array");

        return $settings;
    }
}
