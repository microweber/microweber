<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class ApijsScriptTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {

        $jquery = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery/jquery.js';
        $jqueryUi = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery-ui/jquery-ui.js';
        $jqueryUiCss = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery-ui/jquery-ui.css';


        $apijs_combined_loaded = app()->template_manager->get_apijs_combined_url();

        $apijs_combined_loaded_new =  public_asset('vendor/microweber-packages/frontend-assets/build/frontend.js');;

        $append_html = '';

         $append_html = '' . "\r\n";
         $append_html .= '<script src="' . $jquery . '" id="mw-jquery-js-libs-scripts"></script>' . "\r\n";

         $append_html .= '' . "\r\n";
         $append_html .= '<script src="' . $jqueryUi . '" id="mw-jquery-ui-js-libs-scripts"></script>' . "\r\n";

         $append_html .= '' . "\r\n";
         $append_html .= '<link rel="stylesheet" href="' . $jqueryUiCss . '" id="mw-jquery-ui-js-libs-styles">' . "\r\n";

        $append_html .= '' . "\r\n";
//        $append_html .= '<script  src="' . $apijs_combined_loaded . '"  id="mw-js-core-scripts-legacy"></script>' . "\r\n";

         $append_html .= '' . "\r\n";
         $append_html .= '<script  src="' . $apijs_combined_loaded_new . '"  id="mw-js-core-scripts"></script>' . "\r\n";

        return $append_html;
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
            'type' => 'apijs',
        ];
    }
}
