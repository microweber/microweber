<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-a8b4e2 / AI-1087 — silent-stub family extends to
 * /tag + /tags + /categories + /captcha archive routes.
 * Jira: https://microweber.atlassian.net/browse/AI-1087
 *
 * 8th confirmed silent-stub Stage-3 sibling-renderer family member
 * (lineage: AI-755 -> AI-795 -> AI-837 -> AI-849 -> AI-850 -> AI-856
 *  -> AI-1087 + AI-735 admin-prefix sibling).
 *
 * Pre-fix: /tag + /tags + /categories + /captcha all returned HTTP
 * 200 with empty / fixture content. FrontendController's
 * is_installed check returned true for tag / category / captcha
 * because Modules/Tag + Modules/Category + Modules/Captcha all ship
 * with the standard install -- but only as backend / taxonomy
 * modules (embeddable widgets consumed by Content templates +
 * admin-side Filament resources). None expose a public listing
 * surface. The controller emitted the module tag into an empty
 * content + rendered an empty body or fixture template. Same Stage-3
 * sibling-renderer pattern as /shop (AI-849), /customer (AI-850),
 * /blog + /faq + /testimonials (AI-856 pre-fix), /search (AI-837
 * pre-fix).
 *
 * Fix slice 1: add tag word-boundary + tags word-boundary +
 * categories word-boundary + captcha word-boundary to the
 * FrontendController catch-all exclusion regex at
 * src/MicroweberPackages/Frontend/routes/web.php. The four URLs now
 * fall through to Route::fallback() which renders the AI-795
 * chrome-wrapped 404 view (extended by AI-850 to cover excluded-
 * prefix URLs) -- proper "no such page" surface vs. silent-stub
 * leak.
 *
 * AI-1087b follow-up candidate flagged in route comment + this test
 * docblock: build actual taxonomy listing-page controllers
 * (TagController + CategoryController) if standalone /tag/{slug} +
 * /category/{slug} archive pages are desired (would mirror AI-837
 * SearchController shape + register Route::get('tag/{slug}', ...)
 * etc. + add taxonomy-content DB queries + listing views).
 *
 * Selector-self-match guard: setUp() pre-strips PHP line + block
 * comments before the absence assertion in case docblock prose ever
 * mentions a literal exclusion-list signature.
 */
class FrontendA8b4e2AI1087ArchivePrefixesExclusionContractTest extends TestCase
{
    private const FRONTEND_ROUTES = 'src/MicroweberPackages/Frontend/routes/web.php';

    private string $routesSource;

    private string $routesSourceStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->routesSource = (string) file_get_contents(base_path(self::FRONTEND_ROUTES));
        $this->routesSourceStripped = $this->stripPhpComments($this->routesSource);
    }

    private function stripPhpComments(string $source): string
    {
        $stripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $source);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
        return $stripped;
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function ai1087ExcludedPrefixProvider(): array
    {
        return [
            'tag'        => ['tag'],
            'tags'       => ['tags'],
            'categories' => ['categories'],
            'captcha'    => ['captcha'],
        ];
    }

    // ---------------------------------------------------------------------
    // Group A -- Each new prefix appears in the catch-all exclusion regex
    //            (future-proof anchor shape per AI-849 / AI-850 / AI-856)
    // ---------------------------------------------------------------------

    #[Test]
    #[DataProvider('ai1087ExcludedPrefixProvider')]
    public function frontend_catchall_exclusion_regex_carries_prefix(string $prefix): void
    {
        $this->assertMatchesRegularExpression(
            "/->where\\(\\s*'slug'\\s*,\\s*'\\^\\(\\?![^)]*\\|{$prefix}\\b[^)]*\\)/",
            $this->routesSourceStripped,
            "AI-1087 ({$prefix}): frontend catch-all `->where('slug', ...)` regex MUST include `{$prefix}` (with word boundary) in its exclusion list. Pre-fix `/{$prefix}` rendered fixture / empty content via FrontendController's is_installed silent-stub path."
        );
    }

    // ---------------------------------------------------------------------
    // Group B -- Existing sibling-family prefixes preserved (regression
    //            guard): AI-735 admin + AI-837 search + AI-849 shop +
    //            AI-850 customer + AI-856 faq + AI-856 testimonials all
    //            stay in the list.
    // ---------------------------------------------------------------------

    #[Test]
    public function sibling_family_prefixes_preserved(): void
    {
        $siblings = ['admin', 'search', 'shop', 'customer', 'faq', 'testimonials'];
        foreach ($siblings as $sibling) {
            $this->assertMatchesRegularExpression(
                "/->where\\(\\s*'slug'\\s*,\\s*'\\^\\(\\?![^)]*\\|{$sibling}\\b[^)]*\\)/",
                $this->routesSourceStripped,
                "AI-1087 regression guard: sibling-family prefix `{$sibling}` (from AI-735/AI-837/AI-849/AI-850/AI-856) MUST remain in the catch-all exclusion regex."
            );
        }
    }

    // ---------------------------------------------------------------------
    // Group C -- AI-946 blog-removal preserved (regression guard): /blog
    //            is a real content page since AI-792 createDefaultBlogPage
    //            -- excluding it would re-introduce AI-946's "/blog
    //            returns 404 instead of blog listing" regression.
    // ---------------------------------------------------------------------

    #[Test]
    public function ai946_blog_removal_preserved(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            "/->where\\(\\s*'slug'\\s*,\\s*'\\^\\(\\?![^)]*\\|blog\\b[^)]*\\)/",
            $this->routesSourceStripped,
            'AI-1087 regression guard: `blog` MUST NOT be re-added to the exclusion regex. AI-946 removed it because /blog resolves to a real blog listing page (AI-792 createDefaultBlogPage with layout_file=blog.blade.php).'
        );
    }

    // ---------------------------------------------------------------------
    // Group D -- task-id + AI-1087 markers (audit grep contract)
    // ---------------------------------------------------------------------

    #[Test]
    public function task_id_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-a8b4e2',
            $this->routesSource,
            'AI-1087: frontend routes file MUST carry the AI-1087 task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-1087',
            $this->routesSource,
            'AI-1087: frontend routes file MUST cite the AI-1087 ticket ID.'
        );
    }

    // ---------------------------------------------------------------------
    // Group E -- silent-stub family lineage citation
    // ---------------------------------------------------------------------

    #[Test]
    public function silent_stub_family_lineage_cited(): void
    {
        $anchorPos = strpos($this->routesSource, 'task-2026-05-28-a8b4e2 / AI-1087');
        $this->assertNotFalse($anchorPos, 'AI-1087: marker anchor must be present.');

        $slice = substr($this->routesSource, $anchorPos, 2000);

        $hasLineage = false;
        foreach (['AI-755', 'AI-795', 'AI-837', 'AI-849', 'AI-850', 'AI-856'] as $sibling) {
            if (strpos($slice, $sibling) !== false) {
                $hasLineage = true;
                break;
            }
        }

        $this->assertTrue(
            $hasLineage,
            'AI-1087: comment block MUST cite at least one sibling silent-stub family ticket (AI-755 / AI-795 / AI-837 / AI-849 / AI-850 / AI-856) so future agents can grep the family lineage.'
        );
    }

    // ---------------------------------------------------------------------
    // Group F -- AI-1087b follow-up candidate flagged for future dispatch
    // ---------------------------------------------------------------------

    #[Test]
    public function ai1087b_followup_documented(): void
    {
        $this->assertStringContainsString(
            'AI-1087b',
            $this->routesSource,
            'AI-1087: comment block MUST flag AI-1087b as the follow-up candidate (build actual TagController / CategoryController taxonomy listing pages if standalone archive surfaces are desired).'
        );
    }
}
