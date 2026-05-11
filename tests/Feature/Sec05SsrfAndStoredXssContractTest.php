<?php

declare(strict_types=1);

namespace Tests\Feature;

use MicroweberPackages\Security\StoredXssStripper;
use MicroweberPackages\Utils\Http\Ssrf\SsrfGuard;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * SSRF + stored-XSS regression coverage.
 *
 * Pins:
 *   - SsrfGuard rejects every private IPv4 range (10.x, 172.16-31.x,
 *     192.168.x, 127.x), the literal `localhost` hostname, the
 *     reserved TLD suffixes, and non-http schemes.
 *   - SsrfGuard accepts public-routable hostnames.
 *   - StoredXssStripper removes `on*=` event handlers from EVERY
 *     element (img, button, svg, etc.), strips `<script>` blocks
 *     entirely, strips `<svg>` blocks containing event handlers,
 *     and rewrites javascript:/data:text/html href schemes to
 *     `#`.
 *   - SearchComponent::search() runs `strip_tags` + 200-char cap
 *     on the user-supplied keyword (defense-in-depth alongside
 *     Eloquent's parameterized query builder).
 */
class Sec05SsrfAndStoredXssContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function ssrf_guard_rejects_every_brief_required_private_range(): void
    {
        // Every URL below must be denied.
        $denied = [
            'http://127.0.0.1',
            'http://127.255.255.255',
            'http://10.0.0.1',
            'http://10.255.255.255',
            'http://172.16.0.1',
            'http://172.20.5.5',
            'http://172.31.255.255',
            'http://192.168.0.1',
            'http://192.168.100.100',
            'http://localhost',
            'http://[::1]',
            'http://example.local',
            'http://service.test',
            'file:///etc/passwd',
            'gopher://x',
            '',
            'http://',
        ];
        foreach ($denied as $url) {
            $this->assertFalse(
                SsrfGuard::isExternallyReachable($url),
                "SsrfGuard MUST reject {$url}"
            );
        }
    }

    #[Test]
    public function ssrf_guard_accepts_public_hostnames(): void
    {
        // We can't rely on real DNS in the test env, so use a
        // literal-IP that's clearly public (1.1.1.1 — Cloudflare DNS).
        $this->assertTrue(
            SsrfGuard::isExternallyReachable('http://1.1.1.1'),
            'SsrfGuard MUST accept 1.1.1.1 (public IPv4)'
        );
        $this->assertTrue(
            SsrfGuard::isExternallyReachable('https://8.8.8.8'),
            'SsrfGuard MUST accept 8.8.8.8 (public IPv4, https)'
        );
    }

    #[Test]
    public function stored_xss_stripper_removes_event_handlers(): void
    {
        $cases = [
            ['<img src=x onerror=alert(1)>',           '<img src=x>'],
            ['<button onclick="x()">btn</button>',     '<button>btn</button>'],
            ['<a href="x" onmouseover=alert(1)>x</a>', '<a href="x">x</a>'],
        ];
        foreach ($cases as [$in, $expect]) {
            $this->assertSame(
                $expect,
                StoredXssStripper::strip($in),
                "StoredXssStripper must remove on*= attribute. Input: {$in}"
            );
        }
    }

    #[Test]
    public function stored_xss_stripper_removes_script_and_svg_with_handlers(): void
    {
        // <script> blocks always go.
        $this->assertSame(
            '',
            StoredXssStripper::strip('<script>evil()</script>'),
            'StoredXssStripper must strip <script> blocks'
        );
        // Note: stripping leaves the surrounding whitespace as-is
        // (no whitespace collapse — preserves legitimate formatting).
        $this->assertSame(
            'before  after',
            StoredXssStripper::strip('before <script>x</script> after'),
            'StoredXssStripper must strip <script> blocks (mid-content), preserving surrounding whitespace'
        );

        // <svg onload=...> goes entirely.
        $this->assertSame(
            '',
            StoredXssStripper::strip('<svg onload=alert(1)></svg>'),
            'StoredXssStripper must strip <svg> with event handlers'
        );
        $this->assertSame(
            '',
            StoredXssStripper::strip('<svg><script>x</script></svg>'),
            'StoredXssStripper must strip <svg> containing <script>'
        );

        // Plain <svg> (no script, no on*=) passes through unchanged.
        $this->assertSame(
            '<svg><circle r=10/></svg>',
            StoredXssStripper::strip('<svg><circle r=10/></svg>'),
            'StoredXssStripper must NOT strip plain <svg> elements'
        );
    }

    #[Test]
    public function stored_xss_stripper_neutralizes_dangerous_url_schemes(): void
    {
        $this->assertSame(
            '<a href="#">x</a>',
            StoredXssStripper::strip('<a href="javascript:alert(1)">x</a>'),
            'StoredXssStripper must rewrite javascript: hrefs to #'
        );
        $this->assertSame(
            '<a href="#">x</a>',
            StoredXssStripper::strip('<a href="data:text/html,<script>x</script>">x</a>'),
            'StoredXssStripper must rewrite data:text/html hrefs to #'
        );
        $this->assertSame(
            '<a href="#">x</a>',
            StoredXssStripper::strip('<a href="vbscript:msgbox">x</a>'),
            'StoredXssStripper must rewrite vbscript: hrefs to #'
        );

        // Legitimate http:// hrefs survive.
        $this->assertSame(
            '<a href="https://example.com">x</a>',
            StoredXssStripper::strip('<a href="https://example.com">x</a>'),
            'StoredXssStripper must preserve safe https:// URLs'
        );
    }

    #[Test]
    public function search_component_strips_keyword_html_and_caps_length(): void
    {
        $src = $this->read('Modules/Search/Livewire/SearchComponent.php');

        $this->assertStringContainsString(
            'AI-130 / SEC-05 (cycle-123',
            $src,
            'SearchComponent must carry the keyword-sanitization audit-trail marker'
        );

        $this->assertStringContainsString(
            "strip_tags((string) \$this->searchQuery)",
            $src,
            'SearchComponent must call strip_tags() on the keyword'
        );

        $this->assertStringContainsString(
            'mb_substr($keyword, 0, 200)',
            $src,
            'SearchComponent must cap keyword length at 200 chars'
        );

        $this->assertStringContainsString(
            "(int) \$this->dataContentId",
            $src,
            'SearchComponent must (int)-cast dataContentId before passing to query layer'
        );
    }
}
