<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-bcb327 / AI-702 (Medium) — Restore Microweber
 * brand mark at the very top-left of the admin top bar.
 *
 * Designer dispatch (admin-shell-improvements-2026-05-16.md §2
 * AD1, per-ticket email 2026-05-16T13:39): filament-5 admin
 * stripped the brand anchor — left edge of the top bar reads
 * `[+ Add] [☰] ... [search] ... [Live Edit] [bell] [user]` with
 * no Microweber identity. Insert wordmark + mark, height 24px
 * desktop / 16px mobile, click routes to /admin.
 *
 * Two-file implementation:
 *
 *   1. FilamentAdminPanelProvider.php — new TOPBAR_START render
 *      hook registered BEFORE the existing `admin-top-navigation-
 *      actions` Livewire hook (Filament accumulates hooks at the
 *      same name in registration order; first-registered renders
 *      leftmost). Builds the brand-mark `<a>` with logo URL from
 *      the same fallback chain `brandLogo()` uses (app()->ui
 *      ->admin_logo() → admin_logo_login()), brand name from
 *      `app()->ui->brand_name()`, and click route to
 *      url(mw_admin_prefix_url() ?: 'admin').
 *
 *   2. general-styles.css — .mw-admin-brand-mark CSS:
 *      - 24px logo height desktop / 16px mobile
 *      - margin-inline-end: var(--space-md, 13px) — separates
 *        from the existing topbar actions
 *      - hover/focus-visible: --ese-surface-hover bg pill
 *      - focus-visible: --ese-accent outline (a11y)
 *      - .sr-only on the wordmark label so AT users still hear
 *        the brand name without occupying horizontal width
 *      - prefers-reduced-motion: reduce — transition: none
 *
 * Token-scoping note (per SOUL #108 spec-doc-nit honoured): every
 * var() carries a literal fallback. The brand mark renders inside
 * the Filament admin panel; :root ESE tokens resolve naturally.
 */
class AdminBcb327AI702BrandMarkContractTest extends TestCase
{
    private string $panelProvider;
    private string $generalStyles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->panelProvider = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Admin/Filament/FilamentAdminPanelProvider.php'
        ));
        $this->generalStyles = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Render hook registration
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function brand_mark_renderhook_registered_at_topbar_start(): void
    {
        $this->assertMatchesRegularExpression(
            '/AI-702[\s\S]*?\$panel->renderHook\(\s*name:\s*PanelsRenderHook::TOPBAR_START/',
            $this->panelProvider,
            'AI-702 must register a render hook at PanelsRenderHook::TOPBAR_START (the brand-mark hook).'
        );
    }

    #[Test]
    public function brand_mark_hook_registered_before_existing_topnav_actions(): void
    {
        // Hook order matters — Filament accumulates hooks in
        // registration order; first-registered renders leftmost.
        // AI-702's brand hook MUST come before the existing
        // admin-top-navigation-actions hook so the brand sits at
        // the very top-left.
        $brandPos = strpos($this->panelProvider, 'AI-702');
        $this->assertNotFalse($brandPos);
        $existingHookPos = strpos($this->panelProvider, "Blade::render('@livewire(\\'admin-top-navigation-actions\\')')");
        $this->assertNotFalse($existingHookPos, 'Existing admin-top-navigation-actions render hook must remain present.');
        $this->assertLessThan(
            $existingHookPos,
            $brandPos,
            'AI-702 brand-mark render hook MUST be registered BEFORE the admin-top-navigation-actions hook so the brand renders leftmost in TOPBAR_START.'
        );
    }

    #[Test]
    public function brand_mark_hook_uses_admin_logo_fallback_chain(): void
    {
        // Same fallback chain as ->brandLogo() — admin_logo()
        // first; falls back to admin_logo_login() when admin_logo
        // is empty. Keeps the brand mark in sync with the
        // configured admin logo source-of-truth.
        $this->assertMatchesRegularExpression(
            '/AI-702[\s\S]*?\$logoUrl\s*=\s*mw\(\)->ui->admin_logo\(\)[\s\S]*?if\s*\(empty\(\$logoUrl\)\)\s*\{[\s\S]*?\$logoUrl\s*=\s*mw\(\)->ui->admin_logo_login\(\)/',
            $this->panelProvider,
            'Brand-mark hook must use admin_logo() with admin_logo_login() fallback (matches the ->brandLogo() pattern).'
        );
    }

    #[Test]
    public function brand_mark_hook_uses_brand_name_with_microweber_fallback(): void
    {
        $this->assertMatchesRegularExpression(
            "/AI-702[\\s\\S]*?\\\$brandName\\s*=\\s*mw\\(\\)->ui->brand_name\\(\\)\\s*\\?:\\s*'Microweber'/",
            $this->panelProvider,
            'Brand-mark hook must read brand_name() with literal "Microweber" fallback.'
        );
    }

    #[Test]
    public function brand_mark_hook_routes_to_admin_url(): void
    {
        $this->assertMatchesRegularExpression(
            "/AI-702[\\s\\S]*?\\\$adminUrl\\s*=\\s*url\\(mw_admin_prefix_url\\(\\)\\s*\\?:\\s*'admin'\\)/",
            $this->panelProvider,
            'Brand-mark hook must compute $adminUrl = url(mw_admin_prefix_url() ?: \'admin\') for the click target.'
        );
    }

    #[Test]
    public function brand_mark_hook_emits_required_html_structure(): void
    {
        // The brand mark <a> must carry:
        //   - class="mw-admin-brand-mark"
        //   - aria-label + title (escaped brand name)
        //   - <img class="mw-admin-brand-mark__image">
        //   - <span class="mw-admin-brand-mark__label sr-only">
        $this->assertMatchesRegularExpression(
            "/AI-702[\\s\\S]*?class=\"mw-admin-brand-mark\"/",
            $this->panelProvider,
            'Brand-mark <a> must carry class="mw-admin-brand-mark".'
        );
        $this->assertMatchesRegularExpression(
            "/AI-702[\\s\\S]*?class=\"mw-admin-brand-mark__image\"/",
            $this->panelProvider,
            'Brand-mark <img> must carry class="mw-admin-brand-mark__image".'
        );
        $this->assertMatchesRegularExpression(
            "/AI-702[\\s\\S]*?class=\"mw-admin-brand-mark__label sr-only\"/",
            $this->panelProvider,
            'Brand-mark <span> wordmark label must carry class="mw-admin-brand-mark__label sr-only" so AT users still hear the brand name.'
        );
    }

    #[Test]
    public function brand_mark_hook_html_escapes_user_data(): void
    {
        // Defence-in-depth: every dynamic value must go through e().
        // The logo URL, brand name, and admin URL are config-derived
        // (low trust); escaping them prevents any future config
        // injection from corrupting the topbar HTML.
        $this->assertMatchesRegularExpression(
            '/AI-702[\s\S]*?href="\'\s*\.\s*e\(\$adminUrl\)\s*\.\s*\'"/',
            $this->panelProvider,
            'href attribute must e() the $adminUrl value.'
        );
        $this->assertMatchesRegularExpression(
            '/AI-702[\s\S]*?src="\'\s*\.\s*e\(\$logoUrl\)\s*\.\s*\'"/',
            $this->panelProvider,
            'src attribute must e() the $logoUrl value.'
        );
        $this->assertMatchesRegularExpression(
            '/AI-702[\s\S]*?aria-label="\'\s*\.\s*e\(\$brandName\)\s*\.\s*\' admin"/',
            $this->panelProvider,
            'aria-label must e() the $brandName value.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — CSS shape (desktop + mobile + a11y)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function brand_mark_css_uses_24px_desktop_height(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-brand-mark__image\s*\{[^}]*height:\s*24px/s',
            $this->generalStyles,
            'Brand-mark image desktop height must be 24px per designer spec.'
        );
    }

    #[Test]
    public function brand_mark_css_collapses_to_16px_on_mobile(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*\{[\s\S]*?\.mw-admin-brand-mark__image\s*\{[^}]*height:\s*16px[^}]*width:\s*16px/s',
            $this->generalStyles,
            'Mobile breakpoint ≤768px must collapse brand-mark image to 16×16 per designer spec.'
        );
    }

    #[Test]
    public function brand_mark_css_uses_space_md_margin_end(): void
    {
        $this->assertMatchesRegularExpression(
            '/a\.mw-admin-brand-mark\s*\{[^}]*margin-inline-end:\s*var\(--space-md,\s*13px\)/s',
            $this->generalStyles,
            'Brand-mark wrapper must use margin-inline-end: var(--space-md) per designer spec.'
        );
    }

    #[Test]
    public function brand_mark_css_uses_focus_visible_accent_outline(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-brand-mark:focus-visible\s*\{[^}]*outline:\s*2px\s+solid\s+var\(--ese-accent/s',
            $this->generalStyles,
            'Brand-mark focus-visible must use a 2px --ese-accent outline (a11y).'
        );
    }

    #[Test]
    public function brand_mark_css_hover_uses_surface_hover(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-brand-mark:hover,\s*\.mw-admin-brand-mark:focus-visible\s*\{[^}]*background-color:\s*var\(--ese-surface-hover/s',
            $this->generalStyles,
            'Brand-mark hover/focus must apply --ese-surface-hover bg pill.'
        );
    }

    #[Test]
    public function brand_mark_css_respects_prefers_reduced_motion(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*prefers-reduced-motion:\s*reduce\s*\)\s*\{[\s\S]*?\.mw-admin-brand-mark\s*\{[^}]*transition:\s*none/s',
            $this->generalStyles,
            'prefers-reduced-motion: reduce must disable the brand-mark transition (SOUL #108 motion pattern).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Markers + token-fallback hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-bcb327', $this->panelProvider);
        $this->assertStringContainsString('task-2026-05-16-bcb327', $this->generalStyles);
    }

    #[Test]
    public function css_tokens_carry_literal_fallbacks(): void
    {
        $start = strpos($this->generalStyles, 'AI-702 — Microweber brand mark');
        $this->assertNotFalse($start, 'AI-702 task marker must be present in general-styles.css.');
        $slice = substr($this->generalStyles, $start);
        $tokens = [
            '--space-md'         => '13px',
            '--space-xs'         => '6px',
            '--space-sm'         => '8px',
            '--radius-sm'        => '6px',
            '--ese-surface-hover' => 'rgba(0, 0, 0, 0.04)',
            '--ese-accent'       => '#0d6efd',
            '--t-fast'           => '120ms',
        ];
        foreach ($tokens as $token => $fallback) {
            $this->assertMatchesRegularExpression(
                '/var\(' . preg_quote($token, '/') . ',\s*[^)]*' . preg_quote($fallback, '/') . '/',
                $slice,
                "Token {$token} must be consumed as var({$token}, <literal {$fallback}>) in the AI-702 slice."
            );
        }
    }
}
