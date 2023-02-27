<?php

namespace MicroweberPackages\Template\Adapters;

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

        delete_option( 'admin_theme_vars', 'admin');
         cache_clear('repositories');
        $this->cleanCompiledStylesheets();
    }

    public function resetSelectedStyle()
    {

         $option = array();
         delete_option( 'admin_theme_name', 'admin');
        cache_clear('repositories');

        $this->cleanCompiledStylesheets();
    }


    public function getAdminTemplates()
    {
        $ui_root_dir = mw_includes_path() . 'api/libs/mw-ui/';
        $themes_dir = $ui_root_dir . 'grunt/plugins/ui/css/bootswatch/themes/';

        $dirs = scandir($themes_dir);
        $templates = [];
        if ($dirs) {
            foreach ($dirs as $dir) {
                if ($dir != '.' and $dir != '..') {
                    if (is_file($themes_dir . $dir . '/_bootswatch.scss')) {
                        $templates[] = $dir;
                    }
                }
            }
        }


        return $templates;
    }

    public function getAdminTemplateVars($theme)
    {
        if (!$theme) {
            return;
        }

        $ui_root_dir = mw_includes_path() . 'api/libs/mw-ui/';
        $themes_dir = $ui_root_dir . 'grunt/plugins/ui/css/bootswatch/themes/';
        $theme = sanitize_path($theme);
        $vars_file = normalize_path($themes_dir . $theme . '/_variables.scss', false);

        if (is_file($vars_file)) {
            $input = file_get_contents($vars_file);
            $scss = new \ScssPhp\ScssPhp\Parser($input);
            $parsed = $scss->parse($input);
            $vars = [];
            if (isset($parsed->children)) {
                $children = $parsed->children;
                if ($children) {
                    foreach ($children as $item) {
                        if (isset($item[0]) and $item[0] == 'assign') {
                            if (isset($item[1][0]) and isset($item[2][1]) and isset($item[1][1]) and $item[1][0] == 'var') {

                                $vars[$item[1][1]] = $item[2][1];
                            }
                        }
                    }
                }

            }
            return $vars;
        }
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

    public function getVariablesHashForCssFileSuffix()
    {
        $hash = 'colors';

        $vars = $this->getVars();
        if (defined('MW_VERSION')) {
            $hash .= MW_VERSION . '-';
        }
        if ($vars) {
            $hash .= crc32(json_encode($vars));
        }
        return $hash;
    }

    public function getLiveEditStylesheets()
    {
        $return = [];
        $return['live-edit-main'] = mw_includes_url() . 'css/liveedit.css';
        $return['live-edit-wysiwyg'] = mw_includes_url() . 'css/wysiwyg.css';
        $return['live-edit-components'] = mw_includes_url() . 'css/components.css';
        if (_lang_is_rtl()) {
            $return['live-edit-rtl'] = mw_includes_url() . 'css/liveedit.rtl.css';
        }
        $custom_admin_css = $this->getLiveEditAdminCssUrl();
        if ($custom_admin_css) {
            $return['live-edit-theme-compiled'] = $custom_admin_css;
        }
        return $return;
    }

    public function compileLiveEditCss()
    {

        if (php_can_use_func('ini_set')) {
            ini_set('memory_limit', '-1');
        }
        $selected_theme = false;
        $vars = false;
        $cont = false;
        $get_vars = $this->getVars();

        if (isset($get_vars['admin_theme_name'])) {
            $selected_theme = $get_vars['admin_theme_name'];
        }
        if (isset($get_vars['admin_theme_vars'])) {
            $vars = $get_vars['admin_theme_vars'];
        }

        if (!$selected_theme and !$vars) {
            return false;
        }


        $ui_root_dir = mw_includes_path() . 'api/libs/mw-ui/';
        $themes_dir = $ui_root_dir . 'grunt/plugins/ui/css/bootswatch/themes/';

        $compiled_output_path = userfiles_path() . 'css/admin-css/';
        $compiled_output_url = userfiles_url() . 'css/admin-css/';
        if (!is_dir($compiled_output_path)) {
            mkdir_recursive($compiled_output_path);
        }

        $compiled_css_output_path_file_sass = normalize_path($compiled_output_path . '__compiled_liveedit.' . $this->getVariablesHashForCssFileSuffix() . '.scss', false);
        $compiled_css_output_path_file_css = normalize_path($compiled_output_path . '__compiled_liveedit.' . $this->getVariablesHashForCssFileSuffix() . '.css', false);
        $compiled_css_output_path_file_css_url = $compiled_output_url . '__compiled_liveedit.' . $this->getVariablesHashForCssFileSuffix() . '.css';

        $compiled_css_map_output_path_file = normalize_path($compiled_output_path . '__compiled_liveedit.' . $this->getVariablesHashForCssFileSuffix() . '.scss.map', false);
        $compiled_css_map_output_path_url = $compiled_output_url . '__compiled_liveedit.' . $this->getVariablesHashForCssFileSuffix() . '.scss.map';


        $theme_file_rel_path = $selected_theme . '/_liveedit.scss';
        $theme_file_abs_path = normalize_path($themes_dir . $theme_file_rel_path, false);

        $theme_file_vars_rel_path = $selected_theme . '/_variables.scss';
        $theme_file_vars_abs_path = normalize_path($themes_dir . $theme_file_vars_rel_path, false);

        if (!is_file($theme_file_abs_path)) {
            return false;
        }

        if (!is_file($theme_file_vars_abs_path)) {
            return false;
        }

        if (!$selected_theme and !$vars) {
            return false;
        }


        if ($selected_theme) {
            if (!is_file($theme_file_abs_path) or !is_file($theme_file_vars_abs_path)) {
                return false;
            }

            if (is_file($compiled_css_output_path_file_css)) {
                return $compiled_css_output_path_file_css_url;
            }
        }

        // $url = mw_includes_url() . 'api/libs/mw-ui/grunt/plugins/ui/css/main_with_mw.css';
        $url_images_dir = mw_includes_url() . 'api/libs/mw-ui/grunt/plugins/ui/img';

        $ui_root_dir = mw_includes_path() . 'api/libs/mw-ui/';

        $sourceRoot = dirname($theme_file_abs_path) . DIRECTORY_SEPARATOR;

        $scss = new \ScssPhp\ScssPhp\Compiler();
        $scss->setImportPaths([$ui_root_dir . 'grunt/plugins/ui/css/']);

        $scss->setSourceMap(\ScssPhp\ScssPhp\Compiler::SOURCE_MAP_FILE);


        $scss->setSourceMapOptions([
            'sourceMapWriteTo' => $compiled_css_map_output_path_file,
            'sourceMapURL' => $compiled_css_map_output_path_url,
            'sourceMapBasepath' => $compiled_output_path,
            'sourceRoot' => $ui_root_dir . 'grunt/plugins/ui/css/',
        ]);


        if ($selected_theme) {
            $cont = "
             @import 'bootswatch/themes/{$theme_file_vars_rel_path}';
             @import 'bootswatch/themes/{$theme_file_rel_path}';
            ";
        }

        if(!$cont){
            return false;
        }

        if (!$selected_theme and $vars) {
            return false;
        } elseif ($vars) {
            if ($vars) {
                $scss->setVariables($vars);
            }
        }

        $output = $scss->compile($cont, $compiled_css_output_path_file_sass);
        if (!$output) {
            return false;
        }

        $output = str_replace('../img', $url_images_dir, $output);
        if (!is_dir(dirname($compiled_css_output_path_file_css))) {
            mkdir_recursive(dirname($compiled_css_output_path_file_css));
        }
        file_put_contents($compiled_css_output_path_file_css, $output);
        return $output;
    }

    public function getLiveEditAdminCssUrl()
    {
        $selected_theme = false;
        $vars = false;
        $get_vars = $this->getVars();

        if (isset($get_vars['admin_theme_name'])) {
            $selected_theme = $get_vars['admin_theme_name'];
        }
        if (isset($get_vars['admin_theme_vars'])) {
            $vars = $get_vars['admin_theme_vars'];
        }


        if (!$selected_theme and !$vars) {
            return false;
        }


        $ui_root_dir = mw_includes_path() . 'api/libs/mw-ui/';
        $themes_dir = $ui_root_dir . 'grunt/plugins/ui/css/bootswatch/themes/';

        $compiled_output_path = userfiles_path() . 'css/admin-css/';
        $compiled_output_url = userfiles_url() . 'css/admin-css/';
        if (!is_dir($compiled_output_path)) {
            mkdir_recursive($compiled_output_path);
        }

        $compiled_css_output_path_file_sass = normalize_path($compiled_output_path . '__compiled_liveedit.' . $this->getVariablesHashForCssFileSuffix() . '.scss', false);
        $compiled_css_output_path_file_css = normalize_path($compiled_output_path . '__compiled_liveedit.' . $this->getVariablesHashForCssFileSuffix() . '.css', false);
        $compiled_css_output_path_file_css_url = $compiled_output_url . '__compiled_liveedit.' . $this->getVariablesHashForCssFileSuffix() . '.css';

        $compiled_css_map_output_path_file = normalize_path($compiled_output_path . '__compiled_liveedit.' . $this->getVariablesHashForCssFileSuffix() . '.scss.map', false);
        $compiled_css_map_output_path_url = $compiled_output_url . '__compiled_liveedit.' . $this->getVariablesHashForCssFileSuffix() . '.scss.map';


        $theme_file_rel_path = $selected_theme . '/_liveedit.scss';
        $theme_file_abs_path = normalize_path($themes_dir . $theme_file_rel_path, false);

        $theme_file_vars_rel_path = $selected_theme . '/_variables.scss';
        $theme_file_vars_abs_path = normalize_path($themes_dir . $theme_file_vars_rel_path, false);

        if (!is_file($theme_file_abs_path)) {
            return false;
        }

        if (!is_file($theme_file_vars_abs_path)) {
            return false;
        }

        if (!$selected_theme and !$vars) {
            return false;
        }


        if ($selected_theme) {


            if (is_file($compiled_css_output_path_file_css)) {
                return $compiled_css_output_path_file_css_url;
            }


            return route('api.template.compile_admin_live_edit_css');


        }

    }


    public function getAdminCssUrl()
    {

        $selected_theme = false;
        $vars = false;
        $get_vars = $this->getVars();

        if (isset($get_vars['admin_theme_name'])) {
            $selected_theme = $get_vars['admin_theme_name'];
        }
        if (isset($get_vars['admin_theme_vars'])) {
            $vars = $get_vars['admin_theme_vars'];
        }


        $url = mw_includes_url() . 'api/libs/mw-ui/grunt/plugins/ui/css/main_with_mw.css';
        $ui_root_dir = mw_includes_path() . 'api/libs/mw-ui/';
        $themes_dir = $ui_root_dir . 'grunt/plugins/ui/css/bootswatch/themes/';

        $compiled_output_path = userfiles_path() . 'css/admin-css/';
        $compiled_output_url = userfiles_url() . 'css/admin-css/';


        $compiled_css_output_path_file_css = normalize_path($compiled_output_path . '__compiled_admin.' . $this->getVariablesHashForCssFileSuffix() . '.css', false);
        $compiled_css_output_path_file_css_url = $compiled_output_url . '__compiled_admin.' . $this->getVariablesHashForCssFileSuffix() . '.css';


        $theme_file_rel_path = $selected_theme . '/_bootswatch.scss';
        $theme_file_abs_path = normalize_path($themes_dir . $theme_file_rel_path, false);

        $theme_file_vars_rel_path = $selected_theme . '/_variables.scss';
        $theme_file_vars_abs_path = normalize_path($themes_dir . $theme_file_vars_rel_path, false);

        if (!$selected_theme and !$vars) {
            return $url;
        }

        if ($selected_theme) {
            if (!is_file($theme_file_abs_path) or !is_file($theme_file_vars_abs_path)) {
                return $url;
            }

            if (is_file($compiled_css_output_path_file_css)) {
                return $compiled_css_output_path_file_css_url;
            }
        }


        return route('api.template.compile_admin_css');

    }

    public function compileAdminCss()
    {

        if (php_can_use_func('ini_set')) {
            ini_set('memory_limit', '-1');
        }

        $selected_theme = false;
        $vars = $cont = $output = false;
        $get_vars = $this->getVars();

        if (isset($get_vars['admin_theme_name'])) {
            $selected_theme = $get_vars['admin_theme_name'];
        }
        if (isset($get_vars['admin_theme_vars'])) {
            $vars = $get_vars['admin_theme_vars'];
        }


        $url = mw_includes_url() . 'api/libs/mw-ui/grunt/plugins/ui/css/main_with_mw.css';
        $url_images_dir = mw_includes_url() . 'api/libs/mw-ui/grunt/plugins/ui/img';
        $ui_root_dir = mw_includes_path() . 'api/libs/mw-ui/';
        $themes_dir = $ui_root_dir . 'grunt/plugins/ui/css/bootswatch/themes/';

        $compiled_output_path = userfiles_path() . 'css/admin-css/';
        $compiled_output_url = userfiles_url() . 'css/admin-css/';
        if (!is_dir($compiled_output_path)) {
            mkdir_recursive($compiled_output_path);
        }

        $compiled_css_output_path_file_sass = normalize_path($compiled_output_path . '__compiled_admin' . $this->getVariablesHashForCssFileSuffix() . '.scss', false);
        $compiled_css_output_path_file_css = normalize_path($compiled_output_path . '__compiled_admin.' . $this->getVariablesHashForCssFileSuffix() . '.css', false);
        $compiled_css_output_path_file_css_url = $compiled_output_url . '__compiled_admin.' . $this->getVariablesHashForCssFileSuffix() . '.css';

        $compiled_css_map_output_path_file = normalize_path($compiled_output_path . '__compiled_admin.' . $this->getVariablesHashForCssFileSuffix() . '.scss.map', false);
        $compiled_css_map_output_path_url = $compiled_output_url . '__compiled_admin.' . $this->getVariablesHashForCssFileSuffix() . '.scss.map';


        $theme_file_rel_path = $selected_theme . '/_bootswatch.scss';
        $theme_file_abs_path = normalize_path($themes_dir . $theme_file_rel_path, false);

        $theme_file_vars_rel_path = $selected_theme . '/_variables.scss';
        $theme_file_vars_abs_path = normalize_path($themes_dir . $theme_file_vars_rel_path, false);


        $scss = new \ScssPhp\ScssPhp\Compiler();
    //    $scss->setImportPaths([$ui_root_dir . 'grunt/plugins/ui/css/']);
        $scss->setImportPaths([$ui_root_dir . 'grunt/plugins/ui/css/']);

        $scss->setSourceMap(\ScssPhp\ScssPhp\Compiler::SOURCE_MAP_NONE);

        $compiled_output_path = str_replace(mw_root_path(),'',$compiled_output_path);



        $scss->setSourceMapOptions([
            'sourceMapWriteTo' => $compiled_css_map_output_path_file,
            'sourceMapURL' => $compiled_css_map_output_path_url,
            'sourceMapBasepath' => $compiled_output_path,
            //'sourceRoot' => $ui_root_dir . 'grunt/plugins/ui/css/',
            'sourceRoot' =>   '../grunt/plugins/ui/css/',
        ]);


        if ($selected_theme) {
            $cont = "
             //Bootswatch variables
             //@import 'bootswatch/_variables';
             @import 'bootswatch/themes/{$theme_file_vars_rel_path}';

             //UI Variables
             @import 'bootstrap_variables';

             //Bootstrap
             @import '../../bootstrap/scss/bootstrap';

             //Bootswatch structure
             @import 'bootswatch/_bootswatch';
             @import 'bootswatch/themes/{$theme_file_rel_path}';

             //UI
             //@import '_ui';
             //@import '_mw';
             @import 'main_with_mw';
            ";
        }

        if (!$selected_theme and $vars) {
            $cont = "@import 'main_with_mw';";

            if ($vars) {
                $scss->setVariables($vars);
            }
        } elseif ($vars) {

            // $cont = "@import 'main_with_mw';";

            if ($vars) {
                $scss->setVariables($vars);
            }
        }
        if($cont){
                $output = $scss->compile($cont, $compiled_css_output_path_file_sass);
        }

        $output = str_replace('../img', $url_images_dir, $output);
        if (!is_dir(dirname($compiled_css_output_path_file_css))) {
            mkdir_recursive(dirname($compiled_css_output_path_file_css));
        }
        file_put_contents($compiled_css_output_path_file_css, $output);
        return $output;
    }


    public function getVars()
    {

        $branding_colors = mw()->ui->admin_colors_sass();

        $selected_theme = get_option('admin_theme_name', 'admin');
        $selected_vars = get_option('admin_theme_vars', 'admin');


        if (!$selected_theme and $branding_colors) {
            $vars_from_branding_json = [];

            $branding_colors_explode = explode("\n", $branding_colors);

            if ($branding_colors_explode) {
                foreach ($branding_colors_explode as $item) {
                    if (trim($item) != '') {
                        $item_arr = explode(':', $item);
                        if ($item_arr and isset($item_arr[1])) {
                            $var_name = trim($item_arr[0]);
                            $var_val = trim($item_arr[1]);
                            $var_name = ltrim($var_name, '$');
                            $var_val = str_ireplace('!default', '', $var_val);
                            $var_val = str_ireplace(';', '', $var_val);
                            $vars_from_branding_json[$var_name] = $var_val;
                        }
                    }
                }
            }
            if ($vars_from_branding_json) {
                $selected_theme = 'custom';
                return [
                    'admin_theme_name' => $selected_theme,
                    'admin_theme_vars' => $vars_from_branding_json
                ];
            }
        }


        $vars = [];
        if ($selected_vars and is_string($selected_vars)) {
            $vars = json_decode($selected_vars, true);
        }
        return [
            'admin_theme_name' => $selected_theme,
            'admin_theme_vars' => $vars
        ];

    }

}
