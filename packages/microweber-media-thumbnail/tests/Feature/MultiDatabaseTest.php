<?php

namespace MicroweberPackages\MediaThumbnail\Tests\Feature;

use MicroweberPackages\MediaThumbnail\MediaThumbnailServiceProvider;
use MicroweberPackages\MediaThumbnail\Models\MediaThumbnail;
use MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository;
use Orchestra\Testbench\TestCase;

/**
 * Tests that the package works correctly on SQLite, MySQL, and PostgreSQL.
 *
 * Each test method runs the full CRUD lifecycle against an ISOLATED per-driver
 * database (not the shared CMS install), so it extends Orchestra Testbench
 * directly rather than the full-CMS base. The driver is selected by the
 * MW_TEST_DB_DRIVER env var (default: sqlite).
 */
class MultiDatabaseTest extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [MediaThumbnailServiceProvider::class];
    }

    /**
     * Create the package's table on the configured (isolated) connection.
     * Testbench runs these and rolls them back on tearDown.
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(dirname(__DIR__, 2) . '/database/migrations');
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));

        $driver = env('MW_TEST_DB_DRIVER', 'sqlite');

        if ($driver === 'mysql') {
            $app['config']->set('database.default', 'mysql');
            $app['config']->set('database.connections.mysql', [
                'driver'   => 'mysql',
                'host'     => env('DB_HOST', '127.0.0.1'),
                'port'     => env('DB_PORT', '3306'),
                'database' => env('DB_DATABASE', 'media_thumbnail_test'),
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', 'root'),
                'charset'  => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix'   => '',
            ]);
        } elseif ($driver === 'pgsql') {
            $app['config']->set('database.default', 'pgsql');
            $app['config']->set('database.connections.pgsql', [
                'driver'   => 'pgsql',
                'host'     => env('DB_HOST', '127.0.0.1'),
                'port'     => env('DB_PORT', '5432'),
                'database' => env('DB_DATABASE', 'media_thumbnail_test'),
                'username' => env('DB_USERNAME', 'postgres'),
                'password' => env('DB_PASSWORD', 'postgres'),
                'charset'  => 'utf8',
                'prefix'   => '',
                'schema'   => 'public',
            ]);
        } else {
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]);
        }

        $thumbnailsPath = $app->storagePath('app/public/thumbnails');
        $app['config']->set('thumbnailer.thumbnails_path', $thumbnailsPath);
    }

    public function test_create_and_find_by_filename(): void
    {
        $model = MediaThumbnail::create([
            'filename'      => 'tn-multidb-' . uniqid(),
            'image_options' => ['src' => '/test.jpg', 'width' => 200],
        ]);

        $this->assertNotNull($model->id);
        $this->assertNotNull($model->uuid);

        $found = MediaThumbnail::findByFilename($model->filename);
        $this->assertNotNull($found);
        $this->assertEquals($model->filename, $found['filename']);
    }

    public function test_repository_full_cycle(): void
    {
        $repo  = app(MediaThumbnailRepository::class);
        $fname = 'tn-repo-multidb-' . uniqid();

        $model = $repo->store($fname, ['src' => '/cycle.jpg', 'width' => 300]);
        $this->assertNotNull($model->id);

        $found = $repo->findByFilename($fname);
        $this->assertNotNull($found);

        $byUuid = $repo->findByUuid($model->uuid);
        $this->assertNotNull($byUuid);

        $deleted = $repo->removeByFilename($fname);
        $this->assertEquals(1, $deleted);

        $this->assertNull($repo->findByFilename($fname));
    }

    public function test_prune_older_than(): void
    {
        $repo  = app(MediaThumbnailRepository::class);
        $fname = 'tn-prune-multidb-' . uniqid();

        $model = $repo->store($fname, ['src' => '/prune.jpg']);

        MediaThumbnail::where('id', $model->id)->update([
            'created_at' => now()->subDays(100),
        ]);

        $pruned = $repo->pruneOlderThan(now()->subDays(90));
        $this->assertGreaterThanOrEqual(1, $pruned);
    }

    public function test_json_cast_round_trip(): void
    {
        $options = [
            'src'    => '/images/roundtrip.png',
            'width'  => 400,
            'height' => 300,
            'crop'   => true,
            'nested' => ['key' => 'value'],
        ];

        $model = MediaThumbnail::create([
            'filename'      => 'tn-json-multidb-' . uniqid(),
            'image_options' => $options,
        ]);

        $fresh = MediaThumbnail::find($model->id);
        $this->assertIsArray($fresh->image_options);
        $this->assertEquals($options, $fresh->image_options);
    }
}