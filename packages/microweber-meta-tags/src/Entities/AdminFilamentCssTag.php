<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Vite;

class AdminFilamentCssTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        //$libs = Vite::asset('src/MicroweberPackages/LiveEdit/resources/front-end/js/admin/admin-filament-libs.js');
        // $srcipts = Vite::asset('src/MicroweberPackages/LiveEdit/resources/front-end/js/admin/admin-filament-app.js');
       // $styles = Vite::asset('src/MicroweberPackages/LiveEdit/resources/js/ui/css/admin-filament.scss');
     //   $adminJs = public_asset() . 'vendor/microweber-packages/frontend-assets/build/admin.js';
        $adminCss = public_asset() . 'vendor/microweber-packages/frontend-assets/build/admin.css';
        $append_html = '' . "\r\n";

//
//        $append_html .= '<script id="mw-admin-settings">
//            mw.settings.adminUrl = "' . admin_url() . '";
//            </script>' . "\r\n";


        $append_html .= '' . "\r\n";
   //  $append_html .= '<script src="' . $adminJs . '" id="mw-admin-js-scripts"></script>' . "\r\n";
        $append_html .= '<link rel="stylesheet" href="' . $adminCss . '" id="mw-admin-css">' . "\r\n";


        //$append_html .= '<script src="' . $libs . '" type="module" id="mw-filament-js-libs-scripts"></script>' . "\r\n";
        /*$append_html .= '<script src="' . $srcipts . '" type="module" id="mw-filament-js-core-scripts"></script>' . "\r\n";
        $append_html .= '<link rel="stylesheet" href="' . $styles . '" id="mw-filament-js-core-styles" />' . "\r\n";*/

        return $append_html;
    }

    public function getPlacement(): string
    {
        return Meta::PLACEMENT_FOOTER;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }


    public function toArray(): array
    {
        return [
            'type' => 'admin-filament-js-core',
        ];
    }
}
