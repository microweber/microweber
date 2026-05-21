<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-21-a4832f / AI-871 + AI-872 Slice 1
 * Jira: https://microweber.atlassian.net/browse/AI-871
 * Jira: https://microweber.atlassian.net/browse/AI-872
 *
 * AI-871: Live-edit sidebar design pass.
 *   1. Right-rail mini-labels via data-mw-label + CSS ::after
 *   2. Active panel indicator CSS (.live-edit-right-sidebar-active)
 *   3. ESE empty-state: centered icon + guidance text
 *   4. MainDrawer active page CSS + depth indicators
 *
 * AI-872 Slice 1: Module settings design system (Blog, Shop, Pictures/Gallery).
 *   Blog: Sort + Layout controls added
 *   Shop: Columns + show_price + show_add_to_cart toggles added
 *   Pictures: Display tab with Columns + Aspect Ratio + Lightbox toggle
 */
class LiveEditA4832fAI871AI872SidebarsPanelsContractTest extends TestCase
{
    private string $settingsCustomize;
    private string $eseApp;
    private string $generalStyles;
    private string $generalStylesStripped;
    private string $blogSettings;
    private string $shopSettings;
    private string $picturesSettings;

    protected function setUp(): void
    {
        parent::setUp();
        $this->settingsCustomize  = (string) file_get_contents(base_path('packages/frontend-assets/resources/assets/ui/components/Toolbar/SettingsCustomize.vue'));
        $this->eseApp             = (string) file_get_contents(base_path('packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorApp.vue'));
        $this->generalStyles      = (string) file_get_contents(base_path('packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'));
        $this->generalStylesStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->generalStyles) ?? $this->generalStyles;
        $this->blogSettings       = (string) file_get_contents(base_path('Modules/Blog/Filament/BlogSettings.php'));
        $this->shopSettings       = (string) file_get_contents(base_path('Modules/Shop/Filament/ShopModuleSettings.php'));
        $this->picturesSettings   = (string) file_get_contents(base_path('Modules/Pictures/Filament/PicturesModuleSettings.php'));
    }

    // ─── AI-871 §1: Right-rail mini-labels ──────────────────────────────

    #[Test]
    public function right_rail_buttons_carry_data_mw_label(): void
    {
        $labels = ['Layout', 'Template', 'Style', 'AI Edit', 'More'];
        foreach ($labels as $label) {
            $this->assertStringContainsString(
                'data-mw-label="' . $label . '"',
                $this->settingsCustomize,
                "Button must carry data-mw-label=\"{$label}\" for the mini-label ::after pattern."
            );
        }
    }

    #[Test]
    public function css_mini_label_after_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-toolbar-icon-btn\[data-mw-label\]::after\s*\{[^}]*content:\s*attr\(data-mw-label\)/s',
            $this->generalStylesStripped,
            'general-styles.css must define ::after { content: attr(data-mw-label) } for right-rail mini-labels.'
        );
    }

    // ─── AI-871 §2: Active panel indicator ──────────────────────────────

    #[Test]
    public function active_panel_indicator_css_uses_accent(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-toolbar-icon-btn\.live-edit-right-sidebar-active\s*\{[^}]*background-color:\s*var\(--ese-accent-soft/s',
            $this->generalStylesStripped,
            'Active panel indicator must use var(--ese-accent-soft) background.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-toolbar-icon-btn\.live-edit-right-sidebar-active\s*\{[^}]*border-right:\s*2px\s+solid\s+var\(--ese-accent/s',
            $this->generalStylesStripped,
            'Active panel indicator must have border-right: 2px solid var(--ese-accent).'
        );
    }

    // ─── AI-871 §3: ESE empty-state ────────────────────────────────────

    #[Test]
    public function ese_empty_state_uses_new_component(): void
    {
        $this->assertStringContainsString(
            'mw-ese-empty-state',
            $this->eseApp,
            'ElementStyleEditorApp.vue must render .mw-ese-empty-state when no element selected.'
        );
        $this->assertStringContainsString(
            'Click any element to edit its styles',
            $this->eseApp,
            'ESE empty-state must include guidance copy.'
        );
    }

    #[Test]
    public function ese_empty_state_css_present(): void
    {
        $this->assertStringContainsString(
            '.mw-ese-empty-state',
            $this->generalStylesStripped,
            'general-styles.css must include .mw-ese-empty-state CSS rules.'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-ese-empty-state__title\s*\{/s',
            $this->generalStylesStripped,
            'general-styles.css must define .mw-ese-empty-state__title.'
        );
    }

    // ─── AI-871 §4: MainDrawer CSS ──────────────────────────────────────

    #[Test]
    public function main_drawer_active_page_highlight_css_present(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer__item\[data-mw-active-page="true"\]\s*\{[^}]*background-color:\s*var\(--ese-accent-soft/s',
            $this->generalStylesStripped,
            'general-styles.css must include active-page highlight for MainDrawer items.'
        );
    }

    #[Test]
    public function main_drawer_depth_indicator_css_present(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer__item--depth-1::before\s*\{[^}]*width:\s*1px/s',
            $this->generalStylesStripped,
            'general-styles.css must define ::before rule for depth-1 tree connectors.'
        );
    }

    // ─── AI-872 Slice 1: Blog ───────────────────────────────────────────

    #[Test]
    public function blog_settings_has_sort_control(): void
    {
        $this->assertStringContainsString(
            "options.order_by",
            $this->blogSettings,
            'BlogSettings must include a sort-by Select field (options.order_by).'
        );
    }

    #[Test]
    public function blog_settings_has_layout_toggle(): void
    {
        $this->assertStringContainsString(
            "options.layout",
            $this->blogSettings,
            'BlogSettings must include a Layout toggle buttons field (options.layout).'
        );
    }

    // ─── AI-872 Slice 1: Shop ───────────────────────────────────────────

    #[Test]
    public function shop_settings_has_columns_control(): void
    {
        $this->assertStringContainsString(
            "options.columns",
            $this->shopSettings,
            'ShopModuleSettings must include a Columns toggle buttons field (options.columns).'
        );
    }

    #[Test]
    public function shop_settings_has_price_and_cta_toggles(): void
    {
        $this->assertStringContainsString(
            "options.show_price",
            $this->shopSettings,
            'ShopModuleSettings must include Show Price toggle.'
        );
        $this->assertStringContainsString(
            "options.show_add_to_cart",
            $this->shopSettings,
            'ShopModuleSettings must include Show Add to Cart toggle.'
        );
    }

    // ─── AI-872 Slice 1: Pictures (Gallery) ─────────────────────────────

    #[Test]
    public function pictures_settings_has_columns_and_aspect_ratio(): void
    {
        $this->assertStringContainsString(
            "options.columns",
            $this->picturesSettings,
            'PicturesModuleSettings must include Columns toggle buttons.'
        );
        $this->assertStringContainsString(
            "options.aspect_ratio",
            $this->picturesSettings,
            'PicturesModuleSettings must include Aspect Ratio select.'
        );
    }

    #[Test]
    public function pictures_settings_has_lightbox_toggle(): void
    {
        $this->assertStringContainsString(
            "options.lightbox",
            $this->picturesSettings,
            'PicturesModuleSettings must include Lightbox toggle.'
        );
    }

    // ─── Markers ────────────────────────────────────────────────────────

    #[Test]
    public function task_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-21-a4832f', $this->generalStyles);
        $this->assertStringContainsString('task-2026-05-21-a4832f', $this->eseApp);
        $this->assertStringContainsString('task-2026-05-21-a4832f', $this->blogSettings);
        $this->assertStringContainsString('task-2026-05-21-a4832f', $this->shopSettings);
        $this->assertStringContainsString('task-2026-05-21-a4832f', $this->picturesSettings);
    }
}
