<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Tests\Feature;

use MicroweberPackages\ImageOptimization\Tests\TestCase;

/**
 * Lightweight smoke tests for every package route.
 */
class SmokeRoutesTest extends TestCase
{
    /**
     * @return list<array{0: string, 1: string, 2: array<string, mixed>, 3: list<int>}>
     */
    public static function routeProvider(): array
    {
        return [
            ['GET', 'image-optimization.stats', [], [200]],
            ['GET', 'image-optimization.webp', [], [422]],
            ['GET', 'image-optimization.webp', ['src' => 'missing.jpg'], [404, 422, 200]],
            ['GET', 'image-optimization.convert', [], [422]],
            ['GET', 'image-optimization.convert', ['src' => 'missing.jpg'], [422, 404]],
            ['POST', 'image-optimization.clear-cache', [], [200, 403]],
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
