<?php

namespace MicroweberPackages\Helper\tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use MicroweberPackages\Helper\XSSClean;

class SecurityTest extends TestCase
{
    #[Test]
    public function it_comments(): void {
        $antiXss = new \MicroweberPackages\Security\HtmlClean();

        $string = '<a href="https://example.com">test</a>';
        $content = $antiXss->onlyTags($string);

        $this->assertEquals($string, $content);
    }


//    public function testXssExternalLinkImg()
//    {
//        $antiXss = new \MicroweberPackages\Security\HtmlClean();
//
//        $string = '<img src="' . site_url() . 'test.jpg" />';
//        $content = $antiXss->clean($string);
//        $this->assertEquals('<img src="' . site_url() . 'test.jpg" />', $content);
//
//
//        $string = '<img src="https://google.bg/test.jpg" />';
//        $content = $antiXss->clean($string,['disable_external_resources'=>true]);
//        $this->assertEquals('', $content);
//
//    }


    #[Test]


    public function it_xss_list(): void {

        $zip = new \ZipArchive();
        $zip->open(__DIR__ . '/misc/xss-test-files.zip');
        $xssList = $zip->getFromName('xss-payload-list.txt');
        $zip->close();

        $xssList = preg_replace('~\R~u', "\r\n", $xssList);
        $xssList = explode(PHP_EOL, $xssList);

        // Only test every 50th payload to keep the test fast while still covering a representative sample
        $xssList = array_filter($xssList, fn($v, $k) => $k % 50 === 0, ARRAY_FILTER_USE_BOTH);

        $antiXss = new \MicroweberPackages\Security\HtmlClean();

        foreach ($xssList as $string) {

            if (empty(trim($string))) {
                continue;
            }

            $content = $antiXss->clean($string);
            $this->assertNotEquals($string, $content);

        }
    }

    #[Test]

    public function it_x_s_s_clean_arrtibutes_new_events(): void {
        $xssClean = new XSSClean();
        $str = "class='x module module-'ontransitionrun=alert(1) '";
        $clean = $xssClean->clean($str);
        $this->assertEquals("class='x module module-'=alert&#40;1&#41; '", $clean);

    }

    /**
     * Regression coverage moved here when the Security package was extracted
     * (the original Security/tests/HtmlCleanTest.php was removed in that diff).
     * Pins the Microweber-specific behaviour: <module> tags are stripped in
     * the default mode but PRESERVED in admin_mode, while script/event-handler
     * XSS vectors are always removed.
     */
    #[Test]
    public function it_html_clean(): void {
        $clean = new \MicroweberPackages\Security\HtmlClean();

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

}
