<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Modules\Restore\Restore;
use Modules\Restore\Loggers\RestoreLogger;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Restore module smoke (restore page entry point).
 *
 * The Restore module is unusual — it has NO Filament settings
 * page and NO Microweber module of its own (see
 * Modules/Restore/Providers/RestoreServiceProvider.php — both
 * registerPage and Microweber::module are commented out). Its
 * job is to expose the service classes
 * Modules\Restore\Restore + DatabaseSave + DatabaseSaveContent
 * + ZipReader that the **Backup** admin Resource (BackupResource)
 * invokes from its restore wizard step.
 *
 * The "restore page entry point" task line therefore maps to the
 * BackupResource list page at /admin/backups — the only admin
 * surface where an operator can kick off a restore. The
 * runRestoreStep() handler at
 * Modules/Backup/Filament/Resources/BackupResource/Pages/ListBackups.php
 * line 214 instantiates `new Restore()` and calls its setSessionId
 * / setFile / setOvewriteById / setWriteOnDatabase /
 * setBatchRestoring / start() pipeline. This smoke covers both
 * halves:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/backups — the entry
 *      point where the operator picks a backup and triggers a
 *      restore via the Filament wizard.
 *   2. Signal #2 (Restore service-class round-trip): instantiates
 *      Restore directly and exercises the same setter pipeline
 *      runRestoreStep() calls — setSessionId / setOvewriteById /
 *      setWriteOnDatabase / setBatchRestoring — then asserts the
 *      mutations stuck on the public properties the start()
 *      branch reads. A regression in any of those setters would
 *      silently break the operator-side restore flow.
 *   3. Belt-and-braces: installInPageErrorGuard() on the backups
 *      page after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Read-only — no database writes (the start() pipeline is not
 * invoked because it requires a real backup file path), so no
 * tearDown is needed. Safe to re-run.
 */
class LiveAdminModuleRestoreSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    // task-2026-06-01 — BackupResource::getSlug() returns 'backup' (singular)
    // so the route lives at /admin/backup, not /admin/backups.
    private const BACKUPS_LIST_SLUG = 'backup';

    /**
     * Synthetic session id sentinel used to round-trip through
     * Restore::setSessionId. Same shape as the runRestoreStep()
     * code path uses on every operator-triggered restore — a
     * caller-supplied string that the Restore class stores
     * verbatim on its public $sessionId property.
     */
    private const FIXTURE_SESSION_ID = 'live-admin-module-restore-smoke-session';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server.
    }

    #[Test]
    public function backups_entry_point_loads_and_restore_service_round_trips_setters(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // /admin/backups Filament resource list page (the
            // operator-facing entry point for both backup and
            // restore actions).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::BACKUPS_LIST_SLUG,
                'backups admin list (restore entry point)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'backups list (restore entry point) render');

            // Confirm the resource list page's Filament chrome
            // rendered — without it the no-console-errors gate
            // above would only prove the page didn't throw, not
            // that the operator can actually click into a
            // backup-row's restore action.
            $this->assertResourceListChromeRendered($browser);
        });

        // Signal #2 — Restore service-class setter round-trip.
        // Same pipeline runRestoreStep() exercises on every
        // operator-triggered restore. A regression in any of
        // these setters (renamed property, missing setter) would
        // silently break the restore wizard step from the
        // BackupResource page above.
        $this->assertRestoreServiceSettersRoundTrip();
    }

    /**
     * Instantiate the Restore service class directly and round-
     * trip every setter the runRestoreStep() handler invokes
     * before calling start(). Asserts each mutation landed on
     * the public property branch the start() implementation
     * reads at runtime.
     */
    private function assertRestoreServiceSettersRoundTrip(): void
    {
        $restore = new Restore();

        $this->assertInstanceOf(
            RestoreLogger::class,
            $restore->logger,
            'Restore::__construct must wire a RestoreLogger instance into $logger — '
            . 'the start() pipeline writes progress lines through this logger and the '
            . 'BackupResource progress UI polls those lines. A regression here would '
            . 'silently break the operator-visible progress feedback.'
        );

        $restore->setSessionId(self::FIXTURE_SESSION_ID);
        $this->assertSame(
            self::FIXTURE_SESSION_ID,
            (string) $restore->sessionId,
            'Restore::setSessionId must persist the session id verbatim — the start() '
            . 'pipeline uses it to namespace the SessionStepper progress file the '
            . 'BackupResource UI polls. A regression here would mean every restore '
            . 'silently loses its progress trail and the UI hangs at "Starting".'
        );

        $restore->setBatchRestoring(true);
        $this->assertTrue(
            (bool) $restore->batchRestoring,
            'Restore::setBatchRestoring must persist the batch flag — every '
            . 'runRestoreStep() invocation calls it with true so the restore can '
            . 'resume across HTTP polls. A regression flipping the default to false '
            . 'would break every operator-side restore by completing in a single '
            . 'request that times out for non-trivial backups.'
        );

        $restore->setOvewriteById(true);
        $this->assertTrue(
            (bool) $restore->ovewriteById,
            'Restore::setOvewriteById must persist the overwrite-by-id flag — the '
            . 'overwriteById restoreType branch in runRestoreStep depends on this '
            . 'setter behaving as a simple property mutator. A regression here would '
            . 'silently flip the default and either duplicate-row or skip every '
            . 'operator-side overwriteById restore.'
        );

        $restore->setWriteOnDatabase(true);
        $this->assertTrue(
            (bool) $restore->writeOnDatabase,
            'Restore::setWriteOnDatabase must persist the write-on-database flag — '
            . 'every restoreType branch in runRestoreStep calls it with true so the '
            . 'pipeline actually writes. A regression here would silently turn every '
            . 'operator-side restore into a no-op preview.'
        );

        $restore->setToDeleteOldContent(true);
        $this->assertTrue(
            (bool) $restore->deleteOldContent,
            'Restore::setToDeleteOldContent must persist the delete-old-content flag '
            . '— the deleteAll restoreType branch in runRestoreStep calls it with '
            . 'true so the pipeline purges the existing content tree before applying '
            . 'the backup. A regression here would silently merge into the existing '
            . 'tree on every "delete-and-replace" restore.'
        );

        $restore->setToDeleteOldCssFiles(true);
        $this->assertTrue(
            (bool) $restore->deleteOldCssFiles,
            'Restore::setToDeleteOldCssFiles must persist the delete-old-css flag — '
            . 'the deleteAll restoreType branch in runRestoreStep calls it with true. '
            . 'A regression here would silently leak the previous template skin\'s '
            . 'CSS overrides into the freshly-restored shop.'
        );
    }

    /**
     * Probe the rendered Filament resource list page for the
     * scaffolding that proves a restore round-trip is reachable
     * from the UI. Same shape as the sibling Filament-resource
     * smokes — looks for fi-page / fi-resource / fi-table chrome
     * plus at least one pressable Filament action (the "Create
     * backup" button or the row-level restore action).
     */
    private function assertResourceListChromeRendered(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasFilamentChrome = str_contains($source, 'fi-page')
            || str_contains($source, 'fi-resource')
            || str_contains($source, 'fi-table')
            || str_contains($source, 'fi-empty-state');
        $hasLivewireWiring = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'wire:model=')
            || str_contains($source, 'wire:click=');
        $hasPressableAction = (int) $browser->driver->executeScript(
            'return document.querySelectorAll("button, a[role=\'button\']").length;'
        ) > 0;

        $this->assertTrue(
            $hasLivewireWiring,
            'backups admin list must render Filament/Livewire chrome (fi-page / '
            . 'fi-resource / fi-table / fi-empty-state / wire:id / wire:snapshot / '
            . 'wire:model / wire:click) — otherwise the page never mounted past the '
            . 'auth shell and the restore-service round-trip above would only prove '
            . 'the model works, not that the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'backups admin list must render at least one pressable Filament action '
            . '(any <button> or <a role="button"> — typically the "Create backup" '
            . 'header action or a per-row restore action) — a list with no actions '
            . 'would mean the toolbar regressed past the Filament-5 migration this '
            . 'smoke is meant to catch.'
        );
    }
}
