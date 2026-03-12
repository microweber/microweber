<?php

declare(strict_types=1);

namespace MicroweberPackages\Utils\Http\Tests;

use MicroweberPackages\Utils\Http\HttpClientFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HttpClientFactoryTest extends TestCase
{
    #[Test]
    public function it_ca_cert_path_returns_existing_file(): void
    {
        $path = HttpClientFactory::caCertPath();

        $this->assertFileExists($path);
        $this->assertGreaterThan(0, filesize($path));
        $this->assertStringEndsWith('cacert.pem.txt', $path);
    }

    #[Test]
    public function it_guzzle_returns_client_with_ssl_verification(): void
    {
        $client = HttpClientFactory::guzzle();
        $config = $client->getConfig();

        $this->assertTrue($config['verify'], 'Guzzle client must have verify => true');
        $this->assertSame(60, $config['timeout'], 'Default timeout should be 60');
    }

    #[Test]
    public function it_guzzle_merges_custom_options(): void
    {
        $client = HttpClientFactory::guzzle([
            'timeout' => 300,
            'headers' => ['User-Agent' => 'TestBot'],
        ]);
        $config = $client->getConfig();

        $this->assertTrue($config['verify'], 'SSL verification must not be overridden');
        $this->assertSame(300, $config['timeout'], 'Custom timeout should be applied');
        $this->assertArrayHasKey('User-Agent', $config['headers']);
    }

    #[Test]
    public function it_guzzle_allows_verify_override_with_cert_path(): void
    {
        $certPath = HttpClientFactory::caCertPath();
        $client = HttpClientFactory::guzzle(['verify' => $certPath]);
        $config = $client->getConfig();

        $this->assertSame($certPath, $config['verify'], 'Should allow overriding verify with a cert path');
    }

    #[Test]
    public function it_curl_creates_handle_with_ssl_options(): void
    {
        $ch = HttpClientFactory::curl('https://example.com', 120);

        $this->assertInstanceOf(\CurlHandle::class, $ch);

        curl_close($ch);
    }

    #[Test]
    public function it_apply_ssl_options_sets_correct_curl_options(): void
    {
        $ch = curl_init('https://example.com');
        HttpClientFactory::applySslOptions($ch);

        // We can't directly read curl options, but we can verify the handle is valid
        $this->assertInstanceOf(\CurlHandle::class, $ch);

        curl_close($ch);
    }

    #[Test]
    public function it_execute_curl_returns_structured_result(): void
    {
        // Use a non-existent host to trigger a curl error quickly
        $ch = HttpClientFactory::curl('https://0.0.0.0:1/', 1);

        $result = HttpClientFactory::executeCurl($ch);

        $this->assertArrayHasKey('body', $result);
        $this->assertArrayHasKey('http_code', $result);
        $this->assertArrayHasKey('error', $result);
        $this->assertIsInt($result['http_code']);
    }

    #[Test]
    public function it_execute_curl_json_throws_on_curl_error(): void
    {
        $ch = HttpClientFactory::curl('https://0.0.0.0:1/', 1);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/cURL Error/');

        HttpClientFactory::executeCurlJson($ch, 'Test API');
    }

    #[Test]
    public function it_factory_curl_method_source_contains_ssl_settings(): void
    {
        $reflection = new \ReflectionMethod(HttpClientFactory::class, 'curl');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();
        $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));

        $this->assertStringContainsString('CURLOPT_SSL_VERIFYPEER, true', $source);
        $this->assertStringContainsString('CURLOPT_SSL_VERIFYHOST, 2', $source);
        $this->assertStringContainsString('CURLOPT_CAINFO', $source);
        $this->assertStringContainsString('CURLOPT_PROTOCOLS', $source);
        $this->assertStringContainsString('CURLPROTO_HTTPS', $source);
    }

    #[Test]
    public function it_factory_apply_ssl_options_source_contains_ssl_settings(): void
    {
        $reflection = new \ReflectionMethod(HttpClientFactory::class, 'applySslOptions');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();
        $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));

        $this->assertStringContainsString('CURLOPT_SSL_VERIFYPEER, true', $source);
        $this->assertStringContainsString('CURLOPT_SSL_VERIFYHOST, 2', $source);
        $this->assertStringContainsString('CURLOPT_CAINFO', $source);
        $this->assertStringContainsString('CURLOPT_PROTOCOLS', $source);
    }
}
