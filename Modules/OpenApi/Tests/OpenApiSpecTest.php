<?php

declare(strict_types=1);

namespace Modules\OpenApi\Tests;

use OpenApi\Generator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Verifies the root @OA\Info and @OA\SecurityScheme annotations declared
 * on Modules\OpenApi\OpenApiSpec are syntactically valid and produce the
 * expected top-level OpenAPI structure.
 *
 * Full spec generation across the whole repo is exercised by artisan
 * l5-swagger:generate in CI; this test is intentionally narrow so it
 * doesn't depend on every module being annotated yet.
 */
class OpenApiSpecTest extends TestCase
{
    #[Test]
    public function root_info_and_security_schemes_are_defined(): void
    {
        $openapi = Generator::scan(
            [base_path('Modules/OpenApi/OpenApiSpec.php')],
            ['validate' => false]
        );

        $json = json_decode($openapi->toJson(), true);

        $this->assertSame('Microweber Headless API', $json['info']['title']);
        $this->assertSame('1.0.0', $json['info']['version']);
        $this->assertNotEmpty($json['info']['description']);

        $schemes = $json['components']['securitySchemes'] ?? [];

        $this->assertArrayHasKey('bearerAuth', $schemes);
        $this->assertSame('http', $schemes['bearerAuth']['type']);
        $this->assertSame('bearer', $schemes['bearerAuth']['scheme']);

        $this->assertArrayHasKey('passport', $schemes);
        $this->assertSame('oauth2', $schemes['passport']['type']);
        $this->assertArrayHasKey('authorizationCode', $schemes['passport']['flows']);
        $this->assertArrayHasKey('password', $schemes['passport']['flows']);
        $this->assertSame('/oauth/token', $schemes['passport']['flows']['password']['tokenUrl']);
        $this->assertSame('/oauth/authorize', $schemes['passport']['flows']['authorizationCode']['authorizationUrl']);
    }
}
