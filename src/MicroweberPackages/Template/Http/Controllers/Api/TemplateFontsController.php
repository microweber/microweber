<?php

namespace MicroweberPackages\Template\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Template\Adapters\GoogleFontDownloader;
use MicroweberPackages\Template\Http\Livewire\Admin\LiveEditTemplateSettingsSidebar;
use MicroweberPackages\Template\Http\Livewire\Admin\StyleSettingsFirstLevelConvertor;
use MicroweberPackages\Utils\Misc\GoogleFonts;
use Modules\Backup\SessionStepper;
use MicroweberPackages\Template\TemplateInstaller;
use MicroweberPackages\Utils\Zip\Unzip;

class TemplateFontsController
{
    public function getFonts()
    {
        $readyFonts = $this->getAvailableFonts();

        return response()->json([
            'success' => true,
            'data' => $readyFonts
        ]);
    }

    public function getFavoriteFonts()
    {
        $favoritesFonts = GoogleFonts::getEnabledFonts();

        return response()->json([
            'success' => true,
            'data' => $favoritesFonts ?: []
        ]);
    }

    public function removeFavoriteFont(Request $request)
    {
        $fontFamily = $request->get('font', false);
        if (!$fontFamily) {
            return response()->json([
                'success' => false,
                'message' => 'Font family is required',
            ]);
        }

        $favoritesFonts = GoogleFonts::getEnabledFonts();
        $newFavorites = [];

        if (!empty($favoritesFonts)) {
            foreach ($favoritesFonts as $font) {
                if ($font !== $fontFamily) {
                    $newFavorites[] = $font;
                }
            }
        }

        save_option("enabled_custom_fonts", json_encode($newFavorites), "template");

        return response()->json([
            'success' => true,
            'message' => 'Font removed from favorites',
        ]);
    }

    public function saveTemplateFonts(Request $request)
    {
        $fontFamily = $request->get('fonts', false);
        if ($fontFamily) {

            if (is_array($fontFamily)) {
                foreach ($fontFamily as $fontFamily) {
                    $this->favorite($fontFamily);
                }
            } else {
                $this->favorite($fontFamily);
            }


        }

        return response()->json([
            'success' => true,
            'message' => 'Font saved successfully.',
        ]);
    }

    public function favorite($fontFamily)
    {
        // Check if the font exists in our available font list
        $availableFonts = $this->getAvailableFonts();
        $fontExists = false;

        foreach ($availableFonts as $font) {
            if ($font['family'] === $fontFamily) {
                $fontExists = true;
                break;
            }
        }

        if (!$fontExists) {
            return false; // Font doesn't exist in our list, don't save it
        }

        $fontsPath = userfiles_path() . 'fonts';
        if (!is_dir($fontsPath)) {
            mkdir_recursive($fontsPath);
        }

        $googleFontDomain = \MicroweberPackages\Utils\Misc\GoogleFonts::getDomain();
        $fontUrl = str_replace('%2B', '+', $fontFamily);
        $fontUrl = urlencode($fontUrl);


        try {
            $downloader = new GoogleFontDownloader();
            $downloader->setOutputPath($fontsPath);
            //$downloader->addFontUrl("https://{$googleFontDomain}/css?family={$fontUrl}:300italic,400italic,600italic,700italic,800italic,400,600,800,700,300&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic");
            $downloader->addFontUrl("https://{$googleFontDomain}/css?family={$fontUrl}");
            $downloader->download();

        } catch (\Exception $e) {
            return false; // Failed to download the font
        }

        $newFavorites = [];
        $favoritesFonts = GoogleFonts::getEnabledFonts();

        if (is_array($favoritesFonts) && !empty($favoritesFonts)) {
            $newFavorites = array_merge($newFavorites, $favoritesFonts);
            $findFont = false;
            foreach ($favoritesFonts as $font) {
                if ($font == $fontFamily) {
                    $findFont = true;
                }
            }
            if (!$findFont) {
                $newFavorites[] = $fontFamily;
            }
        } else {
            $newFavorites[] = $fontFamily;
        }

        save_option("enabled_custom_fonts", json_encode($newFavorites), "template");

        return true; // Successfully saved the font
    }

    public function getAvailableFonts()
    {
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
}
