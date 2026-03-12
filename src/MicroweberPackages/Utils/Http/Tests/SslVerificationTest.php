<?php

declare(strict_types=1);

namespace MicroweberPackages\Utils\Http\Tests;

use MicroweberPackages\Utils\Http\Adapters\Curl;
use MicroweberPackages\Utils\Http\Adapters\Guzzle;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SslVerificationTest extends TestCase
{
    #[Test]
    public function it_guzzle_get_uses_ssl_verification(): void
    {
        $guzzle = new Guzzle();

        // Use reflection to inspect the get() method source code
        $reflection = new \ReflectionMethod($guzzle, 'get');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));

        $this->assertStringContainsString("'verify' => true", $source, 'Guzzle GET must use SSL verification (verify => true)');
        $this->assertStringNotContainsString("'verify' => false", $source, 'Guzzle GET must not disable SSL verification');
    }

    #[Test]
    public function it_guzzle_post_uses_ssl_verification(): void
    {
        $guzzle = new Guzzle();

        $reflection = new \ReflectionMethod($guzzle, 'post');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));

        $this->assertStringContainsString("'verify' => true", $source, 'Guzzle POST must use SSL verification (verify => true)');
        $this->assertStringNotContainsString("'verify' => false", $source, 'Guzzle POST must not disable SSL verification');
    }

    #[Test]
    public function it_guzzle_download_uses_ssl_verification(): void
    {
        $guzzle = new Guzzle();

        $reflection = new \ReflectionMethod($guzzle, 'download');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));

        $this->assertStringContainsString('CURLOPT_SSL_VERIFYPEER, true', $source, 'Guzzle download must enable CURLOPT_SSL_VERIFYPEER');
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYPEER, false', $source, 'Guzzle download must not disable CURLOPT_SSL_VERIFYPEER');
        $this->assertStringContainsString('CURLOPT_CAINFO', $source, 'Guzzle download must specify a CA certificate bundle');
    }

    #[Test]
    public function it_curl_execute_uses_ssl_verification(): void
    {
        $curl = new Curl();

        $reflection = new \ReflectionMethod($curl, 'execute');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));

        $this->assertStringContainsString('CURLOPT_SSL_VERIFYPEER, true', $source, 'Curl execute must enable CURLOPT_SSL_VERIFYPEER');
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYPEER, false', $source, 'Curl execute must not disable CURLOPT_SSL_VERIFYPEER');
        $this->assertStringContainsString('CURLOPT_CAINFO', $source, 'Curl execute must specify a CA certificate bundle');
    }

    #[Test]
    public function it_curl_set_headers_uses_ssl_verification(): void
    {
        $curl = new Curl();

        $reflection = new \ReflectionMethod($curl, 'setHeaders');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));

        $this->assertStringContainsString('CURLOPT_SSL_VERIFYPEER => true', $source, 'Curl setHeaders must enable CURLOPT_SSL_VERIFYPEER');
        $this->assertStringNotContainsString('CURLOPT_SSL_VERIFYPEER => false', $source, 'Curl setHeaders must not disable CURLOPT_SSL_VERIFYPEER');
        $this->assertStringContainsString('CURLOPT_SSL_VERIFYHOST => 2', $source, 'Curl setHeaders must set CURLOPT_SSL_VERIFYHOST to 2');
        $this->assertStringContainsString('CURLOPT_CAINFO', $source, 'Curl setHeaders must specify a CA certificate bundle');
    }

    #[Test]
    public function it_curl_headers_contain_correct_ssl_options_after_get(): void
    {
        $curl = new Curl();
        $curl->setUrl('https://example.com');

        // Call setHeaders via reflection to populate the headers array
        $method = new \ReflectionMethod($curl, 'setHeaders');
        $method->setAccessible(true);
        $method->invoke($curl);

        // Read the headers property
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

        // When custom HTTP headers are set, CURLOPT_SSL_VERIFYHOST should still be 2
        $this->assertSame(2, $headers[CURLOPT_SSL_VERIFYHOST], 'CURLOPT_SSL_VERIFYHOST must remain 2 even with custom headers');
        $this->assertTrue($headers[CURLOPT_SSL_VERIFYPEER], 'CURLOPT_SSL_VERIFYPEER must remain true with custom headers');
    }

    #[Test]
    public function it_ca_certificate_bundle_exists(): void
    {
        $certPath = dirname((new \ReflectionClass(Curl::class))->getFileName()) . DIRECTORY_SEPARATOR . 'cacert.pem.txt';

        $this->assertFileExists($certPath, 'CA certificate bundle (cacert.pem.txt) must exist in the Adapters directory');
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
    public function it_curl_restricts_protocols_to_http_and_https(): void
    {
        $curl = new Curl();

        $reflection = new \ReflectionMethod($curl, 'execute');
        $filename = $reflection->getFileName();
        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));

        $this->assertStringContainsString('CURLOPT_PROTOCOLS', $source, 'Curl must restrict allowed protocols');
        $this->assertStringContainsString('CURLPROTO_HTTPS', $source, 'Curl must allow HTTPS protocol');
        $this->assertStringContainsString('CURLPROTO_HTTP', $source, 'Curl must allow HTTP protocol');
    }
}
