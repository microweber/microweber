<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-35e2d6 / AI-856 — silent-stub family extends to
 * /blog + /faq + /testimonials.
 * Jira: https://microweber.atlassian.net/browse/AI-856
 *
 * 7th confirmed silent-stub Stage-3 sibling-renderer family member
 * (lineage: AI-755 → AI-795 → AI-837 → AI-849 → AI-850 → AI-856 +
 *  AI-735 admin-prefix sibling).
 *
 * Pre-fix: /blog + /faq + /testimonials all returned HTTP 200 with
 * fixture content ("Describe your company" / "Your Story Should
 * Evolve"). FrontendController's `is_installed('<module>')` check
 * returned true for all three because they ARE installed modules --
 * but only as content-surface modules (embeddable <module type="..."
 * /> widgets, NO standalone listing-page controllers, NO standalone
 * routes). The controller emitted `<module type="..."/>` into an
 * empty content + rendered the fixture template. Same Stage-3
 * sibling-renderer pattern as /shop (AI-849), /customer (AI-850),
 * /search (AI-837 pre-fix).
 *
 * Fix slice 1: add `blog|faq|testimonials` to the FrontendController
 * catch-all exclusion regex at
 * src/MicroweberPackages/Frontend/routes/web.php. The three URLs now
 * fall through to Route::fallback() which renders the AI-795
 * chrome-wrapped 404 view (extended by AI-850 to cover excluded-
 * prefix URLs) -- proper "no such page" surface vs. fixture leak.
 *
 * AI-856b follow-up candidate flagged in route comment + this test
 * docblock: build actual listing-page controllers (BlogController /
 * FaqController / TestimonialsController) if standalone listing
 * pages are desired (would mirror AI-837 SearchController shape +
 * register Route::get('blog/{path?}', ...) etc.).
 */
class Frontend35e2d6AI856BlogFaqTestimonialsExclusionContractTest extends TestCase
{
    private const FRONTEND_ROUTES = 'src/MicroweberPackages/Frontend/routes/web.php';

    /**
     * @return array<string, array{0: string}>
     */
    public static function ai856ExcludedPrefixProvider(): array
    {
        return [
            'blog'         => ['blog'],
            'faq'          => ['faq'],
            'testimonials' => ['testimonials'],
        ];
    }

    private function read(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Each new prefix appears in the catch-all exclusion regex
    //           (future-proof anchor shape per AI-849 / AI-850)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('ai856ExcludedPrefixProvider')]
    public function frontend_catchall_exclusion_regex_carries_prefix(string $prefix): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);

        // Future-proof anchor shape (per pin-evolution discipline AI-770
        // v2 / AI-805 / AI-849 / AI-850): assert the prefix appears as
        // one of the |-separated alternatives inside the (?!...) lookahead
        // — robust against future additions reordering or expanding the
        // ladder. Uses `\b` word-boundary so e.g. `bloggy` wouldn't
        // false-match the `blog` keyword.
        $this->assertMatchesRegularExpression(
            "/->where\\(\\s*'slug'\\s*,\\s*'\\^\\(\\?![^)]*\\|{$prefix}\\b[^)]*\\)/",
            $source,
            "AI-856 ({$prefix}): frontend catch-all `->where('slug', ...)` regex MUST include `{$prefix}` in its exclusion list. Pre-fix `/{$prefix}` rendered fixture content via FrontendController's is_installed('{$prefix}') silent-stub path."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Existing sibling-family prefixes preserved (regression
    //           guard): AI-735 admin + AI-837 search + AI-849 shop +
    //           AI-850 customer all stay in the list
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function sibling_family_prefixes_preserved(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);

        $siblings = ['admin', 'search', 'shop', 'customer'];
        foreach ($siblings as $sibling) {
            $this->assertMatchesRegularExpression(
                "/->where\\(\\s*'slug'\\s*,\\s*'\\^\\(\\?![^)]*\\|{$sibling}\\b[^)]*\\)/",
                $source,
                "AI-856 regression guard: sibling-family prefix `{$sibling}` (from AI-735/AI-837/AI-849/AI-850) MUST remain in the catch-all exclusion regex."
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — task-id + AI-856 markers (audit grep contract)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);

        $this->assertStringContainsString(
            'task-2026-05-17-35e2d6',
            $source,
            'AI-856: frontend routes file MUST carry the AI-856 task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-856',
            $source,
            'AI-856: frontend routes file MUST cite the AI-856 ticket ID.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — silent-stub family lineage citation
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function silent_stub_family_lineage_cited(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);

        // Anchor on the AI-856 comment block and assert lineage citation
        // (slice fixed-length lookahead per AI-816 LESSONS slice-bounding
        // pattern; 1500 chars covers the comment block + route declaration).
        $anchorPos = strpos($source, 'task-2026-05-17-35e2d6 / AI-856');
        $this->assertNotFalse($anchorPos, 'AI-856: marker anchor must be present.');

        $slice = substr($source, $anchorPos, 1500);

        // At least one sibling-family ticket ID must appear in the AI-856
        // comment block as lineage citation.
        $hasLineage = false;
        foreach (['AI-755', 'AI-795', 'AI-837', 'AI-849', 'AI-850'] as $sibling) {
            if (strpos($slice, $sibling) !== false) {
                $hasLineage = true;
                break;
            }
        }

        $this->assertTrue(
            $hasLineage,
            'AI-856: comment block MUST cite at least one sibling silent-stub family ticket (AI-755 / AI-795 / AI-837 / AI-849 / AI-850) so future agents can grep the family lineage.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — AI-856b follow-up candidate flagged for future dispatch
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai856b_followup_documented(): void
    {
        $source = $this->read(self::FRONTEND_ROUTES);

        $this->assertStringContainsString(
            'AI-856b',
            $source,
            'AI-856: comment block MUST flag AI-856b as the follow-up candidate (build actual BlogController / FaqController / TestimonialsController listing pages if standalone listing pages are desired).'
        );
    }
}
