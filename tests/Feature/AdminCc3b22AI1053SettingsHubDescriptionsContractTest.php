<?php

use Tests\TestCase;

/**
 * Contract test — AI-1053 / task-2026-05-23-cc3b22
 *
 * Four System Settings cards on /admin/settings were missing description text.
 * The Settings hub reads the static $description property (or getDescription() method)
 * from each Filament page. These pages had neither — their cards rendered heading-only.
 *
 * Fix: add `public static string $description = '...'` to each of the four pages:
 * - Restore (RestoreAdminPage)
 * - Kitchen Sink (KitchenSink)
 * - Import from WordPress (WordPressMigrationImportPage)
 * - API Applications (ApiApplicationsPage)
 *
 * Selector-self-match guard: PHP block comments stripped before assertions.
 */
class AdminCc3b22AI1053SettingsHubDescriptionsContractTest extends TestCase
{
    private array $sources;

    protected function setUp(): void
    {
        parent::setUp();

        $files = [
            'restore'    => base_path('Modules/Backup/Filament/Pages/RestoreAdminPage.php'),
            'kichen_sink' => base_path('app/Filament/Admin/Pages/KitchenSink.php'),
            'wordpress'  => base_path('Modules/WordPressMigration/Filament/Pages/WordPressMigrationImportPage.php'),
            'api_apps'   => base_path('packages/microweber-passport/src/Filament/Pages/ApiApplicationsPage.php'),
        ];

        foreach ($files as $key => $path) {
            $raw = (string) file_get_contents($path);
            $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $raw);
            $stripped = preg_replace('~//[^\n]*~', '', $stripped);
            $this->sources[$key] = ['raw' => $raw, 'exec' => $stripped];
        }
    }

    // ── Restore ──────────────────────────────────────────────────────────────

    public function test_restore_page_has_description_property(): void
    {
        $this->assertMatchesRegularExpression(
            '~public\s+static\s+string\s+\$description\s*=\s*[\'"]~',
            $this->sources['restore']['exec'],
            'RestoreAdminPage must have a public static $description for the settings hub card'
        );
    }

    public function test_restore_description_is_non_empty(): void
    {
        preg_match('~\$description\s*=\s*[\'"]([^\'\"]+)[\'"]~', $this->sources['restore']['exec'], $m);
        $this->assertNotEmpty($m[1] ?? '', 'RestoreAdminPage $description must be a non-empty string');
    }

    // ── Kitchen Sink ─────────────────────────────────────────────────────────

    public function test_kitchen_sink_has_description_property(): void
    {
        $this->assertMatchesRegularExpression(
            '~public\s+static\s+string\s+\$description\s*=\s*[\'"]~',
            $this->sources['kichen_sink']['exec'],
            'KitchenSink must have a public static $description for the settings hub card'
        );
    }

    public function test_kitchen_sink_description_is_non_empty(): void
    {
        preg_match('~\$description\s*=\s*[\'"]([^\'\"]+)[\'"]~', $this->sources['kichen_sink']['exec'], $m);
        $this->assertNotEmpty($m[1] ?? '', 'KitchenSink $description must be a non-empty string');
    }

    // ── Import from WordPress ─────────────────────────────────────────────────

    public function test_wordpress_import_has_description_property(): void
    {
        $this->assertMatchesRegularExpression(
            '~public\s+static\s+string\s+\$description\s*=\s*[\'"]~',
            $this->sources['wordpress']['exec'],
            'WordPressMigrationImportPage must have a public static $description for the settings hub card'
        );
    }

    public function test_wordpress_import_description_is_non_empty(): void
    {
        preg_match('~\$description\s*=\s*[\'"]([^\'\"]+)[\'"]~', $this->sources['wordpress']['exec'], $m);
        $this->assertNotEmpty($m[1] ?? '', 'WordPressMigrationImportPage $description must be non-empty');
    }

    // ── API Applications ──────────────────────────────────────────────────────

    public function test_api_applications_has_description_property(): void
    {
        $this->assertMatchesRegularExpression(
            '~public\s+static\s+string\s+\$description\s*=\s*[\'"]~',
            $this->sources['api_apps']['exec'],
            'ApiApplicationsPage must have a public static $description for the settings hub card'
        );
    }

    public function test_api_applications_description_is_non_empty(): void
    {
        preg_match('~\$description\s*=\s*[\'"]([^\'\"]+)[\'"]~', $this->sources['api_apps']['exec'], $m);
        $this->assertNotEmpty($m[1] ?? '', 'ApiApplicationsPage $description must be non-empty');
    }

    // ── Task markers ──────────────────────────────────────────────────────────

    public function test_task_id_in_applicable_pages(): void
    {
        // The api_apps page was moved to the microweber-passport
        // package and no longer carries the task-id marker inline.
        $checkablePages = ['restore', 'kichen_sink', 'wordpress'];
        foreach ($checkablePages as $key) {
            $this->assertStringContainsString(
                'task-2026-05-23-cc3b22',
                $this->sources[$key]['raw'],
                "Page '$key' must carry the AI-1053 task-id marker"
            );
        }
    }

    // ── Settings hub reads $description via reflection ────────────────────────

    public function test_settings_hub_reads_description_via_reflection(): void
    {
        $hubPath = base_path('Modules/Settings/Filament/Pages/Settings.php');
        $hubSrc = (string) file_get_contents($hubPath);
        $this->assertStringContainsString(
            'ReflectionClass',
            $hubSrc,
            'Settings hub must read $description via reflection for pages that have it'
        );
        $this->assertStringContainsString(
            "'description'",
            $hubSrc,
            "Settings hub must look for the 'description' property by name"
        );
    }
}
