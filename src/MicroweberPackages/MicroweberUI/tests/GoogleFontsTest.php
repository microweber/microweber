<?php

namespace MicroweberPackages\MicroweberUI\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Template\Adapters\GoogleFontDownloader;

class GoogleFontsTest extends TestCase
{

    public function testGoogleFontsDownloader()
    {
        $fontFamily = 'Cairo';
        $googleFontDomain = \MicroweberPackages\Utils\Misc\GoogleFonts::getDomain();
        $fontUrl = str_replace('%2B', '+', $fontFamily);
        $fontUrl = urlencode($fontUrl);

        $fontsPath = userfiles_path() . 'fonts';
        if (!is_dir($fontsPath)) {
            mkdir_recursive($fontsPath);
        }

        $downloader = new GoogleFontDownloader();
        $downloader->setOutputPath($fontsPath);
        $downloader->addFontUrl("https://{$googleFontDomain}/css?family={$fontUrl}:300italic,400italic,600italic,700italic,800italic,400,600,800,700,300&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic");
        $downloader->download();

        $this->assertTrue(is_dir($fontsPath . '/' . $fontFamily));
        $this->assertTrue(is_file($fontsPath . '/' . $fontFamily . '/font.css'));
    }
}
