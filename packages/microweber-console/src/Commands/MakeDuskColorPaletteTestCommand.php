<?php

namespace MicroweberPackages\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeDuskColorPaletteTestCommand extends Command
{
    protected $name = 'make:dusk:color-palette-test';

    protected $description = 'Scaffold a per-palette Dusk test (Phase-3) for a color pack slug.';

    public function __construct()
    {
        parent::__construct();

        $this->addArgument(
            'slug',
            InputArgument::REQUIRED,
            'Color pack slug (e.g. neon-night) — must match the <slug>.json filename under Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/'
        );
        $this->addOption(
            'force',
            'f',
            InputOption::VALUE_NONE,
            'Overwrite an existing test file with the same class name.'
        );
    }

    public function handle(): int
    {
        $rawSlug = (string)$this->argument('slug');
        $slug = self::normalizeSlug($rawSlug);

        if ($slug === '') {
            $this->error(
                "Slug '{$rawSlug}' is empty after normalization — pass a kebab-case slug like 'neon-night'."
            );
            return self::FAILURE;
        }

        $packPath = base_path(
            "Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/{$slug}.json"
        );
        if (!is_file($packPath)) {
            $this->error(
                "Pack JSON not found at Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/{$slug}.json"
            );
            $this->line('Drop the pack JSON into that directory first, then re-run this command.');
            $this->line('See docs/templates/color-palettes.md for the required JSON shape.');
            return self::FAILURE;
        }

        $class = self::className($slug);
        $method = self::methodName($slug);

        $testPath = base_path("tests/Browser/{$class}.php");
        if (is_file($testPath) && !$this->option('force')) {
            $this->error("Test file already exists at tests/Browser/{$class}.php");
            $this->line('Re-run with --force to overwrite.');
            return self::FAILURE;
        }

        $stubPath = __DIR__ . '/stubs/color-palette-test.stub';
        $stub = file_get_contents($stubPath);
        if ($stub === false) {
            $this->error("Could not read generator stub at {$stubPath}");
            return self::FAILURE;
        }

        $rendered = strtr($stub, [
            '{{slug}}' => $slug,
            '{{class}}' => $class,
            '{{method}}' => $method,
        ]);

        $bytes = file_put_contents($testPath, $rendered);
        if ($bytes === false) {
            $this->error("Failed to write {$testPath}");
            return self::FAILURE;
        }

        $this->info("Created tests/Browser/{$class}.php");
        $this->line('Next steps:');
        $this->line('  1) php artisan test --filter=ColorPaletteFilesTest');
        $this->line("  2) php artisan dusk --filter={$class}");

        return self::SUCCESS;
    }

    /**
     * Kebab-case normalize the input slug. Mirrors the parity rule
     * applied by ColorPaletteFilesTest::toKebabCase so the generated
     * filename lines up with the pack JSON's settings[0].title.
     */
    private static function normalizeSlug(string $input): string
    {
        $s = trim($input);
        if ($s === '') {
            return '';
        }

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s);
            if (is_string($converted) && $converted !== '') {
                $s = $converted;
            }
        }

        $s = strtolower($s);
        $s = (string)preg_replace('/[^a-z0-9]+/', '-', $s);
        $s = trim($s, '-');

        return $s;
    }

    /**
     * `neon-night` → `LiveEditColorPaletteNeonNightTest`.
     */
    private static function className(string $slug): string
    {
        $studly = str_replace(' ', '', ucwords(str_replace('-', ' ', $slug)));
        return 'LiveEditColorPalette' . $studly . 'Test';
    }

    /**
     * `neon-night` → `neon_night_applies_every_css_custom_property_to_root`.
     */
    private static function methodName(string $slug): string
    {
        return str_replace('-', '_', $slug) . '_applies_every_css_custom_property_to_root';
    }
}
