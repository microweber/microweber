<?php

namespace Tests\Feature\Filament;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * Smoke test: every static admin GET route must respond with a non-5xx status
 * for an authenticated admin user. Catches accidental 500s introduced by
 * resource/page edits without having to load each page in a browser.
 *
 * Routes are sourced from tests/fixtures/admin-pages.php which is generated
 * from `php artisan route:list --path=admin --json`.
 */
class AdminPagesNo500Test extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    public static function staticAdminRoutesProvider(): array
    {
        $fixture = require __DIR__ . '/../../fixtures/admin-pages.php';
        $cases = [];
        foreach ($fixture['static'] as $uri) {
            $cases[$uri] = [$uri];
        }
        return $cases;
    }

    #[Test]
    #[DataProvider('staticAdminRoutesProvider')]
    public function admin_route_does_not_500(string $uri): void
    {
        $this->actingAsAdmin();

        $response = $this->get('/' . ltrim($uri, '/'));
        $status = $response->getStatusCode();

        $this->assertLessThan(
            500,
            $status,
            "GET /{$uri} returned {$status} (expected < 500)"
        );
    }
}
