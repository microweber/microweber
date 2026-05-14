<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-532 — Rating module star touch-target floor per PM dispatch
 * 2026-05-14T07:35:14 (sequential, P3 Content).
 *
 * Audit finding: `.starrr span` 28px and `.starrr i` 20-24px — both
 * below the 44x44 WCAG 2.5.5 floor.
 *
 * Fix surface: `Templates/Bootstrap/resources/assets/css/public-touch.css`
 * (Vite source) + byte-identical served mirror. Rule lives inside the
 * existing AI-516/AI-518/AI-522/AI-528/AI-530/AI-531 touch-viewport
 * @media block.
 *
 * Selector covers both DOM shapes: lib.js currently emits `<i>` for
 * each star (lib.js line 49: `this.$el.append("<i class='..'></i>")`);
 * future starrr versions / replacements may emit `<span>`. Bump
 * `font-size: 28px` enlarges the visual glyph too — stars ARE the
 * primary affordance, not an attached label (so the visual-stays-small
 * pattern from AI-517/518 checkboxes does not apply).
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai532RatingTouchTargetContractTest extends TestCase
{
    private const PUBLIC_TOUCH_CSS = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const SERVED_TOUCH_CSS = 'public/templates/bootstrap/css/public-touch.css';
    private const RATING_DEFAULT   = 'Modules/Rating/resources/views/templates/default.blade.php';
    private const RATING_LIB_JS    = 'Modules/Rating/resources/assets/js/lib.js';

    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
    }

    private function ai532Block(): string
    {
        $start = strpos($this->css, 'AI-532');
        $this->assertNotFalse(
            $start,
            'public-touch.css must contain the AI-532 marker comment'
        );
        // Slice from AFTER docblock closing `*/` (slice-start lesson
        // family from AI-531 commit e7b08781fa).
        $remaining = substr($this->css, $start);
        $docEnd = strpos($remaining, '*/');
        $this->assertNotFalse($docEnd, 'AI-532 docblock must terminate with `*/`');
        $remaining = substr($remaining, $docEnd + 2);

        $end = strpos($remaining, "\n    }\n");
        $this->assertNotFalse(
            $end,
            'AI-532 rule body must terminate cleanly with `\n    }\n`'
        );
        return substr($remaining, 0, $end + 6);
    }

    #[Test]
    public function ai532_marker_comment_present(): void
    {
        $this->assertStringContainsString('AI-532', $this->css);
        $this->assertStringContainsString('Rating module', $this->css);
        $this->assertStringContainsString('.module-rating .starrr', $this->css);
    }

    #[Test]
    public function rating_default_template_renders_starrr_inside_module_rating(): void
    {
        $template = file_get_contents(base_path(self::RATING_DEFAULT));
        $this->assertMatchesRegularExpression(
            '/class="module-rating module-rating-template-default"/',
            $template,
            'AI-532 anchor: Rating default template must declare `.module-rating`'
        );
        $this->assertMatchesRegularExpression(
            '/<div\s+class="starrr"/',
            $template,
            'AI-532 anchor: Rating default template must render `<div class="starrr">`'
        );
    }

    #[Test]
    public function lib_js_emits_i_elements_per_star(): void
    {
        $libJs = file_get_contents(base_path(self::RATING_LIB_JS));
        $this->assertMatchesRegularExpression(
            "/<i\\s+class\\s*=\\s*'?\"?\\s*\\+\\s*this\\.options\\.emptyStarClass/",
            $libJs,
            'AI-532 anchor: lib.js must emit `<i class="...">` per star (the DOM shape the rule targets)'
        );
    }

    #[Test]
    public function star_touch_target_floors_44x44_with_centring_and_font_bump(): void
    {
        $block = $this->ai532Block();
        $this->assertMatchesRegularExpression(
            '/\.module-rating\s+\.starrr\s+span\s*,\s*\.module-rating\s+\.starrr\s+i\s*\{[^}]*min-width:\s*44px;[^}]*min-height:\s*44px;[^}]*display:\s*inline-flex;[^}]*align-items:\s*center;[^}]*justify-content:\s*center;[^}]*font-size:\s*28px;[^}]*\}/s',
            $block,
            'AI-532: .module-rating .starrr span/.module-rating .starrr i must share a single rule with 44x44 + inline-flex + center + font-size 28px'
        );
    }

    #[Test]
    public function ai532_rule_lives_inside_touch_viewport_media_query(): void
    {
        $touchMediaStart = strpos(
            $this->css,
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)'
        );
        $this->assertNotFalse($touchMediaStart);
        $ai532Pos = strpos($this->css, 'AI-532');
        $this->assertGreaterThan(
            $touchMediaStart,
            $ai532Pos,
            'AI-532 marker must appear AFTER the canonical touch-viewport @media opener'
        );

        $block = $this->ai532Block();
        $this->assertStringNotContainsString(
            '@media (',
            $block,
            'AI-532 rule body must NOT open its own @media (...) — it inherits the touch-viewport block'
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
