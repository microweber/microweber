<?php

declare(strict_types=1);

namespace MicroweberPackages\Http\Tests;

use MicroweberPackages\Http\Ssrf\SsrfGuard;
use PHPUnit\Framework\Attributes\Test;

class SsrfGuardTest extends TestCase
{
    #[Test]
    public function it_rejects_loopback_ipv4(): void
    {
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://127.0.0.1'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://127.255.255.255'));
    }

    #[Test]
    public function it_rejects_rfc1918_10_range(): void
    {
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://10.0.0.1'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://10.255.255.255'));
    }

    #[Test]
    public function it_rejects_rfc1918_172_range(): void
    {
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://172.16.0.1'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://172.20.5.5'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://172.31.255.255'));
    }

    #[Test]
    public function it_rejects_rfc1918_192_range(): void
    {
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://192.168.0.1'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://192.168.255.255'));
    }

    #[Test]
    public function it_rejects_link_local_and_aws_metadata(): void
    {
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://169.254.169.254/latest/meta-data/'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://169.254.0.1'));
    }

    #[Test]
    public function it_rejects_localhost_hostname(): void
    {
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://localhost'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://LOCALHOST'));
    }

    #[Test]
    public function it_rejects_reserved_tld_suffixes(): void
    {
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://evil.localhost'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://db.local'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://app.test'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://x.invalid'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://a.example'));
    }

    #[Test]
    public function it_rejects_non_http_schemes(): void
    {
        $this->assertFalse(SsrfGuard::isExternallyReachable('file:///etc/passwd'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('ftp://1.1.1.1'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('gopher://1.1.1.1'));
    }

    #[Test]
    public function it_rejects_malformed_urls(): void
    {
        $this->assertFalse(SsrfGuard::isExternallyReachable('not a url'));
        $this->assertFalse(SsrfGuard::isExternallyReachable(''));
    }

    #[Test]
    public function it_accepts_public_ipv4(): void
    {
        $this->assertTrue(SsrfGuard::isExternallyReachable('https://1.1.1.1'));
        $this->assertTrue(SsrfGuard::isExternallyReachable('https://8.8.8.8'));
    }

    #[Test]
    public function it_is_private_ipv4_detects_all_ranges(): void
    {
        $this->assertTrue(SsrfGuard::isPrivateIpv4('127.0.0.1'));
        $this->assertTrue(SsrfGuard::isPrivateIpv4('10.0.0.1'));
        $this->assertTrue(SsrfGuard::isPrivateIpv4('172.16.0.1'));
        $this->assertTrue(SsrfGuard::isPrivateIpv4('192.168.1.1'));
        $this->assertTrue(SsrfGuard::isPrivateIpv4('169.254.169.254'));
        $this->assertTrue(SsrfGuard::isPrivateIpv4('0.0.0.0'));
        $this->assertTrue(SsrfGuard::isPrivateIpv4('100.64.0.1'));
        $this->assertFalse(SsrfGuard::isPrivateIpv4('1.1.1.1'));
        $this->assertFalse(SsrfGuard::isPrivateIpv4('8.8.8.8'));
    }

    #[Test]
    public function it_is_private_ipv6_detects_loopback(): void
    {
        $this->assertTrue(SsrfGuard::isPrivateIpv6('::1'));
    }

    #[Test]
    public function it_is_private_ipv6_detects_link_local(): void
    {
        $this->assertTrue(SsrfGuard::isPrivateIpv6('fe80::1'));
    }

    #[Test]
    public function it_is_private_ipv6_detects_unique_local(): void
    {
        $this->assertTrue(SsrfGuard::isPrivateIpv6('fc00::1'));
        $this->assertTrue(SsrfGuard::isPrivateIpv6('fd00::1'));
    }

    #[Test]
    public function it_rejects_carrier_grade_nat(): void
    {
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://100.64.0.1'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://100.127.255.255'));
    }

    #[Test]
    public function it_rejects_multicast_range(): void
    {
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://224.0.0.1'));
        $this->assertFalse(SsrfGuard::isExternallyReachable('http://239.255.255.255'));
    }
}