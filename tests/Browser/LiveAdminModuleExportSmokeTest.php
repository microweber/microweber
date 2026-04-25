<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Export\Formats\JsonExport;
use Modules\Export\Services\Export;
use Modules\Export\Services\ExportTables;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Export module smoke.
 *
 * Shape diverges from the sibling Filament-settings-page smokes
 * and instead mirrors {@see LiveAdminModuleCloudflareSmokeTest}'s
 * "no-admin-form" shape: the Export module ships NO admin form
 * today. Its ExportServiceProvider has the
 * `FilamentRegistry::registerPage(ExportModuleSettings::class)`
 * line commented out, ships no migrations, and binds nothing in
 * the container. The actual integration surface is the
 * Modules\Export\Services\Export pipeline + the format classes
 * (JsonExport / CsvExport / XlsxExport / XmlExport / ZipBatchExport),
 * consumed by the Order / Product / Invoice Filament Resources
 * via route('filament.admin.<resource>.export', ...) actions.
 *
 * The Plan-C.2 task line is "content export page". No such
 * page exists today. This smoke covers the actual integration
 * surface — the Export service's data-buffer + JsonExport
 * write-to-disk pipeline — and pins the missing-form situation
 * so a future Filament-5 commit that ships an admin "Export"
 * page can swap this smoke for the canonical settings/resource-
 * page pattern.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin (the dashboard) —
 *      Export is process-wide / resource-adjacent because the
 *      Order / Product / Invoice admin pages all bind their
 *      "Export selected" toolbar actions to routes that route
 *      through this module on every render.
 *   2. Signal #2 (export round-trip): construct an
 *      ExportTables data buffer with a marker-prefixed row,
 *      drive it through a JsonExport write-to-disk cycle on a
 *      marker-prefixed temp filename, then verify the JSON file
 *      lands on disk with the buffered row encoded verbatim
 *      AND that the file was placed inside the canonical
 *      backup_location() directory the admin Order / Product /
 *      Invoice "Download exported file" link points to. Same
 *      pipeline every consumer ultimately calls when persisting
 *      an export bundle.
 *   3. Belt-and-braces: installInPageErrorGuard() on /admin
 *      after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Plan-C.4 follow-up: when a future commit lands a real
 * /admin/export-module-settings (or /admin/content-export)
 * Filament page, this smoke should be updated to probe that
 * page directly. Until then, the smoke covers the underlying
 * Export-pipeline round-trip per the same three-assertion-minimum
 * the sibling smokes follow.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed export file in tearDown; safe
 * to re-run.
 */
class LiveAdminModuleExportSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const DASHBOARD_PATH = 'admin';

    /**
     * Marker filename for the round-trip export. Long-form to
     * guarantee no collision with any real backup_location()
     * file. The .json suffix is appended by JsonExport from
     * its $type property.
     */
    private const FIXTURE_EXPORT_FILENAME = 'live-admin-module-export-smoke-fixture';

    private const FIXTURE_TABLE_NAME = 'live_admin_module_export_smoke_fixture_table';

    private const FIXTURE_ROW_TITLE = 'Live Admin Module Export Smoke fixture row';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureExportFile();
        parent::tearDown();
    }

    private function purgeFixtureExportFile(): void
    {
        $path = backup_location() . self::FIXTURE_EXPORT_FILENAME . '.json';
        if (is_file($path)) {
            @unlink($path);
        }
    }

    #[Test]
    public function export_pipeline_round_trips_under_the_admin_dashboard_surface(): void
    {
        $this->purgeFixtureExportFile();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of /admin
            // (the dashboard). Export is process-wide so a
            // regression in the service or any of the format
            // classes would surface as a SEVERE log entry on
            // any Order / Product / Invoice admin page that
            // wires up the "Export selected" toolbar action on
            // mount.
            $this->assertPageSmokeOk(
                $browser,
                '/' . self::DASHBOARD_PATH,
                'admin dashboard (Export pipeline surface)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'admin dashboard render');

            // Signal #2 — Export-pipeline round-trip through
            // the same code path the Order / Product / Invoice
            // admin "Export selected" actions ultimately call
            // when writing an export bundle to disk.
            $this->assertExportPipelineWritesJsonBundleToDisk();

            // Confirm the Export service singleton itself is
            // resolvable (the Order / Product / Invoice
            // resources `new Export(...)` directly today, but
            // the smoke also sanity-checks the constructor +
            // logger wiring works).
            $this->assertExportServiceConstructsCleanly();

            // Document the missing-form situation: the
            // Plan-C.2 task line names a "content export page"
            // but no such page exists today
            // (ExportServiceProvider does not call
            // FilamentRegistry::registerPage). Assert the
            // absence explicitly so a future commit that lands
            // the form (and forgets to update this smoke) flips
            // the test.
            $this->assertNoExportSettingsPageRegistered();

            // Confirm the admin dashboard's Filament chrome
            // rendered.
            $this->assertDashboardBootsAroundExport($browser);
        });
    }

    /**
     * Construct an ExportTables data buffer, hand it to a
     * JsonExport writer keyed at a marker-prefixed filename,
     * call ::start() to write to disk, then assert:
     *
     *   - The reported filepath sits inside backup_location()
     *     (the same dir the admin "Download exported file"
     *     link points at via route('admin.backup.download')).
     *   - The file lands on disk.
     *   - The encoded JSON contains the fixture row's marker
     *     verbatim — proves the data buffer survived the
     *     ExportTables → DefaultExport → JsonExport → file_put_contents
     *     round-trip.
     */
    private function assertExportPipelineWritesJsonBundleToDisk(): void
    {
        $tables = new ExportTables();
        $tables->addItemToTable(self::FIXTURE_TABLE_NAME, [
            'id' => 999999,
            'title' => self::FIXTURE_ROW_TITLE,
            'subtype' => 'live-admin-module-export-smoke',
        ]);

        $this->assertNotEmpty(
            $tables->tables ?? [],
            'ExportTables::addItemToTable must accumulate rows in the public $tables '
            . 'buffer — every admin-side "Export selected" action depends on this '
            . 'pre-write buffer to gather the rows it will persist into the export '
            . 'bundle.'
        );

        $exporter = new JsonExport($tables->tables);
        $exporter->setType('json');
        $exporter->setFilename(self::FIXTURE_EXPORT_FILENAME);

        $result = $exporter->start();

        $this->assertIsArray(
            $result,
            'JsonExport::start must return the canonical {files:[…]} contract — the '
            . 'admin "Download exported file" toolbar uses the returned filepath / '
            . 'download URL to drive the post-export redirect.'
        );
        $this->assertArrayHasKey(
            'files',
            $result,
            'JsonExport::start result must contain a "files" key — every consumer '
            . '(Order / Product / Invoice Filament Resource export action) reads '
            . 'this key to build the operator-facing download link.'
        );

        $files = $result['files'] ?? [];
        $this->assertNotEmpty(
            $files,
            'JsonExport::start must report at least one file written — the admin '
            . 'Download URL would be empty otherwise and the "Export selected" '
            . 'toolbar would silently no-op.'
        );

        $writtenFile = $files[0] ?? [];
        $this->assertArrayHasKey(
            'filepath',
            $writtenFile,
            'JsonExport must report a filepath for each written file — without it the '
            . "admin's post-export redirect cannot resolve the download URL."
        );
        $this->assertSame(
            self::FIXTURE_EXPORT_FILENAME . '.json',
            (string) ($writtenFile['filename'] ?? ''),
            'JsonExport must honour the operator-supplied filename (with the .json '
            . 'suffix appended from $type) — a regression that drops the suffix or '
            . 'the operator-supplied stem would break the download URL the admin '
            . 'toolbar emits.'
        );

        $expectedDir = rtrim(backup_location(), '/\\');
        $writtenPath = (string) $writtenFile['filepath'];
        $this->assertStringStartsWith(
            $expectedDir,
            $writtenPath,
            'JsonExport must write into backup_location() — that is the same canonical '
            . "directory the admin's Download exported file link points at via "
            . 'route(admin.backup.download). A regression here would write the export '
            . 'somewhere unreachable by the admin toolbar.'
        );

        $this->assertFileExists(
            $writtenPath,
            'JsonExport::start must actually write the bundle to disk — the admin '
            . 'toolbar would 404 the download otherwise.'
        );

        $contents = (string) file_get_contents($writtenPath);
        $this->assertStringContainsString(
            self::FIXTURE_TABLE_NAME,
            $contents,
            'The exported JSON bundle must contain the fixture table key — proves the '
            . 'ExportTables buffer was passed through to JsonExport without being '
            . 'silently dropped by the encoding pipeline.'
        );
        $this->assertStringContainsString(
            self::FIXTURE_ROW_TITLE,
            $contents,
            'The exported JSON bundle must contain the fixture row title — proves the '
            . 'EncodingFix-wrapped json_encode call inside JsonExport::getDump did not '
            . 'silently strip / re-encode the operator-supplied row content.'
        );
    }

    /**
     * Construct the Export service shell directly — same call
     * shape every consumer (Order / Product / Invoice admin
     * Resources) uses. A regression in the constructor (or in
     * the ExportLogger it newing up) would silently break every
     * admin "Export selected" flow.
     */
    private function assertExportServiceConstructsCleanly(): void
    {
        $service = new Export();

        $this->assertInstanceOf(
            Export::class,
            $service,
            'Export must construct without arguments — Order / Product / Invoice '
            . 'admin resources `new Export(...)` directly when wiring the export '
            . 'toolbar action; a regression here would silently break the export '
            . 'pipeline across every Filament resource that ships an export action.'
        );
        $this->assertNotNull(
            $service->logger,
            'Export::__construct must initialize the public $logger — the export '
            . 'pipeline writes progress events to it during long-running ZipBatchExport '
            . 'runs; a regression here would surface as an undefined-property fatal '
            . 'mid-export.'
        );
    }

    /**
     * Pin the missing-form situation explicitly.
     * /admin/export-module-settings returns a non-200 today
     * because ExportServiceProvider does not call
     * FilamentRegistry::registerPage. When a future commit
     * lands the form (closing Plan-C.2's "content export page"
     * line for real), this assertion will flip and the smoke
     * will need a refresh pointing at the new admin page.
     */
    private function assertNoExportSettingsPageRegistered(): void
    {
        $url = config('app.url', 'http://127.0.0.1:8000');
        $response = \Illuminate\Support\Facades\Http::withoutVerifying()
            ->withOptions(['http_errors' => false, 'allow_redirects' => false])
            ->timeout(15)
            ->get(rtrim($url, '/') . '/admin/export-module-settings');

        $this->assertNotSame(
            200,
            $response->status(),
            'No /admin/export-module-settings Filament page is registered today '
            . '(ExportServiceProvider has its FilamentRegistry::registerPage line '
            . 'commented out). If this assertion ever fires, it means a future '
            . 'commit landed the long-awaited content-export admin form — update '
            . 'this smoke to probe the new page and round-trip an export action '
            . 'through it, matching the sibling Audio/Accordion/Btn pattern. See '
            . 'the docblock of ' . static::class . ' for migration guidance.'
        );
    }

    /**
     * Probe the rendered admin dashboard for the Filament/Livewire
     * scaffolding that proves the page mounted properly. Same
     * probe shape as the sibling Cloudflare/ContentField/
     * Country smokes.
     */
    private function assertDashboardBootsAroundExport(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasFilamentChrome = str_contains($source, 'fi-page')
            || str_contains($source, 'fi-resource')
            || str_contains($source, 'fi-table');
        $hasLivewireWiring = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'wire:model=')
            || str_contains($source, 'wire:click="save"');
        $hasPressableAction = (int) $browser->driver->executeScript(
            'return document.querySelectorAll("button, a[role=\'button\']").length;'
        ) > 0;

        $this->assertTrue(
            $hasFilamentChrome || $hasLivewireWiring,
            'admin dashboard must render Filament/Livewire chrome (fi-page / fi-resource '
            . '/ fi-table / wire:id / wire:snapshot / wire:model / wire:click="save") — '
            . 'otherwise the page never mounted past the auth shell and the Export '
            . 'pipeline round-trip above would only prove the service works, not that '
            . 'the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'admin dashboard must render at least one pressable Filament action (any '
            . '<button> or <a role="button">) — a dashboard with no actions would mean '
            . 'the toolbar regressed past the Filament-5 migration this smoke is meant '
            . 'to catch.'
        );
    }
}
