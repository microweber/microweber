<?php

function get_editor_fonts() {


    $fonts = json_decode(file_get_contents(__DIR__ . DS . 'fonts.json'), true);
    $fontsMore = json_decode(file_get_contents(__DIR__ . DS . 'fonts-more.json'), true);

    $readyFonts = [];
    if (!empty($fonts) && isset($fonts['items'])) {
        foreach ($fonts['items'] as $font) {
            $readyFonts[] = $font;
        }
    }
    if (!empty($fontsMore) && isset($fontsMore['items'])) {
        foreach ($fontsMore['items'] as $font) {
            $readyFonts[] = $font;
        }
    }

    return $readyFonts;

}
