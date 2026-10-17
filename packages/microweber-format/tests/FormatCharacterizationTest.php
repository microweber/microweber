<?php

namespace MicroweberPackages\Format\Tests;

use MicroweberPackages\Format\FormatService;
use PHPUnit\Framework\Attributes\Test;

/**
 * Characterization tests pinning the CURRENT behaviour of FormatService's
 * security-sensitive cleaners and a few core helpers. These lock in behaviour
 * so the 49-method god class can be safely decomposed later without silent
 * regressions (especially in the XSS/HTML cleaners). They assert observed
 * output, not necessarily ideal output.
 */
class FormatCharacterizationTest extends TestCase
{
    private FormatService $format;

    protected function setUp(): void
    {
        parent::setUp();
        $this->format = new FormatService();
    }

    #[Test]
    public function clean_xss_strips_scripts_and_tags_by_default(): void
    {
        $this->assertSame('hi', $this->format->clean_xss('<script>alert(1)</script><b>hi</b>'));
    }

    #[Test]
    public function clean_scripts_removes_script_tags_keeps_text(): void
    {
        $this->assertSame('hi', $this->format->clean_scripts('<script>x</script>hi'));
    }

    #[Test]
    public function strip_unsafe_removes_event_handlers_but_keeps_safe_markup(): void
    {
        $out = $this->format->strip_unsafe('<a href="#" onclick="x">t</a><p>ok</p>');
        $this->assertStringNotContainsString('onclick', $out);
        $this->assertStringContainsString('<p>ok</p>', $out);
        $this->assertStringContainsString('>t</a>', $out);
    }

    #[Test]
    public function hex_to_rgb_parses_with_and_without_alpha(): void
    {
        $this->assertSame(['r' => 255, 'g' => 255, 'b' => 255], $this->format->hex_to_rgb('#ffffff'));
        $this->assertSame(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0.5], $this->format->hex_to_rgb('#000000', 0.5));
    }

    #[Test]
    public function percent_rounds_to_whole_percent(): void
    {
        $this->assertSame('13', (string) $this->format->percent(25, 200));
    }

    #[Test]
    public function array_to_ul_wraps_items_in_list_markup(): void
    {
        $out = $this->format->array_to_ul(['a', 'b']);
        $this->assertStringStartsWith('<ul>', $out);
        $this->assertStringContainsString('<li>', $out);
        $this->assertStringContainsString('a', $out);
        $this->assertStringContainsString('b', $out);
        $this->assertStringEndsWith('</ul>', $out);
    }
}
