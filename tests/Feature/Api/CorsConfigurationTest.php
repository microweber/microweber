<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * Exercises the CORS behavior on the headless `/api/module/*` namespace
 * that third-party frontends (SPAs, mobile apps, other sites) rely on.
 *
 * Tests run the Laravel-shipped `HandleCors` middleware against synthetic
 * requests so we verify the headers it produces without going through
 * Microweber's `/api/*` API gateway (which would intercept the request
 * and return a 403 "not in the allowed functions list").
 */
final class CorsConfigurationTest extends TestCase
{
    /**
     * Run a request through HandleCors as if it were a real /api/module/*
     * hit, then return the final response so the test can inspect the
     * Access-Control-* headers.
     */
    private function runThroughCors(string $method, string $origin, array $extraHeaders = [], string $path = 'api/module/probe'): Response
    {
        $server = array_filter(array_merge(
            ['HTTP_ORIGIN' => $origin],
            $extraHeaders,
        ));

        $request = Request::create('/' . ltrim($path, '/'), $method, [], [], [], $server);

        /** @var HandleCors $middleware */
        $middleware = app(HandleCors::class);

        return $middleware->handle($request, function () {
            return response()->json(['ok' => true]);
        });
    }

    #[Test]
    public function preflight_from_allowed_origin_succeeds_with_credentials(): void
    {
        config()->set('cors.allowed_origins', ['https://app.example.com']);
        config()->set('cors.allowed_origins_patterns', []);
        config()->set('cors.supports_credentials', true);
        config()->set('cors.allowed_methods', ['*']);
        config()->set('cors.allowed_headers', ['*']);

        $response = $this->runThroughCors('OPTIONS', 'https://app.example.com', [
            'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'POST',
            'HTTP_ACCESS_CONTROL_REQUEST_HEADERS' => 'authorization, content-type',
        ]);

        $this->assertSame(204, $response->getStatusCode());
        $this->assertSame('https://app.example.com', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertSame('true', $response->headers->get('Access-Control-Allow-Credentials'));
    }

    #[Test]
    public function disallowed_origin_does_not_receive_allow_origin(): void
    {
        // With multiple allowed origins the CorsService only echoes the
        // request's Origin back if it's on the list; an unknown origin
        // should get no Access-Control-Allow-Origin at all.
        config()->set('cors.allowed_origins', [
            'https://app.example.com',
            'https://admin.example.com',
        ]);
        config()->set('cors.allowed_origins_patterns', []);

        $response = $this->runThroughCors('GET', 'https://evil.example.org');

        $this->assertNull(
            $response->headers->get('Access-Control-Allow-Origin'),
            'Access-Control-Allow-Origin must not be echoed for origins not on the allow-list'
        );
    }

    #[Test]
    public function regex_pattern_allows_subdomain_origins(): void
    {
        config()->set('cors.allowed_origins', []);
        config()->set('cors.allowed_origins_patterns', ['#^https://[a-z0-9-]+\.example\.com$#']);
        config()->set('cors.supports_credentials', true);

        $response = $this->runThroughCors('GET', 'https://staging.example.com');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('https://staging.example.com', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertSame('true', $response->headers->get('Access-Control-Allow-Credentials'));
    }

    #[Test]
    public function wildcard_origin_disables_credentials_on_the_response(): void
    {
        // In production config/cors.php forces credentials off when
        // allowed_origins is ['*']. The direct config equivalent is set
        // here so the test mirrors the shipped guarantee.
        config()->set('cors.allowed_origins', ['*']);
        config()->set('cors.allowed_origins_patterns', []);
        config()->set('cors.supports_credentials', false);

        $response = $this->runThroughCors('GET', 'https://any.example.com');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertNull(
            $response->headers->get('Access-Control-Allow-Credentials'),
            'Per the CORS spec, credentials must not be combined with a wildcard origin'
        );
    }

    #[Test]
    public function cors_config_auto_disables_credentials_when_no_origin_env_is_set(): void
    {
        putenv('CORS_ALLOWED_ORIGINS=');
        putenv('CORS_ALLOWED_ORIGIN_PATTERNS=');
        putenv('CORS_SUPPORTS_CREDENTIALS=true');

        $config = require base_path('config/cors.php');

        $this->assertSame(['*'], $config['allowed_origins']);
        $this->assertFalse(
            $config['supports_credentials'],
            'With wildcard origins the config must force supports_credentials=false'
        );
    }

    #[Test]
    public function cors_config_reads_allowed_origins_from_env_as_csv(): void
    {
        putenv('CORS_ALLOWED_ORIGINS=https://a.example.com, https://b.example.com');
        putenv('CORS_ALLOWED_ORIGIN_PATTERNS=');
        putenv('CORS_SUPPORTS_CREDENTIALS=true');

        $config = require base_path('config/cors.php');

        $this->assertSame(
            ['https://a.example.com', 'https://b.example.com'],
            array_values($config['allowed_origins']),
            'Comma-separated CORS_ALLOWED_ORIGINS must expand into the config array'
        );
        $this->assertTrue(
            $config['supports_credentials'],
            'With an explicit origin list supports_credentials should follow the env flag'
        );
    }

    #[Test]
    public function cors_config_exposes_rate_limit_headers_by_default(): void
    {
        putenv('CORS_EXPOSED_HEADERS=');

        $config = require base_path('config/cors.php');

        $this->assertContains('X-RateLimit-Limit', $config['exposed_headers']);
        $this->assertContains('X-RateLimit-Remaining', $config['exposed_headers']);
        $this->assertContains('Retry-After', $config['exposed_headers']);
    }

    #[Test]
    public function cors_config_paths_cover_the_module_api_namespace(): void
    {
        $config = require base_path('config/cors.php');

        $this->assertContains('api/module/*', $config['paths']);
        $this->assertContains('api/*', $config['paths']);
    }

    protected function tearDown(): void
    {
        foreach ([
            'CORS_ALLOWED_ORIGINS',
            'CORS_ALLOWED_ORIGIN_PATTERNS',
            'CORS_SUPPORTS_CREDENTIALS',
            'CORS_EXPOSED_HEADERS',
        ] as $var) {
            putenv($var);
        }

        parent::tearDown();
    }
}
