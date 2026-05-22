<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-834339 — Login page Remember Me + Terms checkbox
 * WCAG 2.5.5 touch-target fixes.
 *
 * Tester measured at 390×844:
 *   Remember me checkbox: 18×18px (FAIL — below 44px floor)
 *   Remember me label:    93×20px (FAIL — below 44px floor)
 *
 * Fix: .checkbox-wrapper gets min-height:44px + cursor:pointer;
 *      .checkbox-wrapper label gets min-height:44px + inline-flex;
 *      covers both Login (remember me) and Register (terms) checkboxes.
 *
 * Note: ticket AI-900 was already used for the CONTACT US salmon fix
 * (task-2026-05-22-10ee46). Using task marker for this fix.
 */
class Auth834339LoginCheckboxTouchTargetContractTest extends TestCase
{
    private string $src;
    private string $srcStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(
            base_path('src/MicroweberPackages/User/resources/views/admin/auth/index.blade.php')
        );
        // Strip CSS /* */ block comments to avoid self-match on prose.
        $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->src) ?? $this->src;
    }

    // ─── .checkbox-wrapper min-height ────────────────────────────────────────

    #[Test]
    public function checkbox_wrapper_gets_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.checkbox-wrapper\s*\{[^}]*min-height:\s*44px~s',
            $this->src,
            '.checkbox-wrapper must have min-height: 44px to form a 44-tall tap area.'
        );
    }

    // ─── .checkbox-wrapper label min-height ──────────────────────────────────

    #[Test]
    public function checkbox_wrapper_label_gets_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.checkbox-wrapper\s+label\s*\{[^}]*min-height:\s*44px~s',
            $this->src,
            '.checkbox-wrapper label must have min-height: 44px — label occupies the full tap height.'
        );
    }

    #[Test]
    public function checkbox_wrapper_label_uses_inline_flex(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.checkbox-wrapper\s+label\s*\{[^}]*display:\s*inline-flex~s',
            $this->src,
            '.checkbox-wrapper label must use display:inline-flex for vertical centering.'
        );
    }

    // ─── Checkbox itself unchanged (visual glyph stays standard size) ────────

    #[Test]
    public function checkbox_input_still_has_width_auto(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.checkbox-wrapper\s+input\[type="checkbox"\]\s*\{[^}]*width:\s*auto~s',
            $this->srcStripped,
            '.checkbox-wrapper input[type="checkbox"] must still carry width:auto — native glyph stays standard size.'
        );
    }

    // ─── Task marker + regression guards ─────────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-834339',
            $this->src,
            'admin/auth/index.blade.php must carry the task-2026-05-22-834339 marker.'
        );
    }

    #[Test]
    public function ai863_forgot_password_anchor_still_present(): void
    {
        $this->assertStringContainsString(
            "route('password.request')",
            $this->src,
            'AI-863 forgot-password anchor (route password.request) must still be present.'
        );
    }

    #[Test]
    public function ai864_autocomplete_tokens_still_present(): void
    {
        $this->assertStringContainsString(
            'autocomplete="username"',
            $this->src,
            'AI-864 autocomplete="username" token must still be present.'
        );
    }

    #[Test]
    public function ai865_form_footer_tap_target_still_present(): void
    {
        // AI-865 added min-height: 44px to .form-footer a.
        $this->assertMatchesRegularExpression(
            '~\.form-footer\s+a\s*\{[^}]*min-height:\s*44px~s',
            $this->src,
            'AI-865 .form-footer a min-height: 44px must still be present.'
        );
    }
}
