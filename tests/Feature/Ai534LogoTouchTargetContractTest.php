<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-534 — Logo module link touch-target floor per PM dispatch
 * 2026-05-14T07:43:18.
 *
 * Audit finding: `.logo-module .logo-link` carries `display: inline-block;
 * max-width: 100%;` but no min-height floor (inline `<style>` in
 * `Modules/Logo/resources/views/templates/default.blade.php` lines 43-49).
 * Small logos (100x30 image) or short text logos (30px font) result in
 * 30-36px touch targets, below the 44x44 WCAG 2.5.5 floor.
 *
 * Fix surface: `Templates/Bootstrap/resources/assets/css/public-touch.css`
 * (Vite source) + byte-identical served mirror. Rule lives inside the
 * existing AI-516..AI-532 touch-viewport @media block.
 *
 * Scope: `.logo-module .logo-link` is exclusive to the Logo module's
 * `default.blade.php` template. The Logo `2rows.blade.php` uses
 * `.module-logo.module-logo-2rows` (different shape); Big2 template
 * uses `.linktree-logo .logo-module a` (bare anchor, no `.logo-link`).
 * Bare combinator selector is safe; no collisions across the repo.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai534LogoTouchTargetContractTest extends TestCase
{
    private const PUBLIC_TOUCH_CSS = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const SERVED_TOUCH_CSS = 'public/templates/bootstrap/css/public-touch.css';
    private const LOGO_DEFAULT     = 'Modules/Logo/resources/views/templates/default.blade.php';

    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
    }

    private function ai534Block(): string
    {
        $start = strpos($this->css, 'AI-534');
        $this->assertNotFalse(
            $start,
            'public-touch.css must contain the AI-534 marker comment'
        );
        // Slice from AFTER docblock closing `*/` (slice-start lesson
        // family from AI-531 / AI-532).
        $remaining = substr($this->css, $start);
        $docEnd = strpos($remaining, '*/');
        $this->assertNotFalse($docEnd, 'AI-534 docblock must terminate with `*/`');
        $remaining = substr($remaining, $docEnd + 2);

        $end = strpos($remaining, "\n    }\n");
        $this->assertNotFalse(
            $end,
            'AI-534 rule body must terminate cleanly with `\n    }\n`'
        );
        return substr($remaining, 0, $end + 6);
    }

    #[Test]
    public function ai534_marker_comment_present(): void
    {
        $this->assertStringContainsString('AI-534', $this->css);
        $this->assertStringContainsString('Logo module', $this->css);
        $this->assertStringContainsString('.logo-module .logo-link', $this->css);
    }

    #[Test]
    public function logo_default_template_renders_logo_module_logo_link(): void
    {
        $template = file_get_contents(base_path(self::LOGO_DEFAULT));
        $this->assertStringContainsString(
            'class="logo-module"',
            $template,
            'AI-534 anchor: Logo default template must declare `.logo-module`'
        );
        $this->assertStringContainsString(
            'class="logo-link"',
            $template,
            'AI-534 anchor: Logo default template must declare `.logo-link` on the anchor'
        );
    }

    #[Test]
    public function logo_link_floors_44_height_with_flex_centring(): void
    {
        $block = $this->ai534Block();
        $this->assertMatchesRegularExpression(
            '/\.logo-module\s+\.logo-link\s*\{[^}]*min-height:\s*44px;[^}]*display:\s*flex;[^}]*align-items:\s*center;[^}]*\}/s',
            $block,
            'AI-534: .logo-module .logo-link must floor min-height: 44px with flex centring'
        );
    }

    #[Test]
    public function ai534_rule_lives_inside_touch_viewport_media_query(): void
    {
        $touchMediaStart = strpos(
            $this->css,
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)'
        );
        $this->assertNotFalse($touchMediaStart);
        $ai534Pos = strpos($this->css, 'AI-534');
        $this->assertGreaterThan(
            $touchMediaStart,
            $ai534Pos,
            'AI-534 marker must appear AFTER the canonical touch-viewport @media opener'
        );

        $block = $this->ai534Block();
        $this->assertStringNotContainsString(
            '@media (',
            $block,
            'AI-534 rule body must NOT open its own @media (...) — it inherits the touch-viewport block'
        );
    }

    #[Test]
    public function logo_link_class_is_exclusive_to_logo_module_default_template(): void
    {
        // Recon assumption pin: `.logo-link` is used ONLY by the Logo
        // module's `default.blade.php` template. `2rows.blade.php`
        // uses `.module-logo.module-logo-2rows` (different shape).
        // The Big2 template's `.linktree-logo .logo-module a` uses a
        // bare `a` selector (no `.logo-link`) and is unaffected by
        // the AI-534 rule. The combinator `.logo-module .logo-link`
        // is therefore safe at the bare-class level.
        $logoDefault = file_get_contents(base_path(self::LOGO_DEFAULT));
        $this->assertStringContainsString('class="logo-link"', $logoDefault);

        $logo2rows = file_get_contents(base_path('Modules/Logo/resources/views/templates/2rows.blade.php'));
        $this->assertStringNotContainsString(
            'class="logo-link"',
            $logo2rows,
            '2rows.blade.php must NOT use .logo-link (recon assumption — different shape `.module-logo.module-logo-2rows`)'
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
