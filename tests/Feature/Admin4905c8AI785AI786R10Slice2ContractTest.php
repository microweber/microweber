<?php

declare(strict_types=1);

namespace Tests\Feature;

use MicroweberPackages\Filament\Support\AdminDisplayName;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-4905c8 / AI-785 + AI-786 — R10-2 Modules + Marketplace.
 * Jira:
 *   https://microweber.atlassian.net/browse/AI-785
 *   https://microweber.atlassian.net/browse/AI-786
 *
 * AI-785 — CamelCase code-identifier leakage on Marketplace + Modules
 * grid. New helper MicroweberPackages\Filament\Support\AdminDisplayName
 * normalises `LayoutContent` → `Layout content`, `AiWizard` → `AI wizard`,
 * `CustomFields` → `Custom fields`. Acronym whitelist keeps known
 * abbreviations uppercase (AI, SEO, FAQ, URL, …). Applied to the
 * MarketplaceResource `name` column via `formatStateUsing` so module +
 * template names always pass through the display layer.
 *
 * AI-786 — Marketplace cards. Added a `description` column to the card
 * Stack so users can read what each module/template is without opening
 * the slide-over. Truncated to 120 chars (--ese-text-muted color, 3-line
 * clamp via companion CSS). Full description still rendered in the
 * view-details modal.
 *
 * AI-787 (IA reconciliation) — DEFERRED per designer's call (P3, needs
 * product input).
 *
 * What's NOT in this slice (flagged for AI-786 follow-ups):
 *   - Card-level Install CTA primary button (currently in actions
 *     dropdown). Requires Filament card-action layout refactor.
 *   - Price column on MarketplaceItem (schema has only `is_paid` bool;
 *     designer's "no price" complaint moot until the model carries
 *     price data).
 *   - Two-tabs-active-simultaneously visual bug (separate surface,
 *     needs Filament tabs widget recon).
 *   - Bulk-checkbox rationale (Filament default; out of scope).
 */
class Admin4905c8AI785AI786R10Slice2ContractTest extends TestCase
{
    private string $helperSource;
    private string $marketplaceResource;
    private string $css;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->helperSource = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/Support/AdminDisplayName.php'
        ));
        $this->marketplaceResource = (string) file_get_contents(base_path(
            'Modules/Marketplace/Filament/Admin/MarketplaceResource.php'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
        $this->bundle = file_exists(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        )) ? (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        )) : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — AdminDisplayName helper transforms
    // ─────────────────────────────────────────────────────────────────────

    public static function displayNameCases(): array
    {
        return [
            'simple CamelCase' => ['LayoutContent', 'Layout content'],
            'CamelCase with AI acronym prefix' => ['AiWizard', 'AI wizard'],
            'CamelCase with SEO acronym' => ['SeoSettings', 'SEO settings'],
            'single-word acronym FAQ' => ['Faq', 'FAQ'],
            'CamelCase with URL acronym suffix' => ['ShortUrl', 'Short URL'],
            'three-word CamelCase' => ['CustomFieldsSection', 'Custom fields section'],
            'snake_case input' => ['my_first_post', 'My first post'],
            'kebab-case input' => ['header-menu', 'Header menu'],
            'already-Title-Case input idempotent' => ['Active Menu', 'Active menu'],
            'consecutive-cap acronym run RTL' => ['RTLDirection', 'RTL direction'],
            'consecutive-cap acronym run HTML' => ['HTMLEditor', 'HTML editor'],
            'lowercase single word' => ['post', 'Post'],
            'empty string' => ['', ''],
            'whitespace-only' => ['   ', ''],
        ];
    }

    #[Test]
    #[DataProvider('displayNameCases')]
    public function admin_display_name_transforms_correctly(string $input, string $expected): void
    {
        $this->assertSame(
            $expected,
            AdminDisplayName::format($input),
            sprintf('AdminDisplayName::format(%s) must return %s', var_export($input, true), var_export($expected, true))
        );
    }

    #[Test]
    public function admin_display_name_handles_null_input(): void
    {
        // Convenience: null input returns empty string (don't throw).
        $this->assertSame('', AdminDisplayName::format(null));
    }

    #[Test]
    public function admin_display_name_acronyms_constant_includes_audit_examples(): void
    {
        // The audit specifically called out AI as the acronym that
        // needed the special-case. Other acronyms (SEO, FAQ, URL, UI)
        // are equally common in module names. Pin the whitelist core.
        $this->assertArrayHasKey('ai', AdminDisplayName::ACRONYMS);
        $this->assertSame('AI', AdminDisplayName::ACRONYMS['ai']);
        $this->assertArrayHasKey('seo', AdminDisplayName::ACRONYMS);
        $this->assertArrayHasKey('faq', AdminDisplayName::ACRONYMS);
        $this->assertArrayHasKey('url', AdminDisplayName::ACRONYMS);
        $this->assertArrayHasKey('api', AdminDisplayName::ACRONYMS);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — MarketplaceResource consumes the helper on name column
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function marketplace_resource_imports_admin_display_name(): void
    {
        $this->assertStringContainsString(
            'use MicroweberPackages\\Filament\\Support\\AdminDisplayName;',
            $this->marketplaceResource,
            'MarketplaceResource must import the AdminDisplayName helper.'
        );
    }

    #[Test]
    public function marketplace_resource_pipes_name_through_display_name_helper(): void
    {
        // formatStateUsing must call AdminDisplayName::format on the
        // raw `name` state.
        $this->assertMatchesRegularExpression(
            '/->formatStateUsing\(fn\s*\(\?string\s+\$state\):\s*string\s*=>\s*AdminDisplayName::format\(\$state\)\)/',
            $this->marketplaceResource,
            'Marketplace name column must call AdminDisplayName::format($state) via formatStateUsing.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — AI-786 description column added to card Stack
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function marketplace_resource_card_carries_description_column(): void
    {
        // The description column must exist inside the Stack with the
        // mw-marketplace-card-description class for the companion CSS
        // rule to target it.
        $this->assertMatchesRegularExpression(
            "/Tables\\\\Columns\\\\TextColumn::make\('description'\)[^;]*->limit\(120\)[^;]*->columnSpanFull\(\)[^;]*'class' => 'mw-marketplace-card-description'/s",
            $this->marketplaceResource,
            'MarketplaceResource Stack must include a description column with limit(120) + mw-marketplace-card-description class.'
        );
    }

    #[Test]
    public function marketplace_resource_description_uses_textsize_small(): void
    {
        // The description should render at small size so it doesn't
        // compete with the bold title. Use the Filament enum (typed).
        $this->assertStringContainsString(
            '\\Filament\\Support\\Enums\\TextSize::Small',
            $this->marketplaceResource,
            'Description column must use TextSize::Small.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Companion CSS rule + bundle runtime probe
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function css_carries_marketplace_card_description_sizing_rule(): void
    {
        // Pin-evolution: AI-786 CHANGE (task-2026-05-17-bed599) split the
        // original single-rule shape into TWO rules — sizing stays at
        // single-class specificity, COLOUR moves to a compound selector
        // with !important to defeat Filament's dark-mode default cascade.
        // This test now pins only the sizing rule body; the color rule
        // is pinned by its own dedicated test below.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-marketplace-card-description\s*\{/',
            $this->css,
            'general-styles.css must declare `.mw-marketplace-card-description` sizing rule scoped to admin panel.'
        );
        $start = strpos($this->css, '.mw-marketplace-card-description {');
        $this->assertNotFalse($start);
        $end = strpos($this->css, '}', $start);
        $body = substr($this->css, $start, $end - $start);
        $this->assertStringContainsString('-webkit-line-clamp: 3', $body);
        $this->assertStringContainsString('max-height: 4.2em', $body);
        // Negative regression-guard: color MUST NOT live in the sizing
        // rule anymore (the cascade-fix moved it to the compound-selector
        // rule below). If color reappears here, the cascade fix is broken
        // because Filament's dark default will dominate.
        $this->assertStringNotContainsString(
            'color:',
            $body,
            'AI-786 CHANGE moved color OUT of the sizing rule into a compound-selector rule with !important. If color reappears here, the cascade-loss fix is broken.'
        );
    }

    #[Test]
    public function css_color_uses_compound_selector_with_important_dark_mode_fix(): void
    {
        // AI-786 CHANGE (task-2026-05-17-bed599) — Stage-2 cascade-loss
        // fix. The colour rule MUST use the compound
        // `.fi-ta-text-item.mw-marketplace-card-description` selector
        // with `!important` to defeat Filament's higher-specificity
        // dark-mode default (`body.fi-panel-admin.dark .fi-ta-text {
        // color: white }`-style rule). Same Stage-2 family as AI-697 v3.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-text-item\.mw-marketplace-card-description\s*\{[^}]*color:\s*var\(--ese-text-muted[^)]*\)\s*!important/s',
            $this->css,
            'AI-786 CHANGE requires compound `.fi-ta-text-item.mw-marketplace-card-description` selector + `!important` on the color declaration to win against Filament dark-mode defaults.'
        );
    }

    #[Test]
    public function bundle_carries_marketplace_card_description_class(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Served theme bundle absent.');
        }
        $this->assertStringContainsString(
            '.mw-marketplace-card-description',
            $this->bundle,
            'Served theme bundle must carry the .mw-marketplace-card-description rule.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — markers + helper SoT
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai_markers_present_across_surfaces(): void
    {
        $this->assertStringContainsString('task-2026-05-17-4905c8', $this->helperSource);
        $this->assertStringContainsString('AI-785', $this->helperSource);
        $this->assertStringContainsString('task-2026-05-17-4905c8', $this->marketplaceResource);
        $this->assertStringContainsString('AI-785', $this->marketplaceResource);
        $this->assertStringContainsString('AI-786', $this->marketplaceResource);
        $this->assertStringContainsString('task-2026-05-17-4905c8', $this->css);
        $this->assertStringContainsString('AI-786', $this->css);
    }
}
