<?php

namespace MicroweberPackages\Security\Tests;

use MicroweberPackages\Security\XSSSecurity;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Characterization tests for the legacy (CodeInigniter-derived, regex-based)
 * XSSSecurity cleaner. These pin the security-critical invariants so the class
 * can be safely demoted/consolidated behind the DOM-based MwHtmlSanitizer later
 * without silently weakening XSS filtering.
 */
class XSSSecurityTest extends TestCase
{
    private function clean(string $input): string
    {
        return (new XSSSecurity())->clean($input);
    }

    #[Test]
    public function it_neutralises_script_tags(): void
    {
        $out = $this->clean("<script>alert(1)</script>hi");
        $this->assertStringNotContainsString('<script', $out);
        $this->assertStringContainsString('hi', $out);
    }

    #[Test]
    public function it_strips_inline_event_handlers(): void
    {
        $out = $this->clean('<img src=x onerror=alert(1)>');
        $this->assertStringNotContainsString('onerror', $out);
    }

    #[Test]
    public function it_strips_javascript_uris(): void
    {
        $out = $this->clean('<a href="javascript:alert(1)">x</a>');
        $this->assertStringNotContainsString('javascript:', $out);
        $this->assertStringContainsString('x', $out);
    }

    #[Test]
    public function it_preserves_safe_markup(): void
    {
        $this->assertSame('<b>bold</b> normal', $this->clean('<b>bold</b> normal'));
    }

    #[Test]
    public function it_is_idempotent_on_already_clean_input(): void
    {
        $clean = $this->clean('<p>hello <em>world</em></p>');
        $this->assertSame($clean, $this->clean($clean));
    }
}
