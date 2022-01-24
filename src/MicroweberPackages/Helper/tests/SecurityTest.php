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
        
        $zip = new \ZipArchive();
        $zip->open(__DIR__.'/misc/xss-test-files.zip');
        $xssList = $zip->getFromName('xss-payload-list.txt');
        $zip->close();

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

}
