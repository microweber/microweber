<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-admui — Filament admin medium-size button + link-action
 * touch-target floors.
 *
 * DEFECTS (probed at 390x844 across resource list pages — recurs every
 * Filament admin surface using fi-size-md primitives)
 *
 *   1. `.fi-btn.fi-size-md` ships at 42px height — 2px short of the
 *      WCAG 2.5.5 / iOS HIG 44x44 floor. Sites observed:
 *        - `/admin/users` pagination Next: 81x42
 *        - `/admin/posts` Categories toggle: 148x42
 *        - `/admin/products` action group: 72x42
 *      Filament pins both min-height and height on these buttons, so
 *      the override must reset both (same trick as the AI-1135 badge
 *      fix — height wins over min-height when both are !important).
 *
 *   2. `.fi-link.fi-ac-link-action` ships at 20px height (link-styled
 *      page-header actions like "Reload Packages" / "Licenses" on
 *      `/admin/marketplace`). Lift to 44px hit area via inline-flex +
 *      min-height + align-items: center.
 *
 * FIX
 *   `packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css`
 *   carries rules inside the canonical
 *   `@media (max-width: 768px), (pointer: coarse)` block. Each rule
 *   scoped via `body.fi-panel-admin` for specificity bump over
 *   Filament's later-cascade primitive styles.
 */
class AdminAdmuiButtonTouchTargetContractTest extends TestCase
{
    private string $css;
    private string $cssStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css'
        ));

        // Pre-strip CSS block comments so docblock prose mentioning rule
        // selectors does not satisfy negative or positive assertions on
        // its own (selector-self-match guard).
        $this->cssStripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->css);
    }

    #[Test]
    public function admui_block_lives_inside_canonical_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\.fi-panel-admin\s+\.fi-btn\.fi-size-md/s',
            $this->cssStripped,
            'fi-btn.fi-size-md rule must live inside the canonical (max-width: 768px), (pointer: coarse) media block.'
        );
    }

    #[Test]
    public function size_md_button_overrides_both_min_height_and_height(): void
    {
        // The fi-btn.fi-size-md primitive ships with both height: 42px
        // and min-height: 42px pinned !important — override requires
        // both properties (height wins over min-height when both set).
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-btn\.fi-size-md\s*\{[^}]*min-height:\s*44px\s*!important[^}]*height:\s*auto\s*!important[^}]*\}/s',
            $this->cssStripped,
            'fi-btn.fi-size-md rule must override both min-height (44px) and height (auto) with !important.'
        );
    }

    #[Test]
    public function link_action_lifted_to_44px_inline_flex(): void
    {
        // Link-style page-header actions ship at 20px height (no
        // padding). Lift via inline-flex + min-height + align-items.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-link\.fi-ac-link-action[\s\S]*?\{[^}]*display:\s*inline-flex\s*!important[^}]*align-items:\s*center\s*!important[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $this->cssStripped,
            'fi-link.fi-ac-link-action rule must use inline-flex + align-items: center + min-height: 44px all !important.'
        );
    }

    #[Test]
    public function admui_block_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-admui', $this->css);
    }

    #[Test]
    public function admui_rules_are_scoped_to_admin_panel_not_global(): void
    {
        // Defence: rules must NOT escape the body.fi-panel-admin scope.
        // Checkout / profile panels keep their own button styles.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.fi-btn\.fi-size-md\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'fi-btn.fi-size-md touch-target rule must NOT exist at unscoped global level.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.fi-link\.fi-ac-link-action\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'fi-link.fi-ac-link-action touch-target rule must NOT exist at unscoped global level.'
        );
    }
}
