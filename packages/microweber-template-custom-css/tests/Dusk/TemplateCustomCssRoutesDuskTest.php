<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Dusk;

use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

/**
 * Dusk-oriented smoke tests for template-custom-css package routes.
 *
 * Extends package TestCase (not Tests\DuskTestCase) so `php artisan test`
 * works without Chromedriver. When a real browser is available, optional
 * browse assertions can be added by running via `php artisan dusk`.
 */
class TemplateCustomCssRoutesDuskTest extends TestCase
{
    public function test_print_css_route_smoke(): void
    {
        $response = $this->get('/api/template/print_custom_css');
        $response->assertStatus(200);
        $this->assertStringContainsString('text/css', (string) $response->headers->get('Content-Type'));
    }

    public function test_validate_css_route_smoke(): void
    {
        $response = $this->post('/api/template/validate_css', [
            'css' => 'body { color: black; }',
        ]);
        $this->assertContains($response->status(), [200, 401, 403, 302, 419]);
    }

    public function test_save_live_edit_route_smoke(): void
    {
        $response = $this->post('/api/current_template_save_custom_css', [
            'css_file_content' => '.dusk { color: red; }',
            'active_site_template' => 'Bootstrap',
        ]);
        $this->assertContains($response->status(), [200, 400, 401, 403, 302, 419, 422]);
    }

    public function test_remove_css_route_smoke(): void
    {
        $response = $this->post('/api/layouts/template_remove_custom_css', [
            'template' => 'Bootstrap',
        ]);
        $this->assertContains($response->status(), [200, 400, 401, 403, 302, 419, 422]);
    }

    public function test_save_custom_css_route_smoke(): void
    {
        $response = $this->post('/api/template/save_custom_css', [
            'css' => '/* dusk custom */',
        ]);
        $this->assertContains($response->status(), [200, 401, 403, 302, 419, 422]);
    }

    public function test_live_edit_css_url_route_smoke(): void
    {
        $response = $this->get('/api/template/live_edit_css_url?template=Bootstrap');
        $this->assertContains($response->status(), [200, 401, 403, 302]);
    }
}
