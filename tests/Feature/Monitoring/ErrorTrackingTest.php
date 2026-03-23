<?php

namespace Tests\Feature\Monitoring;

use Tests\TestCase;
use MicroweberPackages\Monitoring\Models\ErrorTracking;
use MicroweberPackages\Monitoring\Services\ErrorTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Exception;

class ErrorTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected ErrorTrackingService $errorTracking;

    protected function setUp(): void
    {
        parent::setUp();
        $this->errorTracking = app(ErrorTrackingService::class);
    }

    /** @test */
    public function it_tracks_exception_in_database(): void
    {
        $exception = new Exception('Test exception message', 500);
        
        $id = $this->errorTracking->trackException($exception);
        
        $this->assertNotFalse($id);
        
        $error = ErrorTracking::find($id);
        $this->assertNotNull($error);
        $this->assertEquals('Test exception message', $error->message);
        $this->assertEquals('Exception', $error->exception_class);
        $this->assertEquals('error', $error->level);
        $this->assertFalse($error->is_resolved);
    }

    /** @test */
    public function it_tracks_error_with_custom_level(): void
    {
        $id = $this->errorTracking->trackError('critical', 'Critical system error', [
            'file' => '/test/file.php',
            'line' => 42,
        ]);
        
        $this->assertNotFalse($id);
        
        $error = ErrorTracking::find($id);
        $this->assertEquals('critical', $error->level);
        $this->assertEquals('Critical system error', $error->message);
        $this->assertEquals('/test/file.php', $error->file);
        $this->assertEquals(42, $error->line);
    }

    /** @test */
    public function it_increments_occurrence_count_for_similar_errors(): void
    {
        $id1 = $this->errorTracking->trackError('error', 'Duplicate error', [
            'file' => '/same/file.php',
            'line' => 10,
        ]);
        
        $id2 = $this->errorTracking->trackError('error', 'Duplicate error', [
            'file' => '/same/file.php',
            'line' => 10,
        ]);
        
        $this->assertEquals($id1, $id2);
        
        $error = ErrorTracking::find($id1);
        $this->assertEquals(2, $error->occurrence_count);
    }

    /** @test */
    public function it_returns_error_stats_by_period(): void
    {
        // Clear existing records first
        ErrorTracking::query()->delete();
        
        // Create some test errors
        ErrorTracking::factory()->count(5)->create([
            'created_at' => now(),
            'is_resolved' => false,
        ]);
        
        ErrorTracking::factory()->count(3)->create([
            'created_at' => now(),
            'is_resolved' => true,
        ]);
        
        ErrorTracking::factory()->count(2)->create([
            'created_at' => now()->subDays(2),
            'is_resolved' => false,
        ]);
        
        // Clear cache to ensure fresh stats
        Cache::flush();
        
        $todayStats = $this->errorTracking->getErrorStats('today');
        
        $this->assertEquals(8, $todayStats['total']);
        $this->assertEquals(5, $todayStats['unresolved']);
        $this->assertEquals('today', $todayStats['period']);
    }

    /** @test */
    public function it_gets_recent_errors(): void
    {
        ErrorTracking::query()->delete();
        ErrorTracking::factory()->count(10)->create();
        
        $errors = $this->errorTracking->getRecentErrors(5);
        
        $this->assertCount(5, $errors);
    }

    /** @test */
    public function it_filters_unresolved_errors(): void
    {
        ErrorTracking::query()->delete();
        ErrorTracking::factory()->count(3)->create(['is_resolved' => false]);
        ErrorTracking::factory()->count(2)->create(['is_resolved' => true]);
        
        $unresolved = $this->errorTracking->getRecentErrors(20, true);
        
        $this->assertCount(3, $unresolved);
    }

    /** @test */
    public function it_marks_error_as_resolved(): void
    {
        ErrorTracking::query()->delete();
        $error = ErrorTracking::factory()->create(['is_resolved' => false]);
        
        $result = $this->errorTracking->markAsResolved($error->id, 'Fixed in commit abc123');
        
        $this->assertTrue($result);
        
        $error->refresh();
        $this->assertTrue($error->is_resolved);
        $this->assertNotNull($error->resolved_at);
        $this->assertEquals('Fixed in commit abc123', $error->resolution_notes);
    }

    /** @test */
    public function it_gets_top_error_sources(): void
    {
        ErrorTracking::query()->delete();
        // Create errors from different files - all unresolved
        for ($i = 0; $i < 5; $i++) {
            ErrorTracking::factory()->create(['file' => '/app/Controllers/TestController.php', 'is_resolved' => false]);
        }
        for ($i = 0; $i < 3; $i++) {
            ErrorTracking::factory()->create(['file' => '/app/Services/TestService.php', 'is_resolved' => false]);
        }
        ErrorTracking::factory()->count(1)->create(['file' => '/app/Models/TestModel.php', 'is_resolved' => false]);
        
        $sources = $this->errorTracking->getTopErrorSources(3);
        
        $this->assertCount(3, $sources);
        $this->assertEquals('/app/Controllers/TestController.php', $sources[0]->file);
        $this->assertEquals(5, $sources[0]->error_count);
    }

    /** @test */
    public function it_sanitizes_server_data(): void
    {
        $originalServer = $_SERVER;
        $_SERVER['HTTP_COOKIE'] = 'session_id=secret_value';
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer secret_token';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        
        try {
            $id = $this->errorTracking->trackError('error', 'Test with server data', []);
            
            $error = ErrorTracking::find($id);
            $serverData = json_decode($error->server_data, true);
            
            $this->assertEquals('[REDACTED]', $serverData['HTTP_COOKIE']);
            $this->assertEquals('[REDACTED]', $serverData['HTTP_AUTHORIZATION']);
            $this->assertEquals('127.0.0.1', $serverData['REMOTE_ADDR']);
        } finally {
            $_SERVER = $originalServer;
        }
    }

    /** @test */
    public function error_tracking_model_has_scopes(): void
    {
        ErrorTracking::query()->delete();
        // Create 2 unresolved errors (not critical)
        ErrorTracking::factory()->count(2)->create(['is_resolved' => false, 'level' => 'error']);
        // Create 3 resolved errors
        ErrorTracking::factory()->count(3)->create(['is_resolved' => true, 'level' => 'error']);
        // Create 1 unresolved critical error
        ErrorTracking::factory()->count(1)->create([
            'level' => 'critical',
            'is_resolved' => false,
        ]);
        
        // 2 non-critical unresolved + 1 critical unresolved = 3 total unresolved
        $this->assertEquals(3, ErrorTracking::unresolved()->count());
        // 3 resolved
        $this->assertEquals(3, ErrorTracking::resolved()->count());
        // 1 critical (all unresolved)
        $this->assertEquals(1, ErrorTracking::critical()->count());
    }

    /** @test */
    public function error_tracking_model_can_mark_as_resolved(): void
    {
        $error = ErrorTracking::factory()->create(['is_resolved' => false]);
        
        $result = $error->markAsResolved('Fixed the bug');
        
        $this->assertTrue($result);
        $this->assertTrue($error->fresh()->is_resolved);
        $this->assertEquals('Fixed the bug', $error->fresh()->resolution_notes);
    }

    /** @test */
    public function error_tracking_model_increments_occurrence(): void
    {
        $error = ErrorTracking::factory()->create(['occurrence_count' => 1]);
        
        $error->incrementOccurrence();
        
        $this->assertEquals(2, $error->fresh()->occurrence_count);
    }

    /** @test */
    public function error_has_location_attribute(): void
    {
        $error = ErrorTracking::factory()->create([
            'file' => '/var/www/app/Controllers/TestController.php',
            'line' => 25,
        ]);
        
        $this->assertEquals('TestController.php:25', $error->location);
    }

    /** @test */
    public function error_has_severity_color_attribute(): void
    {
        $critical = ErrorTracking::factory()->make(['level' => 'critical']);
        $error = ErrorTracking::factory()->make(['level' => 'error']);
        $warning = ErrorTracking::factory()->make(['level' => 'warning']);
        $info = ErrorTracking::factory()->make(['level' => 'info']);
        
        $this->assertEquals('danger', $critical->severity_color);
        $this->assertEquals('danger', $error->severity_color);
        $this->assertEquals('warning', $warning->severity_color);
        $this->assertEquals('info', $info->severity_color);
    }

    /** @test */
    public function error_has_formatted_trace_attribute(): void
    {
        $trace = implode("\n", array_map(fn($i) => "#{$i} /app/file{$i}.php({$i}): test()", range(1, 20)));
        
        $error = ErrorTracking::factory()->make(['trace' => $trace]);
        
        $formatted = $error->formatted_trace;
        $lines = explode("\n", $formatted);
        
        $this->assertCount(10, $lines);
    }

    /** @test */
    public function database_has_error_tracking_table(): void
    {
        $this->assertTrue(Schema::hasTable('error_tracking'));

        $this->assertTrue(Schema::hasColumns('error_tracking', [
            'id',
            'level',
            'message',
            'exception_class',
            'file',
            'line',
            'code',
            'trace',
            'url',
            'method',
            'user_id',
            'user_ip',
            'user_agent',
            'server_data',
            'context',
            'is_resolved',
            'occurrence_count',
            'last_occurred_at',
            'resolved_at',
            'resolved_by',
            'resolution_notes',
            'created_at',
            'updated_at',
        ]));
    }

    /** @test */
    public function error_tracking_table_has_indexes(): void
    {
        // Skip on SQLite as it doesn't support getDoctrineSchemaManager
        if (DB::getDriverName() === 'sqlite') {
            $this->markTestSkipped('Index verification skipped for SQLite');
        }
        
        $indexes = DB::getDoctrineSchemaManager()->listTableIndexes('error_tracking');
        $indexNames = array_keys($indexes);
        
        $this->assertContains('error_tracking_level_index', $indexNames);
        $this->assertContains('error_tracking_exception_class_index', $indexNames);
        $this->assertContains('error_tracking_is_resolved_index', $indexNames);
        $this->assertContains('error_tracking_file_line_index', $indexNames);
        $this->assertContains('error_tracking_level_is_resolved_index', $indexNames);
    }

    /** @test */
    public function it_handles_tracking_failure_gracefully(): void
    {
        // Mock a database failure
        DB::shouldReceive('table')->andThrow(new Exception('Database connection failed'));
        
        $exception = new Exception('Original error');
        
        $result = $this->errorTracking->trackException($exception);
        
        // Should return false on failure, not throw
        $this->assertFalse($result);
    }
}
