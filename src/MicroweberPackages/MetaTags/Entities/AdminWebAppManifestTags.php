<?php

namespace MicroweberPackages\MetaTags\Entities;

use Arcanedev\SeoHelper\Entities\Analytics;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class AdminWebAppManifestTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {

        if (!mw_is_installed()) {
            return '';
        }
        $secFetchDest = request()->header('Sec-Fetch-Dest');
        if ($secFetchDest == 'iframe') {
            //do not append on iframes
            return '';
        }
        if (!is_admin()) {
            return '';
        }

        if (is_ajax()) {
            return '';
        }

        $save_file = $this->getManifestFilenameToSave();

        if (!is_file($save_file)) {
            $this->webAppManifestGenerateCacheFile();
        }


        if (!is_file($save_file)) {
            return '';
        }

        $save_file_url = $this->getManifestFilenameUrl();

        $manifestRoute = $save_file_url;
        $template_headers_src_items = [];
        $template_headers_src_items[] = '<link crossorigin="use-credentials" rel="manifest" href="' . $manifestRoute . '" />';
        $template_headers_src_items[] = '<meta name="mobile-web-app-capable" content="yes">';
        $template_headers_src_items[] = '<meta name="apple-mobile-web-app-capable" content="yes">';
        $template_headers_src_items[] = '<meta name="application-name" content="' . site_hostname() . '">';

        $template_headers_src = implode("\n", $template_headers_src_items);

        return $template_headers_src;

    }

    public function getPlacement(): string
    {
        return Meta::PLACEMENT_HEAD;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }


    public function toArray(): array
    {
        return [
            'type' => 'admin_web_app_manifest',
        ];
    }


    private function webAppManifestGenerateCacheFile()
    {
        $website_name = get_option('website_name', 'website');
        $hostname = site_hostname();
        if (!$website_name) {
            $website_name = 'Microweber';
        }
        $favicon_image = get_favicon_image();
        if (!$favicon_image) {
            $favicon_image = site_url('favicon.ico');
        }


        $save_file = $this->getManifestFilenameToSave();

        $maskable_icon = get_option('maskable_icon', 'website');
        if (!$maskable_icon) {
            $maskable_icon = asset('vendor/microweber-packages/frontend-assets-libs/img/logo-mobile.svg');
        }
        $manifest_app_icon = get_option('manifest_app_icon', 'website');
        if (!$manifest_app_icon) {
            $manifest_app_icon = asset('vendor/microweber-packages/frontend-assets-libs/img/logo-144x144.png');
        }

        $manifest = [
            "name" => "$website_name on $hostname",
            "short_name" => $website_name,
            "description" => $website_name . " Admin",
            "start_url" => admin_url(),
            "scope" => site_url(),
            "background_color" => "#2196f3",
            "theme_color" => "#2196f3",
            "icons" => [
                [
                    "src" => $manifest_app_icon,
                    "sizes" => "144x144",
                    "type" => "image/png",
                    "purpose" => "any"
                ],

                [
                    "src" => $maskable_icon,
                    "purpose" => "maskable"
                ], [
                    "src" => $favicon_image,
                    "purpose" => "any"
                ],
            ],
            "display" => "standalone"
        ];

        $manifestJson = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);


        if (!is_dir(dirname($save_file))) {
            mkdir_recursive(dirname($save_file));
        }

        if (!is_file($save_file)) {
            file_put_contents($save_file, $manifestJson);
        }

    }

    private function getManifestFilenameToSave(): string
    {

        $filename = $this->generateManifestFilename();

        $userfiles_dir = userfiles_path();
        $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);
        $save_file = $userfiles_cache_dir . $filename;
        return $save_file;
    }

    private function getManifestFilenameUrl(): string
    {
        $filename = $this->generateManifestFilename();
        $userfiles_dir = userfiles_url();
        $userfiles_cache_dir = $userfiles_dir . 'cache/';
        $save_file = $userfiles_cache_dir . $filename;
        return $save_file;
    }

    private function generateManifestFilename(): string
    {
        $hash = 'manifest';

        $cache_file_name = '';
        if (mw_is_multisite()) {
            $environment = app()->environment();
            $cache_file_name = 'admin.' . $environment . '.' . $hash . MW_VERSION . '.json';
        } else {
            $cache_file_name = 'admin.default.' . $hash . MW_VERSION . '.json';
        }
        return $cache_file_name;
    }

}
