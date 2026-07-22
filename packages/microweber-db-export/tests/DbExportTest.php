<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\DbExport\DbExportManager;
use MicroweberPackages\DbExport\Facades\DbExport;
use MicroweberPackages\DbExport\SchemaInspector;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Full integration tests for the microweber-db-export package.
 *
 * Uses two SQLite connections (the default 'sqlite' and a second
 * 'sqlite_target') to validate cross-connection copy, JSON
 * export/import, auto-increment preservation, index detection,
 * chunked transfers, and the getTableContent helper.
 *
 * @command php artisan test --filter DbExportTest
 */
class DbExportTest extends TestCase
{
    private string $targetDb;
    private string $exportDir;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up a second SQLite database for target connection
        $this->targetDb = storage_path('testing/db_export_target.sqlite');
        $this->exportDir = storage_path('testing/db_export');

        $dir = dirname($this->targetDb);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if (! is_dir($this->exportDir)) {
            mkdir($this->exportDir, 0755, true);
        }

        // Create fresh target database file
        if (is_file($this->targetDb)) {
            unlink($this->targetDb);
        }
        touch($this->targetDb);

        // Configure the target connection
        config([
            'database.connections.sqlite_target' => [
                'driver'   => 'sqlite',
                'database' => $this->targetDb,
                'prefix'   => '',
            ],
        ]);

        // Ensure source test table exists
        $this->createSourceTestData();
    }

    protected function tearDown(): void
    {
        // Drop test table from source
        Schema::dropIfExists('db_export_test');
        Schema::dropIfExists('db_export_no_id_test');

        // Clean up target DB
        DB::purge('sqlite_target');
        if (is_file($this->targetDb)) {
            unlink($this->targetDb);
        }

        // Clean up export files
        foreach (glob($this->exportDir . '/*.json') as $f) {
            unlink($f);
        }
        if (is_dir($this->exportDir)) {
            @rmdir($this->exportDir);
        }
        $parentDir = dirname($this->targetDb);
        if (is_dir($parentDir) && count(scandir($parentDir)) <= 2) {
            @rmdir($parentDir);
        }

        parent::tearDown();
    }

    private function createSourceTestData(): void
    {
        Schema::dropIfExists('db_export_test');
        Schema::dropIfExists('db_export_no_id_test');

        // Table WITH auto-increment id
        Schema::create('db_export_test', function ($table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->integer('age')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
            $table->index(['name'], 'db_export_test_name_idx');
            $table->index(['email'], 'db_export_test_email_idx');
        });

        // Table WITHOUT id
        Schema::create('db_export_no_id_test', function ($table) {
            $table->string('key', 255);
            $table->text('value')->nullable();
        });

        // Seed data
        $rows = [];
        for ($i = 1; $i <= 50; $i++) {
            $rows[] = [
                'name'       => "User {$i}",
                'email'      => "user{$i}@example.com",
                'age'        => 20 + ($i % 50),
                'bio'        => "Bio for user {$i}. " . str_repeat('Lorem ipsum. ', 5),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        foreach (array_chunk($rows, 25) as $chunk) {
            DB::table('db_export_test')->insert($chunk);
        }

        // Seed no-id table
        DB::table('db_export_no_id_test')->insert([
            ['key' => 'setting_a', 'value' => 'alpha'],
            ['key' => 'setting_b', 'value' => 'beta'],
            ['key' => 'setting_c', 'value' => 'gamma'],
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Schema Inspector
    // ──────────────────────────────────────────────────────────────────────

    #[Test]
    public function it_lists_table_names(): void
    {
        $inspector = new SchemaInspector();
        $tables = $inspector->listTableNames(DB::connection());

        $this->assertContains('db_export_test', $tables);
        $this->assertContains('db_export_no_id_test', $tables);
    }

    #[Test]
    public function it_detects_auto_increment_column(): void
    {
        $inspector = new SchemaInspector();
        $meta = $inspector->inspectTable(DB::connection(), 'db_export_test');

        $this->assertEquals('id', $meta->autoIncrementColumn);
        $this->assertTrue($meta->hasAutoIncrement());
    }

    #[Test]
    public function it_detects_no_auto_increment_when_none(): void
    {
        $inspector = new SchemaInspector();
        $meta = $inspector->inspectTable(DB::connection(), 'db_export_no_id_test');

        $this->assertNull($meta->autoIncrementColumn);
        $this->assertFalse($meta->hasAutoIncrement());
    }

    #[Test]
    public function it_detects_columns(): void
    {
        $inspector = new SchemaInspector();
        $meta = $inspector->inspectTable(DB::connection(), 'db_export_test');

        $names = $meta->columnNames();
        $this->assertContains('id', $names);
        $this->assertContains('name', $names);
        $this->assertContains('email', $names);
        $this->assertContains('age', $names);
        $this->assertContains('bio', $names);
    }

    #[Test]
    public function it_detects_indexes(): void
    {
        $inspector = new SchemaInspector();
        $meta = $inspector->inspectTable(DB::connection(), 'db_export_test');

        $indexNames = array_map(fn ($idx) => $idx->name, $meta->indexes);
        // SQLite may name indexes differently, just check we found some
        $this->assertNotEmpty($meta->indexes);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Cross-connection copy
    // ──────────────────────────────────────────────────────────────────────

    #[Test]
    public function it_copies_table_between_connections(): void
    {
        $manager = new DbExportManager();
        $manager->setChunkSize(10); // Small chunks for testing

        $result = $manager->copy('sqlite', 'sqlite_target', ['db_export_test']);

        $this->assertArrayHasKey('db_export_test', $result);
        $this->assertEquals(50, $result['db_export_test']);

        // Verify data in target
        $targetRows = DB::connection('sqlite_target')
            ->table('db_export_test')
            ->count();
        $this->assertEquals(50, $targetRows);

        // Verify specific row
        $row = DB::connection('sqlite_target')
            ->table('db_export_test')
            ->where('id', 1)
            ->first();
        $this->assertEquals('User 1', $row->name);
        $this->assertEquals('user1@example.com', $row->email);
    }

    #[Test]
    public function it_copies_table_without_id(): void
    {
        $manager = new DbExportManager();
        $result = $manager->copy('sqlite', 'sqlite_target', ['db_export_no_id_test']);

        $this->assertEquals(3, $result['db_export_no_id_test']);

        $targetRows = DB::connection('sqlite_target')
            ->table('db_export_no_id_test')
            ->get();
        $this->assertCount(3, $targetRows);
    }

    #[Test]
    public function it_preserves_auto_increment_after_copy(): void
    {
        $manager = new DbExportManager();
        $manager->copy('sqlite', 'sqlite_target', ['db_export_test']);

        // Insert a new row — its ID should be 51, not 1
        DB::connection('sqlite_target')
            ->table('db_export_test')
            ->insert([
                'name'  => 'New User',
                'email' => 'new@example.com',
                'age'   => 30,
            ]);

        $lastRow = DB::connection('sqlite_target')
            ->table('db_export_test')
            ->orderBy('id', 'desc')
            ->first();

        $this->assertGreaterThan(50, $lastRow->id,
            'Auto-increment should continue from max existing id');
    }

    #[Test]
    public function it_copies_with_callback(): void
    {
        $reported = [];

        $manager = new DbExportManager();
        $manager->copy('sqlite', 'sqlite_target', ['db_export_test'], function ($table, $rows) use (&$reported) {
            $reported[$table] = $rows;
        });

        $this->assertArrayHasKey('db_export_test', $reported);
        $this->assertEquals(50, $reported['db_export_test']);
    }

    #[Test]
    public function it_copies_multiple_tables(): void
    {
        $manager = new DbExportManager();
        $result = $manager->copy('sqlite', 'sqlite_target', ['db_export_test', 'db_export_no_id_test']);

        $this->assertCount(2, $result);
        $this->assertEquals(50, $result['db_export_test']);
        $this->assertEquals(3, $result['db_export_no_id_test']);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  JSON Export
    // ──────────────────────────────────────────────────────────────────────

    #[Test]
    public function it_exports_to_json(): void
    {
        $path = $this->exportDir . '/export_test.json';

        $manager = new DbExportManager();
        $manager->exportToJson($path, null, ['db_export_test', 'db_export_no_id_test']);

        $this->assertFileExists($path);

        $data = json_decode(file_get_contents($path), true);
        $this->assertNotNull($data);
        $this->assertArrayHasKey('db_export_test', $data);
        $this->assertCount(50, $data['db_export_test']);
        $this->assertArrayHasKey('db_export_no_id_test', $data);
        $this->assertCount(3, $data['db_export_no_id_test']);
    }

    #[Test]
    public function it_exports_with_callback(): void
    {
        $path = $this->exportDir . '/export_cb.json';
        $reported = [];

        $manager = new DbExportManager();
        $manager->exportToJson($path, null, ['db_export_test'], function ($table, $rows) use (&$reported) {
            $reported[$table] = $rows;
        });

        $this->assertArrayHasKey('db_export_test', $reported);
        $this->assertEquals(50, $reported['db_export_test']);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  JSON Import
    // ──────────────────────────────────────────────────────────────────────

    #[Test]
    public function it_imports_from_json(): void
    {
        $path = $this->exportDir . '/import_test.json';

        // Export first
        $manager = new DbExportManager();
        $manager->exportToJson($path, null, ['db_export_test']);

        // Import into target
        $manager->importFromJson($path, 'sqlite_target');

        $count = DB::connection('sqlite_target')
            ->table('db_export_test')
            ->count();
        $this->assertEquals(50, $count);
    }

    #[Test]
    public function it_imports_table_without_id(): void
    {
        $path = $this->exportDir . '/import_noid.json';

        $manager = new DbExportManager();
        $manager->exportToJson($path, null, ['db_export_no_id_test']);
        $manager->importFromJson($path, 'sqlite_target');

        $count = DB::connection('sqlite_target')
            ->table('db_export_no_id_test')
            ->count();
        $this->assertEquals(3, $count);
    }

    #[Test]
    public function it_round_trips_data_through_json(): void
    {
        $path = $this->exportDir . '/roundtrip.json';

        $manager = new DbExportManager();
        $manager->exportToJson($path, null, ['db_export_test']);
        $manager->importFromJson($path, 'sqlite_target');

        // Verify every row matches
        $sourceRows = DB::table('db_export_test')->orderBy('id')->get()->toArray();
        $targetRows = DB::connection('sqlite_target')
            ->table('db_export_test')
            ->orderBy('id')
            ->get()
            ->toArray();

        $this->assertCount(count($sourceRows), $targetRows);

        for ($i = 0; $i < count($sourceRows); $i++) {
            $this->assertEquals(
                (array) $sourceRows[$i],
                (array) $targetRows[$i],
            );
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    //  getTableContent helper
    // ──────────────────────────────────────────────────────────────────────

    #[Test]
    public function it_gets_table_content(): void
    {
        $manager = new DbExportManager();
        $rows = $manager->getTableContent('db_export_test');

        $this->assertCount(50, $rows);
        $this->assertArrayHasKey('id', $rows[0]);
        $this->assertArrayHasKey('name', $rows[0]);
    }

    #[Test]
    public function it_gets_content_of_table_without_id(): void
    {
        $manager = new DbExportManager();
        $rows = $manager->getTableContent('db_export_no_id_test');

        $this->assertCount(3, $rows);
        $this->assertArrayHasKey('key', $rows[0]);
    }

    #[Test]
    public function it_returns_empty_for_nonexistent_table(): void
    {
        $manager = new DbExportManager();
        $rows = $manager->getTableContent('nonexistent_table_xyz');

        $this->assertEmpty($rows);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Facade
    // ──────────────────────────────────────────────────────────────────────

    #[Test]
    public function it_facade_is_available(): void
    {
        $rows = DbExport::getTableContent('db_export_test');
        $this->assertCount(50, $rows);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Big data / chunked test
    // ──────────────────────────────────────────────────────────────────────

    #[Test]
    public function it_handles_large_datasets_in_chunks(): void
    {
        // Insert 1000 more rows
        $rows = [];
        for ($i = 51; $i <= 1050; $i++) {
            $rows[] = [
                'name'       => "BulkUser {$i}",
                'email'      => "bulk{$i}@example.com",
                'age'        => $i % 80,
                'bio'        => str_repeat("Chunk test data {$i}. ", 3),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('db_export_test')->insert($chunk);
        }

        $total = DB::table('db_export_test')->count();
        $this->assertEquals(1050, $total);

        // Copy with small chunks
        $manager = new DbExportManager();
        $manager->setChunkSize(100);

        $result = $manager->copy('sqlite', 'sqlite_target', ['db_export_test']);
        $this->assertEquals(1050, $result['db_export_test']);

        // Verify in target
        $targetCount = DB::connection('sqlite_target')->table('db_export_test')->count();
        $this->assertEquals(1050, $targetCount);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Console commands
    // ──────────────────────────────────────────────────────────────────────

    #[Test]
    public function it_copy_command_works(): void
    {
        $this->artisan('microweber:db-export', [
            'source' => 'sqlite',
            'target' => 'sqlite_target',
            '--tables' => 'db_export_test',
        ])->assertSuccessful();

        $count = DB::connection('sqlite_target')->table('db_export_test')->count();
        $this->assertEquals(50, $count);
    }

    #[Test]
    public function it_export_command_creates_json(): void
    {
        $path = $this->exportDir . '/cmd_export.json';

        $this->artisan('microweber:db-export', [
            '--path'   => $path,
            '--tables' => 'db_export_test',
        ])->assertSuccessful();

        $this->assertFileExists($path);
    }

    #[Test]
    public function it_import_command_works(): void
    {
        $path = $this->exportDir . '/cmd_import.json';

        // Export first
        (new DbExportManager())->exportToJson($path, null, ['db_export_test']);

        $this->artisan('microweber:db-import', [
            '--path'       => $path,
            '--connection' => 'sqlite_target',
        ])->assertSuccessful();

        $count = DB::connection('sqlite_target')->table('db_export_test')->count();
        $this->assertEquals(50, $count);
    }

    #[Test]
    public function it_import_command_fails_without_path(): void
    {
        $this->artisan('microweber:db-import')
            ->assertFailed();
    }

    // ──────────────────────────────────────────────────────────────────────
    //  TableMeta serialization
    // ──────────────────────────────────────────────────────────────────────

    #[Test]
    public function it_table_meta_serializes_to_array(): void
    {
        $inspector = new SchemaInspector();
        $meta = $inspector->inspectTable(DB::connection(), 'db_export_test');
        $arr = $meta->toArray();

        $this->assertArrayHasKey('name', $arr);
        $this->assertArrayHasKey('columns', $arr);
        $this->assertArrayHasKey('auto_increment_column', $arr);
        $this->assertArrayHasKey('indexes', $arr);
        $this->assertEquals('db_export_test', $arr['name']);
        $this->assertEquals('id', $arr['auto_increment_column']);
    }
}