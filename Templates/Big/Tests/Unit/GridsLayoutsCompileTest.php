<?php

namespace Templates\Big\Tests\Unit;

use Illuminate\Support\Facades\Blade;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Regression guard for the Grid layout skins.
 *
 * Every grids/* skin opened its metadata header with `@php` + a `/*` comment
 * opener that was never closed before `@endphp`. Blade compiles `@php ... @endphp`
 * to `<?php ... ?>`, producing `<?php /* ...metadata... ?>` — a fatal PHP
 * "Unterminated comment" parse error. The skins were SELECTABLE in the layout
 * picker (the LayoutsManager metadata parser regex-scans raw source, so it read
 * `name:`/`position:` fine) but rendered a fatal the moment one was inserted onto
 * a page. Fixed by moving the metadata header to the `{{-- ... --}}` Blade-comment
 * style used by the gallery/* skins.
 *
 * This test pins BOTH layers:
 *   (1) no grids skin reintroduces the malformed `@php` + `/*` metadata opener;
 *   (2) every grids skin Blade-compiles to lint-clean PHP.
 */
class GridsLayoutsCompileTest extends TestCase
{
    /**
     * @return array<string, array{0: string}>
     */
    public static function gridsSkins(): array
    {
        $dir = dirname(__DIR__, 2)
            . '/resources/views/modules/layouts/templates/grids';
        $out = [];
        foreach (glob($dir . '/*.blade.php') as $path) {
            $out[basename($path)] = [$path];
        }
        return $out;
    }

    #[DataProvider('gridsSkins')]
    public function testGridSkinHasNoUnterminatedMetadataComment(string $path): void
    {
        $src = (string) file_get_contents($path);

        // The defect signature: a `@php` block whose first non-blank line opens
        // a `/*` comment that is closed by `@endphp` instead of `*/`.
        $this->assertDoesNotMatchRegularExpression(
            '/@php\s*\n\s*\/\*(?:(?!\*\/).)*?@endphp/s',
            $src,
            basename($path) . ': metadata header must not open a /* comment that '
            . 'is never closed before @endphp (compiles to an Unterminated comment). '
            . 'Use the {{-- --}} Blade-comment style instead.'
        );
    }

    #[DataProvider('gridsSkins')]
    public function testGridSkinCompilesToValidPhp(string $path): void
    {
        $php = Blade::compileString((string) file_get_contents($path));

        $tmp = tempnam(sys_get_temp_dir(), 'big2grid') . '.php';
        file_put_contents($tmp, $php);
        try {
            $lint = (string) shell_exec('php -l ' . escapeshellarg($tmp) . ' 2>&1');
        } finally {
            @unlink($tmp);
        }

        $this->assertStringContainsString(
            'No syntax errors',
            $lint,
            basename($path) . ': compiled Blade must be syntactically valid PHP. ' . trim($lint)
        );
    }
}
