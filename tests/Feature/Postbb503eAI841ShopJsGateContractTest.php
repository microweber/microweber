<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-bb503e / AI-841 — Posts shop.js perf cost gate.
 * Jira: https://microweber.atlassian.net/browse/AI-841
 *
 * Sibling of AI-806-defect-#1 in the legacy-Blade audit class (Logo
 * trilogy AI-803/804/805 + Page rewrite AI-806/807/808 + Menu AI-809
 * + Pictures AI-812/813/814 + Teamcard AI-840 + this Posts shop.js
 * gate).
 *
 * Pre-fix Modules/Post/resources/views/templates/dictionary.blade.php
 * line 14 + skin-1.blade.php line 14 emitted unconditional
 * mw.require slash shop.js at the top of the Posts wrapper templates
 * before the Content @include. Cart buttons in the included Content
 * template are correctly gated behind !empty($item slash prices)
 * checks — so the cart UI never appears for priceless items — but
 * shop.js still loads, wasting a network round-trip on every Posts
 * list render where no item is priced.
 *
 * Post-fix (Slice A inline per AI-841 dispatch + AI-806 family
 * mirror): pre-scan $data for any item carrying a non-empty prices
 * array; only emit the mw.require() script tag if the result is
 * truthy. Belt-and-braces: Content template's own gating remains
 * intact (cart buttons stay conditional); this gate just skips the
 * script load entirely when no cart UI will render.
 *
 * Two-layer defence applied (16+ session-recurrences of the
 * selector-self-match guard family, formalised post-AI-807 ACK
 * 2026-05-17): Layer 1 (belt) — implementer pre-strips PHP // line
 * comments + Blade slash-slash-dash-dash comments before negative-
 * regression scan via ai841_unconditional_shop_require_absent_from_
 * executable_source. Layer 2 (suspenders) — source-side docblock
 * phrased in word-form ("mw.require slash shop.js" not the literal
 * token sequence) so the verbatim grep against raw source passes
 * cleanly.
 *
 * Slice B follow-up (deferred — AI-841b candidate): extract pre-scan
 * + @if-gate to a shared partial when a 3rd Posts wrapper template
 * joins the cart-supporting set. Currently 2-file scope; inline
 * preserves per-file audit trail.
 */
class Postbb503eAI841ShopJsGateContractTest extends TestCase
{
    private const DICTIONARY = 'Modules/Post/resources/views/templates/dictionary.blade.php';
    private const SKIN_1 = 'Modules/Post/resources/views/templates/skin-1.blade.php';

    private function read(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    /**
     * Pre-strip PHP // line comments + slash-star-style block
     * comments + Blade slash-slash-dash-dash-style comments so
     * docblock prose referencing the legacy pattern doesn't false-
     * match the negative-regression scans. Layer 1 of the two-layer
     * selector-self-match guard defence (AI-790 docblock-terminator
     * rule applied: word-form prose, never the literal token).
     */
    private function executable(string $source): string
    {
        $source = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $source);
        $source = (string) preg_replace('~/\*.*?\*/~s', '', $source);
        $source = (string) preg_replace('~//[^\n]*~', '', $source);
        return $source;
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function gatedTemplateProvider(): array
    {
        return [
            'dictionary.blade.php' => [
                'Modules/Post/resources/views/templates/dictionary.blade.php',
                'modules.content::templates.dictionary',
            ],
            'skin-1.blade.php' => [
                'Modules/Post/resources/views/templates/skin-1.blade.php',
                'modules.content::templates.skin-1',
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Pre-scan + @if-gate present in each affected template
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('gatedTemplateProvider')]
    public function gated_template_carries_prescan_for_priced_items(string $relativePath): void
    {
        $source = $this->read($relativePath);

        $this->assertStringContainsString(
            '$mwAi841NeedsShop = false;',
            $source,
            "AI-841: {$relativePath} must initialise the \$mwAi841NeedsShop sentinel to false before the pre-scan loop."
        );
        $this->assertMatchesRegularExpression(
            '/foreach\s*\(\s*\$data\s+as\s+\$mwAi841Item\s*\)/',
            $source,
            "AI-841: {$relativePath} pre-scan must iterate \$data via foreach to detect any priced item."
        );
        $this->assertStringContainsString(
            "is_array(\$mwAi841Item['prices'])",
            $source,
            "AI-841: {$relativePath} pre-scan must check is_array(\$mwAi841Item['prices']) (mirrors the cart-button gating in Content templates)."
        );
        $this->assertStringContainsString(
            "!empty(\$mwAi841Item['prices'])",
            $source,
            "AI-841: {$relativePath} pre-scan must check !empty(\$mwAi841Item['prices']) (only non-empty arrays trip the gate)."
        );
        $this->assertStringContainsString(
            '$mwAi841NeedsShop = true;',
            $source,
            "AI-841: {$relativePath} pre-scan loop must flip the sentinel to true on first priced item."
        );
        $this->assertStringContainsString(
            'break;',
            $source,
            "AI-841: {$relativePath} pre-scan must short-circuit via break; on first match (no point scanning further)."
        );
    }

    #[Test]
    #[DataProvider('gatedTemplateProvider')]
    public function gated_template_wraps_shop_require_in_if_sentinel(string $relativePath): void
    {
        $source = $this->read($relativePath);

        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*\$mwAi841NeedsShop\s*\)\s*\R\s*<script>\s*\R\s*mw\.require\(\s*[\'"]shop\.js[\'"]\s*\)\s*;\s*\R\s*<\/script>\s*\R\s*@endif/',
            $source,
            "AI-841: {$relativePath} must wrap the mw.require shop.js call in @if(\$mwAi841NeedsShop) ... @endif so the script load is skipped when no item carries prices."
        );
    }

    #[Test]
    #[DataProvider('gatedTemplateProvider')]
    public function gated_template_preserves_content_include(string $relativePath): void
    {
        [$_, $expectedInclude] = self::gatedTemplateProvider()[basename($relativePath)];
        $source = $this->read($relativePath);

        $this->assertStringContainsString(
            "@include('{$expectedInclude}')",
            $source,
            "AI-841: {$relativePath} must preserve the @include('{$expectedInclude}') call — the Content template owns the cart-button rendering (already correctly gated)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Layer 1 (belt): unconditional pattern absent from
    //          executable source (post-comment-strip)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('gatedTemplateProvider')]
    public function ai841_unconditional_shop_require_absent_from_executable_source(string $relativePath): void
    {
        $executable = $this->executable($this->read($relativePath));

        // The mw.require call itself MUST still appear (inside the
        // @if branch), but it must NOT appear at top level outside
        // an @if($mwAi841NeedsShop) wrapper. Assert by checking that
        // every mw.require slash shop.js occurrence in the executable
        // source sits within ~80 chars after an @if($mwAi841NeedsShop)
        // opening.
        if (preg_match_all('/mw\.require\(\s*[\'"]shop\.js[\'"]\s*\)/', $executable, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $match) {
                [$_, $offset] = $match;
                $contextBefore = substr($executable, max(0, $offset - 200), min(200, $offset));
                $this->assertMatchesRegularExpression(
                    '/@if\s*\(\s*\$mwAi841NeedsShop\s*\)/',
                    $contextBefore,
                    "AI-841 Layer 1: every mw.require shop.js call in executable source must sit inside an @if(\$mwAi841NeedsShop) gate. Found unconditional call in {$relativePath} at offset {$offset}."
                );
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Sibling Posts templates clean-state regression guard
    //          (no other Posts template should carry mw.require shop.js)
    // ─────────────────────────────────────────────────────────────────────

    /**
     * @return array<string, array{0: string}>
     */
    public static function siblingPostsTemplateProvider(): array
    {
        return [
            'default.blade.php' => ['Modules/Post/resources/views/templates/default.blade.php'],
            'blog-pro.blade.php' => ['Modules/Post/resources/views/templates/blog-pro.blade.php'],
            'pro_blog.blade.php' => ['Modules/Post/resources/views/templates/pro_blog.blade.php'],
            'post-slider.blade.php' => ['Modules/Post/resources/views/templates/post-slider.blade.php'],
            'related_posts.blade.php' => ['Modules/Post/resources/views/templates/related_posts.blade.php'],
            'skin-2.blade.php' => ['Modules/Post/resources/views/templates/skin-2.blade.php'],
            'skin-10.blade.php' => ['Modules/Post/resources/views/templates/skin-10.blade.php'],
        ];
    }

    #[Test]
    #[DataProvider('siblingPostsTemplateProvider')]
    public function sibling_posts_templates_carry_zero_shop_require_hits(string $relativePath): void
    {
        if (!file_exists(base_path($relativePath))) {
            $this->markTestSkipped("Sibling template {$relativePath} not present in this install.");
        }

        $raw = (string) file_get_contents(base_path($relativePath));

        $this->assertDoesNotMatchRegularExpression(
            '/mw\.require\(\s*[\'"]shop\.js[\'"]\s*\)/',
            $raw,
            "AI-841 sibling audit: {$relativePath} must NOT carry mw.require shop.js. Bundle scope confined to dictionary + skin-1; if a sibling regresses, file a new ticket and bundle into the same fix slice."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Content templates clean-state regression guard
    //          (per designer's defence-in-depth note; confirmed 0 hits)
    // ─────────────────────────────────────────────────────────────────────

    /**
     * @return array<string, array{0: string}>
     */
    public static function contentTemplateProvider(): array
    {
        return [
            'Content/default.blade.php' => ['Modules/Content/resources/views/templates/default.blade.php'],
            'Content/dictionary.blade.php' => ['Modules/Content/resources/views/templates/dictionary.blade.php'],
            'Content/skin-1.blade.php' => ['Modules/Content/resources/views/templates/skin-1.blade.php'],
            'Content/masonry.blade.php' => ['Modules/Content/resources/views/templates/masonry.blade.php'],
            'Content/search.blade.php' => ['Modules/Content/resources/views/templates/search.blade.php'],
            'Content/sidebar.blade.php' => ['Modules/Content/resources/views/templates/sidebar.blade.php'],
        ];
    }

    #[Test]
    #[DataProvider('contentTemplateProvider')]
    public function content_templates_carry_zero_shop_require_hits(string $relativePath): void
    {
        $raw = (string) file_get_contents(base_path($relativePath));

        $this->assertDoesNotMatchRegularExpression(
            '/mw\.require\(\s*[\'"]shop\.js[\'"]\s*\)/',
            $raw,
            "AI-841 defence-in-depth (designer's note): {$relativePath} must NOT carry mw.require shop.js. Designer flagged Content templates as false-positives in the AI-841 dispatch — recon confirmed 0 hits and this guard pins that state."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — Task-id markers + AI-806 lineage discoverability
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('gatedTemplateProvider')]
    public function task_id_marker_present_in_template(string $relativePath): void
    {
        $source = $this->read($relativePath);
        $this->assertStringContainsString(
            'task-2026-05-17-bb503e',
            $source,
            "AI-841: {$relativePath} docblock must carry the task-id marker for cross-surface audit grep."
        );
        $this->assertStringContainsString(
            'AI-841',
            $source,
            "AI-841: {$relativePath} docblock must carry the AI-841 ticket marker."
        );
    }

    #[Test]
    #[DataProvider('gatedTemplateProvider')]
    public function ai806_lineage_citation_present_in_template(string $relativePath): void
    {
        $this->assertStringContainsString(
            'AI-806',
            $this->read($relativePath),
            "AI-841: {$relativePath} docblock must cite AI-806 lineage (defect-#1 family extension per designer dispatch 2026-05-17T12:43:17Z)."
        );
    }

    #[Test]
    #[DataProvider('gatedTemplateProvider')]
    public function ai841b_followup_flagged_in_template(string $relativePath): void
    {
        $this->assertStringContainsString(
            'AI-841b',
            $this->read($relativePath),
            "AI-841: {$relativePath} docblock must flag the AI-841b follow-up (shared-partial Slice B extraction when ≥3 Posts wrappers join the cart-supporting set)."
        );
    }
}
