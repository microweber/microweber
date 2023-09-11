<?php

namespace MicroweberPackages\Utils\Misc;

class GoogleFonts
{

    public static function getEnabledFonts() : array
    {
        $enabledCustomFonts = get_option("enabled_custom_fonts", "template");
        if ($enabledCustomFonts) {
            if (!empty($enabledCustomFonts) && is_array($enabledCustomFonts)) {
                return $enabledCustomFonts;
            }
        }

        return [];

    }

    public static function getDomain() : string
    {
        $use_google_fonts_proxy = get_option('use_google_fonts_proxy', 'template');
        if (intval($use_google_fonts_proxy) == 1) {
            $google_font_domain = 'google-fonts.microweberapi.com';
        } else {
            $google_font_domain = 'fonts.googleapis.com';
        }

        return $google_font_domain;


    }
}
