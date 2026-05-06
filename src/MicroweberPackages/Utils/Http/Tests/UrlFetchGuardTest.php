<?php

declare(strict_types=1);

namespace MicroweberPackages\Utils\Http\Tests;

use InvalidArgumentException;
use MicroweberPackages\Utils\Http\UrlFetchGuard;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UrlFetchGuardTest extends TestCase
{
    #[Test]
    public function it_rejects_loopback_ipv4(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('non-public IP range');
        UrlFetchGuard::assertSafe('http://127.0.0.1/secret');
    }

    #[Test]
    public function it_rejects_aws_metadata_endpoint(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('non-public IP range');
        UrlFetchGuard::assertSafe('http://169.254.169.254/latest/meta-data/');
    }

    #[Test]
    public function it_rejects_rfc1918_private_ranges(): void
    {
        foreach (['http://10.0.0.1/', 'http://172.16.0.1/', 'http://192.168.1.1/'] as $url) {
            try {
                UrlFetchGuard::assertSafe($url);
                $this->fail("Expected rejection of $url");
            } catch (InvalidArgumentException $e) {
                $this->assertStringContainsString('non-public', $e->getMessage());
            }
        }
    }

    #[Test]
    public function it_rejects_ipv6_loopback(): void
    {
        $this->expectException(InvalidArgumentException::class);
        UrlFetchGuard::assertSafe('http://[::1]/');
    }

    #[Test]
    public function it_rejects_ipv4_mapped_ipv6_metadata_endpoint(): void
    {
        // The classic SSRF bypass: wrap 169.254.169.254 in IPv4-mapped
        // IPv6 syntax. The guard must collapse it back to v4 before the
        // public-range check.
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('non-public IP range');
        UrlFetchGuard::assertSafe('http://[::ffff:169.254.169.254]/latest/');
    }

    #[Test]
    public function it_rejects_non_http_schemes(): void
    {
        // gopher / ftp parse with a host so they fail at the scheme check;
        // file:// has no host so it fails at the malformed check first.
        // Both are acceptable rejections — the point is the URL never reaches
        // the network. Match either error message.
        foreach (['file:///etc/passwd', 'gopher://example.com/', 'ftp://example.com/'] as $url) {
            try {
                UrlFetchGuard::assertSafe($url);
                $this->fail("Expected rejection of $url");
            } catch (InvalidArgumentException $e) {
                $msg = $e->getMessage();
                $this->assertTrue(
                    str_contains($msg, 'http(s)') || str_contains($msg, 'malformed'),
                    "Unexpected error message for $url: $msg"
                );
            }
        }
    }

    #[Test]
    public function it_rejects_malformed_url(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('malformed');
        UrlFetchGuard::assertSafe('not a url');
    }

    #[Test]
    public function it_allows_public_ipv4_literal(): void
    {
        // 1.1.1.1 (Cloudflare) is a stable public IPv4 — confirms the
        // guard does not over-reject legitimate addresses. No exception.
        UrlFetchGuard::assertSafe('https://1.1.1.1/');
        $this->assertTrue(true, 'public IPv4 literal accepted');
    }
}
