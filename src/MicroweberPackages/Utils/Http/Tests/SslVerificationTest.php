<?php

declare(strict_types=1);

namespace MicroweberPackages\Utils\Http\Tests;

use MicroweberPackages\Utils\Http\Adapters\Curl;
use MicroweberPackages\Utils\Http\Adapters\Guzzle;
use MicroweberPackages\Utils\Http\HttpClientFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SslVerificationTest extends TestCase
{
    #[Test]
    public function it_guzzle_get_uses_factory(): void
    {
        $guzzle = new Guzzle();

        $reflection = new \ReflectionMethod($guzzle, 'get');
        $source = $this->getMethodSource($reflection);

        $this->assertStringContainsString('HttpClientFactory::guzzle', $source, 'Guzzle GET must use HttpClientFactory');
        $this->assertStringNotContainsString("'verify' => false", $source, 'Guzzle GET must not disable SSL verification');
    }

    #[Test]
    public function it_guzzle_post_uses_factory(): void
    {
        $guzzle = new Guzzle();

        $reflection = new \ReflectionMethod($guzzle, 'post');
        $source = $this->getMethodSource($reflection);

        $this->assertStringContainsString('HttpClientFactory::guzzle', $source, 'Guzzle POST must use HttpClientFactory');
        $this->assertStringNotContainsString("'verify' => false", $source, 'Guzzle POST must not disable SSL verification');
    }

    #[Test]
    public function it_guzzle_download_uses_factory_ssl(): void
    {
        $guzzle = new Guzzle();

        $reflection = new \ReflectionMethod($guzzle, 'download');
        $source = $this->getMethodSource($reflection);

        $this->assertStringContainsString('HttpClientFactory::applySslOptions', $source, 'Guzzle download must use HttpClientFactory::applySslOptions');
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYPEER, false', $source, 'Guzzle download must not disable CURLOPT_SSL_VERIFYPEER');
    }

    #[Test]
    public function it_curl_execute_uses_factory_ssl(): void
    {
        $curl = new Curl();

        $reflection = new \ReflectionMethod($curl, 'execute');
        $source = $this->getMethodSource($reflection);

        $this->assertStringContainsString('HttpClientFactory::applySslOptions', $source, 'Curl execute must use HttpClientFactory::applySslOptions');
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYPEER, false', $source, 'Curl execute must not disable CURLOPT_SSL_VERIFYPEER');
    }

    #[Test]
    public function it_curl_set_headers_uses_factory_ca_cert(): void
    {
        $curl = new Curl();

        $reflection = new \ReflectionMethod($curl, 'setHeaders');
        $source = $this->getMethodSource($reflection);

        $this->assertStringContainsString('CURLOPT_SSL_VERIFYPEER => true', $source, 'Curl setHeaders must enable CURLOPT_SSL_VERIFYPEER');
        $this->assertStringContainsString('CURLOPT_SSL_VERIFYHOST => 2', $source, 'Curl setHeaders must set CURLOPT_SSL_VERIFYHOST to 2');
        $this->assertStringContainsString('HttpClientFactory::caCertPath()', $source, 'Curl setHeaders must use HttpClientFactory for CA cert path');
    }

    #[Test]
    public function it_curl_headers_contain_correct_ssl_options_after_get(): void
    {
        $curl = new Curl();
        $curl->setUrl('https://example.com');

        $method = new \ReflectionMethod($curl, 'setHeaders');
        $method->setAccessible(true);
        $method->invoke($curl);

        $prop = new \ReflectionProperty($curl, 'headers');
        $prop->setAccessible(true);
        $headers = $prop->getValue($curl);

        $this->assertArrayHasKey(CURLOPT_SSL_VERIFYPEER, $headers);
        $this->assertTrue($headers[CURLOPT_SSL_VERIFYPEER], 'CURLOPT_SSL_VERIFYPEER must be true');

        $this->assertArrayHasKey(CURLOPT_SSL_VERIFYHOST, $headers);
        $this->assertSame(2, $headers[CURLOPT_SSL_VERIFYHOST], 'CURLOPT_SSL_VERIFYHOST must be 2');

        $this->assertArrayHasKey(CURLOPT_CAINFO, $headers);
        $this->assertFileExists($headers[CURLOPT_CAINFO], 'CA certificate bundle file must exist');
    }

    #[Test]
    public function it_curl_headers_contain_correct_ssl_options_with_custom_http_headers(): void
    {
        $curl = new Curl();
        $curl->setUrl('https://example.com');
        $curl->setHttpHeaders(['Accept: application/json']);

        $method = new \ReflectionMethod($curl, 'setHeaders');
        $method->setAccessible(true);
        $method->invoke($curl);

        $prop = new \ReflectionProperty($curl, 'headers');
        $prop->setAccessible(true);
        $headers = $prop->getValue($curl);

        $this->assertSame(2, $headers[CURLOPT_SSL_VERIFYHOST], 'CURLOPT_SSL_VERIFYHOST must remain 2 even with custom headers');
        $this->assertTrue($headers[CURLOPT_SSL_VERIFYPEER], 'CURLOPT_SSL_VERIFYPEER must remain true with custom headers');
    }

    #[Test]
    public function it_ca_certificate_bundle_exists(): void
    {
        $certPath = HttpClientFactory::caCertPath();

        $this->assertFileExists($certPath, 'CA certificate bundle must exist');
        $this->assertGreaterThan(0, filesize($certPath), 'CA certificate bundle must not be empty');
    }

    #[Test]
    public function it_guzzle_default_timeout_is_set(): void
    {
        $guzzle = new Guzzle();
        $this->assertSame(60, $guzzle->timeout, 'Default timeout should be 60 seconds');
    }

    #[Test]
    public function it_curl_default_timeout_is_set(): void
    {
        $curl = new Curl();
        $this->assertSame(60, $curl->timeout, 'Default timeout should be 60 seconds');
    }

    #[Test]
    public function it_curl_restricts_protocols_via_factory(): void
    {
        // Protocol restrictions are now enforced in HttpClientFactory::applySslOptions
        $reflection = new \ReflectionMethod(HttpClientFactory::class, 'applySslOptions');
        $source = $this->getMethodSource($reflection);

        $this->assertStringContainsString('CURLOPT_PROTOCOLS', $source, 'Factory must restrict allowed protocols');
        $this->assertStringContainsString('CURLPROTO_HTTPS', $source, 'Factory must allow HTTPS protocol');
        $this->assertStringContainsString('CURLPROTO_HTTP', $source, 'Factory must allow HTTP protocol');
    }

    private function getMethodSource(\ReflectionMethod $reflection): string
    {
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        return implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));
    }
}
