<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-7326d6 / AI-700 (Medium) — Consolidate Template
 * Settings + Layers + Tools + admin nav into a single left drawer
 * triggered from the toolbar `☰` hamburger.
 *
 * Mutually-unblocks AI-698b item 3: this slice ships BOTH the
 * drawer (AI-700 core) AND the visible toolbar hamburger that
 * triggers it (AI-698b item 3, the previously-blocked piece).
 * Remaining AI-698b items (1: device-preview MwSegmented, 2: `⋯
 * Tools` popover) stay deferred and ship as AI-698c when
 * dispatched.
 *
 * Drawer specification (designer dispatch
 * 2026-05-16T13:39, live-edit-inspiration-from-v2-2026-05-16.md
 * §2 P4 + P5 + P7):
 *
 *   - Width 280px desktop / 100vw mobile (≤ 768px).
 *   - Open: slide from left over var(--t-slow).
 *   - Close via: × button / backdrop tap / ESC.
 *   - Backdrop dim 40%.
 *   - 8 items: Back to Admin, Layers, Template & Layout (renamed
 *     per AI-708), Theme Settings (renamed per AI-708), Users,
 *     See website, Dark-mode toggle (footer per L3.1), Log out.
 *   - prefers-reduced-motion: reduce — drawer transitions instant.
 *   - ESE stays as its own right-side panel — NOT in the drawer.
 *
 * Architecture:
 *
 *   - `MainDrawer.vue` (new component) uses `<Teleport to="body">`
 *     so the drawer + backdrop escape the toolbar's overflow
 *     context. Receives `backToAdminLink` + `menu` as props from
 *     Toolbar.vue (no new API call — re-uses
 *     `api.live-edit.get-top-right-menu` data already fetched).
 *
 *   - `Toolbar.vue` mounts `<MainDrawer ref="mainDrawer">` and
 *     adds a new visible `☰` button at the right end of the
 *     toolbar (after SaveButton, before the legacy-hidden
 *     `#user-menu-wrapper`). The legacy hidden hamburger stays
 *     `display: none` for Dusk-test back-compat per
 *     `tests/Browser/AdminLiveEditDropdownAndButtonsTest.php`.
 *
 *   - `general-styles.css` adds drawer + backdrop + nav-item +
 *     dark-theme rules. Every consumed var() carries a literal
 *     fallback (SOUL #108 spec-doc-nit honoured); the drawer
 *     renders at document.body root outside `.mw-live-edit-page`.
 *
 * Wiring (best-effort handlers; designer verifies in browser):
 *
 *   - Back to Admin → existing `backToAdminLink` prop.
 *   - Layers → `mw.top().app.domTree.show()`.
 *   - Template & Layout → `mw.top().app.templateSettingsWidget.toggle()`.
 *   - Theme Settings → `mw.top().app.templateSettingsWidget.show()`
 *     (same controlBox; the RightSidebar.vue complementary wrapper
 *     hosts the same TemplateSettings content per AI-708 — if
 *     designer wants a distinct trigger that's a follow-up
 *     refinement).
 *   - Users / See website / Log out → URLs from the cached
 *     `menu` data when available; fallback to plain admin paths.
 *   - Dark-mode toggle → `mw.top().admin.theme.toggle()` (same
 *     handler as Toolbar.vue's existing `toggleDarkMode()`).
 */
class LiveEdit7326d6AI700MainDrawerContractTest extends TestCase
{
    private string $mainDrawer;
    private string $toolbar;
    private string $generalStyles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mainDrawer = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/MainDrawer.vue'
        ));
        $this->toolbar = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/Toolbar.vue'
        ));
        $this->generalStyles = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — MainDrawer component shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function drawer_uses_teleport_to_body(): void
    {
        // <Teleport to="body"> escapes the toolbar's overflow context.
        $this->assertMatchesRegularExpression(
            '/<Teleport\s+to="body">/',
            $this->mainDrawer,
            'MainDrawer must use <Teleport to="body"> to render outside the toolbar overflow context.'
        );
    }

    #[Test]
    public function drawer_panel_has_dialog_role_and_aria_modal(): void
    {
        $this->assertMatchesRegularExpression(
            '/<aside[^>]*class="mw-main-drawer"[^>]*role="dialog"[^>]*aria-modal="true"/',
            $this->mainDrawer,
            'Drawer panel must carry role="dialog" + aria-modal="true" so AT users get modal semantics.'
        );
    }

    #[Test]
    public function drawer_open_class_binds_isopen(): void
    {
        $this->assertMatchesRegularExpression(
            "/:class=\"\\{ 'mw-main-drawer--open': isOpen \\}\"/",
            $this->mainDrawer,
            'Drawer must bind .mw-main-drawer--open class to isOpen state.'
        );
    }

    #[Test]
    public function backdrop_renders_only_when_open(): void
    {
        // v-if on the backdrop so it doesn't intercept clicks while
        // closed.
        $this->assertMatchesRegularExpression(
            '/<div\s+v-if="isOpen"\s+class="mw-main-drawer-backdrop"/',
            $this->mainDrawer,
            'Backdrop must be v-if="isOpen" so it does not intercept clicks while drawer is closed.'
        );
        // Backdrop click closes the drawer.
        $this->assertMatchesRegularExpression(
            '/class="mw-main-drawer-backdrop"[\s\S]*?@click="close\(\)"/',
            $this->mainDrawer,
            'Backdrop click must close the drawer.'
        );
    }

    #[Test]
    public function drawer_has_close_button_with_aria_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/<button[^>]*class="mw-main-drawer__close"[^>]*aria-label="Close navigation"[^>]*@click="close\(\)"\s*>/',
            $this->mainDrawer,
            'Drawer must have a × close button with aria-label="Close navigation" wired to close(). (AI-931 renamed from \'Close menu\' to \'Close navigation\' for contextual clarity.)'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Nav items (8 items per designer spec)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function drawer_renders_back_to_admin_item(): void
    {
        // Pin-evolution per task-2026-05-17-7a9913 / AI-798: item labels
        // are now wrapped in <span class="mw-main-drawer__item-label">…</span>
        // for the per-section affordance layout. Original contract
        // (Back to Admin label + backToAdminLink prop binding) preserved.
        $this->assertMatchesRegularExpression(
            '/:href="backToAdminLink"[\s\S]*?<span[^>]*>Back to admin<\/span>/',
            $this->mainDrawer,
            'Back to Admin item must use the backToAdminLink prop and render the label "Back to Admin".'
        );
    }

    #[Test]
    public function drawer_renders_layers_item_wired_to_domtree(): void
    {
        // Pin-evolution per AI-798 — see drawer_renders_back_to_admin_item.
        $this->assertMatchesRegularExpression(
            '/@click="openLayers\(\)"[\s\S]*?<span[^>]*>Layers<\/span>/',
            $this->mainDrawer,
            'Layers item must call openLayers() and render label "Layers".'
        );
        // openLayers() must call mw.top().app.domTree.show().
        $this->assertMatchesRegularExpression(
            "/openLayers\\(\\)\\s*\\{[\\s\\S]*?top\\.app\\.domTree\\.show\\(\\)/",
            $this->mainDrawer,
            'openLayers() must call mw.top().app.domTree.show().'
        );
    }

    #[Test]
    public function drawer_renders_template_and_layout_item_wired_to_widget_toggle(): void
    {
        // Pin-evolution per AI-798.
        $this->assertMatchesRegularExpression(
            '/@click="openTemplateAndLayout\(\)"[\s\S]*?<span[^>]*>Templates &amp; layouts<\/span>/',
            $this->mainDrawer,
            'Template & Layout item must call openTemplateAndLayout() and render "Template &amp; Layout" (HTML-entity escaped per Vue context).'
        );
        $this->assertMatchesRegularExpression(
            "/openTemplateAndLayout\\(\\)\\s*\\{[\\s\\S]*?top\\.app\\.templateSettingsWidget\\.toggle\\(\\)/",
            $this->mainDrawer,
            'openTemplateAndLayout() must call mw.top().app.templateSettingsWidget.toggle().'
        );
    }

    #[Test]
    public function drawer_renders_theme_settings_item_wired_to_widget_show(): void
    {
        // Pin-evolution per AI-798.
        $this->assertMatchesRegularExpression(
            '/@click="openThemeSettings\(\)"[\s\S]*?<span[^>]*>Theme settings<\/span>/',
            $this->mainDrawer,
            'Theme Settings item must call openThemeSettings() and render "Theme Settings".'
        );
        $this->assertMatchesRegularExpression(
            "/openThemeSettings\\(\\)\\s*\\{[\\s\\S]*?top\\.app\\.templateSettingsWidget\\.show\\(\\)/",
            $this->mainDrawer,
            'openThemeSettings() must call mw.top().app.templateSettingsWidget.show() (same content panel via the RightSidebar complementary wrapper per AI-708).'
        );
    }

    #[Test]
    public function drawer_renders_users_see_website_logout_items(): void
    {
        // Pin-evolution per AI-798 — labels now wrapped in <span class="mw-main-drawer__item-label">.
        $this->assertMatchesRegularExpression('/<span[^>]*>Users<\/span>/', $this->mainDrawer);
        $this->assertMatchesRegularExpression('/<span[^>]*>View public site<\/span>/', $this->mainDrawer);
        $this->assertMatchesRegularExpression('/<span[^>]*>Log out<\/span>/', $this->mainDrawer);
    }

    #[Test]
    public function drawer_dark_mode_toggle_swaps_label_by_theme(): void
    {
        // Footer toggle binds aria-pressed to theme state; label
        // switches between "Light mode" / "Dark mode" based on
        // current theme.
        $this->assertMatchesRegularExpression(
            "/:aria-pressed=\"theme === 'dark' \\? 'true' : 'false'\"/",
            $this->mainDrawer,
            'Dark-mode toggle must bind aria-pressed to the theme state for AT users.'
        );
        $this->assertStringContainsString(
            "{{ theme === 'dark' ? 'Light mode' : 'Dark mode' }}",
            $this->mainDrawer,
            'Dark-mode toggle label must swap based on current theme.'
        );
        // toggleTheme() must call mw.top().admin.theme.toggle()
        // (matches Toolbar.vue's existing toggleDarkMode() handler).
        $this->assertMatchesRegularExpression(
            "/toggleTheme\\(\\)\\s*\\{[\\s\\S]*?top\\.admin\\.theme\\.toggle\\(\\)/",
            $this->mainDrawer,
            'toggleTheme() must call mw.top().admin.theme.toggle().'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — ESC key handler + open/close API
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function drawer_open_method_sets_isopen_true(): void
    {
        $this->assertMatchesRegularExpression(
            "/open\\(\\)\\s*\\{[\\s\\S]*?this\\.isOpen\\s*=\\s*true/",
            $this->mainDrawer,
            'open() method must set isOpen = true.'
        );
        // close() flips to false.
        $this->assertMatchesRegularExpression(
            "/close\\(\\)\\s*\\{[\\s\\S]*?this\\.isOpen\\s*=\\s*false/",
            $this->mainDrawer,
            'close() method must set isOpen = false.'
        );
    }

    #[Test]
    public function drawer_handles_escape_key_to_close(): void
    {
        $this->assertMatchesRegularExpression(
            "/onKeyDown\\(event\\)\\s*\\{[\\s\\S]*?event\\.key\\s*===\\s*'Escape'[\\s\\S]*?this\\.close\\(\\)/",
            $this->mainDrawer,
            'onKeyDown handler must close the drawer on ESC key.'
        );
        // Listener registered on mount, removed on unmount.
        $this->assertStringContainsString(
            "window.addEventListener('keydown', this._mwKeyHandler)",
            $this->mainDrawer,
            'ESC handler must be wired via window.addEventListener on mount.'
        );
        $this->assertStringContainsString(
            "window.removeEventListener('keydown', this._mwKeyHandler)",
            $this->mainDrawer,
            'ESC handler must be removed on beforeUnmount/beforeDestroy.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Toolbar wiring (hamburger trigger + MainDrawer mount)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function toolbar_imports_and_registers_main_drawer(): void
    {
        $this->assertStringContainsString(
            'import MainDrawer from "./MainDrawer.vue"',
            $this->toolbar,
            'Toolbar.vue must import MainDrawer.'
        );
        // Component registered in the components object.
        $this->assertMatchesRegularExpression(
            '/components:\s*\{[^}]*MainDrawer/s',
            $this->toolbar,
            'MainDrawer must be registered in Toolbar.vue components.'
        );
    }

    #[Test]
    public function toolbar_mounts_main_drawer_with_props(): void
    {
        $this->assertMatchesRegularExpression(
            '/<MainDrawer\s+ref="mainDrawer"\s+:back-to-admin-link="backToAdminLink"\s+:menu="menu"\s*\/>/s',
            $this->toolbar,
            'Toolbar.vue must mount <MainDrawer ref="mainDrawer" :back-to-admin-link="backToAdminLink" :menu="menu" />.'
        );
    }

    #[Test]
    public function toolbar_renders_visible_hamburger_button(): void
    {
        // New visible hamburger button — id, class, aria-label, click
        // handler all pinned.
        $this->assertMatchesRegularExpression(
            '/<button[^>]*id="mw-live-edit-main-drawer-button"[\s\S]*?aria-label="Open menu"[\s\S]*?@click="\$refs\.mainDrawer && \$refs\.mainDrawer\.open\(\)"/',
            $this->toolbar,
            'Toolbar.vue must render a visible #mw-live-edit-main-drawer-button hamburger triggering MainDrawer.open().'
        );
    }

    #[Test]
    public function legacy_hidden_user_menu_wrapper_preserved(): void
    {
        // Per the live-edit-css-must-be-scoped skill: the Dusk test
        // `AdminLiveEditDropdownAndButtonsTest.php` asserts the
        // presence of #toolbar-user-menu-button + #user-menu-wrapper
        // + #user-menu nav. AI-700 MUST NOT remove them.
        $this->assertStringContainsString(
            'id="toolbar-user-menu-button"',
            $this->toolbar,
            'Legacy #toolbar-user-menu-button must be preserved (Dusk test back-compat).'
        );
        $this->assertStringContainsString(
            'id="user-menu-wrapper"',
            $this->toolbar
        );
        // And still hidden so the new hamburger is the only visible
        // trigger.
        $this->assertMatchesRegularExpression(
            '/id="user-menu-wrapper"[^>]*style="display: none;"/',
            $this->toolbar,
            'Legacy user-menu-wrapper must remain style="display: none;" so the new hamburger is the only visible trigger.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — CSS layout, animation, reduced-motion, mobile width
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function drawer_css_280px_desktop_full_width_mobile(): void
    {
        // Desktop width 280px.
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer\s*\{[^}]*width:\s*280px/s',
            $this->generalStyles,
            'Drawer base width must be 280px per designer spec.'
        );
        // Mobile (≤768px) → 100vw.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*\{[^}]*\.mw-main-drawer\s*\{[^}]*width:\s*100vw/s',
            $this->generalStyles,
            'Mobile breakpoint ≤768px must set drawer width to 100vw per designer spec.'
        );
    }

    #[Test]
    public function drawer_slide_uses_translateX_and_t_slow_transition(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer\s*\{[^}]*transform:\s*translateX\(-100%\)[^}]*transition:\s*transform\s+var\(--t-slow,\s*320ms\)/s',
            $this->generalStyles,
            'Drawer slide-in must use translateX(-100%) baseline + transition: transform var(--t-slow, 320ms) per spec.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer--open\s*\{[^}]*transform:\s*translateX\(0\)/s',
            $this->generalStyles,
            'Open state must set transform: translateX(0).'
        );
    }

    #[Test]
    public function prefers_reduced_motion_disables_slide(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*prefers-reduced-motion:\s*reduce\s*\)\s*\{[\s\S]*?\.mw-main-drawer\s*\{[^}]*transition:\s*none/s',
            $this->generalStyles,
            'prefers-reduced-motion: reduce must disable the drawer slide transition.'
        );
    }

    #[Test]
    public function backdrop_uses_40_percent_black_dim(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer-backdrop\s*\{[^}]*background-color:\s*rgba\(0,\s*0,\s*0,\s*0\.4\)/s',
            $this->generalStyles,
            'Backdrop must use rgba(0, 0, 0, 0.4) for the 40% dim per designer spec.'
        );
    }

    #[Test]
    public function rtl_translates_drawer_in_from_right(): void
    {
        // Logical-property layout — RTL flips the slide direction so
        // the drawer enters from the right (inline-start in RTL).
        $this->assertMatchesRegularExpression(
            '/html\[dir="rtl"\]\s+\.mw-main-drawer\s*\{[^}]*transform:\s*translateX\(100%\)/s',
            $this->generalStyles,
            'RTL must flip the drawer slide so it enters from the inline-start (right edge in RTL).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group F — Markers + token-fallback hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_all_three_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-7326d6', $this->mainDrawer);
        $this->assertStringContainsString('task-2026-05-16-7326d6', $this->toolbar);
        $this->assertStringContainsString('task-2026-05-16-7326d6', $this->generalStyles);
    }

    #[Test]
    public function ai_698b_unblock_note_present_in_source(): void
    {
        // Sub-slicing rationale discoverable from source — same
        // pattern as AI-698a's AI-698b reference.
        $this->assertStringContainsString(
            'AI-698b',
            $this->mainDrawer,
            'MainDrawer.vue must reference AI-698b in the docblock so the mutually-unblocks rationale is discoverable from source.'
        );
        $this->assertStringContainsString(
            'AI-698b',
            $this->generalStyles
        );
    }

    #[Test]
    public function css_tokens_carry_literal_fallbacks(): void
    {
        // Slice from the AI-700 marker to the end of the file and
        // confirm key tokens carry literal fallbacks per SOUL #108
        // spec-doc-nit ask.
        $start = strpos($this->generalStyles, 'AI-700 — Main drawer');
        $this->assertNotFalse($start, 'AI-700 task marker must be present in general-styles.css.');
        $slice = substr($this->generalStyles, $start);
        $tokens = [
            '--ese-surface' => '#ffffff',
            '--ese-text'    => '#111827',
            '--t-slow'      => '320ms',
            '--space-md'    => '13px',
            '--space-sm'    => '8px',
            '--font-control' => '13px',
            '--font-section' => '15px',
            '--radius-sm'   => '6px',
            '--ese-accent'  => '#0d6efd',
        ];
        foreach ($tokens as $token => $fallback) {
            $this->assertMatchesRegularExpression(
                '/var\(' . preg_quote($token, '/') . ',\s*[^)]*' . preg_quote($fallback, '/') . '/',
                $slice,
                "Token {$token} must be consumed as var({$token}, <literal {$fallback}>) in the AI-700 slice."
            );
        }
    }
}
