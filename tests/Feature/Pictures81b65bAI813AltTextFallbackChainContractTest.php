<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-81b65b / AI-813 — Pictures gallery alt-text
 * title→description→generic fallback chain across all affected
 * templates.
 *
 * Jira: https://microweber.atlassian.net/browse/AI-813
 * Priority: Medium (WCAG 1.1.1 Level A — non-text content)
 * Lineage: AI-804 (Logo three-tier alt fallback, same WCAG family)
 *
 * Pre-fix: Pictures templates rendered hardcoded generic alt text
 * (`__('Image')`, `__('Product image')`, or literal `'Open'`)
 * regardless of whether the picture record had a title / description
 * available. A 12-image gallery rendered 12 identical alts —
 * useless to screen-reader users.
 *
 * Recon delta (per AI-812 ×34 precedent): designer named
 * default.blade.php; ship-time recon found **23 instances across
 * 20 Pictures templates** in 4 defect categories:
 *
 *   Cat 1 (8 instances): hardcoded `'alt' => __('Image')`
 *     → `'alt' => $item['title'] ?? $item['description'] ?? __('Image')`
 *
 *   Cat 2 (9 instances): hardcoded `'alt' => __('Product image')`
 *     (shop / product surfaces — contextual fallback preserved)
 *     → `'alt' => $item['title'] ?? $item['description'] ?? __('Product image')`
 *
 *   Cat 3 (6 instances across simple/slider/skin-18/skin-20/
 *     sliding-skin): partial fix (title fallback only, no
 *     description). Switch ternary or partial `??` chain to the
 *     canonical three-tier shape.
 *     → `$item['title'] ?? $item['description'] ?? __('Image')`
 *
 *   Cat 4 (1 instance): blog_pro.blade.php precomputed default
 *     `$itemAltText = 'Open';`
 *     → `$itemAltText = $item['title'] ?? $item['description']
 *        ?? __('Image');`
 *     The `$item['image_options']['alt-text']` manual override
 *     STILL wins over the new default (operator-set alt always
 *     trumps auto-derived).
 *
 * Selector-self-match guard family (now 19+ session-recurrences):
 * the per-template docblock prose and this test's own docblock
 * legitimately mention the legacy patterns. Negative regression
 * assertions pre-strip Blade `{{-- ... --}}` comments before
 * scanning the executable source.
 */
class Pictures81b65bAI813AltTextFallbackChainContractTest extends TestCase
{
    /**
     * All 20 Pictures templates affected by AI-813. Each entry
     * carries the relative path + the number of alt-text instances
     * the ship script replaced in that file (so a regression where
     * a sibling alt-text gets added without a fallback fails the
     * count assertion).
     */
    public static function affectedTemplatesProvider(): array
    {
        return [
            'blog_pro'                => ['Modules/Pictures/resources/views/templates/blog_pro.blade.php',                1],
            'default'                 => ['Modules/Pictures/resources/views/templates/default.blade.php',                 1],
            'masonry'                 => ['Modules/Pictures/resources/views/templates/masonry.blade.php',                 1],
            'shop-inner'              => ['Modules/Pictures/resources/views/templates/shop-inner.blade.php',              1],
            'shop-inner-templates'    => ['Modules/Pictures/resources/views/templates/shop-inner-templates.blade.php',    2],
            'shop-inner-templates-2'  => ['Modules/Pictures/resources/views/templates/shop-inner-templates-2.blade.php',  1],
            'simple'                  => ['Modules/Pictures/resources/views/templates/simple.blade.php',                  1],
            'skin-2'                  => ['Modules/Pictures/resources/views/templates/skin-2.blade.php',                  1],
            'skin-3'                  => ['Modules/Pictures/resources/views/templates/skin-3.blade.php',                  1],
            'skin-5'                  => ['Modules/Pictures/resources/views/templates/skin-5.blade.php',                  1],
            'skin-6'                  => ['Modules/Pictures/resources/views/templates/skin-6.blade.php',                  1],
            'skin-8'                  => ['Modules/Pictures/resources/views/templates/skin-8.blade.php',                  1],
            'skin-14'                 => ['Modules/Pictures/resources/views/templates/skin-14.blade.php',                 2],
            'skin-14-ocean'           => ['Modules/Pictures/resources/views/templates/skin-14-ocean.blade.php',           1],
            'skin-16'                 => ['Modules/Pictures/resources/views/templates/skin-16.blade.php',                 1],
            'skin-18'                 => ['Modules/Pictures/resources/views/templates/skin-18.blade.php',                 1],
            'skin-20'                 => ['Modules/Pictures/resources/views/templates/skin-20.blade.php',                 1],
            'slick'                   => ['Modules/Pictures/resources/views/templates/slick.blade.php',                   1],
            'slider'                  => ['Modules/Pictures/resources/views/templates/slider.blade.php',                  1],
            'sliding-skin'            => ['Modules/Pictures/resources/views/templates/sliding-skin.blade.php',            2],
        ];
    }

    /**
     * Read file relative-to-base + pre-strip Blade {{-- ... --}}
     * comments so docblock prose mentioning legacy patterns cannot
     * false-fail absence assertions (selector-self-match guard).
     */
    private function executableTemplate(string $relativePath): string
    {
        $src = (string) file_get_contents(base_path($relativePath));
        return preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $src);
    }

    private function rawTemplate(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  legacy hardcoded patterns are GONE
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function no_legacy_hardcoded_image_alt(string $relativePath, int $expectedInstances): void
    {
        $exec = $this->executableTemplate($relativePath);
        // Cat 1 legacy shape: 'alt' => __('Image') with NO preceding
        // title/description chain. Negative-lookbehind ensures we
        // only fail when the chain ISN'T present.
        $this->assertDoesNotMatchRegularExpression(
            "/(?<!\\\$item\\['description'\\] \\?\\? )'alt' => __\\('Image'\\)/",
            $exec,
            "AI-813: {$relativePath} MUST NOT carry bare `'alt' => __('Image')` without the title/description fallback chain."
        );
    }

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function no_legacy_hardcoded_product_image_alt(string $relativePath, int $expectedInstances): void
    {
        $exec = $this->executableTemplate($relativePath);
        $this->assertDoesNotMatchRegularExpression(
            "/(?<!\\\$item\\['description'\\] \\?\\? )'alt' => __\\('Product image'\\)/",
            $exec,
            "AI-813: {$relativePath} MUST NOT carry bare `'alt' => __('Product image')` without the title/description fallback chain (shop surfaces)."
        );
    }

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function no_partial_title_only_alt_chain(string $relativePath, int $expectedInstances): void
    {
        $exec = $this->executableTemplate($relativePath);
        // Cat 3 partial fix: $item['title'] ?? __('Image') without
        // description in the middle. Negative-lookbehind on
        // $item['description'] ?? .
        $this->assertDoesNotMatchRegularExpression(
            "/(?<!\\\$item\\['description'\\] \\?\\? )\\\$item\\['title'\\] \\?\\? __\\('Image'\\)/",
            $exec,
            "AI-813: {$relativePath} MUST carry the description fallback in the middle of the title-chain (canonical: title ?? description ?? generic)."
        );
        // Cat 3a legacy ternary shape: gone everywhere.
        $this->assertDoesNotMatchRegularExpression(
            "/isset\\(\\\$item\\['title'\\]\\)\\s*\\?\\s*\\\$item\\['title'\\]\\s*:\\s*__\\('Image'\\)/",
            $exec,
            "AI-813: {$relativePath} MUST NOT carry the legacy ternary `isset(\$item['title']) ? \$item['title'] : __('Image')` shape."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  canonical title→description→generic chain is present
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function canonical_alt_chain_present(string $relativePath, int $expectedInstances): void
    {
        $exec = $this->executableTemplate($relativePath);
        // The canonical post-fix chain: $item['title'] ?? $item['description'] ?? __('...')
        // — match either Image or Product image generic fallback,
        // since both are contextually correct.
        $this->assertMatchesRegularExpression(
            "/\\\$item\\['title'\\]\\s*\\?\\?\\s*\\\$item\\['description'\\]\\s*\\?\\?\\s*(__\\('Image'\\)|__\\('Product image'\\)|__\\('Open'\\))/",
            $exec,
            "AI-813: {$relativePath} MUST carry the canonical alt-fallback chain `\$item['title'] ?? \$item['description'] ?? __('...')`."
        );
    }

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function canonical_alt_chain_appears_expected_instance_count(string $relativePath, int $expectedInstances): void
    {
        $exec = $this->executableTemplate($relativePath);
        // Count occurrences of the canonical chain pattern.
        // The pattern matches BOTH shapes:
        //   1. 'alt' => $item['title'] ?? $item['description'] ?? __('...')
        //      (Cat 1/2/3 — inline expression as responsive_thumbnail arg).
        //   2. $itemAltText = $item['title'] ?? $item['description'] ?? __('Image');
        //      (Cat 4 — blog_pro precomputed variable).
        // Both are picked up by the same regex since they share the
        // `$item['title'] ?? $item['description'] ?? __('...')` core.
        $count = preg_match_all(
            "/\\\$item\\['title'\\]\\s*\\?\\?\\s*\\\$item\\['description'\\]\\s*\\?\\?\\s*(__\\('Image'\\)|__\\('Product image'\\)|__\\('Open'\\))/",
            $exec
        );
        $this->assertSame(
            $expectedInstances,
            $count,
            "AI-813: {$relativePath} expected exactly {$expectedInstances} canonical alt-chain occurrence(s)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  blog_pro Cat 4 special case
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function blog_pro_item_alt_text_default_uses_canonical_chain(): void
    {
        $exec = $this->executableTemplate('Modules/Pictures/resources/views/templates/blog_pro.blade.php');
        $this->assertMatchesRegularExpression(
            "/\\\$itemAltText\\s*=\\s*\\\$item\\['title'\\]\\s*\\?\\?\\s*\\\$item\\['description'\\]\\s*\\?\\?\\s*__\\('Image'\\)\\s*;/",
            $exec,
            "AI-813 Cat 4: blog_pro.blade.php MUST set \$itemAltText default to the canonical chain."
        );
        $this->assertDoesNotMatchRegularExpression(
            "/\\\$itemAltText\\s*=\\s*'Open'/",
            $exec,
            "AI-813 Cat 4: blog_pro.blade.php MUST NOT carry the legacy literal `\$itemAltText = 'Open'` default."
        );
    }

    #[Test]
    public function blog_pro_image_options_alt_text_override_preserved(): void
    {
        // Operator's manual image_options['alt-text'] override
        // MUST still win over the auto-derived default — pinning
        // this so a future refactor doesn't accidentally drop the
        // override path.
        $exec = $this->executableTemplate('Modules/Pictures/resources/views/templates/blog_pro.blade.php');
        $this->assertStringContainsString(
            "if (isset(\$item['image_options']['alt-text']))",
            $exec,
            "AI-813 Cat 4 regression guard: blog_pro.blade.php MUST preserve the image_options['alt-text'] manual override branch (operator-set alt always trumps auto-derived)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  recon-surface guard — no NEW affected template can sneak in
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function no_other_pictures_template_carries_unscoped_alt(): void
    {
        $templatesDir = base_path('Modules/Pictures/resources/views/templates');
        $allBlades = glob($templatesDir . '/*.blade.php');
        $covered = array_map(
            fn ($p) => base_path($p[0]),
            array_values(static::affectedTemplatesProvider())
        );
        $uncovered = array_diff($allBlades, $covered);

        foreach ($uncovered as $file) {
            $raw = (string) file_get_contents($file);
            $exec = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $raw);

            // 4 legacy patterns to scan for in uncovered templates.
            $legacyPatterns = [
                "/'alt' => __\\('Image'\\)/",
                "/'alt' => __\\('Product image'\\)/",
                "/isset\\(\\\$item\\['title'\\]\\)\\s*\\?\\s*\\\$item\\['title'\\]\\s*:\\s*__\\('Image'\\)/",
                "/(?<!\\\$item\\['description'\\] \\?\\? )\\\$item\\['title'\\] \\?\\? __\\('Image'\\)/",
            ];
            foreach ($legacyPatterns as $pattern) {
                if (preg_match($pattern, $exec)) {
                    $this->fail(sprintf(
                        'AI-813: uncovered Pictures template %s carries a legacy alt-text shape. ' .
                        'Add to Pictures81b65bAI813AltTextFallbackChainContractTest::affectedTemplatesProvider() AND apply the canonical chain.',
                        basename($file)
                    ));
                }
            }
        }
        $this->addToAssertionCount(1);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E  task-id markers per file
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function task_id_or_marker_present_optional(string $relativePath, int $expectedInstances): void
    {
        // The fix is a single-line-per-instance change; per-file
        // task-id markers are not REQUIRED (would be inline-comment
        // noise on every alt= attribute), but the contract test's
        // own marker AND this docblock cite the task-id so audit
        // grep across `git log --grep task-2026-05-17-81b65b` finds
        // the full ship via the commit subject.
        // Sanity check: file is at least readable.
        $this->assertGreaterThan(
            0,
            strlen($this->rawTemplate($relativePath)),
            "AI-813: {$relativePath} MUST be readable + non-empty."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group F  data provider count sanity
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function data_provider_contains_20_affected_templates(): void
    {
        $this->assertCount(
            20,
            static::affectedTemplatesProvider(),
            'AI-813: data provider MUST contain exactly 20 templates per recon-delta count (designer named 1, recon found 20 with 23 total instances).'
        );

        // Sum of expectedInstances per template MUST equal 23.
        $totalInstances = array_sum(array_map(
            fn ($entry) => $entry[1],
            array_values(static::affectedTemplatesProvider())
        ));
        $this->assertSame(
            23,
            $totalInstances,
            'AI-813: total alt-text instances across all 20 affected templates MUST equal 23 per recon-delta count.'
        );
    }
}
