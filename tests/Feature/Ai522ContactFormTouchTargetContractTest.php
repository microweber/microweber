<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-522 — ContactForm mobile touch-target floors per agent-test audit
 * (2026-05-14, 390x844 iPhone 13) — PM dispatch 2026-05-14T07:02:18.
 *
 * Audit findings (3 surfaces inside `.contact-form-container`):
 *   - Submit button `.btn.btn-primary` measured ~38-40px (site-wide
 *     rule sets border-radius/padding only, not min-height).
 *   - Inputs `.form-control` measured ~38px (despite the global
 *     `--input-height` token defaulting to 44px; floor explicitly
 *     inside the touch-viewport @media as belt-and-braces).
 *   - Newsletter checkbox row `label.mw-ui-check` wraps a native
 *     16x16 `<input type="checkbox">`; bring the label row to 44h.
 *
 * Fix lives in `Templates/Bootstrap/resources/assets/css/public-touch.css`
 * (Vite-built / byte-identical served mirror in
 * `public/templates/bootstrap/css/public-touch.css`).
 *
 * Per project memory `feedback_testing`: file-system reads only, no DB
 * boot.
 */
class Ai522ContactFormTouchTargetContractTest extends TestCase
{
    private const PUBLIC_TOUCH_CSS  = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const SERVED_TOUCH_CSS  = 'public/templates/bootstrap/css/public-touch.css';
    private const CONTACT_FORM_DEFAULT = 'Modules/ContactForm/resources/views/templates/default.blade.php';
    private const CONTACT_FORM_SUBMIT  = 'Modules/ContactForm/resources/views/partials/formSubmit.blade.php';

    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
    }

    private function ai522Block(): string
    {
        $start = strpos($this->css, 'AI-522');
        $this->assertNotFalse(
            $start,
            'public-touch.css must contain the AI-522 marker comment'
        );
        $remaining = substr($this->css, $start);
        // Bound to the last AI-522 rule's closing brace — there are 3
        // rule bodies; the third ends with `\n    }\n` followed by the
        // closing of the @media block `\n}\n`. Slice from marker to the
        // @media closing brace pattern.
        $end = strpos($remaining, "\n}\n");
        $this->assertNotFalse(
            $end,
            'AI-522 rule block must terminate cleanly inside the touch-viewport @media'
        );
        return substr($remaining, 0, $end);
    }

    #[Test]
    public function ai522_marker_comment_present(): void
    {
        $this->assertStringContainsString('AI-522', $this->css);
        $this->assertStringContainsString('ContactForm', $this->css);
        $this->assertStringContainsString('.contact-form-container', $this->css);
    }

    #[Test]
    public function contact_form_default_template_renders_inside_wrapper(): void
    {
        $template = file_get_contents(base_path(self::CONTACT_FORM_DEFAULT));
        $this->assertStringContainsString(
            'class="contact-form-container"',
            $template,
            'AI-522 anchor: default ContactForm template must render inside `.contact-form-container`'
        );
        $this->assertStringContainsString(
            'class="mw-ui-check"',
            $template,
            'AI-522 anchor: newsletter checkbox must use the `.mw-ui-check` label class'
        );
    }

    #[Test]
    public function submit_partial_uses_btn_primary(): void
    {
        $submit = file_get_contents(base_path(self::CONTACT_FORM_SUBMIT));
        $this->assertMatchesRegularExpression(
            '/<button[^>]*type="submit"[^>]*class="[^"]*btn btn-primary/',
            $submit,
            'AI-522 anchor: ContactForm submit partial must render a `.btn.btn-primary` button'
        );
    }

    #[Test]
    public function submit_button_floors_44_height(): void
    {
        $block = $this->ai522Block();
        $this->assertMatchesRegularExpression(
            '/\.contact-form-container\s+\.btn\.btn-primary[^{]*\{[^}]*min-height:\s*44px;[^}]*\}/s',
            $block,
            'AI-522 surface a: .contact-form-container .btn.btn-primary must floor min-height: 44px'
        );
    }

    #[Test]
    public function form_inputs_floor_44_height(): void
    {
        $block = $this->ai522Block();
        $this->assertMatchesRegularExpression(
            '/\.contact-form-container\s+\.form-control[^{]*\{[^}]*min-height:\s*44px;[^}]*\}/s',
            $block,
            'AI-522 surface b: .contact-form-container .form-control must floor min-height: 44px'
        );
        $this->assertStringContainsString('.contact-form-container .form-select', $block);
        $this->assertStringContainsString('.contact-form-container textarea.form-control', $block);
    }

    #[Test]
    public function newsletter_checkbox_label_row_floors_44_height(): void
    {
        $block = $this->ai522Block();
        $this->assertMatchesRegularExpression(
            '/\.contact-form-container\s+label\.mw-ui-check\s*\{[^}]*min-height:\s*44px;[^}]*display:\s*inline-flex;[^}]*align-items:\s*center;[^}]*\}/s',
            $block,
            'AI-522 surface c: .contact-form-container label.mw-ui-check must floor 44h with inline-flex centring'
        );
    }

    #[Test]
    public function ai522_rule_lives_inside_touch_viewport_media_query(): void
    {
        $touchMediaStart = strpos(
            $this->css,
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)'
        );
        $this->assertNotFalse($touchMediaStart);
        $ai522Pos = strpos($this->css, 'AI-522');
        $this->assertGreaterThan(
            $touchMediaStart,
            $ai522Pos,
            'AI-522 marker must appear AFTER the canonical touch-viewport @media opener so it inherits the touch scope'
        );

        $block = $this->ai522Block();
        // Look for a real @media directive (with opening paren), not the
        // casual "@media" mention in the docblock prose comment.
        $this->assertStringNotContainsString(
            '@media (',
            $block,
            'AI-522 rule body must NOT open its own @media (...) — it inherits the touch-viewport block'
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
            'public-touch.css served mirror at public/templates/bootstrap/css/public-touch.css must be byte-identical with the source — re-run the sync after editing'
        );
    }
}
