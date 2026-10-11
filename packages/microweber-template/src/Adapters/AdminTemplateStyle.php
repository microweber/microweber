<?php

namespace MicroweberPackages\Template\Adapters;


/**
 * @deprecated This class is deprecated.
 *
 */
class AdminTemplateStyle
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    public function __construct($app = null)
    {

        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

        $this->app = $app;
    }

    public function cleanCompiledStylesheets()
    {
        $compiled_output_path = userfiles_path() . 'css/admin-css/';
        if (is_dir($compiled_output_path)) {
            $files = glob($compiled_output_path . "*.css");
            if ($files) {
                foreach ($files as $file) {

                    if (is_file($file)) {
                        @unlink($file);
                    }
                }
            }
        }

    }

    public function resetSelectedStyleVariables()
    {

        delete_option('admin_theme_vars', 'admin');
        cache_clear('repositories');
        $this->cleanCompiledStylesheets();
    }

    public function resetSelectedStyle()
    {

        $option = array();
        delete_option('admin_theme_name', 'admin');
        cache_clear('repositories');

        $this->cleanCompiledStylesheets();
    }


    public function getAdminTemplates()
    {

//todo
        return [];
    }

    public function getAdminTemplateVars($theme)
    {
        //todo
        return [];

    }

    public function getLiveEditTemplateHeadHtml()
    {
        $styles = $this->getLiveEditStylesheets();
        $html = '';
        if ($styles) {
            foreach ($styles as $key => $style) {
                $html .= '<link id="' . $key . '" href="' . $style . '" rel="stylesheet" type="text/css"/>' . "\n";
            }
        }

        return $html;

    }


    public function getLiveEditStylesheets()
    {
        $return = [];
        /*  //$return['live-edit-main'] = mw_includes_url() . 'css/liveedit.css';
          $return['live-edit-wysiwyg'] = mw_includes_url() . 'css/wysiwyg.css';
          $return['live-edit-components'] = mw_includes_url() . 'css/components.css';
          if (_lang_is_rtl()) {
              $return['live-edit-rtl'] = mw_includes_url() . 'css/liveedit.rtl.css';
          }
          $custom_admin_css = $this->getLiveEditAdminCssUrl();
          if ($custom_admin_css) {
              $return['live-edit-theme-compiled'] = $custom_admin_css;
          }*/

        return $return;
    }

    /* @deprecated */
    public function compileLiveEditCss()
    {

        return '';
    }

    /* @deprecated */
    public function getLiveEditAdminCssUrl()
    {
        return '';

    }

    /* @deprecated */
    public function getAdminCssUrl()
    {
        // deprecated
        return '';

    }

    /* @deprecated */
    public function compileAdminCss()
    {
        // deprecated
        return '';
    }

    /* @deprecated */

    public function getVars()
    {


    }

}
