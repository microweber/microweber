<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Feature;

use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

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
            ['GET', 'print_custom_css', [], [200]],
            ['POST', 'current_template_save_custom_css', [], [200, 400, 401, 403, 302, 419, 422]],
            ['POST', 'template_remove_custom_css', [], [200, 400, 401, 403, 302, 419, 422]],
            ['POST', 'api.template.save_custom_css', [], [200, 401, 403, 302, 419, 422]],
            ['POST', 'api.template.validate_css', [], [200, 401, 403, 302, 419, 422]],
            ['GET', 'api.template.live_edit_css_url', [], [200, 401, 403, 302]],
        ];
    }

    /**
     * @param  array<string, mixed>  $params
     * @param  list<int>  $allowedStatuses
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('routeProvider')]
    public function test_route_smoke(string $method, string $routeName, array $params, array $allowedStatuses): void
    {
        if (!app('router')->has($routeName)) {
            $this->markTestSkipped("Route {$routeName} not registered (provider may not be loaded yet)");
        }

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
        if (!app('router')->has('print_custom_css')) {
            $this->markTestSkipped('print_custom_css route not registered');
        }
        $response = $this->get(route('print_custom_css'));
        $response->assertStatus(200);
        $this->assertStringContainsString('text/css', (string) $response->headers->get('Content-Type'));
    }

    public function test_validate_css_endpoint(): void
    {
        if (!app('router')->has('api.template.validate_css')) {
            $this->markTestSkipped('validate_css route not registered');
        }

        $user = $this->actingAsAdminIfPossible();

        $ok = $this->post(route('api.template.validate_css'), ['css' => 'a { color: red; }']);
        $this->assertContains($ok->status(), [200, 401, 403, 302, 419]);

        $bad = $this->post(route('api.template.validate_css'), ['css' => '.x { color: ']);
        $this->assertContains($bad->status(), [422, 401, 403, 302, 419]);
    }

    protected function actingAsAdminIfPossible(): mixed
    {
        $userClass = class_exists(\MicroweberPackages\User\Models\User::class)
            ? \MicroweberPackages\User\Models\User::class
            : (class_exists(\App\Models\User::class) ? \App\Models\User::class : null);

        if ($userClass === null) {
            return null;
        }

        try {
            $user = $userClass::query()->where('is_admin', 1)->first();
            if (!$user) {
                $user = $userClass::query()->create([
                    'username' => 'css_admin_' . uniqid(),
                    'email' => 'css_admin_' . uniqid() . '@test.com',
                    'password' => bcrypt('password'),
                    'is_admin' => 1,
                    'is_active' => 1,
                ]);
            }
            $this->actingAs($user);

            return $user;
        } catch (\Throwable) {
            return null;
        }
    }
}
