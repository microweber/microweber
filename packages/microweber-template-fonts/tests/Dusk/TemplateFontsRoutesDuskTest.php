<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Dusk;

use MicroweberPackages\TemplateFonts\Tests\TestCase;

/**
 * Dusk-oriented smoke tests for template-fonts package routes.
 *
 * Extends package TestCase (not Tests\DuskTestCase) so `php artisan test`
 * works without Chromedriver. When a real browser is available, optional
 * browse assertions can be added by running via `php artisan dusk`.
 */
class TemplateFontsRoutesDuskTest extends TestCase
{
    public function test_print_css_route_smoke(): void
    {
        $response = $this->get('/api/template/print_custom_css_fonts');
        $response->assertStatus(200);
        $this->assertStringContainsString('text/css', (string) $response->headers->get('Content-Type'));
    }

    public function test_get_fonts_route_smoke(): void
    {
        $response = $this->get('/api/template/get-fonts');
        $this->assertContains($response->status(), [200, 401, 403, 302]);
    }

    public function test_get_favorite_fonts_route_smoke(): void
    {
        $response = $this->get('/api/template/get-favorite-fonts');
        $this->assertContains($response->status(), [200, 401, 403, 302]);
    }

    public function test_save_and_remove_routes_smoke(): void
    {
        $save = $this->post('/api/template/save-template-fonts', ['fonts' => ['DuskSmokeFont']]);
        $this->assertContains($save->status(), [200, 401, 403, 302, 419, 422]);

        $remove = $this->post('/api/template/remove-favorite-font', ['font' => 'DuskSmokeFont']);
        $this->assertContains($remove->status(), [200, 401, 403, 302, 419, 422]);
    }

    public function test_upload_route_smoke_missing_file(): void
    {
        $response = $this->post('/api/template/upload-custom-font', []);
        $this->assertContains($response->status(), [200, 401, 403, 302, 419, 422]);
    }
}
