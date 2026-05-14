<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-535 — Breadcrumb module link touch-target floor per PM dispatch
 * 2026-05-14T07:50:47.
 *
 * Audit finding: `.breadcrumb-item a` carries no `<a>` padding by
 * default — clickable area is text-bound (~16x14 px), below the
 * 44x44 WCAG 2.5.5 floor.
 *
 * Fix surface: `Templates/Bootstrap/resources/assets/css/public-touch.css`
 * (Vite source) + byte-identical served mirror. Rule lives inside the
 * existing AI-516..AI-534 touch-viewport @media block.
 *
 * **Scope note:** `.breadcrumb-item` is Bootstrap's stock class —
 * broader than the per-module bare classes used in AI-528/AI-531/AI-534.
 * The rule therefore floors EVERY breadcrumb link on the public
 * surface (Microweber breadcrumbs + any custom Bootstrap-styled
 * breadcrumb). Per PM dispatch this is intentional + matches the
 * project's site-wide WCAG policy.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai535BreadcrumbTouchTargetContractTest extends TestCase
{
    private const PUBLIC_TOUCH_CSS  = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const SERVED_TOUCH_CSS  = 'public/templates/bootstrap/css/public-touch.css';
    private const BREADCRUMB_DEFAULT = 'Modules/Breadcrumb/resources/views/templates/default.blade.php';

    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
    }

    private function ai535Block(): string
    {
        $start = strpos($this->css, 'AI-535');
        $this->assertNotFalse(
            $start,
            'public-touch.css must contain the AI-535 marker comment'
        );
        // Slice from AFTER docblock closing `*/` (slice-start lesson
        // family).
        $remaining = substr($this->css, $start);
        $docEnd = strpos($remaining, '*/');
        $this->assertNotFalse($docEnd, 'AI-535 docblock must terminate with `*/`');
        $remaining = substr($remaining, $docEnd + 2);

        $end = strpos($remaining, "\n    }\n");
        $this->assertNotFalse(
            $end,
            'AI-535 rule body must terminate cleanly with `\n    }\n`'
        );
        return substr($remaining, 0, $end + 6);
    }

    #[Test]
    public function ai535_marker_comment_present(): void
    {
        $this->assertStringContainsString('AI-535', $this->css);
        $this->assertStringContainsString('Breadcrumb module', $this->css);
        $this->assertStringContainsString('.breadcrumb-item', $this->css);
    }

    #[Test]
    public function breadcrumb_default_template_renders_breadcrumb_item_with_link(): void
    {
        $template = file_get_contents(base_path(self::BREADCRUMB_DEFAULT));
        $this->assertStringContainsString(
            '<nav aria-label="Breadcrumb">',
            $template,
            'AI-535 anchor: Breadcrumb default template must declare `<nav aria-label="Breadcrumb">`'
        );
        $this->assertMatchesRegularExpression(
            '/<li\s+class="breadcrumb-item">[\s\S]*?<a\s+href=/',
            $template,
            'AI-535 anchor: Breadcrumb default template must render `<li class="breadcrumb-item"><a href="...">` for non-active crumbs'
        );
        $this->assertStringContainsString(
            'aria-current="page"',
            $template,
            'AI-535 a11y anchor: last (active) crumb must carry aria-current="page"'
        );
    }

    #[Test]
    public function breadcrumb_link_floors_44_height_with_inline_flex_and_padding(): void
    {
        $block = $this->ai535Block();
        $this->assertMatchesRegularExpression(
            '/\.breadcrumb-item\s+a\s*\{[^}]*min-height:\s*44px;[^}]*display:\s*inline-flex;[^}]*align-items:\s*center;[^}]*padding:\s*8px\s+4px;[^}]*\}/s',
            $block,
            'AI-535: .breadcrumb-item a must floor min-height: 44px with inline-flex centring and padding 8px 4px (vertical padding gives perceived hit-area inside the row)'
        );
    }

    #[Test]
    public function ai535_rule_lives_inside_touch_viewport_media_query(): void
    {
        $touchMediaStart = strpos(
            $this->css,
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)'
        );
        $this->assertNotFalse($touchMediaStart);
        $ai535Pos = strpos($this->css, 'AI-535');
        $this->assertGreaterThan(
            $touchMediaStart,
            $ai535Pos,
            'AI-535 marker must appear AFTER the canonical touch-viewport @media opener'
        );

        $block = $this->ai535Block();
        $this->assertStringNotContainsString(
            '@media (',
            $block,
            'AI-535 rule body must NOT open its own @media (...) — it inherits the touch-viewport block'
        );
    }

    #[Test]
    public function ai535_scope_is_bootstrap_stock_class_intentional(): void
    {
        // Recon-decision pin: AI-535 intentionally uses the Bootstrap
        // stock `.breadcrumb-item` class (broader than per-module bare
        // classes used in AI-528/AI-531/AI-534). The docblock must
        // explicitly call this out so the next agent doesn't narrow
        // the selector thinking it was a per-module oversight.
        $self = file_get_contents(__FILE__);
        $this->assertStringContainsString(
            'Bootstrap',
            $self,
            'AI-535 docblock must record that `.breadcrumb-item` is Bootstrap stock (intentional broader scope)'
        );
        $this->assertStringContainsString(
            'site-wide WCAG policy',
            $self,
            'AI-535 docblock must record the site-wide WCAG policy rationale for the broader scope'
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
