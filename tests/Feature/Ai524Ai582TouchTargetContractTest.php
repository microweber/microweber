<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-524 — Teamcard website-link anchor touch target (min-height < 44px).
 * AI-582 — Newsletter `.form-control` ~38px height + checkbox ~13px.
 *
 * Audit source: agent-test tester mobile evaluation 2026-05-15 (390×844).
 *
 * Fix target:
 *   `Templates/Bootstrap/resources/assets/css/public-touch.css`
 *   (source) and its byte-identical served mirror at
 *   `public/templates/bootstrap/css/public-touch.css`.
 *
 * Per project memory `feedback_testing`: file-system reads only,
 * no DB / Filament boot.
 */
class Ai524Ai582TouchTargetContractTest extends TestCase
{
    private const PUBLIC_TOUCH_CSS_SRC    = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const PUBLIC_TOUCH_CSS_SERVED = 'public/templates/bootstrap/css/public-touch.css';

    private string $src;
    private string $served;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src    = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS_SRC));
        $this->served = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS_SERVED));
    }

    // ---- AI-524: Teamcard website link ----------------------------------------

    #[Test]
    public function ai524_marker_comment_present(): void
    {
        $this->assertStringContainsString('AI-524', $this->src, 'Source CSS must reference AI-524');
        $this->assertStringContainsString('AI-524', $this->served, 'Served CSS must reference AI-524');
    }

    #[Test]
    public function ai524_teamcard_anchor_floors_44_height_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.team-card-item\s+a\s*\{[^}]*min-height:\s*44px;[^}]*\}/s',
            $this->src,
            'Source CSS: .team-card-item a must declare min-height: 44px (AI-524)'
        );
    }

    #[Test]
    public function ai524_teamcard_anchor_floors_44_height_in_served(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.team-card-item\s+a\s*\{[^}]*min-height:\s*44px;[^}]*\}/s',
            $this->served,
            'Served CSS: .team-card-item a must declare min-height: 44px (AI-524)'
        );
    }

    #[Test]
    public function ai524_rule_scoped_to_team_card_item_not_global(): void
    {
        // The rule must be selector-scoped: no bare `a { min-height: 44px }`
        // (which would affect every anchor on the page).
        // Block bare `a { min-height: 44px }` declarations anchored to
        // start-of-line. Descendant selectors like `.team-card-item a {`
        // are scoped and allowed; only an unscoped top-level rule fails.
        $this->assertDoesNotMatchRegularExpression(
            '/^a\s*\{\s*min-height:\s*44px/m',
            $this->src,
            'Source CSS must NOT have an unscoped `a { min-height: 44px }` rule at the start of a line'
        );
    }

    // ---- AI-582: Newsletter form-control + checkbox ---------------------------

    #[Test]
    public function ai582_marker_comment_present(): void
    {
        $this->assertStringContainsString('AI-582', $this->src, 'Source CSS must reference AI-582');
        $this->assertStringContainsString('AI-582', $this->served, 'Served CSS must reference AI-582');
    }

    #[Test]
    public function ai582_form_control_floors_44_height_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.newsletter-module-wrapper\s+\.form-control\s*\{[^}]*min-height:\s*44px;[^}]*\}/s',
            $this->src,
            'Source CSS: .newsletter-module-wrapper .form-control must declare min-height: 44px (AI-582)'
        );
    }

    #[Test]
    public function ai582_form_control_floors_44_height_in_served(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.newsletter-module-wrapper\s+\.form-control\s*\{[^}]*min-height:\s*44px;[^}]*\}/s',
            $this->served,
            'Served CSS: .newsletter-module-wrapper .form-control must declare min-height: 44px (AI-582)'
        );
    }

    #[Test]
    public function ai582_checkbox_label_floors_44_height_and_inline_flex_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.newsletter-module-wrapper\s+label:has\(input\[type="checkbox"\]\)\s*\{[^}]*min-height:\s*44px;[^}]*display:\s*inline-flex;[^}]*align-items:\s*center;[^}]*\}/s',
            $this->src,
            'Source CSS: newsletter checkbox label must declare min-height: 44px + inline-flex + align-items: center (AI-582)'
        );
    }

    #[Test]
    public function ai582_checkbox_label_floors_44_height_and_inline_flex_in_served(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.newsletter-module-wrapper\s+label:has\(input\[type="checkbox"\]\)\s*\{[^}]*min-height:\s*44px;[^}]*display:\s*inline-flex;[^}]*align-items:\s*center;[^}]*\}/s',
            $this->served,
            'Served CSS: newsletter checkbox label must declare min-height: 44px + inline-flex + align-items: center (AI-582)'
        );
    }

    // ---- Mirror parity --------------------------------------------------------

    #[Test]
    public function source_and_served_css_are_byte_identical(): void
    {
        $this->assertSame(
            $this->src,
            $this->served,
            'public-touch.css source and served mirror must be byte-identical'
        );
    }
}
