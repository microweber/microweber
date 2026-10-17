<?php

namespace MicroweberPackages\Template\Concerns;

use Illuminate\Support\Str;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ManagesApiJsAssets
{
    /*
        public function get_apijs_url()
        {
            return $this->js_adapter->get_apijs_url();
        }*/

    public function get_apijs_settings_url()
    {
        return $this->js_adapter->get_apijs_settings_url();
    }

    public function get_apijs_combined_url()
    {
        return $this->js_adapter->get_apijs_combined_url();
    }

    /**
     * @deprecated
     */
//    public function append_livewire_to_layout($layout)
//    {
////        $alpineUrl = mw_includes_url() . 'api/libs/alpine/alpine.min.js';
////
////        $alpineScript = '<script src="' . $alpineUrl . '" defer></script>';
//
//        $scripts = \Livewire\Livewire::scripts();
//        $styles = \Livewire\Livewire::styles();
//        $modal = \Livewire\Livewire::mount('livewire-ui-modal')->html();
//
//
//       // $layout = Str::replaceFirst('<head>', '<head>' . $alpineScript, $layout);
//        $layout = Str::replaceFirst('</head>', $styles . '</head>', $layout);
//        $layout = Str::replaceFirst('</head>', $scripts . '</head>', $layout);
//        $layout = Str::replaceFirst('</body>', $modal . '</body>', $layout);
//
//
//        return $layout;
//    }

    /**
     * @deprecated must be moved to the MetaTags package
     */
    public function frontend_append_meta_tags($layout, $is_laravel_template = false)
    {

        event_trigger('mw.template.before_render', $layout);

        //   $layout = $this->append_livewire_to_layout($layout);
        //  $layout = $this->append_api_js_to_layout($layout);
        if ($is_laravel_template) {
            $meta = '';

        } else {
            $meta = mw_header_scripts();

        }

        $layout = Str::replaceFirst('<head>', '<head>' . $meta, $layout);

//        $liveEditTags = new \MicroweberPackages\MetaTags\Entities\LiveEditCssHeadTags();
//        $liveEditTagsHtml = $liveEditTags->toHtml();
//
//        if ($liveEditTagsHtml) {
//            $layout = Str::replaceFirst('</head>', $liveEditTagsHtml . '</head>', $layout);
//        }
        if (!$is_laravel_template) {
            $meta = mw_footer_scripts();
            $layout = Str::replaceFirst('</body>', $meta . '</body>', $layout);

            $layout = $this->injectConditionalJqueryFooter($layout);
        }

        return $layout;
    }

    /** Inject jQuery + jQuery UI before </body> when the rendered layout contains plugin markers that require them. */
    public function injectConditionalJqueryFooter(string $layout): string
    {
        if (stripos($layout, '</body>') === false) {
            return $layout;
        }

        if (!$this->layoutNeedsJquery($layout)) {
            return $layout;
        }

        $jquery = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery/jquery.js';
        $jqueryUi = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery-ui/jquery-ui.js';
        $jqueryUiCss = public_asset() . 'vendor/microweber-packages/frontend-assets-libs/jquery-ui/jquery-ui.css';

        $inject = '<!-- jQuery loaded conditionally because page has jQuery-dependent module skin markers -->' . "\r\n";
        $inject .= '<link rel="stylesheet" href="' . $jqueryUiCss . '" id="mw-jquery-ui-js-libs-styles">' . "\r\n";
        $inject .= '<script src="' . $jquery . '" id="mw-jquery-js-libs-scripts"></script>' . "\r\n";
        $inject .= '<script src="' . $jqueryUi . '" id="mw-jquery-ui-js-libs-scripts"></script>' . "\r\n";
        // Re-register frontend.js extensions now that jQuery is loaded + bootstrap the legacy $.ajaxSetup CSRF shim.
        $inject .= '<script id="mw-jquery-late-bootstrap" type="text/javascript">' . "\r\n";
        $inject .= '(function () {' . "\r\n";
        $inject .= '    if (typeof window.jQuery === "undefined") return;' . "\r\n";
        $inject .= '    var token = (document.querySelector(\'meta[name="csrf-token"]\') || {}).content;' . "\r\n";
        $inject .= '    if (token) {' . "\r\n";
        $inject .= '        window.jQuery.ajaxSetup({ headers: { "X-CSRF-TOKEN": token } });' . "\r\n";
        $inject .= '    }' . "\r\n";
        $inject .= '    var ext = window.__mwRegisterJqueryExtensions || [];' . "\r\n";
        $inject .= '    for (var i = 0; i < ext.length; i++) {' . "\r\n";
        $inject .= '        try { ext[i](); } catch (e) {}' . "\r\n";
        $inject .= '    }' . "\r\n";
        $inject .= '    window.__mwRegisterJqueryExtensions = [];' . "\r\n";
        $inject .= '})();' . "\r\n";
        $inject .= '</script>' . "\r\n";

        return Str::replaceFirst('</body>', $inject . '</body>', $layout);
    }

    /** Detect whether the rendered layout contains any marker that requires jQuery. */
    protected function layoutNeedsJquery(string $layout): bool
    {
        $markers = [
            'slick-slider',        // Slick carousel container
            'slick-track',         // Slick inner track
            'data-slick',          // Slick options data attribute
            'class="slick',        // Loose slick- class prefix
            "class='slick",
            'masonry',             // Masonry layout (covers class + data attr)
            'datetimepicker',      // Bootstrap Datetimepicker
            'chosen-select',       // Chosen <select> opt-in class
            'chosen-container',    // Chosen rendered wrapper
            'data-mw-needs-jquery', // Explicit opt-in marker
        ];

        foreach ($markers as $marker) {
            if (stripos($layout, $marker) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * @deprecated
     */
    public function append_api_js_to_layout($layout)
    {
        $apijs_combined_loaded = $this->get_apijs_combined_url();
        $append_html = '';

        if (!stristr($layout, $apijs_combined_loaded)) {
            $append_html = $append_html . "\r\n" . '<script src="' . $apijs_combined_loaded . '"></script>' . "\r\n";
        }

        if ($append_html) {
            $rep = 0;
            $layout = Str::replaceFirst('<head>', '<head>' . $append_html, $layout);
        }

        return $layout;
    }

    public function clear_cached_apijs_assets()
    {
        $userfiles_dir = userfiles_path();
        $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs' . DS);
        if (!is_dir($userfiles_cache_dir)) {
            return;
        }
        $files = glob($userfiles_cache_dir . "*.js");
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }
}
