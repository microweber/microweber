<?php

namespace MicroweberPackages\Utils\Misc;

class GoogleFonts
{

    public static function getEnabledFonts()
    {
        $custom_fonts = [];
        $enabled_custom_fonts = get_option("enabled_custom_fonts", "template");
        if ($enabled_custom_fonts) {
            $custom_fonts = $enabled_custom_fonts;
        }
        return $custom_fonts;

    }

    public static function getEnabledFontsAsString()
    {
        return implode(',', self::getEnabledFonts());
    }

    public static function getDomain()
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
