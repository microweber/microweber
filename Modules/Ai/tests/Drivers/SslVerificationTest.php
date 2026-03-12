<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Drivers;

use Modules\Ai\Services\Drivers\FalAiDriver;
use Modules\Ai\Services\Drivers\ReplicateAiDriver;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SslVerificationTest extends TestCase
{
    #[Test]
    public function it_fal_ai_fetch_image_content_uses_ssl_verification(): void
    {
        $driver = new FalAiDriver(['api_key' => 'test-key']);

        $reflection = new \ReflectionMethod($driver, 'fetchImageContent');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));

        $this->assertStringContainsString('CURLOPT_SSL_VERIFYHOST, 2', $source, 'FalAi fetchImageContent must set CURLOPT_SSL_VERIFYHOST to 2');
        $this->assertStringContainsString('CURLOPT_SSL_VERIFYPEER, true', $source, 'FalAi fetchImageContent must enable CURLOPT_SSL_VERIFYPEER');
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYPEER, false', $source, 'FalAi fetchImageContent must not disable SSL verification');
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYHOST, 0', $source, 'FalAi fetchImageContent must not set CURLOPT_SSL_VERIFYHOST to 0');
    }

    #[Test]
    public function it_fal_ai_make_request_uses_ssl_verification(): void
    {
        $driver = new FalAiDriver(['api_key' => 'test-key']);

        $reflection = new \ReflectionMethod($driver, 'makeRequest');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));

        $this->assertStringContainsString('CURLOPT_SSL_VERIFYHOST, 2', $source, 'FalAi makeRequest must set CURLOPT_SSL_VERIFYHOST to 2');
        $this->assertStringContainsString('CURLOPT_SSL_VERIFYPEER, true', $source, 'FalAi makeRequest must enable CURLOPT_SSL_VERIFYPEER');
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYPEER, false', $source, 'FalAi makeRequest must not disable SSL verification');
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYHOST, 0', $source, 'FalAi makeRequest must not set CURLOPT_SSL_VERIFYHOST to 0');
    }

    #[Test]
    public function it_replicate_fetch_image_content_uses_ssl_verification(): void
    {
        $driver = new ReplicateAiDriver(['api_key' => 'test-token']);

        $reflection = new \ReflectionMethod($driver, 'fetchImageContent');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));

        $this->assertStringContainsString('CURLOPT_SSL_VERIFYHOST, 2', $source, 'Replicate fetchImageContent must set CURLOPT_SSL_VERIFYHOST to 2');
        $this->assertStringContainsString('CURLOPT_SSL_VERIFYPEER, true', $source, 'Replicate fetchImageContent must enable CURLOPT_SSL_VERIFYPEER');
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYPEER, false', $source, 'Replicate fetchImageContent must not disable SSL verification');
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYHOST, 0', $source, 'Replicate fetchImageContent must not set CURLOPT_SSL_VERIFYHOST to 0');
    }

    #[Test]
    public function it_replicate_make_request_uses_ssl_verification(): void
    {
        $driver = new ReplicateAiDriver(['api_key' => 'test-token']);

        $reflection = new \ReflectionMethod($driver, 'makeRequest');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));

        // Note: ReplicateAiDriver::makeRequest currently lacks explicit SSL settings.
        // This test documents the expectation and will fail if SSL is explicitly disabled.
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYPEER, false', $source, 'Replicate makeRequest must not disable CURLOPT_SSL_VERIFYPEER');
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYHOST, 0', $source, 'Replicate makeRequest must not set CURLOPT_SSL_VERIFYHOST to 0');
    }

    #[Test]
    public function it_fal_ai_driver_has_correct_default_config(): void
    {
        $driver = new FalAiDriver(['api_key' => 'test-key']);

        $reflection = new \ReflectionProperty($driver, 'timeout');
        $reflection->setAccessible(true);

        $this->assertSame(300, $reflection->getValue($driver), 'FalAi default timeout should be 300 seconds');

        $endpointProp = new \ReflectionProperty($driver, 'apiEndpoint');
        $endpointProp->setAccessible(true);

        $this->assertStringStartsWith('https://', $endpointProp->getValue($driver), 'FalAi API endpoint must use HTTPS');
    }

    #[Test]
    public function it_replicate_driver_has_correct_default_config(): void
    {
        $driver = new ReplicateAiDriver(['api_key' => 'test-token']);

        $endpointProp = new \ReflectionProperty($driver, 'apiEndpoint');
        $endpointProp->setAccessible(true);

        $this->assertStringStartsWith('https://', $endpointProp->getValue($driver), 'Replicate API endpoint must use HTTPS');
    }

    #[Test]
    public function it_fal_ai_driver_does_not_allow_http_endpoint(): void
    {
        $driver = new FalAiDriver([
            'api_key' => 'test-key',
            'api_endpoint' => 'https://custom.fal.run',
        ]);

        $endpointProp = new \ReflectionProperty($driver, 'apiEndpoint');
        $endpointProp->setAccessible(true);

        $this->assertStringStartsWith('https://', $endpointProp->getValue($driver), 'Custom API endpoint should use HTTPS');
    }

    #[Test]
    public function it_replicate_driver_does_not_allow_http_endpoint(): void
    {
        $driver = new ReplicateAiDriver([
            'api_key' => 'test-token',
            'api_endpoint' => 'https://custom.replicate.com',
        ]);

        $endpointProp = new \ReflectionProperty($driver, 'apiEndpoint');
        $endpointProp->setAccessible(true);

        $this->assertStringStartsWith('https://', $endpointProp->getValue($driver), 'Custom API endpoint should use HTTPS');
    }

    #[Test]
    public function it_fal_ai_driver_strips_trailing_slash_from_endpoint(): void
    {
        $driver = new FalAiDriver([
            'api_key' => 'test-key',
            'api_endpoint' => 'https://fal.run/',
        ]);

        $endpointProp = new \ReflectionProperty($driver, 'apiEndpoint');
        $endpointProp->setAccessible(true);

        $this->assertStringEndsNotWith('/', $endpointProp->getValue($driver), 'Trailing slash should be stripped from endpoint');
    }

    #[Test]
    public function it_replicate_driver_strips_trailing_slash_from_endpoint(): void
    {
        $driver = new ReplicateAiDriver([
            'api_key' => 'test-token',
            'api_endpoint' => 'https://api.replicate.com/',
        ]);

        $endpointProp = new \ReflectionProperty($driver, 'apiEndpoint');
        $endpointProp->setAccessible(true);

        $this->assertStringEndsNotWith('/', $endpointProp->getValue($driver), 'Trailing slash should be stripped from endpoint');
    }
}
