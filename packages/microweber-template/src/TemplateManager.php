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

use MicroweberPackages\App\Http\Controllers\JsCompileController;
use MicroweberPackages\Template\Adapters\AdminTemplateStyle;
use MicroweberPackages\Template\Adapters\MicroweberTemplate;
use MicroweberPackages\Template\Adapters\RenderHelpers\TemplateOptimizeLoadingHelper;
use MicroweberPackages\Template\Adapters\TemplateCssParser;
use MicroweberPackages\Template\Adapters\TemplateFonts;
use MicroweberPackages\Template\Adapters\TemplateIconFonts;
use MicroweberPackages\Template\Adapters\TemplateStackRenderer;
use MicroweberPackages\Template\Managers\ScriptStyleManager;
use MicroweberPackages\Template\Managers\TemplateMetaTagManager;
use MicroweberPackages\TemplateCustomCss\Services\CustomCssManager;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use MicroweberPackages\Format\Facades\Format;
use MicroweberPackages\Event\Facades\EventManager;
use MicroweberPackages\Template\Concerns\ConfiguresTemplateAdapters;
use MicroweberPackages\Template\Concerns\ReadsTemplateFields;
use MicroweberPackages\Template\Concerns\ManagesTemplateContext;
use MicroweberPackages\Template\Concerns\ManagesStacks;
use MicroweberPackages\Template\Concerns\ManagesCustomCssAndFonts;
use MicroweberPackages\Template\Concerns\ManagesApiJsAssets;
use MicroweberPackages\Template\Concerns\InjectsHeadFoot;
use MicroweberPackages\Template\Concerns\ReadsSiteTemplates;

class TemplateManager
{
    use ConfiguresTemplateAdapters;
    use ReadsTemplateFields;
    use ManagesTemplateContext;
    use ManagesStacks;
    use ManagesCustomCssAndFonts;
    use ManagesApiJsAssets;
    use InjectsHeadFoot;
    use ReadsSiteTemplates;

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
     * @var  $customCssAdapter CustomCssManager
     */
    public $customCssAdapter = null;
    /**
     *
     * @var  $liveEditCssAdapter LiveEditCssManager
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
        // CSS editing lives in microweber-packages/template-custom-css
        $this->setCustomCssAdapter(app(CustomCssManager::class));
        $this->setLiveEditCssAdapter(app(LiveEditCssManager::class));
        $this->setIconFontsAdapter(new TemplateIconFonts());

        // Initialize new manager classes for SRP
        $this->scriptStyleManager = new ScriptStyleManager();
        $this->metaTagManager = new TemplateMetaTagManager();
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

    public function url($add = false)
    {
        $val = $this->templateAdapter->getActiveTemplateUrl();

        if ($add != false) {
            $val = $val . $add;
        }

        return $val;
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

            if (isset($site_host['host']) and Format::is_fqdn($site_host['host'])) {
                $should_replace = true;
                $site_host = $site_host['host'];
            }
            if ($should_replace) {
                if ($static_files_delivery_domain and Format::is_fqdn($static_files_delivery_domain)) {
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

//    public function get_layout_for_laravel_template($page = array())
//    {
//        if (!defined('ACTIVE_TEMPLATE_DIR')) {
//            if (isset($page['id'])) {
//                $this->defineConstants($page);
//            }
//        }
//
//
//        $override = EventManager::trigger('mw.front.get_layout', $page);
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
     * @deprecated Templates and modules are booted from the Laravel modules and Templates service providers
     */
    public function boot()
    {
        return $this->templateAdapter->boot();
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

}
