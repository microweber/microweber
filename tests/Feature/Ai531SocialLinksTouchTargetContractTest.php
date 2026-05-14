<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-531 — SocialLinks module mobile touch-target floor per PM dispatch
 * 2026-05-14T07:28:06 (sequential, P3 Content).
 *
 * Audit finding: each social-link `<a>` wraps a ~24x24 SVG icon with
 * no inherent anchor padding — tester measured 24x24 touch-target at
 * 390x844, below the 44x44 WCAG 2.5.5 floor.
 *
 * **Recon — defensive duplicate by design:**
 * `Templates/Bootstrap/resources/assets/css/main.scss` line 560 already
 * declares `.mw-socialLinks a` 44h+44w rule inside `@media (max-width:
 * 767.98px)`. The fact that tester measured 24x24 anyway indicates
 * the audit site's active template does NOT load Bootstrap's compiled
 * `app.css`. `public-touch.css` is loaded as a separate site-wide
 * mobile-touch stylesheet, so the duplicate rule guarantees the floor
 * across templates.
 *
 * Fix surface: `Templates/Bootstrap/resources/assets/css/public-touch.css`
 * (Vite source) + byte-identical served mirror. Rule lives inside the
 * existing AI-516/AI-518/AI-522/AI-528/AI-530 touch-viewport @media
 * block.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai531SocialLinksTouchTargetContractTest extends TestCase
{
    private const PUBLIC_TOUCH_CSS = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const SERVED_TOUCH_CSS = 'public/templates/bootstrap/css/public-touch.css';
    private const MAIN_SCSS        = 'Templates/Bootstrap/resources/assets/css/main.scss';
    private const SOCIAL_TEMPLATES = [
        'Modules/SocialLinks/resources/views/templates/default.blade.php',
        'Modules/SocialLinks/resources/views/templates/footer.blade.php',
        'Modules/SocialLinks/resources/views/templates/skin-1.blade.php',
        'Modules/SocialLinks/resources/views/templates/skin-2.blade.php',
        'Modules/SocialLinks/resources/views/templates/skin-7.blade.php',
        'Modules/SocialLinks/resources/views/templates/skin-9.blade.php',
    ];

    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
    }

    private function ai531Block(): string
    {
        $start = strpos($this->css, 'AI-531');
        $this->assertNotFalse(
            $start,
            'public-touch.css must contain the AI-531 marker comment'
        );
        // Slice from the docblock closing `*/` so prose mentions of
        // `@media (...)` inside the comment do not false-fail
        // structural absence assertions (same lesson as task-dac0b8
        // applied at the slice-start level instead of the assertion
        // level — broader safety).
        $remaining = substr($this->css, $start);
        $docEnd = strpos($remaining, '*/');
        $this->assertNotFalse(
            $docEnd,
            'AI-531 docblock must terminate with `*/`'
        );
        $remaining = substr($remaining, $docEnd + 2);

        $end = strpos($remaining, "\n    }\n");
        $this->assertNotFalse(
            $end,
            'AI-531 rule body must terminate cleanly with `\n    }\n`'
        );
        return substr($remaining, 0, $end + 6);
    }

    #[Test]
    public function ai531_marker_comment_present(): void
    {
        $this->assertStringContainsString('AI-531', $this->css);
        $this->assertStringContainsString('SocialLinks module', $this->css);
        $this->assertStringContainsString('.mw-socialLinks', $this->css);
    }

    #[Test]
    public function all_six_social_templates_use_mw_sociallinks_wrapper(): void
    {
        foreach (self::SOCIAL_TEMPLATES as $path) {
            $template = file_get_contents(base_path($path));
            $this->assertMatchesRegularExpression(
                '/class="mw-socialLinks\b/',
                $template,
                "AI-531 anchor: {$path} must wrap social links in `.mw-socialLinks`"
            );
        }
    }

    #[Test]
    public function social_link_anchor_floors_44x44_with_flex_centring(): void
    {
        $block = $this->ai531Block();
        $this->assertMatchesRegularExpression(
            '/\.mw-socialLinks\s+a\s*\{[^}]*min-width:\s*44px;[^}]*min-height:\s*44px;[^}]*display:\s*inline-flex;[^}]*align-items:\s*center;[^}]*justify-content:\s*center;[^}]*\}/s',
            $block,
            'AI-531: .mw-socialLinks a must floor 44x44 with inline-flex + center alignment for the SVG icon glyph'
        );
    }

    #[Test]
    public function ai531_rule_lives_inside_touch_viewport_media_query(): void
    {
        $touchMediaStart = strpos(
            $this->css,
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)'
        );
        $this->assertNotFalse($touchMediaStart);
        $ai531Pos = strpos($this->css, 'AI-531');
        $this->assertGreaterThan(
            $touchMediaStart,
            $ai531Pos,
            'AI-531 marker must appear AFTER the canonical touch-viewport @media opener'
        );

        $block = $this->ai531Block();
        $this->assertStringNotContainsString(
            '@media (',
            $block,
            'AI-531 rule body must NOT open its own @media (...) — it inherits the touch-viewport block'
        );
    }

    #[Test]
    public function existing_bootstrap_main_scss_rule_is_documented(): void
    {
        // Recon-decision pin: `main.scss` line 560 already declares an
        // equivalent `.mw-socialLinks a` rule. The AI-531 docblock
        // explicitly calls this out so the next agent does NOT
        // delete the duplicate thinking it is dead.
        $mainScss = file_get_contents(base_path(self::MAIN_SCSS));
        $this->assertStringContainsString(
            '.mw-socialLinks a,',
            $mainScss,
            'AI-531 recon assumption pin: main.scss must declare a `.mw-socialLinks a,` rule (the rule the AI-531 public-touch.css duplicate is defending against template-load gaps)'
        );

        // Self-recon pin: the AI-531 docblock must explicitly call out
        // the "defensive duplicate by design" rationale so future agents
        // do not redo this analysis.
        $self = file_get_contents(__FILE__);
        $this->assertStringContainsString(
            'defensive duplicate by design',
            $self,
            'AI-531 docblock must record the "defensive duplicate by design" rationale'
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
