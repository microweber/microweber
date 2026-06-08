<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-AI1254 — admin topbar "+ Add" button mobile behaviour.
 *
 * Original AI-1254 (task-2026-06-04-add768) HID "+ Add" at ≤768px because it
 * rendered a 73×32 sub-floor pill next to the 44×44 Live Edit pill (WCAG 2.5.5).
 *
 * **task-2026-06-08-addmobile / AI-1254 CHANGE** — once "+ Add" was moved back
 * to the LEFT as the primary v2 action (task-2026-06-08-addleft), hiding it on
 * mobile no longer made sense. So instead of hiding, it now stays VISIBLE on
 * mobile and is lifted to the 44px WCAG touch-target floor. This test was
 * rewritten in place (pin-evolution) to pin the new behaviour on BOTH the
 * source AND the served bundle (Tier-2): a ≤768px @media rule whose
 * `.admin-toolbar-add.fi-color-primary` compound sets min-height:44px and is
 * NOT display:none.
 */
class AdminAI1254AddButtonMobileHideContractTest extends TestCase
{
    private const SOURCE = 'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css';
    private const BUNDLE = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    /**
     * Pull the ≤768px @media block that carries the
     * `.admin-toolbar-add.fi-color-primary` compound rule.
     */
    private function mobileAddRule(string $relative): string
    {
        $css = (string) file_get_contents(base_path($relative));
        $this->assertMatchesRegularExpression(
            '/@media[^{]*max-width:\s*768px[^{]*\{(?:[^{}]|\{[^{}]*\})*?'
            . 'admin-toolbar-add\.fi-color-primary[^{}]*\{[^{}]*\}/s',
            $css,
            "{$relative} must carry a ≤768px @media rule targeting .admin-toolbar-add.fi-color-primary."
        );
        preg_match(
            '/@media[^{]*max-width:\s*768px[^{]*\{(?:[^{}]|\{[^{}]*\})*?'
            . 'admin-toolbar-add\.fi-color-primary[^{}]*\{([^{}]*)\}/s',
            $css,
            $m
        );
        return $m[1] ?? '';
    }

    private function assertMobileVisibleAt44(string $relative): void
    {
        $rule = $this->mobileAddRule($relative);

        $this->assertMatchesRegularExpression(
            '/min-height:\s*44px/',
            $rule,
            "{$relative}: +Add must be lifted to the 44px WCAG touch-target floor on mobile."
        );
        $this->assertDoesNotMatchRegularExpression(
            '/display:\s*none/',
            $rule,
            "{$relative}: +Add must NOT be hidden on mobile anymore — it is the primary v2 left action."
        );
    }

    #[Test]
    public function source_keeps_add_button_visible_at_44px_on_mobile(): void
    {
        $this->assertMobileVisibleAt44(self::SOURCE);
    }

    #[Test]
    public function served_bundle_reflects_the_mobile_visible_fix(): void
    {
        // Tier-2 served-bundle guard — the rebuilt Webpack bundle must carry
        // the same mobile-visible-at-44px rule, not a stale hide.
        $this->assertMobileVisibleAt44(self::BUNDLE);
    }
}
