<?php

namespace Modules\OpenApi\Tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Exercises the HTTP surface of the Swagger UI + JSON docs routes.
 *
 * Docs JSON is now produced by zircote/swagger-php scanning @OA
 * annotations on the module API controllers (see OpenApiSpecTest for
 * the annotation-level coverage). The controller under test simply
 * extends L5Swagger\Http\Controllers\SwaggerController so the vendor
 * middleware wires $documentation / $config onto the request before
 * docs() runs.
 */
class SwaggerControllerTest extends TestCase
{
    #[Test]
    public function docs_json_contains_every_module_api_prefix(): void
    {
        $response = $this->get('/docs');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/json');

        $json = json_decode($response->getContent(), true);

        $this->assertIsArray($json, 'Docs endpoint must return JSON');
        $this->assertSame('Microweber Headless API', $json['info']['title'] ?? null);
        $this->assertArrayHasKey('paths', $json);

        $expectedPrefixes = [
            '/api/module/content',
            '/api/module/pages',
            '/api/module/posts',
            '/api/module/tags',
            '/api/module/comments',
            '/api/module/menus',
            '/api/module/media',
            '/api/module/forms',
            '/api/module/products',
            '/api/module/categories',
            '/api/module/orders',
            '/api/module/cart',
            '/api/module/checkout',
            '/api/module/coupons',
            '/api/module/shipping',
            '/api/module/tax',
            '/api/module/invoices',
            '/api/module/customers',
            '/api/module/profile',
            '/api/module/newsletter',
            '/api/module/settings',
            '/api/module/users',
        ];

        $foundPrefixes = [];
        foreach (array_keys($json['paths']) as $path) {
            if (preg_match('|^(/api/module/[^/]+)|', $path, $m)) {
                $foundPrefixes[$m[1]] = true;
            }
        }

        foreach ($expectedPrefixes as $prefix) {
            $this->assertArrayHasKey(
                $prefix,
                $foundPrefixes,
                "Swagger docs JSON is missing module prefix {$prefix}"
            );
        }
    }

    #[Test]
    public function docs_json_exposes_bearer_and_passport_security_schemes(): void
    {
        $response = $this->get('/docs');
        $response->assertOk();

        $json = json_decode($response->getContent(), true);
        $schemes = $json['components']['securitySchemes'] ?? [];

        $this->assertArrayHasKey('bearerAuth', $schemes);
        $this->assertArrayHasKey('passport', $schemes);
    }

    #[Test]
    public function documentation_route_renders_swagger_ui(): void
    {
        $response = $this->get('/api/documentation');

        $response->assertOk();
        $this->assertStringContainsString('swagger-ui', strtolower($response->getContent()));
    }
}
