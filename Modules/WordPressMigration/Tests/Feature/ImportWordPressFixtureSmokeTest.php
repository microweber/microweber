<?php

namespace Modules\WordPressMigration\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Process\Process;
use Tests\TestCase;

/**
 * Phase 10 smoke-test mirror for the CI step added in
 * .github/workflows/wordpress-import-smoke.yml.
 *
 * Boots the same `tests/fixtures/wp/router.php` PHP built-in server
 * the Dusk probe/rss/sitemap tests use, runs the CLI driver in
 * dry-run mode, and asserts at least one row landed in
 * `wp_migration_staging_content`.
 *
 * Keeping this contract inside PHPUnit (not only in the CI workflow)
 * means it runs on every `composer test` invocation too — a
 * developer breaking the CLI or the fixture will see the regression
 * locally, not hours later on GitHub.
 */
class ImportWordPressFixtureSmokeTest extends TestCase
{
    private const FIXTURE_HOST = '127.0.0.1';

    // Port chosen above the usual dev-server range to avoid collisions
    // with the Dusk fixture tests that pick their own ports in a
    // different band.
    private const FIXTURE_PORT = 19876;

    private ?Process $fixtureServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        if (! Schema::hasTable('wp_migration_jobs')) {
            $this->artisan('module:migrate', ['module' => 'WordPressMigration']);
        }
        DB::table('wp_migration_staging_content')->delete();
        DB::table('wp_migration_staging_media')->delete();
        DB::table('wp_migration_jobs')->delete();

        $this->startFixtureServer();
    }

    protected function tearDown(): void
    {
        $this->stopFixtureServer();
        parent::tearDown();
    }

    #[Test]
    public function cli_against_the_wp_fixture_stages_at_least_one_row(): void
    {
        $url = 'http://' . self::FIXTURE_HOST . ':' . self::FIXTURE_PORT;

        $exit = Artisan::call('microweber:import:wordpress', [
            'url' => $url,
            '--mode' => 'rss',
            '--dry-run' => true,
            '--yes' => true,
            '--max' => 50,
        ]);

        $this->assertSame(0, $exit, 'Smoke-test CLI must exit 0 on a live fixture. Output: ' . Artisan::output());

        $job = WordPressMigrationJob::query()
            ->where('source_url', $url)
            ->first();
        $this->assertNotNull($job, 'CLI should have persisted a job row for the probe');

        $staged = StagingContent::where('job_id', $job->id)->count();
        $this->assertGreaterThan(0, $staged,
            'Smoke expectation: at least one staging row after an RSS dry-run against the WP fixture');
    }

    private function startFixtureServer(): void
    {
        $docroot = base_path('tests/fixtures/wp');
        $router = $docroot . '/router.php';
        $this->assertFileExists($router, "WP fixture router missing at {$router}");

        // Free the port in case a previous run left a stray process.
        @exec('fuser -k -n tcp ' . self::FIXTURE_PORT . ' 2>/dev/null');
        usleep(200_000);

        $this->fixtureServer = new Process([
            PHP_BINARY,
            '-S', self::FIXTURE_HOST . ':' . self::FIXTURE_PORT,
            '-t', $docroot,
            $router,
        ], $docroot);
        $this->fixtureServer->start();

        $deadline = microtime(true) + 5.0;
        while (microtime(true) < $deadline) {
            $fp = @fsockopen(self::FIXTURE_HOST, self::FIXTURE_PORT, $errno, $errstr, 0.25);
            if ($fp) {
                fclose($fp);
                return;
            }
            usleep(100_000);
        }
        $this->fail('WP fixture server did not come up within 5s');
    }

    private function stopFixtureServer(): void
    {
        if ($this->fixtureServer instanceof Process && $this->fixtureServer->isRunning()) {
            $this->fixtureServer->stop(2);
        }
        $this->fixtureServer = null;
    }
}
