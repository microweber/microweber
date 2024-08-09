<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Vite;

class AdminFilamentJsLibsScriptTag implements TagInterface, \Stringable
{
    public function toHtml(): string
    {
        $libs = Vite::asset('src/MicroweberPackages/LiveEdit/resources/front-end/js/admin/admin-filament-libs.js');
       // $jquery = mw_includes_url() . 'api/libs/jqueryui/external/jquery/jquery.js';
        $adminJs = public_asset() . 'vendor/microweber-packages/frontend-assets/js/admin.js';
        $adminCss = public_asset() . 'vendor/microweber-packages/frontend-assets/css/admin.css';
        $jquery = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery/jquery.min.js';
        //$jqueryUi = mw_includes_url() . 'api/libs/jqueryui/jquery-ui.js';
        $jqueryUi = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery-ui/jquery-ui.min.js';
        $jqueryUiCss = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery-ui/jquery-ui.css';
        $jqueryUiNestedSortable = mw_includes_url() . 'api/libs/nestedsortable/jquery.mjs.nestedSortable.js';
        $lib_tynymce = mw_includes_url() . 'api/libs/tinymce/tinymce.min.js';

        $fileRobotUrl = "https://scaleflex.cloudimg.io/v7/plugins/filerobot-image-editor/latest/filerobot-image-editor.min.js";
        $nouislider = mw_includes_url() . 'api/libs/nouislider/nouislider.js';
        $nouisliderCss = mw_includes_url() . 'api/libs/nouislider/nouislider.css';

        //nouislider

        $append_html = '' . "\r\n";
        $append_html .= '<script src="' . $jquery . '" id="mw-jquery-js-libs-scripts"></script>' . "\r\n";

        $append_html .= '' . "\r\n";
        $append_html .= '<script src="' . $jqueryUi . '" id="mw-jquery-ui-js-libs-scripts"></script>' . "\r\n";

        $append_html .= '' . "\r\n";
        $append_html .= '<link rel="stylesheet" href="' . $jqueryUiCss . '" id="mw-jquery-ui-js-libs-styles">' . "\r\n";

        $append_html .= '' . "\r\n";
        $append_html .= '<script src="' . $jqueryUiNestedSortable . '" id="mw-jquery-ui-js-libs-nested-sortable-scripts"></script>' . "\r\n";

        $append_html .= '' . "\r\n";
        $append_html .= '<script src="' . $nouislider . '" id="mw-nouislider-js-libs-scripts"></script>' . "\r\n";
        $append_html .= '' . "\r\n";
        $append_html .= '<link rel="stylesheet" href="' . $nouisliderCss . '" id="mw-nouislider-js-libs-styles">' . "\r\n";

        $append_html .= '' . "\r\n";
        $append_html .= '<script src="' . $lib_tynymce . '" id="mw-tynymce-js-libs-scripts"></script>' . "\r\n";

        $append_html .= '' . "\r\n";
        $append_html .= '<script src="' . $libs . '" type="module"  id="mw-filament-js-libs-scripts"></script>' . "\r\n";


        $append_html .= '' . "\r\n";
        $append_html .= '<script src="' . $fileRobotUrl . '" id="mw-admin-file-robot-js-libs-scripts"></script>' . "\r\n";

        $append_html .= '' . "\r\n";
        $append_html .= '<link rel="stylesheet" href="' . $adminCss . '" id="mw-admin-css-styles">' . "\r\n";

        $append_html .= '' . "\r\n";
        $append_html .= '<script src="' . $adminJs . '" id="mw-admin-js-scripts"></script>' . "\r\n";

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
            'type' => 'admin-filament-js-libs',
        ];
    }
}
