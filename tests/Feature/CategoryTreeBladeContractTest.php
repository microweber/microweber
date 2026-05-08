<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-93 / AI-82 / TICKET-UU — Category templates raw PHP → Blade
 * `{!! !!}` regression coverage.
 *
 * Pins:
 *   - The 3 Category list skin Blades that previously used raw
 *     `<?php category_tree($params); ?>` now use the canonical Blade
 *     `{!! category_tree($params) !!}` syntax.
 *   - The `category_tree()` helper now defaults `return_data=1` so
 *     it consistently RETURNS the rendered string instead of
 *     printing it directly (allowing `{!!` to echo without
 *     double-rendering).
 *
 * Style after the cycle-52..92 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class CategoryTreeBladeContractTest extends TestCase
{
    private const CAT_DEFAULT  = 'Modules/Category/resources/views/templates/default.blade.php';
    private const CAT_SKIN_1   = 'Modules/Category/resources/views/templates/skin-1.blade.php';
    private const CAT_HLIST    = 'Modules/Category/resources/views/templates/horizontal-list-1.blade.php';
    private const CAT_HELPERS  = 'Modules/Category/Support/helpers.php';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function category_skin_blades_use_blade_echo_not_raw_php(): void
    {
        // Strip Blade {{-- ... --}} comments first so the audit-trail
        // text that mentions `<?php category_tree(` doesn't trigger a
        // false positive.
        foreach ([self::CAT_DEFAULT, self::CAT_SKIN_1, self::CAT_HLIST] as $rel) {
            $src = $this->read($rel);
            $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);

            // Negative: no raw `<?php category_tree(` blocks.
            $this->assertDoesNotMatchRegularExpression(
                '/<\\?php\\s+category_tree\\s*\\(/',
                $stripped,
                "{$rel}: must not invoke category_tree() inside `<?php ?>` raw PHP block"
            );

            // Positive: canonical Blade echo.
            $this->assertMatchesRegularExpression(
                '/\\{!!\\s*category_tree\\s*\\(\\s*\\$params\\s*\\)\\s*!!\\}/',
                $src,
                "{$rel}: must invoke category_tree() via Blade `{!! category_tree(\$params) !!}`"
            );
        }
    }

    #[Test]
    public function category_tree_helper_defaults_return_data_one(): void
    {
        // The helper change makes `category_tree()` return-mode-by-
        // default so Blade `{!! !!}` doesn't render the tree twice.
        $src = $this->read(self::CAT_HELPERS);

        $this->assertMatchesRegularExpression(
            "/if\\s*\\(\\s*!isset\\(\\s*\\\$params\\['return_data'\\]\\s*\\)\\s*\\)\\s*\\{\\s*\\\$params\\['return_data'\\]\\s*=\\s*1;/",
            $src,
            'category_tree(): must default $params[\'return_data\'] = 1 when caller did not specify it'
        );
    }

    #[Test]
    public function category_tree_helper_normalises_string_params(): void
    {
        // The unit test (Modules/Category/Tests/Unit/CategoryManagerTest.php)
        // calls `category_tree('use_cache=0&...&all=1')` with a query-
        // string. The helper must `parse_params()` it before checking
        // `isset($params['return_data'])` so the default-injection
        // works regardless of how the caller passes params.
        $src = $this->read(self::CAT_HELPERS);

        $this->assertMatchesRegularExpression(
            "/if\\s*\\(\\s*!is_array\\(\\s*\\\$params\\s*\\)\\s*\\)\\s*\\{\\s*\\\$params\\s*=\\s*parse_params\\(\\s*\\\$params\\s*\\);/",
            $src,
            'category_tree(): must `parse_params()` non-array input before defaulting return_data'
        );
    }
}
