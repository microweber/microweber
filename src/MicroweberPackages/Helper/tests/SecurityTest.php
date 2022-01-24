<?php
namespace MicroweberPackages\Helper\tests;

class SecurityTest extends BaseTest
{

    public function testXssExternalLinkImg()
    {

        $string = '<img src="https://google.bg" />';

        $antiXss = new \MicroweberPackages\Helper\HTMLClean();
        $content = $antiXss->clean($string);

        echo $content;
    }


    public function testXssList()
    {

        $xssList = file_get_contents(__DIR__.'/misc/xss-payload-list.txt');
        $xssList = explode(PHP_EOL, $xssList);

        $antiXss = new \MicroweberPackages\Helper\HTMLClean();

        foreach ($xssList as $string) {

            if (empty(trim($string))) {
                continue;
            }

            dump($string);

            $content = $antiXss->clean($string);

            $this->assertNotEquals($string, $content);

        }
    }

}
