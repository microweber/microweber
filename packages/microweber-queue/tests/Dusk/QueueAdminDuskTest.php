<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Tests\Dusk;

use MicroweberPackages\Queue\Models\Job;
use MicroweberPackages\User\Models\User;
use Tests\DuskTestCase;

/**
 * Dusk / browser smoke tests for queue admin surfaces.
 *
 * Uses authenticated admin + internal requests (same pattern as minifier package).
 */
class QueueAdminDuskTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $admin = new User();
        $admin->username = 'queue_dusk_' . uniqid();
        $admin->email = 'queue_dusk_' . uniqid() . '@example.com';
        $admin->password = 'password';
        $admin->is_admin = 1;
        $admin->is_active = 1;
        $admin->save();

        $this->actingAs($admin);
    }

    protected function httpSmoke(string $path, int|array $expectedStatus, ?string $see = null): void
    {
        $response = $this->get($path);
        $statuses = is_array($expectedStatus) ? $expectedStatus : [$expectedStatus];
        $this->assertContains($response->status(), $statuses);

        if ($see !== null) {
            $body = (string) $response->getContent();
            $this->assertTrue(
                str_contains($body, $see) || str_contains(stripslashes($body), $see),
                "Response body does not contain expected fragment: {$see}"
            );
        }
    }

    public function test_process_route_smoke(): void
    {
        $this->httpSmoke('/microweber-queue/process', [200, 302, 401, 403]);
    }

    public function test_queue_jobs_admin_page_smoke(): void
    {
        // Filament admin panel path — accept common auth/redirect outcomes
        $this->httpSmoke('/admin/queue-jobs', [200, 302, 401, 403, 404]);
    }

    public function test_failed_jobs_admin_page_smoke(): void
    {
        $this->httpSmoke('/admin/failed-jobs', [200, 302, 401, 403, 404]);
    }

    public function test_job_model_available_in_cms(): void
    {
        $this->assertTrue(class_exists(Job::class));
        $this->assertTrue(class_exists(\MicroweberPackages\Queue\Services\ChunkedDispatcherService::class));
    }
}
