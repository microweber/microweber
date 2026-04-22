<?php

namespace Modules\WordPressMigration\Tests\Feature;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\WordPressMigrationJobRepository;
use Modules\WordPressMigration\Services\WordPressSiteProbeResult;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Exercise wp_migration_jobs persistence + state transitions against
 * a real DB. Integration scope is intentional: the interesting bugs
 * here are idempotency and encryption round-trips, both of which
 * only surface in a real MySQL/Eloquent combination.
 *
 * Each test scrubs the table before running so the suite is order-
 * independent; we avoid RefreshDatabase per the project's testing
 * constraints (see feedback_testing memory).
 */
class WordPressMigrationJobRepositoryTest extends TestCase
{
    protected WordPressMigrationJobRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('wp_migration_jobs')) {
            $this->artisan('module:migrate', ['module' => 'WordPressMigration']);
        }

        DB::table('wp_migration_jobs')->delete();

        $this->repository = new WordPressMigrationJobRepository();
    }

    #[Test]
    public function storing_a_probe_result_inserts_a_job_row_with_probe_data(): void
    {
        $result = $this->makeRestProbeResult('https://wp.example', 42, 7);

        $job = $this->repository->storeProbeResult($result);

        $this->assertNotNull($job->id);
        $this->assertSame('https://wp.example', $job->source_url);
        $this->assertSame('wp.example', $job->source_host);
        $this->assertSame(WordPressMigrationJob::STATUS_READY, $job->status);
        $this->assertSame(WordPressSiteProbeResult::MODE_REST, $job->mode);
        $this->assertSame(42, $job->probe_result['estimated_posts']);
        $this->assertSame(7, $job->probe_result['estimated_pages']);
        $this->assertNotNull($job->last_probed_at);
        $this->assertNull($job->encrypted_credentials);
    }

    #[Test]
    public function storing_a_probe_result_twice_upserts_onto_the_same_row(): void
    {
        $first = $this->makeRestProbeResult('https://wp.example', 10, 2);
        $second = $this->makeRestProbeResult('https://wp.example', 50, 3);

        $a = $this->repository->storeProbeResult($first);
        Carbon::setTestNow(Carbon::now()->addMinute());
        $b = $this->repository->storeProbeResult($second);
        Carbon::setTestNow();

        $this->assertSame($a->id, $b->id);
        $this->assertSame(1, DB::table('wp_migration_jobs')->count());
        $this->assertSame(50, $b->probe_result['estimated_posts']);
        $this->assertTrue($b->last_probed_at->greaterThan($a->last_probed_at));
    }

    #[Test]
    public function unreachable_probe_sets_status_to_unreachable(): void
    {
        $result = new WordPressSiteProbeResult(
            sourceUrl: 'https://wp.example',
            sourceHost: 'wp.example',
            mode: WordPressSiteProbeResult::MODE_UNREACHABLE,
            capabilities: [],
            restEnabled: false,
            restNamespace: null,
            rssReachable: false,
            sitemapReachable: false,
            sitemapIndexUrl: null,
            estimatedPosts: null,
            estimatedPages: null,
            disallowedPaths: [],
            warnings: [],
            errors: ['Source at https://wp.example did not respond to any probe endpoint'],
        );

        $job = $this->repository->storeProbeResult($result);

        $this->assertSame(WordPressMigrationJob::STATUS_UNREACHABLE, $job->status);
        $this->assertSame(WordPressSiteProbeResult::MODE_UNREACHABLE, $job->mode);
    }

    #[Test]
    public function re_probing_a_running_job_preserves_status_and_progress(): void
    {
        $job = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://wp.example', 100, 10)
        );
        $this->repository->markRunning($job);
        $this->repository->updateProgress($job, ['cursor' => 'post-42', 'done' => 42]);

        $re = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://wp.example', 200, 15)
        );

        $this->assertSame($job->id, $re->id);
        $this->assertSame(WordPressMigrationJob::STATUS_RUNNING, $re->status);
        $this->assertSame(200, $re->probe_result['estimated_posts']);
        $this->assertSame('post-42', $re->progress['cursor']);
        $this->assertSame(42, $re->progress['done']);
    }

    #[Test]
    public function re_probing_a_finished_job_preserves_terminal_status(): void
    {
        $job = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://wp.example', 5, 1)
        );
        $this->repository->markRunning($job);
        $this->repository->markFinished($job);

        $re = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://wp.example', 7, 2)
        );

        $this->assertSame(WordPressMigrationJob::STATUS_FINISHED, $re->status);
        $this->assertSame(7, $re->probe_result['estimated_posts']);
    }

    #[Test]
    public function findByUrl_normalizes_the_input_before_lookup(): void
    {
        $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://wp.example', 1, 0)
        );

        $byExact = $this->repository->findByUrl('https://wp.example');
        $byTrailingSlash = $this->repository->findByUrl('https://wp.example/');
        $byUpperCaseHost = $this->repository->findByUrl('HTTPS://WP.EXAMPLE');
        $bySchemeless = $this->repository->findByUrl('wp.example');
        $byGarbage = $this->repository->findByUrl('not a url at all');

        $this->assertNotNull($byExact);
        $this->assertNotNull($byTrailingSlash);
        $this->assertNotNull($byUpperCaseHost);
        $this->assertNotNull($bySchemeless);
        $this->assertSame($byExact->id, $byTrailingSlash->id);
        $this->assertSame($byExact->id, $byUpperCaseHost->id);
        $this->assertSame($byExact->id, $bySchemeless->id);
        $this->assertNull($byGarbage);
    }

    #[Test]
    public function application_password_is_stored_encrypted_and_round_trips(): void
    {
        Carbon::setTestNow('2026-04-23 10:00:00');
        $result = $this->makeRestProbeResult('https://wp.example', 1, 0);

        $job = $this->repository->storeProbeResult($result, 'xxxx yyyy zzzz 1234');

        $this->assertSame('xxxx yyyy zzzz 1234', $job->encrypted_credentials);
        $this->assertSame('2026-04-24 10:00:00', $job->credentials_expire_at->format('Y-m-d H:i:s'));
        $this->assertTrue($job->hasValidCredentials());

        $rawCipher = DB::table('wp_migration_jobs')
            ->where('id', $job->id)
            ->value('encrypted_credentials');
        $this->assertNotSame('xxxx yyyy zzzz 1234', $rawCipher);
        $this->assertNotEmpty($rawCipher);

        Carbon::setTestNow();
    }

    #[Test]
    public function pruneExpiredCredentials_clears_only_rows_past_their_expiry(): void
    {
        Carbon::setTestNow('2026-04-23 10:00:00');
        $fresh = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://fresh.example', 1, 0),
            'fresh-password'
        );
        $stale = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://stale.example', 1, 0),
            'stale-password'
        );
        DB::table('wp_migration_jobs')
            ->where('id', $stale->id)
            ->update(['credentials_expire_at' => '2026-04-22 09:00:00']);

        Carbon::setTestNow('2026-04-23 11:00:00');
        $pruned = $this->repository->pruneExpiredCredentials();
        Carbon::setTestNow();

        $this->assertSame(1, $pruned);

        $freshFresh = $fresh->fresh();
        $this->assertSame('fresh-password', $freshFresh->encrypted_credentials);
        $this->assertTrue($freshFresh->hasValidCredentials());

        $freshStale = $stale->fresh();
        $this->assertNull($freshStale->encrypted_credentials);
        $this->assertNull($freshStale->credentials_expire_at);
        $this->assertFalse($freshStale->hasValidCredentials());
    }

    #[Test]
    public function status_transitions_record_started_and_finished_timestamps(): void
    {
        $job = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://wp.example', 1, 0)
        );
        $this->assertNull($job->started_at);
        $this->assertNull($job->finished_at);

        $job = $this->repository->markRunning($job);
        $this->assertSame(WordPressMigrationJob::STATUS_RUNNING, $job->status);
        $this->assertNotNull($job->started_at);
        $this->assertNull($job->finished_at);

        $job = $this->repository->markFinished($job);
        $this->assertSame(WordPressMigrationJob::STATUS_FINISHED, $job->status);
        $this->assertNotNull($job->finished_at);
        $this->assertTrue($job->isTerminal());
    }

    #[Test]
    public function mark_failed_records_the_error_and_is_terminal(): void
    {
        $job = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://wp.example', 1, 0)
        );
        $this->repository->markRunning($job);

        $job = $this->repository->markFailed($job, 'boom');

        $this->assertSame(WordPressMigrationJob::STATUS_FAILED, $job->status);
        $this->assertSame('boom', $job->last_error);
        $this->assertNotNull($job->finished_at);
        $this->assertTrue($job->isTerminal());
    }

    #[Test]
    public function updating_progress_merges_rather_than_replaces(): void
    {
        $job = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://wp.example', 10, 0)
        );
        $this->repository->updateProgress($job, ['done' => 1, 'cursor' => 'a']);
        $this->repository->updateProgress($job, ['done' => 2, 'last_guid' => 'g1']);

        $fresh = $job->fresh();
        $this->assertSame(2, $fresh->progress['done']);
        $this->assertSame('a', $fresh->progress['cursor']);
        $this->assertSame('g1', $fresh->progress['last_guid']);
    }

    #[Test]
    public function clear_credentials_nulls_both_columns(): void
    {
        $job = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://wp.example', 1, 0),
            'secret'
        );
        $this->assertTrue($job->hasValidCredentials());

        $this->repository->clearCredentials($job);

        $this->assertNull($job->encrypted_credentials);
        $this->assertNull($job->credentials_expire_at);
        $this->assertFalse($job->hasValidCredentials());
    }

    #[Test]
    public function options_are_merged_across_subsequent_probes(): void
    {
        $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://wp.example', 1, 0),
            null,
            ['conflict_policy' => 'skip', 'max_items' => 100]
        );
        $job = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://wp.example', 1, 0),
            null,
            ['conflict_policy' => 'overwrite']
        );

        $this->assertSame('overwrite', $job->options['conflict_policy']);
        $this->assertSame(100, $job->options['max_items']);
    }

    #[Test]
    public function two_different_urls_produce_two_different_rows(): void
    {
        $a = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://a.example', 1, 0)
        );
        $b = $this->repository->storeProbeResult(
            $this->makeRestProbeResult('https://b.example', 2, 0)
        );

        $this->assertNotSame($a->id, $b->id);
        $this->assertSame(2, DB::table('wp_migration_jobs')->count());
    }

    private function makeRestProbeResult(string $url, int $posts, int $pages): WordPressSiteProbeResult
    {
        return new WordPressSiteProbeResult(
            sourceUrl: $url,
            sourceHost: parse_url($url, PHP_URL_HOST),
            mode: WordPressSiteProbeResult::MODE_REST,
            capabilities: [WordPressSiteProbeResult::MODE_REST],
            restEnabled: true,
            restNamespace: 'WordPress',
            rssReachable: false,
            sitemapReachable: false,
            sitemapIndexUrl: null,
            estimatedPosts: $posts,
            estimatedPages: $pages,
            disallowedPaths: [],
            warnings: [],
            errors: [],
        );
    }
}
