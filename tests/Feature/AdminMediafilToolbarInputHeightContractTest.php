<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-mediafil — Media Library toolbar input touch-target floors
 * on mobile.
 *
 * DEFECTS (probed at 390x844 on /admin/media)
 *
 *   1. .mw-media-search-input ships at 367x38 — search input above the
 *      media grid (width passes; only height needs lifting).
 *
 *   2. .mw-media-filter-select ships at 86x33 — type filter select
 *      (All types / Images / Videos / ...).
 *
 *   3. .mw-media-filter-date ships at 110x34 — date-range from/to inputs
 *      (two instances side-by-side).
 *
 * SCOPE NOTE
 *   All three rules carry body[class*="fi-panel-admin"] attribute-substring
 *   scope (mirrors sibling task-2026-05-30-mediatap + fitasort + fipprows +
 *   sidegrp blocks).
 *
 * FIX
 *   packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css
 *   carries the rule inside the canonical @media (max-width: 768px),
 *   (pointer: coarse) block.
 */
class AdminMediafilToolbarInputHeightContractTest extends TestCase
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
        // selectors does not satisfy positive or negative assertions on its
        // own (selector-self-match guard).
        $this->cssStripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->css);
    }

    #[Test]
    public function mediafil_block_lives_inside_canonical_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\[class\*="fi-panel-admin"\]\s+\.mw-media-search-input/s',
            $this->cssStripped,
            'mw-media-search-input rule must live inside the canonical (max-width: 768px), (pointer: coarse) media block.'
        );
    }

    #[Test]
    public function media_toolbar_inputs_lifted_to_44px_in_single_multi_selector_rule(): void
    {
        // Three selectors grouped in a single multi-selector rule:
        // search input, filter select, filter date inputs.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.mw-media-search-input\s*,\s*body\[class\*="fi-panel-admin"\]\s+\.mw-media-filter-select\s*,\s*body\[class\*="fi-panel-admin"\]\s+\.mw-media-filter-date\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $this->cssStripped,
            'media toolbar inputs must all lift to 44px min-height !important in a single multi-selector rule.'
        );
    }

    #[Test]
    public function mediafil_block_uses_attribute_substring_scope_not_class_selector(): void
    {
        // Defence: ensure the attribute-substring selector form is used so
        // sibling admin panels (admin-newsletter, admin-shop, ...) inherit
        // the fix.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.mw-media-search-input/s',
            $this->cssStripped,
            'mediafil rules must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.mw-media-filter-select/s',
            $this->cssStripped,
            'mediafil rules must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.mw-media-filter-date/s',
            $this->cssStripped,
            'mediafil rules must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
    }

    #[Test]
    public function mediafil_block_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-mediafil', $this->css);
    }

    #[Test]
    public function mediafil_rules_are_scoped_to_admin_panels_not_global(): void
    {
        // Defence: rules must NOT escape the panel scope. Checkout / profile
        // panels keep their own input styles.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.mw-media-search-input\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'mw-media-search-input touch-target rule must NOT exist at unscoped global level.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.mw-media-filter-select\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'mw-media-filter-select touch-target rule must NOT exist at unscoped global level.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.mw-media-filter-date\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'mw-media-filter-date touch-target rule must NOT exist at unscoped global level.'
        );
    }
}
