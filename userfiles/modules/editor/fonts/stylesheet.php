<?php

$enabled_custom_fonts = get_option("enabled_custom_fonts", "template");

$enabled_custom_fonts_array = array();

if (is_string($enabled_custom_fonts)) {
    $enabled_custom_fonts_array = explode(',', $enabled_custom_fonts);
}

if (!empty($enabled_custom_fonts_array)) {
    foreach ($enabled_custom_fonts_array as $font) {
        $font_url = urlencode($font);
        print "@import url(http://fonts.googleapis.com/css?family={$font_url}:300italic,400italic,600italic,700italic,800italic,400,600,800,700,300&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic);";
        print "\n";
    }
}