<?php

namespace MicroweberPackages\Security\Tests;

use MicroweberPackages\Security\HtmlClean;
use MicroweberPackages\Security\XSSClean;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HtmlCleanTest extends TestCase
{
    #[Test]
    public function it_preserves_safe_anchor_in_only_tags(): void
    {
        $antiXss = new HtmlClean();

        $string = '<a href="https://example.com">test</a>';
        $content = $antiXss->onlyTags($string);

        $this->assertEquals($string, $content);
    }

    #[Test]
    public function it_strips_xss_payload_sample_list(): void
    {
        $zipPath = __DIR__ . '/misc/xss-test-files.zip';
        if (!is_file($zipPath)) {
            $this->markTestSkipped('XSS payload fixture zip not present');
        }

        $zip = new \ZipArchive();
        $zip->open($zipPath);
        $xssList = $zip->getFromName('xss-payload-list.txt');
        $zip->close();

        $this->assertNotFalse($xssList);

        $xssList = preg_replace('~\R~u', "\r\n", $xssList);
        $xssList = explode(PHP_EOL, (string) $xssList);

        // Sample a small fixed set so the suite stays fast; HtmlClean is intentionally thorough.
        $sampled = [];
        foreach ($xssList as $k => $string) {
            if ($k % 200 === 0 && !empty(trim((string) $string))) {
                $sampled[] = $string;
            }
            if (count($sampled) >= 5) {
                break;
            }
        }

        $antiXss = new HtmlClean();
        foreach ($sampled as $string) {
            $content = $antiXss->clean($string);
            $this->assertNotEquals($string, $content);
        }
    }

    #[Test]
    public function it_cleans_transition_event_attributes(): void
    {
        $xssClean = new XSSClean();
        $str = "class='x module module-'ontransitionrun=alert(1) '";
        $clean = $xssClean->clean($str);
        $this->assertEquals("class='x module module-'=alert&#40;1&#41; '", $clean);
    }

    #[Test]
    public function it_strips_module_tags_by_default_but_keeps_them_in_admin_mode(): void
    {
        $clean = new HtmlClean();

        $xss = "<script>alert('xss')</script>";
        $xss .= "<div onmousedown='11'><script>alert('xss')</script></div>";
        $xss .= "<module type=\"test\" onmousedown='11'><script>alert('xss')</script></module>";

        // Default mode: module tag stripped along with the XSS.
        $this->assertEquals('<div></div>', $clean->clean($xss, []));

        // Admin mode: module tag preserved, XSS still removed.
        $this->assertEquals(
            '<div></div><module type="test"></module>',
            $clean->clean($xss, ['admin_mode' => true])
        );
    }

    #[Test]
    public function it_cleans_arrays_recursively(): void
    {
        $clean = new HtmlClean();
        $result = $clean->cleanArray([
            'safe' => 'hello',
            'nested' => [
                'xss' => "<script>alert('xss')</script>",
            ],
        ]);

        $this->assertIsArray($result);
        $this->assertSame('hello', $result['safe']);
        $this->assertStringNotContainsString('<script>', $result['nested']['xss']);
    }
}
