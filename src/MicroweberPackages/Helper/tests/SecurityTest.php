<?php

namespace MicroweberPackages\Helper\tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use MicroweberPackages\Helper\XSSClean;

class SecurityTest extends TestCase
{
    #[Test]
    public function it_comments(): void {
        $antiXss = new \MicroweberPackages\Helper\HTMLClean();

        $string = '<a href="https://example.com">test</a>';
        $content = $antiXss->onlyTags($string);

        $this->assertEquals($string, $content);
    }


//    public function testXssExternalLinkImg()
//    {
//        $antiXss = new \MicroweberPackages\Helper\HTMLClean();
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

        $antiXss = new \MicroweberPackages\Helper\HTMLClean();

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

}
