<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Feature;

use MicroweberPackages\TemplateFonts\Tests\TestCase;

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
            ['GET', 'api.template.get-fonts', [], [200, 401, 403, 302]],
            ['GET', 'api.template.get-favorite-fonts', [], [200, 401, 403, 302]],
            ['POST', 'api.template.save-template-fonts', [], [200, 401, 403, 302, 419, 422]],
            ['POST', 'api.template.remove-favorite-font', [], [200, 401, 403, 302, 419, 422]],
            ['POST', 'api.template.upload-custom-font', [], [200, 401, 403, 302, 419, 422]],
            ['GET', 'print_custom_css_fonts', [], [200]],
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
            'POST' => $this->post($url, $params),
            default => $this->get($url),
        };

        $this->assertContains(
            $response->status(),
            $allowedStatuses,
            "Route {$routeName} returned {$response->status()}, expected one of: " . implode(',', $allowedStatuses)
        );
    }

    public function test_print_css_returns_text_css(): void
    {
        $response = $this->get(route('print_custom_css_fonts'));
        $response->assertStatus(200);
        $this->assertStringContainsString('text/css', (string) $response->headers->get('Content-Type'));
    }
}
