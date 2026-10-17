<?php

namespace MicroweberPackages\Monitoring\Tests;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Monitoring\Services\ErrorTrackingService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Covers the error-tracking service: insert, de-duplication (shared by
 * trackException + trackError), and stats-cache invalidation across periods.
 */
class ErrorTrackingServiceTest extends TestCase
{
    protected string $marker = 'mon-test-';

    protected function service(): ErrorTrackingService
    {
        return new ErrorTrackingService();
    }

    #[Test]
    public function track_exception_inserts_then_deduplicates(): void
    {
        $svc = $this->service();
        $e = new \RuntimeException($this->marker . uniqid());

        $id1 = $svc->trackException($e);
        $id2 = $svc->trackException($e); // same message+file+line → dedupe

        $this->assertIsInt($id1);
        $this->assertSame($id1, $id2, 'repeat exception should update, not insert');

        $row = DB::table('error_tracking')->where('id', $id1)->first();
        $this->assertEquals(2, $row->occurrence_count);
        $this->assertSame(1, DB::table('error_tracking')->where('message', $e->getMessage())->count());
    }

    #[Test]
    public function track_error_inserts_then_deduplicates(): void
    {
        $svc = $this->service();
        $msg = $this->marker . uniqid();

        $id1 = $svc->trackError('warning', $msg, ['file' => 'x.php', 'line' => 10]);
        $id2 = $svc->trackError('warning', $msg, ['file' => 'x.php', 'line' => 10]);

        $this->assertSame($id1, $id2);
        $this->assertEquals(2, DB::table('error_tracking')->where('id', $id1)->value('occurrence_count'));
    }

    #[Test]
    public function stats_cache_is_invalidated_on_new_error_across_periods(): void
    {
        $svc = $this->service();

        // Prime the period-keyed caches.
        $before = $svc->getErrorStats('today')['total'];
        $this->assertNotNull(Cache::get('monitoring:error_stats:today'));

        // A new error must bust the period cache (the bug: it only forgot the
        // un-suffixed key, leaving per-period caches stale).
        $svc->trackError('error', $this->marker . uniqid(), ['file' => 'y.php', 'line' => 1]);

        $this->assertNull(Cache::get('monitoring:error_stats:today'), 'period cache should be cleared');
        $after = $svc->getErrorStats('today')['total'];
        $this->assertSame($before + 1, $after);
    }

    protected function tearDown(): void
    {
        DB::table('error_tracking')->where('message', 'like', $this->marker . '%')->delete();
        foreach (['today', 'week', 'month'] as $p) {
            Cache::forget("monitoring:error_stats:{$p}");
        }

        parent::tearDown();
    }
}
