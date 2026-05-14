<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-530 — Tabs module mobile touch-target floor per PM dispatch
 * 2026-05-14T07:24:20 (sequential, P3 Content) — last Batch 1 fix.
 *
 * Audit finding: Tabs `default.blade.php` (Bootstrap-accordion skin)
 * renders each tab as `<button class="btn btn-link">` inside
 * `<div class="card-header">`. Bootstrap's `.btn-link` default
 * padding gives ~36px total height — below the 44x44 WCAG 2.5.5 floor.
 *
 * Fix surface: `Templates/Bootstrap/resources/assets/css/public-touch.css`
 * (Vite source) + byte-identical served mirror. Rule lives inside the
 * existing AI-516/AI-518/AI-522/AI-528 touch-viewport @media block.
 *
 * Scope: `.card-header .btn` + `.card-header .nav-link` only — confined
 * to the Bootstrap card-header context so site-wide `.btn` rendering
 * is unaffected. Recon found that only `default.blade.php` uses
 * `.card-header`; other Tabs skins use different button shapes
 * (`.flower-tabs-button`, `.merry-tabs-button`, `.tab-link`,
 * `.nav-link` outside card-header) which are outside this fix's scope
 * per the dispatch's narrow `.card-header` target.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai530TabsTouchTargetContractTest extends TestCase
{
    private const PUBLIC_TOUCH_CSS = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const SERVED_TOUCH_CSS = 'public/templates/bootstrap/css/public-touch.css';
    private const TABS_DEFAULT     = 'Modules/Tabs/resources/views/templates/default.blade.php';

    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
    }

    private function ai530Block(): string
    {
        $start = strpos($this->css, 'AI-530');
        $this->assertNotFalse(
            $start,
            'public-touch.css must contain the AI-530 marker comment'
        );
        $remaining = substr($this->css, $start);
        // Bound to the rule's closing brace `\n    }\n` per LESSONS.md
        // 2026-05-14 slice-bounding rule.
        $end = strpos($remaining, "\n    }\n");
        $this->assertNotFalse(
            $end,
            'AI-530 rule body must terminate cleanly with `\n    }\n`'
        );
        return substr($remaining, 0, $end + 6);
    }

    #[Test]
    public function ai530_marker_comment_present(): void
    {
        $this->assertStringContainsString('AI-530', $this->css);
        $this->assertStringContainsString('Tabs module', $this->css);
        $this->assertStringContainsString('.card-header', $this->css);
    }

    #[Test]
    public function tabs_default_template_renders_btn_link_inside_card_header(): void
    {
        $template = file_get_contents(base_path(self::TABS_DEFAULT));
        $this->assertStringContainsString(
            'class="card-header"',
            $template,
            'AI-530 anchor: Tabs default template must declare `.card-header`'
        );
        $this->assertMatchesRegularExpression(
            '/<button[^>]*class="btn btn-link"/',
            $template,
            'AI-530 anchor: Tabs default template must render `<button class="btn btn-link">` (the accordion-skin tab button)'
        );
    }

    #[Test]
    public function card_header_btn_and_nav_link_floor_44_height(): void
    {
        $block = $this->ai530Block();
        $this->assertMatchesRegularExpression(
            '/\.card-header\s+\.btn\s*,\s*\.card-header\s+\.nav-link\s*\{[^}]*min-height:\s*44px;[^}]*display:\s*inline-flex;[^}]*align-items:\s*center;[^}]*\}/s',
            $block,
            'AI-530: .card-header .btn AND .card-header .nav-link must share a single rule with min-height: 44px + inline-flex centring'
        );
    }

    #[Test]
    public function ai530_rule_lives_inside_touch_viewport_media_query(): void
    {
        $touchMediaStart = strpos(
            $this->css,
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)'
        );
        $this->assertNotFalse($touchMediaStart);
        $ai530Pos = strpos($this->css, 'AI-530');
        $this->assertGreaterThan(
            $touchMediaStart,
            $ai530Pos,
            'AI-530 marker must appear AFTER the canonical touch-viewport @media opener'
        );

        $block = $this->ai530Block();
        // `@media (` token guard avoids docblock-prose false-positive
        // (lesson family from task-dac0b8 / AI-522).
        $this->assertStringNotContainsString(
            '@media (',
            $block,
            'AI-530 rule body must NOT open its own @media (...) — it inherits the touch-viewport block'
        );
    }

    #[Test]
    public function ai530_does_not_broaden_to_bare_btn_selector(): void
    {
        // Scope guard: AI-530 must stay inside `.card-header` context.
        // A bare `.btn { min-height: 44px }` would broadcast to every
        // button site-wide and regress dense content layouts.
        $block = $this->ai530Block();
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.btn\s*\{/m',
            $block,
            'AI-530 must not declare a bare `.btn {` selector — scope stays inside `.card-header`'
        );
    }

    #[Test]
    public function served_mirror_is_byte_identical_with_source(): void
    {
        $source = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
        $served = file_get_contents(base_path(self::SERVED_TOUCH_CSS));
        $this->assertSame(
            $source,
            $served,
            'public-touch.css served mirror must be byte-identical with the source'
        );
    }
}
