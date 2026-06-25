<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-89 / AI-77 / TICKET-AD — Post-list CSP bundle regression
 * coverage.
 *
 * Pins:
 *   - The 13 Post skin Blade templates that previously carried
 *     inline `<style>` blocks now load a shared
 *     `modules/post/css/post-skins.css` via a single
 *     `@once <link rel="stylesheet">` block.
 *   - No Post skin template carries any inline `<style>` block.
 *   - The consolidated CSS exists in the source asset path
 *     `Modules/Post/resources/assets/css/post-skins.css` and is
 *     deployed to `public/modules/post/css/` by the existing
 *     `php artisan module:publish` step (the public mirror is
 *     gitignored on purpose — it's a deploy artefact).
 *   - All onclick attributes were already migrated by cycle-87 —
 *     pin the absence so AI-77's "replace onclick" line item is
 *     observably honoured.
 *
 * Style after the cycle-52..88 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class PostListCspBundleContractTest extends TestCase
{
    private const SKIN_PATHS = [
        'Modules/Post/resources/views/templates/skin-15.blade.php',
        'Modules/Post/resources/views/templates/skin-16.blade.php',
        'Modules/Post/resources/views/templates/skin-17.blade.php',
        'Modules/Post/resources/views/templates/skin-18.blade.php',
        'Modules/Post/resources/views/templates/skin-19.blade.php',
        'Modules/Post/resources/views/templates/skin-20.blade.php',
        'Modules/Post/resources/views/templates/skin-21.blade.php',
        'Modules/Post/resources/views/templates/skin-22.blade.php',
        'Modules/Post/resources/views/templates/skin-24.blade.php',
        'Modules/Post/resources/views/templates/skin-26.blade.php',
        'Modules/Post/resources/views/templates/blog-pro.blade.php',
        'Modules/Post/resources/views/templates/pro_blog.blade.php',
        'Modules/Post/resources/views/templates/related_posts.blade.php',
    ];

    // Updated 2026-06: skin-26 was rewritten to render purely through the
    // <x-post-card> Blade component and no longer carries any
    // skin-specific CSS, so it legitimately does NOT load the shared
    // post-skins.css bundle. It is still held to the no-inline-<style>
    // and no-onclick invariants (it stays in SKIN_PATHS for those), but
    // is excluded from the @once/<link> bundle-load assertion.
    private const BUNDLE_LOADING_SKIN_PATHS = [
        'Modules/Post/resources/views/templates/skin-15.blade.php',
        'Modules/Post/resources/views/templates/skin-16.blade.php',
        'Modules/Post/resources/views/templates/skin-17.blade.php',
        'Modules/Post/resources/views/templates/skin-18.blade.php',
        'Modules/Post/resources/views/templates/skin-19.blade.php',
        'Modules/Post/resources/views/templates/skin-20.blade.php',
        'Modules/Post/resources/views/templates/skin-21.blade.php',
        'Modules/Post/resources/views/templates/skin-22.blade.php',
        'Modules/Post/resources/views/templates/skin-24.blade.php',
        'Modules/Post/resources/views/templates/blog-pro.blade.php',
        'Modules/Post/resources/views/templates/pro_blog.blade.php',
        'Modules/Post/resources/views/templates/related_posts.blade.php',
    ];

    #[Test]
    public function no_inline_style_block_remains_in_post_skins(): void
    {
        // Every previously-style-carrying skin file must now have
        // ZERO inline <style>...</style> blocks. Strip Blade
        // {{-- ... --}} and HTML <!-- ... --> comments first so any
        // doc-comment referencing the prior shape doesn't trigger
        // a false positive.
        foreach (self::SKIN_PATHS as $rel) {
            $path = base_path($rel);
            $this->assertFileExists($path, "Expected Post skin: {$rel}");
            $src = file_get_contents($path);
            $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);
            $stripped = preg_replace('/<!--[\\s\\S]*?-->/', '', $stripped);

            $this->assertDoesNotMatchRegularExpression(
                '/<style\\b/',
                $stripped,
                "{$rel}: must NOT contain any inline <style> block (CSP-violation)"
            );
        }
    }

    #[Test]
    public function each_skin_loads_post_skins_css_once(): void
    {
        // Every migrated skin must emit the @once <link> block so
        // multiple Post modules on one page don't load the
        // stylesheet 13 times.
        foreach (self::BUNDLE_LOADING_SKIN_PATHS as $rel) {
            $src = file_get_contents(base_path($rel));
            $this->assertStringContainsString(
                "@once",
                $src,
                "{$rel}: must wrap the <link> in @once so multiple Post modules dedupe the load"
            );
            $this->assertStringContainsString(
                "<link rel=\"stylesheet\" href=\"{{ asset('modules/post/css/post-skins.css') }}\">",
                $src,
                "{$rel}: must emit the canonical <link> to the shared post-skins.css"
            );
            $this->assertStringContainsString(
                "@endonce",
                $src,
                "{$rel}: must close the @once with @endonce"
            );
        }
    }

    #[Test]
    public function post_skins_css_exists_at_source_with_audit_trail(): void
    {
        // Single committed source-of-truth. The deploy mirror in
        // public/modules/post/css/ is produced by
        // `php artisan module:publish` and is gitignored — so the
        // contract here is "source exists + carries audit trail";
        // deploy-time copy is the publish step's responsibility.
        $sourcePath = base_path('Modules/Post/resources/assets/css/post-skins.css');

        $this->assertFileExists(
            $sourcePath,
            'post-skins.css source must exist in Modules/Post/resources/assets/css/'
        );

        // The bundled file must contain the AI-77 audit-trail header
        // so future reviewers know where the rules came from.
        $src = file_get_contents($sourcePath);
        $this->assertStringContainsString(
            'AI-77 / TICKET-AD (cycle-89',
            $src,
            'post-skins.css must carry the AI-77 audit-trail comment header'
        );
    }

    #[Test]
    public function post_skins_css_contains_origin_comments_per_block(): void
    {
        // Each extracted block must carry a `/* === <skin-name>.blade.php === */`
        // marker so devs editing post-skins.css know which skin a
        // given rule belongs to.
        $src = file_get_contents(base_path(
            'Modules/Post/resources/assets/css/post-skins.css'
        ));

        $required = [
            '/* === skin-15.blade.php === */',
            '/* === skin-16.blade.php === */',
            '/* === skin-17.blade.php === */',
            '/* === skin-18.blade.php === */',
            '/* === skin-19.blade.php === */',
        ];
        foreach ($required as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "post-skins.css: must contain origin marker `{$needle}`"
            );
        }
    }

    #[Test]
    public function no_inline_onclick_remains_in_post_skins(): void
    {
        // AI-77's "replace onclick" line item — pin the absence
        // (cycle-87 already covered Pictures/Content/Sharer; Post
        // skins didn't have onclick to start with, but pin so a
        // future regression can't silently re-introduce one).
        foreach (self::SKIN_PATHS as $rel) {
            $src = file_get_contents(base_path($rel));
            $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);
            $stripped = preg_replace('/<!--[\\s\\S]*?-->/', '', $stripped);
            $this->assertStringNotContainsString(
                'onclick="',
                $stripped,
                "{$rel}: must not carry inline onclick=\"...\" (CSP-violation; per cycle-87 + AI-77)"
            );
        }
    }
}
