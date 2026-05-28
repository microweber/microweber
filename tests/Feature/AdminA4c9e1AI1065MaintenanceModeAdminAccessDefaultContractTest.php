<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-a4c9e1 / AI-1065 — Maintenance Mode "Allow Admin Access"
 * toggle defaulted to OFF (no ->default() call).
 * Jira: https://microweber.atlassian.net/browse/AI-1065
 *
 * Pre-fix: Toggle::make('options.website.maintenance_mode_admin_access')
 * had no ->default() call, so it defaulted to false. Admins enabling
 * maintenance mode were inadvertently locked out of their own site
 * (requires server-side recovery).
 *
 * Fix: added ->default(true) so new installs ship with admin access ON.
 * Existing installs that explicitly set the option to false are unaffected
 * (the ->default() only applies when no saved value exists).
 *
 * Source-of-truth:
 *   Modules/Settings/Filament/Pages/AdminMaintenanceModePage.php
 *
 * Selector-self-match guard: setUp() pre-strips PHP block comments
 * before any absence assertion (uniformity-rule from task-7aa48a).
 */
class AdminA4c9e1AI1065MaintenanceModeAdminAccessDefaultContractTest extends TestCase
{
    private const SOURCE = 'Modules/Settings/Filament/Pages/AdminMaintenanceModePage.php';

    private string $source;

    private string $sourceStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->source = (string) file_get_contents(base_path(self::SOURCE));
        $this->sourceStripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $this->source);
        $this->sourceStripped = (string) preg_replace('~//[^\n]*~', '', $this->sourceStripped);
    }

    // -----------------------------------------------------------------------
    // Group A — Admin access toggle has ->default(true)
    // -----------------------------------------------------------------------

    #[Test]
    public function admin_access_toggle_has_default_true(): void
    {
        $this->assertMatchesRegularExpression(
            "/Toggle::make\('options\.website\.maintenance_mode_admin_access'\)[\s\S]{0,300}?->default\(true\)/",
            $this->source,
            "AI-1065: 'Allow Admin Access' toggle MUST carry ->default(true) so admins are not locked out when enabling maintenance mode on a fresh install."
        );
    }

    #[Test]
    public function admin_access_toggle_label_present(): void
    {
        $this->assertMatchesRegularExpression(
            "/Toggle::make\('options\.website\.maintenance_mode_admin_access'\)[\s\S]{0,200}?->label\('Allow Admin Access'\)/",
            $this->source,
            "AI-1065: 'Allow Admin Access' toggle MUST carry the correct label."
        );
    }

    // -----------------------------------------------------------------------
    // Group B — Maintenance mode toggle itself does NOT default to true
    //           (it should remain opt-in, i.e. default OFF)
    // -----------------------------------------------------------------------

    #[Test]
    public function maintenance_mode_enable_toggle_has_no_default_true(): void
    {
        // Slice the enable-maintenance-mode toggle context (before the admin_access toggle)
        $pos = strpos($this->sourceStripped, "maintenance_mode_admin_access");
        $slice = $pos !== false ? substr($this->sourceStripped, 0, $pos) : $this->sourceStripped;

        $this->assertDoesNotMatchRegularExpression(
            "/Toggle::make\('options\.website\.maintenance_mode'\)[\s\S]{0,300}?->default\(true\)/",
            $slice,
            "AI-1065: the 'Enable Maintenance Mode' toggle MUST NOT default to true — maintenance mode should be opt-in, not enabled by default."
        );
    }

    // -----------------------------------------------------------------------
    // Group C — Task-id markers present
    // -----------------------------------------------------------------------

    #[Test]
    public function task_id_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-a4c9e1',
            $this->source,
            'AI-1065: source MUST carry the AI-1065 task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-1065',
            $this->source,
            'AI-1065: source MUST cite the AI-1065 ticket ID.'
        );
    }
}
