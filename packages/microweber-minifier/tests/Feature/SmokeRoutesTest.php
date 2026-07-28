<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Tests\Feature;

use MicroweberPackages\Minifier\Tests\TestCase;
use MicroweberPackages\User\Models\User;

/**
 * Lightweight smoke tests for every package route.
 */
class SmokeRoutesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Routes are admin-gated (config/minifier.php) — authenticate as admin.
        $admin = new User();
        $admin->username = 'minifier_smoke_' . uniqid();
        $admin->email = 'minifier_smoke_' . uniqid() . '@example.com';
        $admin->password = 'password';
        $admin->is_admin = 1;
        $admin->is_active = 1;
        $admin->save();

        $this->actingAs($admin);
    }

    /**
     * @return list<array{0: string, 1: string, 2: array<string, mixed>, 3: list<int>}>
     */
    public static function routeProvider(): array
    {
        return [
            ['GET', 'minifier.stats', [], [200]],
            ['GET', 'minifier.self-test', [], [200]],
            ['GET', 'minifier.ping', [], [200]],
            ['POST', 'minifier.js', [], [422]],
            ['POST', 'minifier.css', [], [422]],
        ];
    }

    /**
     * @param  array<string, mixed>  $params
     * @param  list<int>  $allowedStatuses
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('routeProvider')]
    public function test_route_smoke(string $method, string $routeName, array $params, array $allowedStatuses): void
    {
        $this->assertTrue(app('router')->has($routeName), "Route {$routeName} should be registered");

        $url = route($routeName, $params);

        $response = match (strtoupper($method)) {
            'POST' => $this->post($url),
            default => $this->get($url),
        };

        $this->assertContains(
            $response->status(),
            $allowedStatuses,
            "Route {$routeName} returned {$response->status()}, expected one of: " . implode(',', $allowedStatuses)
        );
    }
}
