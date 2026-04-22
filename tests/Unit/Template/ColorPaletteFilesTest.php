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
     * Core CSS custom properties every skin reads. If any of these is
     * missing from a pack, the previous palette's value bleeds through
     * when a user picks this pack — the top-level bug that motivates
     * this whole test file.
     *
     * Kept as a private constant instead of a provider so the failure
     * message can list every missing variable for a single pack at once
     * instead of producing one failing data-set per missing key.
     *
     * @var string[]
     */
    private const CORE_VARIABLES = [
        '--mw-background-color',
        '--mw-primary-color',
        '--mw-body-color',
        '--mw-heading-color',
        '--mw-paragraph-color',
        '--mw-link-color',
    ];

    #[Test]
    #[DataProvider('colorPackProvider')]
    public function pack_declares_every_core_css_variable(string $slug): void
    {
        $properties = $this->readSettingsZero($slug)['fieldSettings']['styleProperties'][0]['properties'];

        $missing = [];
        foreach (self::CORE_VARIABLES as $var) {
            if (!array_key_exists($var, $properties)) {
                $missing[] = $var;
                continue;
            }
            $value = $properties[$var];
            if (!is_string($value) || trim($value) === '') {
                $missing[] = $var . ' (empty value)';
            }
        }

        $this->assertSame(
            [],
            $missing,
            sprintf(
                "Pack '%s' is missing core CSS variables: %s. A palette that omits one of these leaves the previous palette's value bleeding through when the picker applies it.",
                $slug,
                implode(', ', $missing)
            )
        );
    }

    #[Test]
    public function core_variable_list_is_stable_and_non_empty(): void
    {
        // Lock the contract: changing the core variable list is a
        // breaking change that must show up as a deliberate edit in
        // this test file, not an accidental drift.
        $this->assertSame(
            [
                '--mw-background-color',
                '--mw-primary-color',
                '--mw-body-color',
                '--mw-heading-color',
                '--mw-paragraph-color',
                '--mw-link-color',
            ],
            self::CORE_VARIABLES,
            'Core CSS variable list must stay in the documented order and size'
        );
        $this->assertCount(6, self::CORE_VARIABLES);
    }

    /**
     * Every value in every pack must parse as a valid CSS paint value:
     * a color literal (hex / rgb(a) / hsl(a) / named color) or, for
     * the decorative `*-background-color` properties only, a CSS
     * `<image>` expression (currently only `linear-gradient(...)` is
     * used — golden-hour and robocop). Anything else (typos, trailing
     * whitespace, accidental "red orange", an unquoted url) indicates
     * drift and fails with the offending `pack:property` so the exact
     * culprit is pinpointable from the CI log without grepping JSON.
     */
    #[Test]
    #[DataProvider('colorPackProvider')]
    public function pack_property_values_parse_as_valid_css_color(string $slug): void
    {
        $properties = $this->readSettingsZero($slug)['fieldSettings']['styleProperties'][0]['properties'];

        $offenders = [];
        foreach ($properties as $prop => $value) {
            if (!is_string($value)) {
                $offenders[] = sprintf("%s:%s (non-string value)", $slug, $prop);
                continue;
            }
            $trimmed = trim($value);
            if ($trimmed === '') {
                $offenders[] = sprintf("%s:%s (empty value)", $slug, $prop);
                continue;
            }
            // Preserve untrimmed-ness as its own failure: a sneaky
            // trailing space is a real bug (the CSS parser would keep
            // it, but JSON diffs won't notice).
            if ($trimmed !== $value) {
                $offenders[] = sprintf("%s:%s (leading/trailing whitespace in value)", $slug, $prop);
                continue;
            }

            if (!self::isValidCssPaintValue($trimmed, (string)$prop)) {
                $offenders[] = sprintf("%s:%s (unrecognized value: %s)", $slug, $prop, $value);
            }
        }

        $this->assertSame(
            [],
            $offenders,
            sprintf(
                "Pack '%s' contains properties whose values did not parse as valid CSS paint tokens:\n  - %s",
                $slug,
                implode("\n  - ", $offenders)
            )
        );
    }

    /**
     * Return true if $value is accepted as a CSS paint value for the
     * property $prop. Gradients are only accepted on the property
     * names that semantically paint a fill ("*-background-color");
     * properties like `--mw-heading-color` must be a true color.
     */
    private static function isValidCssPaintValue(string $value, string $prop): bool
    {
        if (self::isValidCssColor($value)) {
            return true;
        }

        // Gradients and url() images are only meaningful for
        // background-type tokens — a `--mw-link-color: linear-gradient(...)`
        // would still be an error.
        if (self::allowsImagePaint($prop) && self::isCssImageFunction($value)) {
            return true;
        }

        return false;
    }

    private static function allowsImagePaint(string $prop): bool
    {
        return (bool)preg_match('/background(-hover)?-color$/', $prop);
    }

    private static function isCssImageFunction(string $value): bool
    {
        $lower = strtolower($value);
        foreach (['linear-gradient(', 'radial-gradient(', 'conic-gradient(',
                 'repeating-linear-gradient(', 'repeating-radial-gradient(',
                 'repeating-conic-gradient(', 'url('] as $prefix) {
            if (str_starts_with($lower, $prefix) && str_ends_with($lower, ')')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Literal CSS `<color>` check. Accepts:
     *   - #rgb, #rgba, #rrggbb, #rrggbbaa
     *   - rgb(r, g, b) / rgba(r, g, b, a) — legacy comma syntax
     *   - rgb(r g b / a)                  — modern slash syntax
     *   - hsl(h, s%, l%) / hsla(h, s%, l%, a)
     *   - hsl(h s% l% / a)
     *   - CSS named color (see NAMED_COLORS)
     *   - transparent / currentColor / inherit / initial / unset / revert
     *   - var(--…)                        — since packs that chain
     *     vars are still applying to `:root` via the cssEditor call,
     *     this is indirect but legal.
     */
    public static function isValidCssColor(string $raw): bool
    {
        $v = strtolower(trim($raw));
        if ($v === '') {
            return false;
        }

        if (in_array($v, ['transparent', 'currentcolor', 'inherit', 'initial', 'unset', 'revert', 'none'], true)) {
            return true;
        }

        if (preg_match('/^#([0-9a-f]{3}|[0-9a-f]{4}|[0-9a-f]{6}|[0-9a-f]{8})$/', $v) === 1) {
            return true;
        }

        if (preg_match('/^rgba?\(\s*[^)]+\s*\)$/', $v) === 1) {
            return true;
        }

        if (preg_match('/^hsla?\(\s*[^)]+\s*\)$/', $v) === 1) {
            return true;
        }

        if (preg_match('/^var\(\s*--[a-z0-9_-]+\s*(?:,\s*[^)]+\s*)?\)$/', $v) === 1) {
            return true;
        }

        if (in_array($v, self::NAMED_COLORS, true)) {
            return true;
        }

        return false;
    }

    /**
     * CSS4 named color keywords. Covers the 148 standard names.
     * (system colors like `Canvas` and `CanvasText` are intentionally
     * omitted — packs should ship concrete paints, not UA-themed ones.)
     *
     * @var string[]
     */
    private const NAMED_COLORS = [
        'aliceblue','antiquewhite','aqua','aquamarine','azure','beige','bisque','black','blanchedalmond','blue',
        'blueviolet','brown','burlywood','cadetblue','chartreuse','chocolate','coral','cornflowerblue','cornsilk',
        'crimson','cyan','darkblue','darkcyan','darkgoldenrod','darkgray','darkgreen','darkgrey','darkkhaki',
        'darkmagenta','darkolivegreen','darkorange','darkorchid','darkred','darksalmon','darkseagreen','darkslateblue',
        'darkslategray','darkslategrey','darkturquoise','darkviolet','deeppink','deepskyblue','dimgray','dimgrey',
        'dodgerblue','firebrick','floralwhite','forestgreen','fuchsia','gainsboro','ghostwhite','gold','goldenrod',
        'gray','green','greenyellow','grey','honeydew','hotpink','indianred','indigo','ivory','khaki','lavender',
        'lavenderblush','lawngreen','lemonchiffon','lightblue','lightcoral','lightcyan','lightgoldenrodyellow',
        'lightgray','lightgreen','lightgrey','lightpink','lightsalmon','lightseagreen','lightskyblue','lightslategray',
        'lightslategrey','lightsteelblue','lightyellow','lime','limegreen','linen','magenta','maroon',
        'mediumaquamarine','mediumblue','mediumorchid','mediumpurple','mediumseagreen','mediumslateblue',
        'mediumspringgreen','mediumturquoise','mediumvioletred','midnightblue','mintcream','mistyrose','moccasin',
        'navajowhite','navy','oldlace','olive','olivedrab','orange','orangered','orchid','palegoldenrod','palegreen',
        'paleturquoise','palevioletred','papayawhip','peachpuff','peru','pink','plum','powderblue','purple',
        'rebeccapurple','red','rosybrown','royalblue','saddlebrown','salmon','sandybrown','seagreen','seashell',
        'sienna','silver','skyblue','slateblue','slategray','slategrey','snow','springgreen','steelblue','tan',
        'teal','thistle','tomato','turquoise','violet','wheat','white','whitesmoke','yellow','yellowgreen',
    ];

    /**
     * Self-test: lock in the accepted/rejected CSS color formats so
     * the validator itself can't silently broaden or narrow over time.
     */
    #[Test]
    public function css_color_validator_accepts_standard_forms_and_rejects_drift(): void
    {
        $accepted = [
            '#fff', '#FFFFFF', '#abcd', '#aabbccdd',
            'rgb(255, 255, 255)', 'rgb(255 255 255 / 0.5)',
            'rgba(0,0,0,0.5)', 'rgba(255, 255, 255, 1)',
            'hsl(120, 100%, 50%)', 'hsla(0, 0%, 0%, 0.5)',
            'hsl(120 100% 50% / 0.5)',
            'transparent', 'currentColor', 'inherit', 'initial', 'unset', 'revert', 'none',
            'red', 'rebeccapurple', 'whitesmoke',
            'var(--mw-primary-color)',
            'var(--mw-primary-color, #fff)',
        ];
        foreach ($accepted as $v) {
            $this->assertTrue(
                self::isValidCssColor($v),
                "Validator must accept '{$v}' as a valid CSS color"
            );
        }

        $rejected = [
            '',
            '#',
            '#ff',
            '#gggggg',
            'notacolor',
            'red orange',
            '123456',
            'rgb',
            'rgb()',
            'url(foo.png)',
            'linear-gradient(45deg, red, blue)', // only allowed on *-background-color via allowsImagePaint
        ];
        foreach ($rejected as $v) {
            $this->assertFalse(
                self::isValidCssColor($v),
                "Validator must reject '{$v}' as a CSS color"
            );
        }
    }

    /**
     * Filename ↔ title parity. The picker's swatch label comes from
     * `settings[0].title` and the apply-click handler is keyed by the
     * pack's filename-derived slug. If a contributor renames the file
     * without updating the title (or vice versa), the sidebar shows
     * "Apple Shine" for a pack the JS applies as `arctic-frost` —
     * silently serving the wrong palette. Normalize both sides to
     * kebab-case and compare.
     */
    #[Test]
    #[DataProvider('colorPackProvider')]
    public function pack_title_kebab_case_matches_filename_slug(string $slug): void
    {
        $settings = $this->readSettingsZero($slug);

        $this->assertArrayHasKey('title', $settings, "Pack '{$slug}' settings[0] must declare a title");
        $this->assertIsString($settings['title'], "Pack '{$slug}' title must be a string");

        $normalizedTitle = self::toKebabCase($settings['title']);

        $this->assertSame(
            $slug,
            $normalizedTitle,
            sprintf(
                "Pack '%s' filename does not match its title after kebab-case normalization. Title '%s' normalizes to '%s'; either rename the file or fix settings[0].title so picker-label and apply-slug stay in sync.",
                $slug,
                $settings['title'],
                $normalizedTitle
            )
        );
    }

    /**
     * Kebab-case normalization used for slug↔title parity. Matches the
     * picker's expectation that a pack's visible label is a straight
     * Title-Case rendering of its file-slug: lowercases, strips
     * diacritics, collapses non-alphanumeric runs to single hyphens,
     * and trims leading/trailing hyphens.
     */
    public static function toKebabCase(string $input): string
    {
        $value = trim($input);
        if ($value === '') {
            return '';
        }

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
            if (is_string($converted) && $converted !== '') {
                $value = $converted;
            }
        }

        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
        $value = trim($value, '-');

        return $value;
    }

    #[Test]
    public function kebab_case_normalizer_is_stable_across_common_inputs(): void
    {
        // Lock in the normalizer contract — if this test fails after a
        // change to toKebabCase(), the filename↔title parity test's
        // meaning has shifted and must be reviewed.
        $this->assertSame('apple-shine', self::toKebabCase('Apple Shine'));
        $this->assertSame('apple-shine', self::toKebabCase('APPLE SHINE'));
        $this->assertSame('apple-shine', self::toKebabCase('  Apple   Shine  '));
        $this->assertSame('apple-shine', self::toKebabCase('Apple-Shine'));
        $this->assertSame('robocop', self::toKebabCase('Robocop'));
        $this->assertSame('sunset-boulevard', self::toKebabCase('Sunset Boulevard'));
        $this->assertSame('coral-pop', self::toKebabCase('Coral_Pop'));
        $this->assertSame('a-b', self::toKebabCase('A & B'));
        $this->assertSame('', self::toKebabCase(''));
        $this->assertSame('', self::toKebabCase('---'));
    }

    #[Test]
    public function gradient_paint_is_only_allowed_on_background_color_properties(): void
    {
        $grad = 'linear-gradient(45deg, #ff4d8d, #ff8a32)';

        // Allowed targets — the background-paint group.
        $this->assertTrue(self::isValidCssPaintValue($grad, '--mw-btn-background-color'));
        $this->assertTrue(self::isValidCssPaintValue($grad, '--mw-btn-background-hover-color'));
        $this->assertTrue(self::isValidCssPaintValue($grad, '--mw-top-header-button-background-color'));
        $this->assertTrue(self::isValidCssPaintValue($grad, '--mw-section-background-color'));

        // Disallowed targets — true color properties.
        $this->assertFalse(self::isValidCssPaintValue($grad, '--mw-heading-color'));
        $this->assertFalse(self::isValidCssPaintValue($grad, '--mw-link-color'));
        $this->assertFalse(self::isValidCssPaintValue($grad, '--mw-body-color'));
        $this->assertFalse(self::isValidCssPaintValue($grad, '--mw-primary-color'));
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
