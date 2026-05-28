<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-f8a3c1 / AI-1225
 * Live-edit Tools dropdown: 44px touch targets + IA Preferences/Session sections.
 *
 * Root causes:
 *  KPI-1 — Trigger button was 35px (height: 35px; max-height: 35px).
 *           12 of 14 dropdown items were ~38px (padding: 10px 20px + 15px
 *           font/line-height ≈ 38px total). All below the WCAG 2.5.5 44px floor.
 *  KPI-7 — "Log out" and session items had no grouping header. "Dark mode"
 *           (a global preference) appeared after all editing tools with no
 *           visual separation. "Back to Admin" was duplicated here while
 *           already present in the MainDrawer Navigate section.
 *
 * Fixes:
 *  - Trigger: `height: 35px; max-height: 35px` → `min-height: 44px`
 *  - Items: added `min-height: 44px` on li a, li button (main + submenu)
 *  - Added "Preferences" section label before Dark mode toggle
 *  - Added "Session" section label before user-menu loop
 *  - fetchUserMenu() filters item id="js-live-edit-back-to-admin-link"
 *  - Added `.section-label` CSS (aria-hidden non-interactive label row)
 *
 * Source-of-truth:
 *   packages/frontend-assets/resources/assets/ui/components/Toolbar/ToolbarToolsDropdown.vue
 */
class LiveEditF8a3c1AI1225ToolsDropdownTouchTargetIAContractTest extends TestCase
{
    private const VUE_SOURCE = 'packages/frontend-assets/resources/assets/ui/components/Toolbar/ToolbarToolsDropdown.vue';
    private const SERVED_JS  = 'public/vendor/microweber-packages/frontend-assets/build/live-edit-app.js';

    private string $vueSource;
    private string $vueStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vueSource   = (string) file_get_contents(base_path(self::VUE_SOURCE));
        // Strip JS block comments and line comments before absence assertions
        // so selector-self-match guard does not false-fail on docblock prose.
        $stripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $this->vueSource);
        $this->vueStripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
    }

    // -----------------------------------------------------------------------
    // Group A — Trigger button touch target (WCAG 2.5.5)
    // -----------------------------------------------------------------------

    #[Test]
    public function trigger_has_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.dropdown-trigger\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->vueSource,
            'AI-1225: .dropdown-trigger CSS block MUST carry min-height: 44px (WCAG 2.5.5 44px floor).'
        );
    }

    #[Test]
    public function trigger_no_longer_has_fixed_35px_height(): void
    {
        // The old `.dropdown-trigger { height: 35px; max-height: 35px }` is gone.
        $this->assertDoesNotMatchRegularExpression(
            '/\.dropdown-trigger\s*\{[^}]*\bheight\s*:\s*35px/s',
            $this->vueStripped,
            'AI-1225: .dropdown-trigger MUST NOT carry `height: 35px` — that was the pre-fix 9px-short value.'
        );
    }

    // -----------------------------------------------------------------------
    // Group B — Dropdown item touch targets (WCAG 2.5.5)
    // -----------------------------------------------------------------------

    #[Test]
    public function main_items_have_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.dropdown-content li a,\s*\.dropdown-content li button\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->vueSource,
            'AI-1225: .dropdown-content li a, .dropdown-content li button MUST have min-height: 44px.'
        );
    }

    #[Test]
    public function submenu_items_have_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.submenu li a,\s*\.submenu li button\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->vueSource,
            'AI-1225: .submenu li a, .submenu li button MUST have min-height: 44px.'
        );
    }

    // -----------------------------------------------------------------------
    // Group C — IA Preferences section (Dark mode separated from editing tools)
    // -----------------------------------------------------------------------

    #[Test]
    public function preferences_section_label_present(): void
    {
        $this->assertStringContainsString(
            'class="section-label"',
            $this->vueSource,
            'AI-1225: template MUST carry at least one element with class="section-label" for IA grouping.'
        );
    }

    #[Test]
    public function preferences_label_text_present(): void
    {
        $this->assertStringContainsString(
            '<span>Preferences</span>',
            $this->vueSource,
            'AI-1225: template MUST contain <span>Preferences</span> section label.'
        );
    }

    #[Test]
    public function session_label_text_present(): void
    {
        $this->assertStringContainsString(
            '<span>Session</span>',
            $this->vueSource,
            'AI-1225: template MUST contain <span>Session</span> section label for user-menu items.'
        );
    }

    #[Test]
    public function preferences_section_appears_before_dark_mode_button(): void
    {
        $prefPos = strpos($this->vueSource, '<span>Preferences</span>');
        $darkPos = strpos($this->vueSource, 'handleToggleDarkMode');
        $this->assertNotFalse($prefPos, 'AI-1225: <span>Preferences</span> not found.');
        $this->assertNotFalse($darkPos, 'AI-1225: handleToggleDarkMode not found.');
        $this->assertLessThan(
            $darkPos,
            $prefPos,
            'AI-1225: "Preferences" section label MUST appear BEFORE the Dark mode toggle button in template order.'
        );
    }

    #[Test]
    public function session_section_appears_before_user_menu_loop(): void
    {
        $sessionPos   = strpos($this->vueSource, '<span>Session</span>');
        $userMenuLoop = strpos($this->vueSource, 'v-for="(menuItem, idx) in userMenuItems"');
        $this->assertNotFalse($sessionPos, 'AI-1225: <span>Session</span> not found.');
        $this->assertNotFalse($userMenuLoop, 'AI-1225: userMenuItems v-for not found.');
        $this->assertLessThan(
            $userMenuLoop,
            $sessionPos,
            'AI-1225: "Session" section label MUST appear BEFORE the userMenuItems v-for loop in template order.'
        );
    }

    // -----------------------------------------------------------------------
    // Group D — fetchUserMenu() filters "Back to Admin" duplicate
    // -----------------------------------------------------------------------

    #[Test]
    public function fetch_user_menu_filters_back_to_admin(): void
    {
        $this->assertStringContainsString(
            "item.id !== 'js-live-edit-back-to-admin-link'",
            $this->vueSource,
            "AI-1225: fetchUserMenu() MUST filter item id='js-live-edit-back-to-admin-link' — it duplicates MainDrawer Navigate."
        );
    }

    // -----------------------------------------------------------------------
    // Group E — section-label CSS styling present
    // -----------------------------------------------------------------------

    #[Test]
    public function section_label_css_block_present(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.section-label\s*\{[^}]*pointer-events\s*:\s*none/s',
            $this->vueSource,
            'AI-1225: .section-label CSS block MUST carry pointer-events: none (non-interactive row).'
        );
    }

    #[Test]
    public function section_label_span_has_small_caps_style(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.section-label span\s*\{[^}]*text-transform\s*:\s*uppercase/s',
            $this->vueSource,
            'AI-1225: .section-label span MUST carry text-transform: uppercase for the all-caps IA label style.'
        );
    }

    // -----------------------------------------------------------------------
    // Group F — Task-id markers present
    // -----------------------------------------------------------------------

    #[Test]
    public function task_id_marker_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-f8a3c1',
            $this->vueSource,
            'AI-1225: Vue source MUST carry the task-id marker for cross-surface audit grep.'
        );
    }
}
