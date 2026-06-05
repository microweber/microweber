<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-AI1254 — the admin topbar "+Add" button must be hidden at
 * <=768px (WCAG 2.5.5: it rendered a 73x32 sub-floor pill next to the 44x44
 * Live Edit pill).
 *
 * The source fix (compound `.fi-color-primary` selector on the mobile hide rule
 * so it ties the always-on display:flex rule at (0,4,1) and wins on source
 * order) was already committed (task-2026-06-04-add768) but the Webpack theme
 * bundle was never rebuilt, so the fix was not served. This pins BOTH layers —
 * source AND the served bundle (Tier-2) — so the served regression can't recur.
 *
 * Runtime-verified: at 390px .admin-toolbar-add computes display:none (0x0);
 * at 1440px it stays display:flex (visible). Live Edit pill unaffected (44x44).
 */
class AdminAI1254AddButtonMobileHideContractTest extends TestCase
{
    private const SOURCE = 'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css';
    private const BUNDLE = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    /**
     * Assert the file has a max-width:768px media block whose selector list
     * carries the `.admin-toolbar-add.fi-color-primary` compound followed by
     * display:none — tolerant of source vs built whitespace.
     */
    private function assertMobileCompoundHide(string $relative): void
    {
        $css = (string) file_get_contents(base_path($relative));

        $this->assertMatchesRegularExpression(
            '/@media[^{]*max-width:\s*768px[^{]*\{(?:[^{}]|\{[^{}]*\})*?'
            . 'admin-toolbar-add\.fi-color-primary[^{}]*\{[^{}]*display:\s*none/s',
            $css,
            "{$relative} must hide .admin-toolbar-add.fi-color-primary at <=768px (compound selector wins the specificity tie)."
        );
    }

    #[Test]
    public function source_hides_add_button_on_mobile_with_compound_selector(): void
    {
        $this->assertMobileCompoundHide(self::SOURCE);
    }

    #[Test]
    public function served_bundle_reflects_the_mobile_hide_fix(): void
    {
        // Tier-2 served-bundle guard: the source fix shipped but the bundle was
        // stale (never rebuilt), so the fix wasn't live. Pin the served bytes.
        $this->assertMobileCompoundHide(self::BUNDLE);
    }
}
