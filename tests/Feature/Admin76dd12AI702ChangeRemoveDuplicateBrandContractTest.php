<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-76dd12 / AI-702 CHANGE — remove duplicate brand
 * mark in admin topbar. Designer verified `441050920e` (the
 * original AI-702 ship at task-bcb327) and rejected it because
 * the existing Filament `->brandLogo()` panel-config call was
 * still rendering alongside the new TOPBAR_START hook — two
 * side-by-side Microweber logos visible at desktop 1440.
 *
 * Fix: remove the existing `->brandLogo()` + `->brandName()` +
 * `->brandLogoHeight()` panel-config calls from
 * `src/MicroweberPackages/Admin/Filament/FilamentAdminPanelProvider.php`
 * so Filament's own `.fi-logo` slot does not render. The
 * TOPBAR_START render hook (still in place) owns the full brand-
 * anchor surface.
 *
 * Acceptance per designer dispatch: only the AI-702 brand mark
 * renders; the Filament `.fi-logo` element is absent from the
 * admin topbar.
 */
class Admin76dd12AI702ChangeRemoveDuplicateBrandContractTest extends TestCase
{
    private string $providerSrc;
    private string $providerCodeOnly;

    protected function setUp(): void
    {
        parent::setUp();
        $this->providerSrc = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Admin/Filament/FilamentAdminPanelProvider.php'
        ));
        // Strip block + line comments before scanning so the
        // migration-rationale block (which legitimately quotes the
        // old ->brandLogo() / ->brandName() / ->brandLogoHeight()
        // lines) doesn't false-match the absence-asserts.
        $stripped = preg_replace('/\/\*.*?\*\//s', '', $this->providerSrc);
        $stripped = preg_replace('/\/\/.*$/m', '', $stripped);
        $this->providerCodeOnly = $stripped;
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — duplicate brand path removed
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function brand_logo_panel_call_is_gone_from_code(): void
    {
        // Code-only (comment-stripped) view of the provider must
        // not contain a live ->brandLogo() call.
        $this->assertDoesNotMatchRegularExpression(
            '/->brandLogo\s*\(/',
            $this->providerCodeOnly,
            'Live ->brandLogo() panel call must be removed (AI-702 CHANGE).'
        );
    }

    #[Test]
    public function brand_name_panel_call_is_gone_from_code(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/->brandName\s*\(/',
            $this->providerCodeOnly,
            'Live ->brandName() panel call must be removed (AI-702 CHANGE).'
        );
    }

    #[Test]
    public function brand_logo_height_panel_call_is_gone_from_code(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/->brandLogoHeight\s*\(/',
            $this->providerCodeOnly,
            'Live ->brandLogoHeight() panel call must be removed (AI-702 CHANGE).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — TOPBAR_START hook still owns the brand surface
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function topbar_start_hook_still_renders_brand_mark(): void
    {
        // The render hook from the original AI-702 ship at
        // task-bcb327 must remain — it's the canonical brand
        // surface after AI-702 CHANGE.
        $this->assertMatchesRegularExpression(
            '/PanelsRenderHook::TOPBAR_START/',
            $this->providerSrc,
            'TOPBAR_START render hook must remain.'
        );
        $this->assertMatchesRegularExpression(
            '/mw-admin-brand-mark/',
            $this->providerSrc,
            'TOPBAR_START hook must still emit the .mw-admin-brand-mark class.'
        );
    }

    #[Test]
    public function topbar_start_hook_uses_same_admin_logo_fallback_chain(): void
    {
        // The hook reads mw()->ui->admin_logo() with a fallback to
        // admin_logo_login() — same chain the removed ->brandLogo()
        // used. Pin so the audit reads continuity intentionally.
        $this->assertStringContainsString(
            'admin_logo',
            $this->providerSrc,
            'Brand asset must still resolve via admin_logo() (continuity from the removed ->brandLogo()).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_change_markers_pinned(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-17-76dd12',
            $this->providerSrc,
            'AI-702 CHANGE task-id marker must be present in the provider source.'
        );
        $this->assertStringContainsString(
            'AI-702 CHANGE',
            $this->providerSrc,
            'Source comment must explicitly cite "AI-702 CHANGE" so the audit chain is grep-able.'
        );
        // The original ship hash + the bcb327 task ID should be
        // discoverable in the migration-rationale comment.
        $this->assertStringContainsString(
            '441050920e',
            $this->providerSrc,
            'Comment must cite the original AI-702 ship commit (441050920e) so the audit chain reads from new → old.'
        );
        $this->assertStringContainsString(
            'task-bcb327',
            $this->providerSrc,
            'Comment must cite the original AI-702 ship task-id (task-bcb327) for cross-task grep continuity.'
        );
    }
}
