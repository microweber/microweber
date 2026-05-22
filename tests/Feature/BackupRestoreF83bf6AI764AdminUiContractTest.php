<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-f83bf6 / AI-764 — Backup / Restore admin UI surfaced.
 *
 * Problem: Five guessed /admin/backup* URLs all returned 404 because
 *   BackupResource had `shouldRegisterNavigation = false` and no custom
 *   slug, so the admin sidebar never exposed the existing Backup UI.
 *
 * Fix (Phase 1 — manual only):
 *   1. BackupResource — navigation enabled, slug `backup`,
 *      icon `heroicon-o-archive-box`, group `System Settings`.
 *   2. RestoreAdminPage — new Filament Page with InteractsWithTable,
 *      slug `restore`, icon `heroicon-o-arrow-path`, same group.
 *      Shows the backup-file list with Restore / Download / Delete
 *      row actions and a confirmation modal.
 *   3. BackupServiceProvider — `FilamentRegistry::registerPage(RestoreAdminPage::class)`.
 *
 * Phase 2/3 (progress bar, scheduled restore) are deferred.
 *
 * Style: file-system reads only — no DB / Filament boot.
 */
class BackupRestoreF83bf6AI764AdminUiContractTest extends TestCase
{
    private const BACKUP_RESOURCE   = 'Modules/Backup/Filament/Resources/BackupResource.php';
    private const RESTORE_PAGE      = 'Modules/Backup/Filament/Pages/RestoreAdminPage.php';
    private const RESTORE_VIEW      = 'Modules/Backup/resources/views/filament/pages/restore-admin.blade.php';
    private const PROVIDER          = 'Modules/Backup/Providers/BackupServiceProvider.php';

    private string $backupResource;
    private string $backupResourceStripped;
    private string $restorePage;
    private string $restorePageStripped;
    private string $provider;
    private string $providerStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $strip = fn (string $s): string =>
            preg_replace('~/\*[\s\S]*?\*/~s', '', preg_replace('~//[^\n]*~', '', $s) ?? $s) ?? $s;

        $this->backupResource        = (string) file_get_contents(base_path(self::BACKUP_RESOURCE));
        $this->backupResourceStripped = $strip($this->backupResource);

        $this->restorePage           = (string) file_get_contents(base_path(self::RESTORE_PAGE));
        $this->restorePageStripped   = $strip($this->restorePage);

        $this->provider              = (string) file_get_contents(base_path(self::PROVIDER));
        $this->providerStripped      = $strip($this->provider);
    }

    // ─── Task markers ─────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_in_backup_resource(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-f83bf6',
            $this->backupResource,
            'BackupResource must carry the AI-764 task marker.'
        );
    }

    #[Test]
    public function task_marker_in_restore_page(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-f83bf6',
            $this->restorePage,
            'RestoreAdminPage must carry the AI-764 task marker.'
        );
    }

    // ─── BackupResource navigation enabled ────────────────────────────────────

    #[Test]
    public function backup_resource_navigation_no_longer_suppressed(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '~shouldRegisterNavigation\s*=\s*false~',
            $this->backupResourceStripped,
            'BackupResource must NOT have shouldRegisterNavigation = false — navigation must be enabled'
        );
    }

    #[Test]
    public function backup_resource_slug_is_backup(): void
    {
        $this->assertMatchesRegularExpression(
            "~function\s+getSlug\([^)]*\)[^{]*\{[^}]*return\s+['\"]backup['\"]~s",
            $this->backupResourceStripped,
            "BackupResource::getSlug() must return 'backup' for the /admin/backup URL"
        );
    }

    #[Test]
    public function backup_resource_icon_is_archive_box(): void
    {
        $this->assertStringContainsString(
            'heroicon-o-archive-box',
            $this->backupResourceStripped,
            'BackupResource navigationIcon must be heroicon-o-archive-box (dispatch spec)'
        );
    }

    #[Test]
    public function backup_resource_group_is_system_settings(): void
    {
        $this->assertStringContainsString(
            'System Settings',
            $this->backupResourceStripped,
            'BackupResource navigationGroup must be System Settings'
        );
    }

    // ─── RestoreAdminPage ─────────────────────────────────────────────────────

    #[Test]
    public function restore_page_file_exists(): void
    {
        $this->assertFileExists(
            base_path(self::RESTORE_PAGE),
            'Modules/Backup/Filament/Pages/RestoreAdminPage.php must exist'
        );
    }

    #[Test]
    public function restore_page_slug_is_restore(): void
    {
        $this->assertMatchesRegularExpression(
            "~function\s+getSlug\([^)]*\)[^{]*\{[^}]*return\s+['\"]restore['\"]~s",
            $this->restorePageStripped,
            "RestoreAdminPage::getSlug() must return 'restore' for the /admin/restore URL"
        );
    }

    #[Test]
    public function restore_page_icon_is_arrow_path(): void
    {
        $this->assertStringContainsString(
            'heroicon-o-arrow-path',
            $this->restorePageStripped,
            'RestoreAdminPage navigationIcon must be heroicon-o-arrow-path (dispatch spec)'
        );
    }

    #[Test]
    public function restore_page_group_is_system_settings(): void
    {
        $this->assertStringContainsString(
            'System Settings',
            $this->restorePageStripped,
            'RestoreAdminPage navigationGroup must be System Settings'
        );
    }

    #[Test]
    public function restore_page_has_table_method(): void
    {
        $this->assertStringContainsString(
            'function table(',
            $this->restorePageStripped,
            'RestoreAdminPage must implement a table() method (InteractsWithTable)'
        );
    }

    #[Test]
    public function restore_page_has_restore_row_action(): void
    {
        $this->assertStringContainsString(
            "'restore'",
            $this->restorePageStripped,
            'RestoreAdminPage table must have a restore row action'
        );
    }

    #[Test]
    public function restore_page_has_confirmation_modal(): void
    {
        $this->assertStringContainsString(
            'requiresConfirmation',
            $this->restorePageStripped,
            'Restore row action must use requiresConfirmation() for safety'
        );
    }

    // ─── BackupServiceProvider registers both ─────────────────────────────────

    #[Test]
    public function provider_registers_restore_page(): void
    {
        $this->assertStringContainsString(
            'RestoreAdminPage::class',
            $this->providerStripped,
            'BackupServiceProvider must register RestoreAdminPage via FilamentRegistry::registerPage()'
        );
    }

    #[Test]
    public function provider_registers_backup_resource(): void
    {
        $this->assertStringContainsString(
            'BackupResource::class',
            $this->providerStripped,
            'BackupServiceProvider must register BackupResource (already present, regression guard)'
        );
    }

    // ─── View file exists ─────────────────────────────────────────────────────

    #[Test]
    public function restore_view_file_exists(): void
    {
        $this->assertFileExists(
            base_path(self::RESTORE_VIEW),
            'restore-admin.blade.php view file must exist for RestoreAdminPage'
        );
    }

    #[Test]
    public function restore_view_renders_table(): void
    {
        $view = (string) file_get_contents(base_path(self::RESTORE_VIEW));
        $this->assertStringContainsString(
            '$this->table',
            $view,
            'restore-admin.blade.php must render {{ $this->table }}'
        );
    }
}
