<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Drivers;

use Modules\Ai\Services\Drivers\FalAiDriver;
use Modules\Ai\Services\Drivers\GeminiAiDriver;
use Modules\Ai\Services\Drivers\OllamaAiDriver;
use Modules\Ai\Services\Drivers\OpenRouterAiDriver;
use Modules\Ai\Services\Drivers\ReplicateAiDriver;
use MicroweberPackages\Utils\Http\HttpClientFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SslVerificationTest extends TestCase
{
    #[Test]
    public function it_fal_ai_fetch_image_uses_factory(): void
    {
        $driver = new FalAiDriver(['api_key' => 'test-key']);

        $source = $this->getMethodSource($driver, 'fetchImageContent');

        $this->assertStringContainsString('HttpClientFactory::fetchContent', $source, 'FalAi fetchImageContent must use HttpClientFactory');
    }

    #[Test]
    public function it_fal_ai_make_request_uses_factory(): void
    {
        $driver = new FalAiDriver(['api_key' => 'test-key']);

        $source = $this->getMethodSource($driver, 'makeRequest');

        $this->assertStringContainsString('HttpClientFactory::curl', $source, 'FalAi makeRequest must use HttpClientFactory::curl');
        $this->assertStringContainsString('HttpClientFactory::executeCurlJson', $source, 'FalAi makeRequest must use HttpClientFactory::executeCurlJson');
    }

    #[Test]
    public function it_replicate_fetch_image_uses_factory(): void
    {
        $driver = new ReplicateAiDriver(['api_key' => 'test-token']);

        $source = $this->getMethodSource($driver, 'fetchImageContent');

        $this->assertStringContainsString('HttpClientFactory::fetchContent', $source, 'Replicate fetchImageContent must use HttpClientFactory');
    }

    #[Test]
    public function it_replicate_make_request_uses_factory(): void
    {
        $driver = new ReplicateAiDriver(['api_key' => 'test-token']);

        $source = $this->getMethodSource($driver, 'makeRequest');

        $this->assertStringContainsString('HttpClientFactory::curl', $source, 'Replicate makeRequest must use HttpClientFactory::curl');
        $this->assertStringContainsString('HttpClientFactory::executeCurlJson', $source, 'Replicate makeRequest must use HttpClientFactory::executeCurlJson');
    }

    #[Test]
    public function it_gemini_make_request_uses_factory(): void
    {
        $driver = new GeminiAiDriver(['api_key' => 'test-key']);

        $source = $this->getMethodSource($driver, 'makeRequest');

        $this->assertStringContainsString('HttpClientFactory::curl', $source, 'Gemini makeRequest must use HttpClientFactory::curl');
        $this->assertStringContainsString('HttpClientFactory::executeCurlJson', $source, 'Gemini makeRequest must use HttpClientFactory::executeCurlJson');
    }

    #[Test]
    public function it_ollama_make_request_uses_factory(): void
    {
        $driver = new OllamaAiDriver(['api_key' => 'test-key']);

        $source = $this->getMethodSource($driver, 'makeRequest');

        $this->assertStringContainsString('HttpClientFactory::curl', $source, 'Ollama makeRequest must use HttpClientFactory::curl');
        $this->assertStringContainsString('HttpClientFactory::executeCurlJson', $source, 'Ollama makeRequest must use HttpClientFactory::executeCurlJson');
    }

    #[Test]
    public function it_openrouter_make_request_uses_factory(): void
    {
        $driver = new OpenRouterAiDriver(['api_key' => 'test-key']);

        $source = $this->getMethodSource($driver, 'makeRequest');

        $this->assertStringContainsString('HttpClientFactory::curl', $source, 'OpenRouter makeRequest must use HttpClientFactory::curl');
        $this->assertStringContainsString('HttpClientFactory::executeCurlJson', $source, 'OpenRouter makeRequest must use HttpClientFactory::executeCurlJson');
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
    public function it_fal_ai_driver_strips_trailing_slash_from_endpoint(): void
    {
        $driver = new FalAiDriver([
            'api_key' => 'test-key',
            'api_endpoint' => 'https://fal.run/',
        ]);

        $endpointProp = new \ReflectionProperty($driver, 'apiEndpoint');
        $endpointProp->setAccessible(true);

        $this->assertStringEndsNotWith('/', $endpointProp->getValue($driver));
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

        $this->assertStringEndsNotWith('/', $endpointProp->getValue($driver));
    }

    #[Test]
    public function it_factory_ssl_options_are_comprehensive(): void
    {
        $source = $this->getMethodSource(new HttpClientFactory(), 'curl');

        $this->assertStringContainsString('CURLOPT_SSL_VERIFYPEER, true', $source);
        $this->assertStringContainsString('CURLOPT_SSL_VERIFYHOST, 2', $source);
        $this->assertStringContainsString('CURLOPT_CAINFO', $source);
        $this->assertStringContainsString('CURLOPT_PROTOCOLS', $source);
        $this->assertStringContainsString('CURLOPT_REDIR_PROTOCOLS', $source);
    }

    private function getMethodSource(object $object, string $method): string
    {
        $reflection = new \ReflectionMethod($object, $method);
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        return implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));
    }
}
