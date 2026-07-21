<?php


/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Template;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use MicroweberPackages\App\Http\Controllers\JsCompileController;
use MicroweberPackages\Template\Adapters\AdminTemplateStyle;
use MicroweberPackages\Template\Adapters\MicroweberTemplate;
use MicroweberPackages\Template\Adapters\RenderHelpers\TemplateOptimizeLoadingHelper;
use MicroweberPackages\Template\Adapters\TemplateCssParser;
use MicroweberPackages\Template\Adapters\TemplateCustomCss;
use MicroweberPackages\Template\Adapters\TemplateFonts;
use MicroweberPackages\Template\Adapters\TemplateIconFonts;
use MicroweberPackages\Template\Adapters\TemplateLiveEditCss;
use MicroweberPackages\Template\Adapters\TemplateStackRenderer;
use MicroweberPackages\Template\Managers\ScriptStyleManager;
use MicroweberPackages\Template\Managers\TemplateMetaTagManager;


class TemplateManager
{


    /**
     * An instance of the Microweber Application class.
     *
     * @var
     */
    public $app;

    /**
     * @var ScriptStyleManager
     */
    protected $scriptStyleManager;

    /**
     * @var TemplateMetaTagManager
     */
    protected $metaTagManager;

    /**
     * @deprecated Use $scriptStyleManager instead
     */
    public $head = array();

    /**
     * @deprecated Use $scriptStyleManager instead
     */
    public $head_callable = array();

    /**
     * @deprecated Use $scriptStyleManager instead
     */
    public $foot = array();

    /**
     * @deprecated Use $scriptStyleManager instead
     */
    public $foot_callable = array();

    /**
     * @deprecated Use $metaTagManager instead
     */
    public $meta_tags = array();

    /**
     * @deprecated Use $metaTagManager instead
     */
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
     * @var  $iconFontsAdapter TemplateIconFonts
     */

    public $iconFontsAdapter = null;


    /**
     *
     * @var  $customCssAdapter TemplateCustomCss
     */
    public $customCssAdapter = null;
    /**
     *
     * @var  $liveEditCssAdapter TemplateLiveEditCss
     */
    public $liveEditCssAdapter = null;


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

        $this->setTemplateAdapter(new MicroweberTemplate());
        $this->setFontsAdapter(new TemplateFonts());
        $this->setCustomCssAdapter(new TemplateCustomCss());
        $this->setLiveEditCssAdapter(new TemplateLiveEditCss());
        $this->setIconFontsAdapter(new TemplateIconFonts());

        // Initialize new manager classes for SRP
        $this->scriptStyleManager = new ScriptStyleManager();
        $this->metaTagManager = new TemplateMetaTagManager();
    }

    public function setIconFontsAdapter($adapter)
    {
        $this->iconFontsAdapter = $adapter;
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

    public function setLiveEditCssAdapter($adapter)
    {
        $this->liveEditCssAdapter = $adapter;
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

    /**
     * @deprecated Use metaTagManager instead
     */
    public function meta($name, $value = false)
    {
        $this->meta_tags[$name] = $value;
        $this->metaTagManager->setMetaTag($name, $value);
    }

    /**
     * @deprecated Use metaTagManager instead
     */
    public function html_opening_tag($name, $value = false)
    {
        $this->html_opening_tag[$name] = $value;
        $this->metaTagManager->setHtmlAttribute($name, $value);
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

    public function is_laravel_template($template = false)
    {
        return $this->templateAdapter->isLaravelTemplate($template);
    }

    public function get_config($template = false)
    {
        return $this->templateAdapter->getConfig($template);
    }

    public function get_composer_json($template = false): array
    {
        return $this->templateAdapter->getComposerJson($template);
    }

    public function getStyleSettings($templateDir = false)
    {
        return $this->templateAdapter->getStyleSettings($templateDir);
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

    public function getFonts()
    {
        return $this->fontsAdapter->getFonts();
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
        if ($optimize_asset_loading == 1) {

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

            if (isset($site_host['host']) and app()->format->is_fqdn($site_host['host'])) {
                $should_replace = true;
                $site_host = $site_host['host'];
            }
            if ($should_replace) {
                if ($static_files_delivery_domain and app()->format->is_fqdn($static_files_delivery_domain)) {
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
        // $url = mw_includes_url() . 'default.css';
        $url = asset('vendor/microweber-packages/frontend-assets/build/default.css');
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


    public function get_icon_fonts_stylesheet_url()
    {
        return $this->iconFontsAdapter->getIconFontsStylesheetUrl();
    }

    public function clear_cached_custom_css()
    {
        $url = api_url('template/print_custom_css');
        $compile_assets = Config::get('microweber.compile_assets');
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

    /**
     * @internal
     * @deprecated Use scriptStyleManager->admin_head() instead
     */
    public function admin_head($script_src)
    {
        return $this->scriptStyleManager->admin_head($script_src);
    }

    /**
     * @internal
     * @deprecated Use scriptStyleManager->head() instead
     */
    public function head($script_src)
    {
        // Maintain backward compatibility with public properties
        $result = $this->scriptStyleManager->head($script_src);

        if (is_string($script_src)) {
            if (!in_array($script_src, $this->head)) {
                $this->head[] = $script_src;
            }
            if (!in_array($script_src, $this->head_callable)) {
                $this->head_callable = $this->scriptStyleManager->getHeadCallable();
            }
        }

        return $result;
    }

    /**
     * @internal
     * @deprecated Use scriptStyleManager->head_callback() instead
     */
    public function head_callback($data = false)
    {
        return $this->scriptStyleManager->head_callback($data);
    }

    /**
     * @internal
     * @deprecated Use scriptStyleManager->foot() instead
     */
    public function foot($script_src)
    {
        // Maintain backward compatibility with public properties
        $result = $this->scriptStyleManager->foot($script_src);

        if (is_string($script_src)) {
            if (!in_array($script_src, $this->foot)) {
                $this->foot[] = $script_src;
            }
            if (!in_array($script_src, $this->foot_callable)) {
                $this->foot_callable = $this->scriptStyleManager->getFootCallable();
            }
        }

        return $result;
    }

    /**
     * @internal
     * @deprecated Use scriptStyleManager->foot_callback() instead
     */
    public function foot_callback($data = false)
    {
        return $this->scriptStyleManager->foot_callback($data);
    }


//    public function get_layout_for_laravel_template($page = array())
//    {
//        if (!defined('ACTIVE_TEMPLATE_DIR')) {
//            if (isset($page['id'])) {
//                $this->defineConstants($page);
//            }
//        }
//
//
//        $override = app()->event_manager->trigger('mw.front.get_layout', $page);
//
//        $render_file = false;
//        $look_for_post = false;
//        $template_view_set_inner = false;
//        $fallback_render_internal_file = false;
//        $site_template_settings = app()->option_manager->get('current_template', 'template');
//        if (!isset($page['active_site_template'])) {
//            $page['active_site_template'] = 'default';
//        } elseif (isset($page['active_site_template']) and $page['active_site_template'] == '') {
//            $page['active_site_template'] = $site_template_settings;
//        }
//
//
//        $template = app()->templates->find($page['active_site_template']);
//        if ($template) {
//            $template_path = $template->getPath();
//
//            $index_file = $template_path . '/resources/views/index.blade.php';
//            $index_file = normalize_path($index_file, false);
//            if (is_file($index_file)) {
//                $render_file = $index_file;
//            }
//
//        }
//        return $render_file;
//
//    }

    /**
     * Return the path to the layout file that will render the page.
     */
    public function get_layout($params = array())
    {
        return $this->templateAdapter->getLayout($params);

    }

    public function process_meta($layout)
    {
        // Delegate to the new meta tag manager
        // Also maintain backward compatibility with legacy public properties
        if (!empty($this->html_opening_tag)) {
            foreach ($this->html_opening_tag as $key => $item) {
                $this->metaTagManager->setHtmlAttribute($key, $item);
            }
        }
        if (!empty($this->meta_tags)) {
            foreach ($this->meta_tags as $key => $item) {
                $this->metaTagManager->setMetaTag($key, $item);
            }
        }

        return $this->metaTagManager->render($layout);
    }

    /**
     * Renders the file returned by the get_layout method.
     */
    public function render($params = array())
    {
        $layout = $this->templateAdapter->render($params);
        if (isset($params['meta_tags']) and $params['meta_tags']) {
            $layout = $this->process_meta($layout);
            $layout = $this->process_stacks($layout);
        }
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

        $this->customCssAdapter->clearCache();
        $this->fontsAdapter->clearCache();
        $this->iconFontsAdapter->clearCache();


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
     * @return array|false
     *
     * @author    Microweber Dev Team
     *
     * @since     Version 1.0
     */
    public function site_templates($options = [])
    {
        $path = $options['path'] ?? templates_dir();
        $map = directory_map($path, true, true);

        if (!is_array($map) || empty($map)) {
            return false;
        }

        $remove_hidden = $options['remove_hidden_from_install_screen'] ?? false;
        $templates = [];

        foreach ($map as $dir) {
            $template_path = normalize_path($path . DIRECTORY_SEPARATOR . $dir);
            $template_path = rtrim(ltrim($template_path, '.'), '\\');

            if (is_file($template_path) || !is_dir($template_path)) {
                continue;
            }

            $config = $this->get_template_config($template_path);

            if ($config && (!$remove_hidden || empty($config['is_hidden_from_install_screen']))) {
                if (isset($config['name'])) {
                    $config['screenshot'] = $this->get_template_screenshot($config['name']);

                }
                $templates[] = $config;
            }
        }

        return $templates;
    }

    /**
     * Get the template configuration file.
     *
     * @param string $template_path
     * @param string $dir
     * @return array|false
     */
    public function get_template_config($template_path)
    {

        $legacy_config = normalize_path($template_path, true) . 'config.php';
        $new_config = normalize_path($template_path, true) . 'config' . DS . 'config.php';
        $dir = basename($template_path);
        if (is_file($new_config)) {
            $config = include $new_config;
            $config['dir_name'] = $dir;
            $config['is_legacy'] = false;

            $config['template_type'] = 'laravel';
        } elseif (is_file($legacy_config)) {
            include $legacy_config;
            $config['dir_name'] = $dir;
            $config['is_legacy'] = true;
            $config['template_type'] = 'legacy';

            $config['is_symlink'] = is_link(normalize_path($template_path, false));
        } else {
            return false;
        }

        return $config;
    }

    /**
     * Get the screenshot file for the template.
     *
     * @param string $name
     * @return string|false
     */
    public function get_template_screenshot($activeTemplate)
    {

        $checkIfActiveSiteTemplate = app()->templates->find($activeTemplate);
        if ($checkIfActiveSiteTemplate) {
            $checkIfActiveSiteTemplateLowerName = $checkIfActiveSiteTemplate->getLowerName();

            $basePath = normalize_path(public_path());

            $jpg_screenshot = normalize_path($basePath . 'templates' . DS . $checkIfActiveSiteTemplateLowerName . DS . 'screenshot.jpg', false);
            $png_screenshot = normalize_path($basePath . 'templates' . DS . $checkIfActiveSiteTemplateLowerName . DS . 'screenshot.png', false);

            if (is_file($png_screenshot)) {
                return asset('templates/' . $checkIfActiveSiteTemplateLowerName . '/screenshot.png');
            } elseif (is_file($jpg_screenshot)) {
                return asset('templates/' . $checkIfActiveSiteTemplateLowerName . '/screenshot.jpg');
            } else {
                return false;
            }

        }


        return false;
    }


    /**
     * @deprecated Templates and modules are booted from the Laravel modules and Templates service providers
     */
    public function boot()
    {
        return $this->templateAdapter->boot();
    }

    public function defineConstants($content = false)
    {
        return $this->templateAdapter->defineConstants($content);
    }

    public function defineTemplateConstants(): void
    {
        $this->templateAdapter->defineTemplateConstants();
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

    public function getParentTemplate($templateName = false)
    {
        return $this->templateAdapter->getParentTemplate($templateName);
    }

    public function getConfig($templateName = false)
    {
        return $this->get_config($templateName);
    }


    public $isBooted = false;


    public function boot_template()
    {
        if (!mw_is_installed()) {
            return;
        }
        if ($this->isBooted) {
            return;
        }
        $this->isBooted = true;
        //load_service_providers_for_template();

        // load_functions_files_for_template();
//        $load_template_functions = TEMPLATE_DIR . 'functions.php';
//
//        if (is_file($load_template_functions)) {
//            include_once $load_template_functions;
//        }

// //moved to load_all_service_providers_for_modules function
//        $module = app()->template_manager->get_config();
//
//        if (isset($module['settings']) and $module['settings'] and isset($module['settings']['service_provider']) and $module['settings']['service_provider']) {
//
//            app()->module_manager->boot_module($module);
//        }
    }


}
