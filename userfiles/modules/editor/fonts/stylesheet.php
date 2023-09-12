<?php

$google_font_domain = \MicroweberPackages\Utils\Misc\GoogleFonts::getDomain();
$enabled_custom_fonts =  \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts();

if (!empty($enabled_custom_fonts)) {
    foreach ($enabled_custom_fonts as $font) {
        if ($font) {
            $font = str_replace('%2B', '+', $font);
            $font_url = urlencode($font);
            print "@import url(//{$google_font_domain}/css?family={$font_url}:300italic,400italic,600italic,700italic,800italic,400,600,800,700,300&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic);";
            print "\n";
            print "\n";
        }
    }
}
