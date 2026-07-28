<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Tests\Feature;

use MicroweberPackages\Minifier\Tests\TestCase;
use MicroweberPackages\User\Models\User;

class MinifierRoutesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The minifier routes are admin-gated (see config/minifier.php), so
        // authenticate as an admin — is_admin() gates the Admin middleware.
        $admin = new User();
        $admin->username = 'minifier_admin_' . uniqid();
        $admin->email = 'minifier_admin_' . uniqid() . '@example.com';
        $admin->password = 'password';
        $admin->is_admin = 1;
        $admin->is_active = 1;
        $admin->save();

        $this->actingAs($admin);
    }

    public function test_stats_endpoint(): void
    {
        $response = $this->get(route('minifier.stats'));
        $response->assertOk();
        $response->assertJsonStructure([
            'enabled',
            'minify_js',
            'minify_css',
            'version',
            'engine',
        ]);
    }

    public function test_self_test_endpoint(): void
    {
        $response = $this->get(route('minifier.self-test'));
        $response->assertOk();
        $response->assertJsonPath('js.ok', true);
        $response->assertJsonPath('css.ok', true);
    }

    public function test_ping_endpoint(): void
    {
        $response = $this->get(route('minifier.ping'));
        $response->assertOk();
        $response->assertJsonPath('ok', true);
        $response->assertJsonPath('package', 'microweber-packages/minifier');
    }

    public function test_minify_js_endpoint(): void
    {
        $response = $this->postJson(route('minifier.js'), [
            'code' => "function hello() {\n  // c\n  return 1;\n}",
        ]);
        $response->assertOk();
        $response->assertJsonPath('success', true);
        $this->assertArrayHasKey('code', $response->json());
        $this->assertLessThan(
            $response->json('original_length'),
            $response->json('minified_length') + 1
        );
    }

    public function test_minify_css_endpoint(): void
    {
        $response = $this->postJson(route('minifier.css'), [
            'code' => "/* c */\n.a {\n  color: red;\n}",
        ]);
        $response->assertOk();
        $response->assertJsonPath('success', true);
        $this->assertStringContainsString('color:red', (string) $response->json('code'));
    }

    public function test_minify_js_requires_code(): void
    {
        $this->postJson(route('minifier.js'), [])->assertStatus(422);
    }

    public function test_minify_css_requires_code(): void
    {
        $this->postJson(route('minifier.css'), [])->assertStatus(422);
    }
}
