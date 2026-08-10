<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Tools;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

abstract class ToolTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure AI module is enabled for tests
        config(['modules.ai.enabled' => true]);

        // Don't fake HTTP here - let individual tests set up their own mocks
        // This prevents interference with test-specific Http::fake() calls
    }

    protected function tearDown(): void
    {
        // Clear HTTP fakes after each test
        Http::fake();

        parent::tearDown();
    }

    /**
     * Mock a successful HTTP response with JSON body
     */
    protected function mockJsonResponse(array $data, int $status = 200): void
    {
        Http::fake([
            '*' => Http::response(json_encode($data), $status, ['Content-Type' => 'application/json']),
        ]);
    }

    /**
     * Mock a failed HTTP response
     */
    protected function mockFailedResponse(int $status = 500, string $body = ''): void
    {
        Http::fake([
            '*' => Http::response($body, $status),
        ]);
    }

    /**
     * Mock an HTML response
     */
    protected function mockHtmlResponse(string $html, int $status = 200): void
    {
        Http::fake([
            '*' => Http::response($html, $status, ['Content-Type' => 'text/html']),
        ]);
    }

    /**
     * Mock a network exception
     */
    protected function mockNetworkException(string $message = 'Network error'): void
    {
        Http::fake([
            '*' => function () use ($message) {
                throw new \Exception($message);
            },
        ]);
    }

    /**
     * Assert that HTTP requests were made to specific URLs
     */
    protected function assertHttpRequestsSent(array $urls): void
    {
        foreach ($urls as $url) {
            Http::assertSent(function ($request) use ($url) {
                return str_contains($request->url(), $url);
            });
        }
    }

    /**
     * Create a mock for a service class
     */
    protected function mockService(string $serviceClass, array $methods = []): object
    {
        $mock = $this->createMock($serviceClass);

        foreach ($methods as $method => $returnValue) {
            $mock->method($method)->willReturn($returnValue);
        }

        return $mock;
    }
}
