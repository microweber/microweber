<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Tests\Dusk;

use MicroweberPackages\User\Models\User;
use Tests\DuskTestCase;

/**
 * Dusk / browser smoke tests for minifier package routes.
 *
 * The routes are admin-gated and only registered in the testing environment,
 * so these smokes run as an authenticated admin via internal requests.
 */
class MinifierRoutesDuskTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $admin = new User();
        $admin->username = 'minifier_dusk_' . uniqid();
        $admin->email = 'minifier_dusk_' . uniqid() . '@example.com';
        $admin->password = 'password';
        $admin->is_admin = 1;
        $admin->is_active = 1;
        $admin->save();

        $this->actingAs($admin);
    }

    protected function httpSmoke(string $path, int|array $expectedStatus, ?string $see = null): void
    {
        // Admin-gated routes need the authenticated session (established in
        // setUp), so use internal requests rather than raw HTTP to the server.
        $response = $this->get($path);
        $statuses = is_array($expectedStatus) ? $expectedStatus : [$expectedStatus];
        $this->assertContains($response->status(), $statuses);

        if ($see !== null) {
            $body = (string) $response->getContent();
            // JSON may escape slashes; compare unescaped content too.
            $this->assertTrue(
                str_contains($body, $see) || str_contains(stripslashes($body), $see),
                "Response body does not contain expected fragment: {$see}"
            );
        }
    }

    public function test_stats_route_smoke(): void
    {
        $this->httpSmoke('/minifier/stats', 200, 'minify_js');
    }

    public function test_ping_route_smoke(): void
    {
        $this->httpSmoke('/api/minifier/ping', 200, 'microweber-packages/minifier');
    }

    public function test_self_test_route_smoke(): void
    {
        $this->httpSmoke('/minifier/self-test', 200, 'original_len');
    }
}
