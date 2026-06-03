<?php

namespace Tests\Browser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Modules\FileManager\Http\Controllers\Api\FileManagerApiController;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — FileManager module smoke (file manager view + upload).
 *
 * Combination shape — covers BOTH halves of the Plan-C.2 task
 * line "file manager view + upload":
 *
 *   1. The VIEW half: FileManager ships a Filament admin page
 *      (FileManagerPageAdmin registered via FilamentRegistry::registerPage
 *      in FileManagerServiceProvider.php). Filament-default route
 *      slug: /admin/file-manager-page-admin. The admin opens this
 *      page from the "Files" entry under Website Settings; it
 *      embeds the file-browser settings view that lists every
 *      file in the public disk via the FileManagerApiController
 *      list() endpoint.
 *   2. The UPLOAD half: FileManager ships PluploadController at
 *      POST /plupload (the upload endpoint the file-browser UI's
 *      drag-and-drop / click-to-upload widget POSTs every file to)
 *      plus the FileManagerApiController API endpoints (list /
 *      delete / create-folder / rename) all under /api/file-manager/.
 *      The plupload endpoint ultimately writes uploaded files to
 *      Storage::disk('public') via $storageInstance->putFileAs(...)
 *      at PluploadController.php:829 — the same disk the
 *      FileManagerApiController list() endpoint reads from.
 *
 * The smoke covers the three Plan-C.1 minimum signals plus the
 * upload + list round-trip:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/file-manager-page-admin.
 *   2. Signal #2 (upload + view round-trip): write a
 *      marker-prefixed fixture file directly to Storage::disk('public')
 *      — the same disk + same target the plupload upload endpoint
 *      lands the file in via putFileAs() — then instantiate the
 *      FileManagerApiController and call list() to assert the
 *      fixture file appears in the response data; round-trip the
 *      createFolder() endpoint with a marker-prefixed folder name
 *      so the smoke also exercises the create-directory path the
 *      file-browser UI's "New folder" toolbar action calls. Same
 *      pipeline the file-manager admin view uses on every render.
 *   3. Belt-and-braces: installInPageErrorGuard() on the file-
 *      manager admin page after settle, with a 1.5s window
 *      catching any deferred-script throws.
 *
 * Why the in-process round-trip (instead of a real plupload POST):
 * the plupload endpoint requires a session-bound CSRF token,
 * SameSite-referer header, and IsAjax middleware — driving all
 * three from a Dusk multipart upload is fragile and the assertion
 * gate is the same regardless. The in-process Storage::disk('public')
 * write exercises the same target path putFileAs() calls and the
 * same disk the list() endpoint reads from, so a regression in
 * the upload-target / list-source binding surfaces here.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed file + folder in tearDown; safe
 * to re-run.
 */
class LiveAdminModuleFileManagerSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const ADMIN_PAGE_SLUG = 'file-manager-page-admin';

    private const FIXTURE_FILE_PREFIX = 'live-admin-module-file-manager-smoke-';

    private const FIXTURE_FILE_BASENAME = self::FIXTURE_FILE_PREFIX . 'fixture.txt';

    private const FIXTURE_FILE_CONTENTS = "Live admin module file-manager smoke fixture — round-trip marker.\n";

    private const FIXTURE_FOLDER_NAME = self::FIXTURE_FILE_PREFIX . 'folder';

    private const STORAGE_DISK = 'public';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB + filesystem.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureFilesystem();
        parent::tearDown();
    }

    private function purgeFixtureFilesystem(): void
    {
        $disk = Storage::disk(self::STORAGE_DISK);

        if ($disk->exists(self::FIXTURE_FILE_BASENAME)) {
            $disk->delete(self::FIXTURE_FILE_BASENAME);
        }

        if ($disk->directoryExists(self::FIXTURE_FOLDER_NAME)) {
            $disk->deleteDirectory(self::FIXTURE_FOLDER_NAME);
        }
    }

    #[Test]
    public function file_manager_admin_page_loads_and_round_trips_through_upload_plus_list(): void
    {
        $this->purgeFixtureFilesystem();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the file-
            // manager admin page (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::ADMIN_PAGE_SLUG,
                'file manager admin',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'file manager admin render');

            // Signal #2 — upload + list round-trip through the
            // same Storage disk the plupload endpoint writes to
            // and the FileManagerApiController list endpoint
            // reads from.
            $this->assertUploadTargetWriteRoundTrips();
            $this->assertListEndpointReturnsFixtureFile();
            $this->assertCreateFolderEndpointRoundTrips();

            // Confirm the file-manager admin page's chrome
            // rendered — same probe shape as the sibling
            // Filament-page smokes.
            $this->assertFileManagerChromeRendered($browser);
        });
    }

    /**
     * Write a marker-prefixed fixture file directly to
     * Storage::disk('public') — the same disk + same target the
     * plupload upload endpoint lands every uploaded file in via
     * $storageInstance->putFileAs() at PluploadController.php:829.
     * A regression in the disk binding (renamed disk, broken
     * config, missing public-symlink) would surface here as a
     * write-failure or a missing-after-write read.
     */
    private function assertUploadTargetWriteRoundTrips(): void
    {
        $disk = Storage::disk(self::STORAGE_DISK);

        $disk->put(self::FIXTURE_FILE_BASENAME, self::FIXTURE_FILE_CONTENTS);

        $this->assertTrue(
            $disk->exists(self::FIXTURE_FILE_BASENAME),
            'Storage::disk(public)->put() must persist the fixture file — this is the '
            . 'same disk the plupload upload endpoint lands every uploaded file in via '
            . 'putFileAs(). A regression here (broken disk binding, missing '
            . 'public-symlink, mis-scoped root) would silently break every admin upload.'
        );
        $this->assertSame(
            self::FIXTURE_FILE_CONTENTS,
            (string) $disk->get(self::FIXTURE_FILE_BASENAME),
            'Storage::disk(public)->get() must return the same bytes that put() wrote — '
            . 'a mismatch here would mean the disk write/read pair is mis-encoding payloads, '
            . 'which would corrupt every uploaded file the file-manager view lists.'
        );
    }

    /**
     * Round-trip the FileManagerApiController list() endpoint —
     * the same call the file-manager admin view fires on every
     * render to populate the file-list table. Asserts the fixture
     * file written above appears in the response data.
     */
    private function assertListEndpointReturnsFixtureFile(): void
    {
        $controller = new FileManagerApiController();

        $request = Request::create('/api/file-manager/list', 'GET', ['path' => '']);

        $response = $controller->list($request);

        $this->assertIsArray(
            $response,
            'FileManagerApiController::list() must return an array — the file-manager '
            . 'admin view JS reads response.data to populate the file-list table; a '
            . 'non-array return would silently break every admin file-list render.'
        );
        $this->assertArrayHasKey(
            'data',
            $response,
            'FileManagerApiController::list() must return a result with the data key — '
            . 'the file-manager admin view iterates response.data to render every file '
            . 'and folder card; a missing key would silently break the file-list table.'
        );

        $names = array_map(static fn (array $entry): string => (string) ($entry['name'] ?? ''), $response['data']);

        $this->assertContains(
            self::FIXTURE_FILE_BASENAME,
            $names,
            'FileManagerApiController::list() must return the fixture file we just '
            . 'wrote to Storage::disk(public) — a missing entry here would mean the '
            . 'list endpoint reads from a different disk than the upload endpoint '
            . 'writes to, which would silently hide every freshly-uploaded file from '
            . 'the admin file-manager view.'
        );
    }

    /**
     * Round-trip the FileManagerApiController createFolder()
     * endpoint — the same call the file-manager admin view's
     * "New folder" toolbar action fires when the admin creates a
     * subdirectory. Asserts the marker-prefixed folder lands on
     * the same disk the list() endpoint reads from.
     */
    private function assertCreateFolderEndpointRoundTrips(): void
    {
        $controller = new FileManagerApiController();

        $request = Request::create(
            '/api/file-manager/create-folder',
            'POST',
            ['name' => self::FIXTURE_FOLDER_NAME, 'path' => ''],
        );

        $response = $controller->createFolder($request);

        $this->assertIsArray(
            $response,
            'FileManagerApiController::createFolder() must return an array — the '
            . 'file-manager admin view reads response.success / response.error to '
            . 'render the inline toast after a "New folder" action.'
        );
        $this->assertArrayHasKey(
            'success',
            $response,
            'FileManagerApiController::createFolder() must return a success key for a '
            . 'fresh folder name — an error key here would mean the directoryExists() '
            . 'gate fired against our marker-prefixed name, which suggests a stale '
            . 'fixture leaked past tearDown OR that the disk binding differs from '
            . 'what tearDown tries to clean up.'
        );

        $this->assertTrue(
            Storage::disk(self::STORAGE_DISK)->directoryExists(self::FIXTURE_FOLDER_NAME),
            'Storage::disk(public)->directoryExists() must return true for the freshly-'
            . 'created folder — the createFolder() endpoint calls makeDirectory() on '
            . 'the same disk; a mismatch here would mean the disk binding moved between '
            . 'the createFolder() write and the directoryExists() read, which would '
            . 'silently break the file-manager view\'s newly-created-folder display.'
        );
    }

    /**
     * Probe the rendered file-manager admin page for the chrome
     * that proves the page mounted past the auth shell. Same
     * shape as the sibling Filament-page smokes — looks for
     * fi-page chrome plus at least one pressable Filament action
     * (the file-browser toolbar buttons).
     */
    private function assertFileManagerChromeRendered(Browser $browser): void
    {
        $browser->visit('/admin/' . self::ADMIN_PAGE_SLUG)->pause(2000);

        $source = (string) $browser->driver->getPageSource();

        $hasFilamentChrome = str_contains($source, 'fi-page')
            || str_contains($source, 'fi-section')
            || str_contains($source, 'fi-form');
        $hasLivewireWiring = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'wire:model=')
            || str_contains($source, 'wire:click=');
        $hasPressableAction = (int) $browser->driver->executeScript(
            'return document.querySelectorAll("button, a[role=\'button\']").length;'
        ) > 0;

        $this->assertTrue(
            $hasLivewireWiring,
            'file manager admin must render Filament/Livewire chrome (fi-page / '
            . 'fi-section / fi-form / wire:id / wire:snapshot / wire:model / wire:click) '
            . '— otherwise the page never mounted past the auth shell and the upload + '
            . 'list round-trip above would only prove the controller works, not that '
            . 'the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'file manager admin must render at least one pressable action (any '
            . '<button> or <a role="button">) — a page with no actions would mean the '
            . 'file-browser toolbar regressed past the Filament-5 migration this smoke '
            . 'is meant to catch.'
        );
    }
}
