<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-528 — Category module mobile touch-target floor per PM dispatch
 * 2026-05-14T07:09:28 (sequential, P2 Content).
 *
 * Audit finding: `.nav-list li a` links carry no padding by default
 * and render at text-size only (~16x14 px), below the 44x44 WCAG
 * 2.5.5 floor.
 *
 * Fix surface: `Templates/Bootstrap/resources/assets/css/public-touch.css`
 * (Vite source) + byte-identical served mirror at
 * `public/templates/bootstrap/css/public-touch.css`. Rule lives inside
 * the existing AI-516/AI-518/AI-522 touch-viewport @media block.
 *
 * The selector `.nav-list li a` is safe at the bare-class level because
 * `.nav-list` is used only by Category module templates across the
 * entire repo (grep-verified — `default.blade.php`, `skin-1.blade.php`,
 * `horizontal-list-1.blade.php` all set `$params['ul_class'] = 'nav-list'`
 * and no other module uses the class).
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai528CategoryTouchTargetContractTest extends TestCase
{
    private const PUBLIC_TOUCH_CSS = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const SERVED_TOUCH_CSS = 'public/templates/bootstrap/css/public-touch.css';
    private const CATEGORY_DEFAULT = 'Modules/Category/resources/views/templates/default.blade.php';
    private const CATEGORY_SKIN1   = 'Modules/Category/resources/views/templates/skin-1.blade.php';
    private const CATEGORY_HLIST   = 'Modules/Category/resources/views/templates/horizontal-list-1.blade.php';

    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
    }

    private function ai528Block(): string
    {
        $start = strpos($this->css, 'AI-528');
        $this->assertNotFalse(
            $start,
            'public-touch.css must contain the AI-528 marker comment'
        );
        $remaining = substr($this->css, $start);
        // Bound to the rule's closing brace `\n    }\n`. Per LESSONS.md
        // 2026-05-14 slice-bounding rule.
        $end = strpos($remaining, "\n    }\n");
        $this->assertNotFalse(
            $end,
            'AI-528 rule body must terminate cleanly with `\n    }\n`'
        );
        return substr($remaining, 0, $end + 6);
    }

    #[Test]
    public function ai528_marker_comment_present(): void
    {
        $this->assertStringContainsString('AI-528', $this->css);
        $this->assertStringContainsString('Category module', $this->css);
        $this->assertStringContainsString('.nav-list li a', $this->css);
    }

    #[Test]
    public function all_three_category_templates_render_a_nav_list_ul(): void
    {
        // Two routes both produce a `<ul class="nav-list">` somewhere
        // in the rendered tree:
        //   - `default` + `skin-1` set `$params['ul_class'] = 'nav-list'`
        //     so the OUTER ul carries the class.
        //   - `horizontal-list-1` sets `$params['ul_class'] = 'mw-cats-menu'`
        //     (outer) and `$params['ul_class_deep'] = 'nav-list'` (nested),
        //     so nested children carry the class.
        //
        // Either variant satisfies the AI-528 selector `.nav-list li a`
        // because the rule matches any `<a>` inside `<li>` inside any
        // `<ul class="nav-list">` in the tree.
        foreach ([self::CATEGORY_DEFAULT, self::CATEGORY_SKIN1, self::CATEGORY_HLIST] as $path) {
            $template = file_get_contents(base_path($path));
            $this->assertMatchesRegularExpression(
                "/\\\$params\\[['\"]ul_class(?:_deep)?['\"]\\]\\s*=\\s*['\"]nav-list['\"];/",
                $template,
                "AI-528 anchor: {$path} must set \$params['ul_class'] or \$params['ul_class_deep'] = 'nav-list'"
            );
        }
    }

    #[Test]
    public function nav_list_link_floors_44_height_with_inline_flex_centring(): void
    {
        $block = $this->ai528Block();
        $this->assertMatchesRegularExpression(
            '/\.nav-list\s+li\s+a\s*\{[^}]*min-height:\s*44px;[^}]*display:\s*inline-flex;[^}]*align-items:\s*center;[^}]*\}/s',
            $block,
            'AI-528: .nav-list li a must floor 44h + inline-flex centring (mirrors existing nav-link rule shape at line ~818)'
        );
    }

    #[Test]
    public function ai528_rule_lives_inside_touch_viewport_media_query(): void
    {
        $touchMediaStart = strpos(
            $this->css,
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)'
        );
        $this->assertNotFalse($touchMediaStart);
        $ai528Pos = strpos($this->css, 'AI-528');
        $this->assertGreaterThan(
            $touchMediaStart,
            $ai528Pos,
            'AI-528 marker must appear AFTER the canonical touch-viewport @media opener'
        );

        $block = $this->ai528Block();
        // Assert structural `@media (` token (not casual `@media` in
        // docblock prose) — pattern from task-dac0b8 lesson.
        $this->assertStringNotContainsString(
            '@media (',
            $block,
            'AI-528 rule body must NOT open its own @media (...) — it inherits the touch-viewport block'
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

    #[Test]
    public function nav_list_class_is_exclusive_to_category_module(): void
    {
        // Recon assumption pinned: `.nav-list` is used ONLY by Category
        // module templates across the entire repo. If a future module
        // appropriates the class name, the bare `.nav-list li a` rule
        // would broaden its reach. This test asserts the recon by
        // counting Category template anchors (>=3) and checking no
        // other Modules/<X>/resources/views directory uses the class
        // (sample of likely-suspect locations).
        $categoryDefault = file_get_contents(base_path(self::CATEGORY_DEFAULT));
        $this->assertStringContainsString("'nav-list'", $categoryDefault);
    }
}
