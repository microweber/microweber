<?php

namespace Modules\WordPressMigration\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ImportWordPressStatusCommandTest extends TestCase
{
    private const SOURCE_URL = 'https://status-test.example.invalid';

    private const SOURCE_HOST = 'status-test.example.invalid';

    protected function setUp(): void
    {
        parent::setUp();
        if (! Schema::hasTable('wp_migration_jobs')) {
            $this->artisan('module:migrate', ['module' => 'WordPressMigration']);
        }
        DB::table('wp_migration_staging_content')->delete();
        DB::table('wp_migration_staging_media')->delete();
        DB::table('wp_migration_jobs')->delete();
    }

    private function seedJob(array $overrides = []): WordPressMigrationJob
    {
        return WordPressMigrationJob::create(array_merge([
            'source_url' => self::SOURCE_URL,
            'source_url_hash' => hash('sha256', self::SOURCE_URL),
            'source_host' => self::SOURCE_HOST,
            'status' => WordPressMigrationJob::STATUS_FINISHED,
            'mode' => 'rest',
            'progress' => ['imported' => 5, 'failed' => 1, 'total' => 10, 'processed' => 5],
            'started_at' => now()->subMinutes(10),
            'finished_at' => now(),
        ], $overrides));
    }

    #[Test]
    public function rejects_a_non_numeric_job_id(): void
    {
        $exit = Artisan::call('microweber:import:wordpress:status', ['job' => 'abc']);
        $this->assertSame(2, $exit);
    }

    #[Test]
    public function returns_not_found_when_the_job_does_not_exist(): void
    {
        $exit = Artisan::call('microweber:import:wordpress:status', ['job' => 999999]);
        $this->assertSame(4, $exit);
    }

    #[Test]
    public function prints_a_human_summary_for_an_existing_job(): void
    {
        $job = $this->seedJob();
        $exit = Artisan::call('microweber:import:wordpress:status', ['job' => $job->id]);
        $this->assertSame(0, $exit);

        $output = Artisan::output();
        $this->assertStringContainsString(self::SOURCE_HOST, $output);
        $this->assertStringContainsString('Status:', $output);
        $this->assertStringContainsString('rest', $output);
    }

    #[Test]
    public function emits_json_payload_when_json_flag_is_set(): void
    {
        $job = $this->seedJob();

        StagingContent::create([
            'job_id' => $job->id,
            'source_guid' => 'status:guid-1',
            'source_url' => 'https://' . self::SOURCE_HOST . '/1',
            'title' => 'T1',
            'html' => '<p>X</p>',
            'source' => 'rest',
            'source_host' => self::SOURCE_HOST,
            'excluded' => false,
            'last_commit_error' => 'boom',
        ]);
        StagingContent::create([
            'job_id' => $job->id,
            'source_guid' => 'status:guid-2',
            'source_url' => 'https://' . self::SOURCE_HOST . '/2',
            'title' => 'T2',
            'html' => '<p>X</p>',
            'source' => 'rest',
            'source_host' => self::SOURCE_HOST,
            'excluded' => true,
        ]);

        $exit = Artisan::call('microweber:import:wordpress:status', [
            'job' => $job->id,
            '--json' => true,
        ]);
        $this->assertSame(0, $exit);

        $payload = json_decode(Artisan::output(), true);
        $this->assertIsArray($payload);
        $this->assertSame((int) $job->id, $payload['job_id']);
        $this->assertSame(WordPressMigrationJob::STATUS_FINISHED, $payload['status']);
        $this->assertSame('rest', $payload['mode']);
        $this->assertSame(5, $payload['progress']['imported']);
        $this->assertSame(1, $payload['progress']['failed']);
        $this->assertSame(10, $payload['progress']['total']);
        $this->assertSame(1, $payload['staging']['staged']);
        $this->assertSame(1, $payload['staging']['excluded']);
        $this->assertSame(1, $payload['staging']['last_commit_error_rows']);
    }
}
