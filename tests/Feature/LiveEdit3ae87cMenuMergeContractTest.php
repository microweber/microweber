<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-3ae87c — Live Edit toolbar: single unified dropdown.
 *
 * Before this change the toolbar carried TWO dropdowns side-by-side:
 *   (a) ToolbarToolsDropdown.vue (the 3-dots kebab) — Insert Layout,
 *       Template Settings, Style Editor, Quick AI Edit, etc.
 *   (b) An ad-hoc "Menu" button + `#user-menu-wrapper` panel in
 *       Toolbar.vue carrying Report issue / See website / Log out
 *       and the light/dark-mode toggle.
 * The user reported: "we dont need 2 dordowns" — the panels overlap
 * conceptually, are accessed from two places, and the back-compat
 * "Menu" button kept being clipped by the right-side icon sidebar at
 * narrow viewports (see task-74c5f5).
 *
 * Fix:
 *  - The (b) panel is migrated into (a). ToolbarToolsDropdown.vue
 *    fetches `/api/live-edit/get-top-right-menu` and renders the
 *    items at the bottom of the kebab dropdown, separated from the
 *    tools above by a horizontal rule, plus a light/dark toggle.
 *  - The Toolbar.vue `#user-menu-wrapper` div is KEPT in the DOM but
 *    hidden via `style="display: none;"` so that:
 *      1. tests/Browser/AdminLiveEditDropdownAndButtonsTest.php still
 *         finds `#toolbar-user-menu-button`, `#user-menu-wrapper`,
 *         `#user-menu nav` (it asserts presence + click behaviour).
 *      2. SettingsCustomize.vue:651 defensive `classList.remove` and
 *         existing CSS selectors targeting `.mw-le-hamburger` /
 *         `#user-menu-wrapper.active` keep matching their DOM.
 *
 * This contract test pins the merge at the source-file level
 * (no Webpack build invoked, no DOM boot), so it is cheap to run.
 */
class LiveEdit3ae87cMenuMergeContractTest extends TestCase
{
    private string $toolbarVue;
    private string $toolsDropdownVue;

    protected function setUp(): void
    {
        parent::setUp();
        $base = base_path('packages/frontend-assets/resources/assets/ui/components/Toolbar');
        $this->toolbarVue = (string) file_get_contents("$base/Toolbar.vue");
        $this->toolsDropdownVue = (string) file_get_contents("$base/ToolbarToolsDropdown.vue");
    }

    #[Test]
    public function user_menu_wrapper_stays_in_dom_but_is_hidden(): void
    {
        // The wrapper must still be reachable by external test/JS callers,
        // but must not render visually now that its items live inside the
        // tools dropdown.
        $this->assertMatchesRegularExpression(
            '/<div\s+id="user-menu-wrapper"[^>]*style="[^"]*display:\s*none/',
            $this->toolbarVue,
            'Toolbar.vue must keep #user-menu-wrapper in DOM with inline display:none.'
        );
    }

    #[Test]
    public function back_compat_ids_and_classes_remain_in_toolbar_vue(): void
    {
        // task-2026-05-15 / CLAUDE.md rule: back-compat hooks
        // `id="toolbar-user-menu-button"`, `class="mw-le-hamburger"` and
        // `#user-menu nav` MUST be preserved — external code + Dusk tests
        // look for them.
        $this->assertStringContainsString('id="toolbar-user-menu-button"', $this->toolbarVue);
        $this->assertStringContainsString('mw-le-hamburger', $this->toolbarVue);
        $this->assertStringContainsString('id="user-menu"', $this->toolbarVue);
        $this->assertStringContainsString('aria-label="User navigation"', $this->toolbarVue);
    }

    #[Test]
    public function tools_dropdown_renders_user_menu_items_block(): void
    {
        // The merged section renders `v-for="(menuItem, idx) in userMenuItems"`
        // — a per-item link with icon_html + title — followed by the dark/light
        // mode toggle (it's the only place left with `Dark mode`/`Light mode`
        // copy outside the legacy hidden wrapper).
        $this->assertMatchesRegularExpression(
            '/v-for="\(menuItem,\s*idx\)\s+in\s+userMenuItems"/',
            $this->toolsDropdownVue,
            'ToolbarToolsDropdown must iterate user menu items.'
        );
        $this->assertStringContainsString("'Dark mode'", $this->toolsDropdownVue);
        $this->assertStringContainsString("'Light mode'", $this->toolsDropdownVue);
        $this->assertStringContainsString('@click="handleToggleDarkMode"', $this->toolsDropdownVue);
    }

    #[Test]
    public function tools_dropdown_fetches_user_menu_from_named_route(): void
    {
        $this->assertStringContainsString('fetchUserMenu', $this->toolsDropdownVue);
        $this->assertStringContainsString('api.live-edit.get-top-right-menu', $this->toolsDropdownVue);
    }

    #[Test]
    public function tools_dropdown_data_carries_user_menu_state(): void
    {
        $this->assertMatchesRegularExpression(
            '/userMenuItems:\s*\[\s*\]/',
            $this->toolsDropdownVue,
            'ToolbarToolsDropdown data must initialise userMenuItems: [].'
        );
        $this->assertMatchesRegularExpression(
            "/theme:\s*'light'/",
            $this->toolsDropdownVue,
            "ToolbarToolsDropdown data must initialise theme: 'light'."
        );
    }

    #[Test]
    public function tools_dropdown_keeps_a_visual_separator_for_user_menu_section(): void
    {
        // Visual divider between the tools above and the user-menu items
        // below — comment carries the task id for grepability.
        $this->assertStringContainsString('task-2026-05-16-3ae87c', $this->toolsDropdownVue);
        // Supersession: the separator <li> gained a `role="separator"` a11y
        // attribute between class and the v-if guard. Allow the intervening
        // attribute; the v-if guard on userMenuItems.length is the load-bearing
        // part (separator only shows when merged user-menu items exist).
        $this->assertMatchesRegularExpression(
            '/<li\s+class="separator"[^>]*\sv-if="userMenuItems\.length\s*>\s*0"/',
            $this->toolsDropdownVue,
            'A visual separator must precede the merged user-menu items.'
        );
    }

    #[Test]
    public function toolbar_vue_pins_the_task_id_in_the_hidden_wrapper_comment(): void
    {
        $this->assertStringContainsString('task-2026-05-16-3ae87c', $this->toolbarVue);
    }
}
