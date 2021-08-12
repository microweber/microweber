<?php


namespace MicroweberPackages\Template;

use MicroweberPackages\App\Http\Controllers\JsCompileController;
use MicroweberPackages\Template\Adapters\MicroweberTemplate;
use MicroweberPackages\Template\Adapters\RenderHelpers\TemplateOptimizeLoadingHelper;
use MicroweberPackages\Template\Adapters\TemplateCssParser;
use MicroweberPackages\Template\Adapters\TemplateStackRenderer;

/**
 * Content class is used to get and save content in the database.
 *
 * @category Content
 * @desc     These functions will allow you to get and save content in the database.
 *
 * @property \MicroweberPackages\Template\Adapters\MicroweberTemplate $adapter_current
 * @property \MicroweberPackages\Template\Adapters\MicroweberTemplate $adapter_default
 */
class Template
{
    /**
     * An instance of the Microweber Application class.
     *
     * @var
     */
    public $app;
    public $head = array();
    public $head_callable = array();
    public $foot = array();
    public $foot_callable = array();

    public $meta_tags = array();
    public $html_opening_tag = array();

    public $adapter_current = null;
    public $adapter_default = null;
    public $stylesheet_adapter = null;
    public $js_adapter = null;
    public $stack_compiler_adapter = null;


    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

        $this->stylesheet_adapter = new TemplateCssParser($app);
        $this->js_adapter = new JsCompileController($app);
        $this->stack_compiler_adapter = new TemplateStackRenderer($app);
        $this->adapter_current = $this->adapter_default = new MicroweberTemplate($app);
    }


    public function compile_css($params)
    {
        return $this->stylesheet_adapter->compile($params);
    }

    public function delete_compiled_css($params)
    {
        return $this->stylesheet_adapter->delete_compiled($params);
    }


    public function get_stylesheet($path, $default_stylesheet = false, $cache = true)
    {
        return $this->stylesheet_adapter->getStylesheet($path, $default_stylesheet, $cache);
    }


    public function get_apijs_url()
    {
        return $this->js_adapter->get_apijs_url();
    }


    public function get_apijs_settings_url()
    {
        return $this->js_adapter->get_apijs_settings_url();
    }


    public function get_liveeditjs_url()
    {
        return $this->js_adapter->get_liveeditjs_url();
    }


    public function get_apijs_combined_url()
    {
        return $this->js_adapter->get_apijs_combined_url();
    }

    public function append_api_js_to_layout($layout)
    {
        $apijs_combined_loaded = $this->get_apijs_combined_url();
        $append_html = '';

        if (!stristr($layout, $apijs_combined_loaded)) {
            $append_html = $append_html . "\r\n" . '<script src="' . $apijs_combined_loaded . '"></script>' . "\r\n";
        }

        if ($append_html) {
            $rep = 0;
            $layout = str_ireplace('<head>', '<head>' . $append_html, $layout, $rep);
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

    public function meta($name, $value = false)
    {
        $this->meta_tags[$name] = $value;
    }

    public function html_opening_tag($name, $value = false)
    {
        $this->html_opening_tag[$name] = $value;
    }

    public function folder_name()
    {
        if (!defined('THIS_TEMPLATE_FOLDER_NAME')) {
            $this->app->content_manager->define_constants();
        }
        return THIS_TEMPLATE_FOLDER_NAME;
    }

    public function dir($add = false)
    {
        if (!defined('TEMPLATE_DIR')) {
            $this->app->content_manager->define_constants();
        }
        if (defined('TEMPLATE_DIR')) {
            $val = TEMPLATE_DIR;
        }

        if ($add != false) {
            $val = $val . $add;
        }

        return $val;
    }

    public $template_config_cache = array();

    public function get_config($template = false)
    {
        if ($template == false) {

            $dir = template_dir();

            $file = $dir . 'config.php';

            if (isset($this->template_config_cache[$file])) {
                return $this->template_config_cache[$file];
            }

            if (is_file($file)) {
                include $file;
                if (isset($config)) {
                    $config['dir_name'] = basename($dir);
                    $this->template_config_cache[$file] = $config;
                    return $config;
                }

                return false;
            }
        }
    }

    public function url($add = false)
    {
        if (!defined('TEMPLATE_URL')) {
            $this->app->content_manager->define_constants();
        }

        if (defined('TEMPLATE_URL')) {
            $val = TEMPLATE_URL;
        }

        if ($add != false) {
            $val = $val . $add;
        }

        return $val;
    }

    public function adapter($method, $params)
    {
        if (method_exists($this->adapter_current, $method)) {
            return $this->adapter_current->$method($params);
        } else {
            if (method_exists($this->adapter_default, $method)) {
                return $this->adapter_default->$method($params);
            }
        }
    }

    public function get_custom_css_content()
    {
        ob_start();

        event_trigger('mw.template.print_custom_css_includes');

        $fonts_file = modules_path() . 'editor' . DS . 'fonts' . DS . 'stylesheet.php';
        if (is_file($fonts_file)) {
            include $fonts_file;
        }
        $custom_css = get_option('custom_css', 'template');
        if (is_string($custom_css)) {
            echo $custom_css;
        }

        event_trigger('mw.template.print_custom_css');

        $output = ob_get_contents();
        ob_end_clean();


        return $output;
    }


    public function get_custom_css()
    {


        $l = $this->get_custom_css_content();
        $compile_assets = \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
            $userfiles_dir = userfiles_path();
            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);
            $userfiles_cache_filename = $userfiles_cache_dir . 'custom_css.' . md5(site_url()) . '.' . MW_VERSION . '.css';
            if (!is_file($userfiles_cache_filename)) {
                if (!is_dir($userfiles_cache_dir)) {
                    mkdir_recursive($userfiles_cache_dir);
                }
                if (is_dir($userfiles_cache_dir)) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            } else {
                $fmd5 = md5_file($userfiles_cache_filename);
                $fmd = md5($l);
                if ($fmd5 != $fmd) {
                    @file_put_contents($userfiles_cache_filename, $l);
                }
            }
        }

        return $l;
    }


    public function optimize_page_loading($layout)
    {
        $optimize_asset_loading = get_option('optimize_asset_loading', 'website');
        if ($optimize_asset_loading == 'y') {

            $asset_loading_order = new TemplateOptimizeLoadingHelper($this->app);
            $layout = $asset_loading_order->render($layout);
            //   $layout = $this->app->parser->optimize_asset_loading_order($layout);

        }

        $static_files_delivery_method = get_option('static_files_delivery_method', 'website');
        $static_files_delivery_domain = get_option('static_files_delivery_method_domain', 'website');

        if ($static_files_delivery_method and $static_files_delivery_domain) {
            $should_replace = false;

            //check if site is fqdn
            $site_host = parse_url(site_url());

            if (isset($site_host['host']) and mw()->format->is_fqdn($site_host['host'])) {
                $should_replace = true;
                $site_host = $site_host['host'];
            }
            if ($should_replace) {
                if ($static_files_delivery_domain and mw()->format->is_fqdn($static_files_delivery_domain)) {
                    $should_replace = true;
                } else {
                    $should_replace = false;
                }
            }
            if ($should_replace) {
                $static_files_delivery_domain = trim($static_files_delivery_domain);

                $replaces = array();
                if ($static_files_delivery_method == 'content_proxy') {
                    $replaces[userfiles_url() . 'cache'] = 'https://' . $static_files_delivery_domain . '/' . userfiles_url() . 'cache';
                    $replaces[media_base_url()] = 'https://' . $static_files_delivery_domain . '/' . media_base_url();
                    $replaces[template_url()] = 'https://' . $static_files_delivery_domain . '/' . template_url();
                    $replaces[modules_url()] = 'https://' . $static_files_delivery_domain . '/' . modules_url();
                } else if ($static_files_delivery_method == 'cdn_domain') {
                    $replaces[userfiles_url() . 'cache'] = str_replace($site_host, $static_files_delivery_domain, userfiles_url() . 'cache');
                    $replaces[media_base_url()] = str_replace($site_host, $static_files_delivery_domain, media_base_url());
                    $replaces[template_url()] = str_replace($site_host, $static_files_delivery_domain, template_url());
                    $replaces[modules_url()] = str_replace($site_host, $static_files_delivery_domain, modules_url());


                }
                if ($replaces) {
                    $layout = str_replace(array_keys($replaces), array_values($replaces), $layout);
                }
            }

        }

        return $layout;
    }

    public function get_custom_css_url()
    {
        $url = api_nosession_url('template/print_custom_css');
        if (in_live_edit()) {
            return $url;
        }

        $compile_assets = \Config::get('microweber.compile_assets');
        if ($compile_assets and defined('MW_VERSION')) {
            $userfiles_dir = userfiles_path();
            $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);
            $userfiles_cache_filename = $userfiles_cache_dir . 'custom_css.' . md5(site_url()) . '.' . MW_VERSION . '.css';
            if (is_file($userfiles_cache_filename)) {
                $custom_live_editmtime = filemtime($userfiles_cache_filename);
                $url = userfiles_url() . 'cache/' . 'custom_css.' . md5(site_url()) . '.' . MW_VERSION . '.css?ver=' . $custom_live_editmtime;
            }
        }

        return $url;
    }


    public function add_csrf_token_meta_tags($layout)
    {

        $optimize_asset_loading = get_option('optimize_asset_loading', 'website');


        $ajax = '<script>


        $( document ).ready(function() {


            var _csrf_from_local_storage = null;


            if(typeof(mw.cookie) != \'undefined\'){
                csrf_from_local_storage_data = mw.cookie.get("csrf-token-data")
                if(csrf_from_local_storage_data){
                csrf_from_local_storage_data = JSON.parse(csrf_from_local_storage_data);

                 if (csrf_from_local_storage_data && csrf_from_local_storage_data.value && (new Date()).getTime() < csrf_from_local_storage_data.expiry) {
                     _csrf_from_local_storage = csrf_from_local_storage_data.value
                }
                }

            }

            if(_csrf_from_local_storage){

                $(\'meta[name="csrf-token"]\').attr(\'content\',_csrf_from_local_storage)
                     $.ajaxSetup({
                        headers: {
                            \'X-CSRF-TOKEN\': $(\'meta[name="csrf-token"]\').attr(\'content\')
                        }
                    });


                return;
            }


            setTimeout(function () {
                    $.get( "' . route('csrf') . '", function( data ) {
                    $(\'meta[name="csrf-token"]\').attr(\'content\',data.token)
                    if(typeof(mw.cookie) != \'undefined\' ){

                         var csrf_from_local_storage_ttl = 900000; // 15 minutes
                         var item = {
                            value: data.token,
                            expiry: (new Date()).getTime() + csrf_from_local_storage_ttl,
                        }

                        mw.cookie.set("csrf-token-data", JSON.stringify(item))


                     }

                     $.ajaxSetup({
                        headers: {
                            \'X-CSRF-TOKEN\': $(\'meta[name="csrf-token"]\').attr(\'content\')
                        }
                    });
              })
                }, 1337);


         });


        </script>
       ';
        $ajax = $ajax . ' <meta name="csrf-token" content="" />';

        $one = 1;
        //   $layout = str_ireplace('</head>', $add . '</head>', $layout, $one);

        if ($optimize_asset_loading == 'y') {
            $layout = str_ireplace('</body>', $ajax . '</body>', $layout, $one);

        } else {
            $layout = str_ireplace('</head>', $ajax . '</head>', $layout, $one);

        }


        return $layout;

    }

    public function get_default_system_ui_css_url()
    {
        $url = mw_includes_url() . 'default.css';
        return $url;
    }


    public function get_admin_supported_themes()
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

    public function get_admin_system_ui_css_url()
    {

        $selected_theme = get_option('admin_theme_name', 'admin');
        $cont = false;

        $selected_vars = get_option('admin_theme_vars', 'admin');

        $vars = [];
        if ($selected_vars) {
            $vars = json_decode($selected_vars, true);
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

        $compiled_css_output_path_file_sass = normalize_path($compiled_output_path . '__compiled_main.scss', false);
        $compiled_css_output_path_file_css = normalize_path($compiled_output_path . '__compiled_main.css', false);
        $compiled_css_output_path_file_css_url = $compiled_output_url . '__compiled_main.css';

        $compiled_css_map_output_path_file = normalize_path($compiled_output_path . '__compiled_main.scss.map', false);
        $compiled_css_map_output_path_url = $compiled_output_url . '__compiled_main.scss.map';


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

        $scss = new \ScssPhp\ScssPhp\Compiler();
        $scss->setImportPaths([$ui_root_dir . 'grunt/plugins/ui/css/']);
        $scss->setFormatter('ScssPhp\ScssPhp\Formatter\Compact');

        $scss->setSourceMap(\ScssPhp\ScssPhp\Compiler::SOURCE_MAP_INLINE);


        $scss->setSourceMapOptions([
            'sourceMapWriteTo' => $compiled_css_map_output_path_file,
            'sourceMapURL' => $compiled_css_map_output_path_url,
            'sourceMapBasepath' => $compiled_output_path,
            'sourceRoot' => $ui_root_dir . 'grunt/plugins/ui/css/',
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
             //@import 'bootswatch/_bootswatch';
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
        }

        $output = $scss->compile($cont, $compiled_css_output_path_file_sass);
        if (!$output) {
            return $url;
        }

        $output = str_replace('../img', $url_images_dir, $output);
        file_put_contents($compiled_css_output_path_file_css, $output);
        return $compiled_css_output_path_file_css_url;
    }

    public function clear_cached_custom_css()
    {
        $url = api_nosession_url('template/print_custom_css');
        $compile_assets = \Config::get('microweber.compile_assets');
        $userfiles_dir = userfiles_path();
        $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);
        $userfiles_cache_filename = $userfiles_cache_dir . 'custom_css.' . md5(site_url()) . '.' . MW_VERSION . '.css';
        if (!is_dir($userfiles_cache_dir)) {
            return;
        }
        $files = glob($userfiles_cache_dir . "custom_css*.css");
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }

    public function name()
    {
        if (!defined('TEMPLATE_NAME')) {
            $this->app->content_manager->define_constants();
        }
        if (defined('TEMPLATE_NAME')) {
            return TEMPLATE_NAME;
        }
    }

    public function admin_head($script_src)
    {
        static $mw_template_headers;
        if ($mw_template_headers == null) {
            $mw_template_headers = array();
        }

        if (is_string($script_src)) {
            if (!in_array($script_src, $mw_template_headers)) {
                $mw_template_headers[] = $script_src;

                return $mw_template_headers;
            }
        } else {
            if (is_bool($script_src)) {
                //   return $mw_template_headers;
                $src = '';
                if (is_array($mw_template_headers)) {
                    foreach ($mw_template_headers as $header) {
                        $ext = get_file_extension($header);
                        switch (strtolower($ext)) {

                            case 'css':
                                $src .= '<link rel="stylesheet" href="' . $header . '" type="text/css" media="all">' . "\n";
                                break;

                            case 'js':
                                $src
                                    .= '<script src="' . $header . '"></script>' . "\n";
                                break;

                            default:
                                $src .= $header . "\n";
                                break;
                        }
                    }
                }

                return $src;
            }
        }
    }

    public function head($script_src)
    {
        if ($this->head_callable == null) {
            $this->head_callable = array();
        }

        if (is_string($script_src)) {
            if (!in_array($script_src, $this->head)) {
                $this->head[] = $script_src;

                return $this->head;
            }
        } else {
            if (is_bool($script_src)) {
                //   return $this->head;
                $src = '';

                if (is_array($this->head)) {
                    foreach ($this->head as $header) {
                        $ext = get_file_extension($header);
                        switch (strtolower($ext)) {

                            case 'css':
                                $src .= '<link rel="stylesheet" href="' . $header . '" type="text/css" media="all">' . "\n";
                                break;

                            case 'js':
                                $src
                                    .= '<script src="' . $header . '"></script>' . "\n";
                                break;

                            default:
                                $src .= $header . "\n";
                                break;
                        }
                    }
                }

                return $src;
            } elseif (is_callable($script_src)) {
                if (!in_array($script_src, $this->head_callable)) {
                    $this->head_callable[] = $script_src;

                    return $this->head_callable;
                }
            }
        }
    }

    public function head_callback($data = false)
    {
        $data = array();
        if (!empty($this->head_callable)) {
            foreach ($this->head_callable as $callback) {
                $data[] = call_user_func($callback, $data);
            }
        }

        return $data;
    }

    public function foot($script_src)
    {
        if ($this->foot_callable == null) {
            $this->foot_callable = array();
        }

        if (is_string($script_src)) {
            if (!in_array($script_src, $this->foot)) {
                $this->foot[] = $script_src;

                return $this->foot;
            }
        } else {
            if (is_bool($script_src)) {
                $src = '';
                if (is_array($this->foot)) {
                    foreach ($this->foot as $footer) {
                        $ext = get_file_extension($footer);
                        switch (strtolower($ext)) {

                            case 'css':
                                $src .= '<link rel="stylesheet" href="' . $footer . '" type="text/css" media="all">' . "\n";
                                break;

                            case 'js':
                                $src
                                    .= '<script src="' . $footer . '"></script>' . "\n";
                                break;

                            default:
                                $src .= $footer . "\n";
                                break;
                        }
                    }
                }

                return $src;
            } elseif (is_callable($script_src)) {
                if (!in_array($script_src, $this->foot_callable)) {
                    $this->foot_callable[] = $script_src;

                    return $this->foot_callable;
                }
            }
        }
    }

    public function foot_callback($data = false)
    {
        $data = array();
        if (!empty($this->foot_callable)) {
            foreach ($this->foot_callable as $callback) {
                $data[] = call_user_func($callback, $data);
            }
        }

        return $data;
    }


    /**
     * Return the path to the layout file that will render the page.
     */
    public function get_layout($params = array())
    {
        return $this->adapter('get_layout', $params);
    }

    public function process_meta($layout)
    {
        $count = 1;
        $replace = '';
        if (!empty($this->html_opening_tag)) {
            foreach ($this->html_opening_tag as $key => $item) {
                if (is_string($item)) {
                    $replace .= $key . '="' . $item . '" ';
                }
            }
        }

        $layout = str_replace('<html ', '<html ' . $replace, $layout, $count);
        $count = 1;
        $replace = '';
        if (!empty($this->meta_tags)) {
            foreach ($this->meta_tags as $key => $item) {
                if (is_string($item)) {
                    $replace .= '<meta name="' . $key . '" content="' . $item . '">' . "\n";
                }
            }
        }
        $count = 1;
        $layout = str_replace('<head>', '<head>' . $replace, $layout, $count);

        return $layout;
    }

    /**
     * Renders the file returned by the get_layout method.
     */
    public function render($params = array())
    {
        $layout = $this->adapter('render', $params);

        $layout = $this->process_meta($layout);
        $layout = $this->process_stacks($layout);

        return $layout;
    }

    public function clear_cache()
    {
        $userfiles_dir = userfiles_path();
        $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS . 'apijs');
        if (is_dir($userfiles_cache_dir)) {
            if (function_exists('rmdir_recursive')) {
                rmdir_recursive($userfiles_cache_dir);
            }
        }
    }


    public function stack_add($src, $group = 'default')
    {
        return $this->stack_compiler_adapter->add($src, $group);
    }

    public function stack_display($group = 'default', $to_return = false)
    {
        return $this->stack_compiler_adapter->display($group, $to_return);
    }


    public function process_stacks($layout)
    {
        return $this->stack_compiler_adapter->render($layout);

    }

}
