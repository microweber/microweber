<?php


namespace MicroweberPackages\Template;

use Illuminate\Support\Str;
use MicroweberPackages\App\Http\Controllers\JsCompileController;
use MicroweberPackages\Template\Adapters\AdminTemplateStyle;
use MicroweberPackages\Template\Adapters\MicroweberTemplate;
use MicroweberPackages\Template\Adapters\RenderHelpers\TemplateOptimizeLoadingHelper;
use MicroweberPackages\Template\Adapters\TemplateCssParser;
use MicroweberPackages\Template\Adapters\TemplateCustomCss;
use MicroweberPackages\Template\Adapters\TemplateFonts;
use MicroweberPackages\Template\Adapters\TemplateStackRenderer;

/**
 * Class to render the frontend template and views.
 *
 * @category Template
 * @desc    These functions will allow to wo work with microweber template files.
 *
 * @property \MicroweberPackages\Template\Adapters\MicroweberTemplate $templateAdapter
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

    /**
     *
     * @var  $templateAdapter MicroweberTemplate
     */
    public $templateAdapter = null;

    /**
     *
     * @var  $fontsAdapter TemplateFonts
     */
    public $fontsAdapter = null;


    /**
     *
     * @var  $customCssAdapter TemplateCustomCss
     */
    public $customCssAdapter = null;


    public $adapter_default = null;
    public $admin = null;
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

        $this->admin = new AdminTemplateStyle($app);
        $this->admin = new AdminTemplateStyle($app);

        $this->setTemplateAdapter(new MicroweberTemplate());
        $this->setFontsAdapter(new TemplateFonts());
        $this->setCustomCssAdapter(new TemplateCustomCss());
    }

    public function setTemplateAdapter($adapter)
    {
        $this->templateAdapter = $adapter;
    }

    public function setFontsAdapter($adapter)
    {
        $this->fontsAdapter = $adapter;
    }

    public function setCustomCssAdapter($adapter)
    {
        $this->customCssAdapter = $adapter;
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

    public function append_livewire_to_layout($layout)
    {
        $alpineUrl = mw_includes_url() . 'api/libs/alpine/alpine.min.js';

        $alpineScript = '<script src="' . $alpineUrl . '" defer></script>';

        $scripts = \Livewire\Livewire::scripts();
        $styles = \Livewire\Livewire::styles();
        $modal = \Livewire\Livewire::mount('livewire-ui-modal')->html();


        $layout = Str::replaceFirst('<head>', '<head>' . $alpineScript, $layout);
        $layout = Str::replaceFirst('</head>', $styles . '</head>', $layout);
        $layout = Str::replaceFirst('</head>', $scripts . '</head>', $layout);
        $layout = Str::replaceFirst('</head>', $modal . '</head>', $layout);


        return $layout;
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

    /**
     * @deprecated
     */
    public function meta($name, $value = false)
    {
        $this->meta_tags[$name] = $value;
    }

    /**
     * @deprecated
     */
    public function html_opening_tag($name, $value = false)
    {
        $this->html_opening_tag[$name] = $value;
    }

    public function folder_name()
    {
        return $this->templateAdapter->getTemplateFolderName();

    }

    public function name()
    {
        return $this->templateAdapter->getTemplateFolderName();
    }


    public function dir($add = false)
    {
        if ($this->templateAdapter->getActiveTemplateDir()) {
            $val = $this->templateAdapter->getActiveTemplateDir();
            if ($add != false) {
                $val = $val . $add;
            }
            return $val;
        }

    }


    public function get_config($template = false)
    {
        return $this->templateAdapter->getConfig($template);
    }

    public function get_data_field_title($field, $type = 'general')
    {
        $fieldTitle = '';
        $dataFields = $this->get_data_fields($type);

        foreach ($dataFields as $dataFieldKey => $dataFieldTitle) {
            if ($field == $dataFieldKey) {
                $fieldTitle = $dataFieldTitle;
                break;
            }
        }
        return $fieldTitle;
    }

    public function get_data_fields($type = 'general')
    {
        $templateConfig = $this->get_config();

        $dataFields = [];
        if (isset($templateConfig['data-fields-' . $type]) && !empty($templateConfig['data-fields-' . $type])) {
            foreach ($templateConfig['data-fields-' . $type] as $templateField) {
                $dataFields[$templateField['name']] = $templateField['title'];
            }
        }

        return $dataFields;
    }

    public function get_edit_field_title($field, $type = 'general')
    {
        $fieldTitle = '';
        $editFields = $this->get_edit_fields($type);
        foreach ($editFields as $editFieldKey => $editFieldTitle) {
            if ($field == $editFieldKey) {
                $fieldTitle = $editFieldTitle;
                break;
            }
        }
        return $fieldTitle;
    }

    public function get_edit_fields($type = 'general')
    {
        $templateConfig = $this->get_config();

        $editFields = [];
        if (isset($templateConfig['edit-fields-' . $type]) && !empty($templateConfig['edit-fields-' . $type])) {
            foreach ($templateConfig['edit-fields-' . $type] as $templateField) {
                $editFields[$templateField['name']] = $templateField['title'];
            }
        }

        return $editFields;
    }

    public function url($add = false)
    {
        $val = $this->templateAdapter->getActiveTemplateUrl();

        if ($add != false) {
            $val = $val . $add;
        }

        return $val;
    }


    public function get_custom_fonts_css_content()
    {
        return $this->fontsAdapter->getFontsStylesheetCss();
    }

    public function get_custom_fonts_css_url()
    {
        return $this->fontsAdapter->getFontsStylesheetCssUrl();
    }

    public function get_custom_css_content()
    {
        return $this->customCssAdapter->getCustomCssContent();
    }


    public function get_custom_css()
    {
        return $this->customCssAdapter->getCustomCss();

    }

    public function get_custom_css_url()
    {
        return $this->customCssAdapter->getCustomCssUrl();
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


    public function get_default_system_ui_css_url()
    {
        $url = mw_includes_url() . 'default.css';
        return $url;
    }


    public function get_admin_supported_themes()
    {
        return $this->admin->getAdminTemplates();
    }

    public function get_admin_supported_theme_scss_vars($theme)
    {
        if (!$theme) {
            return;
        }
        return $this->admin->getAdminTemplateVars($theme);
    }

    public function get_admin_system_ui_css_url()
    {
        return $this->admin->getAdminCssUrl();
    }

    public function clear_cached_custom_css()
    {
        $url = api_nosession_url('template/print_custom_css');
        $compile_assets = \Config::get('microweber.compile_assets');
        $userfiles_dir = userfiles_path();
        $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);
        $userfiles_cache_filename = $userfiles_cache_dir . 'custom_css.' . crc32(site_url()) . '.' . MW_VERSION . '.css';
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
        return $this->templateAdapter->getLayout($params);

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
        $layout = $this->templateAdapter->render($params);

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

    /**
     * @deprecated
     */
    public function stack_add($src, $group = 'default')
    {
        return $this->stack_compiler_adapter->add($src, $group);
    }

    /**
     * @deprecated
     */
    public function stack_display($group = 'default', $to_return = false)
    {
        return $this->stack_compiler_adapter->display($group, $to_return);
    }

    /**
     * @deprecated
     */
    public function process_stacks($layout)
    {
        return $this->stack_compiler_adapter->render($layout);

    }


    /**
     * @desc      Get the template layouts info under the layouts subdir on your active template
     *
     * @param $options
     * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
     *
     * @return array
     *
     * @author    Microweber Dev Team
     *
     * @since     Version 1.0
     */
    public function site_templates($options = false)
    {


        if (!isset($options['path'])) {
            $path = templates_dir();
        } else {
            $path = $options['path'];
        }

        $path_to_layouts = $path;
        $layout_path = $path;
        $map = directory_map($path, true, true);
        $to_return = array();
        if (!is_array($map) or empty($map)) {
            return false;
        }

        $remove_hidden_from_install_screen = false;
        if (isset($options['remove_hidden_from_install_screen']) and $options['remove_hidden_from_install_screen']) {
            $remove_hidden_from_install_screen = true;

        }

        foreach ($map as $dir) {
            //$filename = $path . $dir . DIRECTORY_SEPARATOR . 'layout.php';
            $filename = $path . DIRECTORY_SEPARATOR . $dir;
            $filename_location = false;
            $filename_dir = false;
            $filename = normalize_path($filename);
            $filename = rtrim($filename, '\\');
            $filename = (substr($filename, 0, 1) === '.' ? substr($filename, 1) : $filename);
            if (!is_file($filename) and is_dir($filename)) {
                $skip = false;
                $fn1 = normalize_path($filename, true) . 'config.php';
                $fn2 = normalize_path($filename);
                if (is_file($fn1)) {
                    $config = false;
                    include $fn1;
                    if (!empty($config)) {
                        $config['is_symlink'] = false;

                        if (is_link(normalize_path($filename, false))) {
                            $config['is_symlink'] = true;
                        }

                        $c = $config;
                        $c['dir_name'] = $dir;


                        $screensshot_file = $fn2 . '/screenshot.jpg';
                        $screensshot_file = normalize_path($screensshot_file, false);

                        $screensshot_file_png = $fn2 . '/screenshot.png';
                        $screensshot_file_png = normalize_path($screensshot_file_png, false);

                        if (is_file($screensshot_file)) {
                            $c['screenshot'] = $this->app->url_manager->link_to_file($screensshot_file);
                        } elseif (is_file($screensshot_file_png)) {
                            $c['screenshot'] = $this->app->url_manager->link_to_file($screensshot_file_png);
                        }

                        if ($remove_hidden_from_install_screen) {
                            if (isset($c['is_hidden_from_install_screen']) and $c['is_hidden_from_install_screen']) {
                                $skip = true;
                            }
                        }

                        if (!$skip) {
                            $to_return[] = $c;
                        }
                    }
                } else {
                    $filename_dir = false;
                }
                //	$path = $filename;
            }
        }

        return $to_return;
    }

    public function boot()
    {
        return $this->templateAdapter->boot();
    }

    public function defineConstants($content = false)
    {
        return $this->templateAdapter->defineConstants($content);
    }

    public function getContentId()
    {
        return $this->templateAdapter->getContentId();
    }

    public function getProductId()
    {
        return $this->templateAdapter->getProductId();
    }

    public function getPageId()
    {
        return $this->templateAdapter->getPageId();
    }

    public function getPostId()
    {
        return $this->templateAdapter->getPostId();
    }

    public function getCategoryId()
    {
        return $this->templateAdapter->getCategoryId();
    }

    public function getMainPageId()
    {
        return $this->templateAdapter->getMainPageId();
    }

}
