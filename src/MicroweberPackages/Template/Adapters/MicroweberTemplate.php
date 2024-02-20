<?php

namespace MicroweberPackages\Template\Adapters;

use MicroweberPackages\Template\Adapters\RenderHelpers\TemplateMetaTagsRenderer;
use MicroweberPackages\Template\Http\Livewire\Admin\StyleSettingsFirstLevelConvertor;
use MicroweberPackages\View\View;

class MicroweberTemplate
{

    public string $templateFolderName = '';
    public string $templatePath = '';
    public string $templateUrl = '';
    public string $fallbackTempleteFolderName = 'default';
    protected int $mainPageId = 0;
    protected int $postId = 0;
    protected int $categoryId = 0;
    protected int $contentId = 0;
    protected int $productId = 0;
    protected int $rootPageId = 0;
    protected int $parentPageId = 0;
    protected int $pageId = 0;
    public bool $isBooted = false;

    /**
     * Boot the service providers defined in the config
     */
    public function boot()
    {
        if ($this->isBooted) {
            return;
        }

        if (!$this->templateFolderName) {
            return;
        }
        $this->isBooted = true;

        // Load template service providers defined in the config
        $templateConfig = $this->getConfig();

        if ($templateConfig and isset($templateConfig['settings']['service_provider'])) {

            app()->module_manager->boot_module($templateConfig);
        }
    }

    /**
     * @return int
     */
    public function getContentId(): int
    {


        return $this->contentId;
    }

    /**
     * @param int $contentId
     */
    public function setContentId(int $contentId): void
    {
        $this->contentId = $contentId;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getPageId(): int
    {
        return $this->pageId;
    }

    /**
     * @param int $page_id
     */
    public function setPageId(int $page_id): void
    {
        $this->pageId = $page_id;
    }

    /**
     * @return int
     */
    public function getMainPageId(): int
    {
        return $this->mainPageId;
    }

    /**
     * @param int $mainPageId
     */
    public function setMainPageId(int $mainPageId): void
    {
        $this->mainPageId = $mainPageId;
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * @param int $postId
     */
    public function setPostId(int $postId): void
    {
        $this->postId = $postId;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return string
     */
    public function getTemplateFolderName(): string
    {
        if (!isset($this->templateFolderName) or !$this->templateFolderName) {
            $option = app()->option_manager->get('current_template', 'template');
            if ($option) {
                $this->templateFolderName = $option;
            }
        }

        return $this->templateFolderName;
    }

    public function render($params = array())
    {
        $render_file = $params['render_file'];
        $l = new View($render_file);
        $l->page_id = $params['page_id'];
        $l->content_id = $params['content_id'];
        $l->post_id = $params['post_id'];
        // $l->categoryId = $params['categoryId'];

        if (isset($params['content'])) {
            $l->content = $params['content'];
        }
        if (isset($params['category_id'])) {
            $l->category_id = $params['category_id'];
        }

        if (isset($params['category'])) {
            $l->category = $params['category'];
        }
        // $l->category = $params['category'];
        $l->page = $params['page'];
        $l->application = app();

        if (!empty($params)) {
            foreach ($params as $k => $v) {
                $l->assign($k, $v);
            }
        }

        $l = $l->__toString();

        if (isset($params['meta_tags']) and $params['meta_tags']) {
            $params['layout'] = $l;
            $meta_tags_render = new TemplateMetaTagsRenderer();
            $l = $meta_tags_render->render($params);
        }

        return $l;
    }

    /**
     * Get the active template url.
     * @return string
     */
    public function getActiveTemplateUrl(): string
    {
        return templates_url() . $this->getTemplateFolderName() . '/';
    }

    /**
     * Get the active template directory.
     * @return string
     */
    public function getActiveTemplateDir(): string
    {
        return templates_dir() . $this->getTemplateFolderName() . DS;
    }

    /**
     * Get the fallback template directory.
     * @return string
     */
    public function getFallbackTemplateDir(): string
    {
        return templates_dir() . $this->fallbackTempleteFolderName . DS;
    }

    public function setVariablesFromContent($content = false)
    {


        $page = false;
        $content_orig = $content;

        if (is_array($content)) {
            if (!isset($content['active_site_template']) and isset($content['id']) and $content['id'] != 0) {
                $content = app()->content_manager->get_by_id($content['id']);
                $page = $content;
            } elseif (isset($content['id']) and $content['id'] == 0) {
                $content = app()->content_manager->get_by_id($content['id']);
                $page = $content;
            } elseif (isset($content['active_site_template'])) {
                $page = $content;
            }

            if ($page == false) {
                $page = $content;
            }
        }

        if (is_array($page)) {
            if (!empty($page)) {
                if (isset($page['content_type']) and $page['content_type'] != 'page') {
                    if (isset($page['id']) && $page['id'] != 0) {
                        $content = $page;
                        $this->contentId = intval($content['id']);
                        $contentCategories = app()->category_manager->get_for_content($page['id']);
                        if (!empty($contentCategories)) {
                            $current_category = reset($contentCategories);
                            if (isset($current_category['id'])) {
                                $this->categoryId = intval($current_category['id']);
                            }
                        }

                        $page = $parentPage = app()->content_manager->get_by_id($content['parent']);

                        if (isset($parentPage['id']) && $parentPage['id']) {
                            $this->pageId = intval($parentPage['id']);
                        }


                        $this->postId = intval($content['id']);
                        $this->productId = 0;

                        if (is_array($content) && $content['content_type'] == 'product') {
                            $this->productId = intval($content['id']);
                            $this->postId = 0;
                        }
                    } else {
                        $content = $page;
//                        if (isset($content['id'])) {
//                            $this->pageId = intval($content['id']);
//                        }
                    }
                } else {
                    $content = $page;
                    if (isset($content['id'])) {
                        $this->pageId = intval($content['id']);
                        $this->contentId = intval($content['id']);
                    }
                    if (isset($page['content_type']) and $content['content_type'] == 'page' && isset($content['id'])) {
                        $this->pageId = intval($content['id']);
                        $this->contentId = intval($content['id']);
                    } elseif (isset($page['content_type']) and $content['content_type'] != 'page' && isset($content['id'])) {
                        if (isset($content['parent']) && $content['parent']) {
                            $this->pageId = intval($content['parent']);
                        }
                        if ($content['content_type'] == 'post') {
                            $this->postId = intval($content['id']);
                        }
                        if ($content['content_type'] == 'product') {
                            $this->productId = intval($content['id']);
                        }
                    }
                }

                if (isset($page['parent']) && $page['parent']) {
                    $parent_page_check_if_inherited = app()->content_manager->get_by_id($page['parent']);

                    if (isset($parent_page_check_if_inherited['layout_file']) && $parent_page_check_if_inherited['layout_file'] == 'inherit') {
                        $inherit_from_id = app()->content_manager->get_inherited_parent($parent_page_check_if_inherited['id']);

                        if ($inherit_from_id) {
                            $this->mainPageId = intval($inherit_from_id);
                        }

                    }

                    $root_page = app()->content_manager->get_parents($page['id']);
                    if (!empty($root_page) && isset($root_page[0])) {
                        $root_page[0] = end($root_page);
                    } else {
                        $root_page[0] = $page['parent'];
                    }
                    if (isset($root_page[0])) {
                        $this->rootPageId = intval($root_page[0]);
                    }

                    if (!$this->mainPageId) {
                        if ($page['parent'] == 0) {
                            $this->mainPageId = intval($page['id']);
                        } else {
                            $this->mainPageId = intval($page['parent']);
                        }
                    }
                    if (!$this->parentPageId && isset($content['parent'])) {
                        $this->parentPageId = $content['parent'];
                    }
                }
            }
        }


        if (isset($page) and isset($page['active_site_template']) and $page['active_site_template'] != '' and strtolower($page['active_site_template']) != 'inherit' and strtolower($page['active_site_template']) != 'default') {
            $the_active_site_template = $page['active_site_template'];
        } elseif (isset($page) and isset($page['active_site_template']) and ($page['active_site_template']) != '' and strtolower($page['active_site_template']) != 'default') {
            $the_active_site_template = $page['active_site_template'];
        } elseif (isset($content) and isset($content['active_site_template']) and ($content['active_site_template']) != '' and strtolower($content['active_site_template']) != 'default') {
            $the_active_site_template = $content['active_site_template'];
        } elseif (isset($content_orig) and !isset($content_orig['id']) and isset($content_orig['active_site_template']) and ($content_orig['active_site_template']) != '' and strtolower($content_orig['active_site_template']) != 'default' and strtolower($content_orig['active_site_template']) != 'inherit') {
            $the_active_site_template = $content_orig['active_site_template'];
        } else {
            $the_active_site_template = app()->option_manager->get('current_template', 'template');
            //
        }


        if (isset($content['parent']) and $content['parent'] != 0 and isset($content['layout_file']) and $content['layout_file'] == 'inherit') {
            $inh = app()->content_manager->get_inherited_parent($content['id']);
            if ($inh != false) {
                $inh_parent = app()->content_manager->get_by_id($inh);
                if (isset($inh_parent['active_site_template']) and ($inh_parent['active_site_template']) != '' and strtolower($inh_parent['active_site_template']) != 'default') {
                    $the_active_site_template = $inh_parent['active_site_template'];
                } elseif (isset($inh_parent['active_site_template']) and ($inh_parent['active_site_template']) != '' and strtolower($inh_parent['active_site_template']) == 'default') {
                    $the_active_site_template = app()->option_manager->get('current_template', 'template');
                } elseif (isset($inh_parent['active_site_template']) and ($inh_parent['active_site_template']) == '') {
                    $the_active_site_template = app()->option_manager->get('current_template', 'template');
                }
            }
        }

        if (isset($the_active_site_template) and $the_active_site_template != 'default' and $the_active_site_template == 'mw_default') {
            $the_active_site_template = 'default';
        }

        if ($the_active_site_template == false) {
            $the_active_site_template = 'default';
        }

        $this->templateFolderName = $the_active_site_template;


        return true;
    }


    public $templateConfigCache = array();


    public function getConfig($template = false)
    {
        if ($template == false) {

            $dir = $this->getActiveTemplateDir();

            $file = $dir . 'config.php';

            if (isset($this->templateConfigCache[$file])) {
                return $this->templateConfigCache[$file];
            }

            if (is_file($file)) {
                include $file;
                if (isset($config)) {
                    $config['dir_name'] = basename($dir);
                    if (is_link(normalize_path($dir, false))) {
                        $config['is_symlink'] = true;
                    } else {
                        $config['is_symlink'] = false;
                    }
                    $this->templateConfigCache[$file] = $config;
                    return $config;
                }

                return false;
            }
        }
    }

    /**
     * Return the path to the layout file that will render the page.
     */
    public function getLayout($page = array()): string
    {
        $function_cache_id = '';
        if (is_array($page)) {
            ksort($page);
        }

        if (!defined('ACTIVE_TEMPLATE_DIR')) {
            if (isset($page['id'])) {
                $this->defineConstants($page);
            }
        }


        $override = app()->event_manager->trigger('mw.front.get_layout', $page);

        $render_file = false;
        $look_for_post = false;
        $template_view_set_inner = false;
        $fallback_render_internal_file = false;
        $site_template_settings = app()->option_manager->get('current_template', 'template');
        if (!isset($page['active_site_template'])) {
            $page['active_site_template'] = 'default';
        } elseif (isset($page['active_site_template']) and $page['active_site_template'] == '') {
            $page['active_site_template'] = $site_template_settings;
        }

        if ($page['active_site_template'] and ($page['active_site_template'] == 'default' or $page['active_site_template'] == 'mw_default')) {
            if ($site_template_settings != 'default' and $page['active_site_template'] == 'mw_default') {
                $page['active_site_template'] = 'default';
                $site_template_settings = 'default';
            }
            if ($site_template_settings != false) {
                $site_template_settings = sanitize_path($site_template_settings);
                $site_template_settings_dir = templates_dir() . $site_template_settings . DS;
                if (is_dir($site_template_settings_dir) != false) {
                    $page['active_site_template'] = $site_template_settings;
                }
            }
        }
        if (isset($page['content_type'])) {
            $page['content_type'] = sanitize_path($page['content_type']);
        }

        if (isset($page['subtype'])) {
            $page['subtype'] = sanitize_path($page['subtype']);
        }
        if (isset($page['layout_file'])) {
            $page['layout_file'] = sanitize_path($page['layout_file']);
        }
        if (isset($page['active_site_template'])) {
            $page['active_site_template'] = sanitize_path($page['active_site_template']);
        }
        if (is_array($override)) {
            foreach ($override as $resp) {
                if (isset($resp['render_file']) and ($resp['render_file']) != false) {
                    $render_file = $resp['render_file'];
                } elseif (is_array($resp) and !empty($resp)) {
                    $page = array_merge($page, $resp);
                }
            }
        }

        if ($render_file == false) {
            if (isset($page['active_site_template']) and isset($page['layout_file'])) {
                $page['layout_file'] = str_replace('___', DS, $page['layout_file']);
                $page['layout_file'] = str_replace('__', DS, $page['layout_file']);
                $page['layout_file'] = sanitize_path($page['layout_file']);

                $template_d = $page['active_site_template'];
                if ($template_d == 'mw_default') {
                    $template_d = 'default';
                }

                $render_file_temp = normalize_path(templates_dir() . $template_d . DS . $page['layout_file'], false);
                $render_use_default = normalize_path(templates_dir() . $template_d . DS . 'use_default_layouts.php', false);

                $render_file_module_temp = modules_path() . DS . $page['layout_file'];
                $render_file_module_temp = normalize_path($render_file_module_temp, false);
                if (is_file($render_file_temp)) {
                    $render_file = $render_file_temp;
                } elseif (is_file($render_file_module_temp)) {
                    $render_file = $render_file_module_temp;
                } elseif (is_file($render_use_default)) {
                    $render_file_temp = $this->getFallbackTemplateDir() . $page['layout_file'];
                    if (is_file($render_file_temp)) {
                        $render_file = $render_file_temp;
                    }
                }
            }
        }

        if ($render_file == false and isset($page['content_type']) and isset($page['parent']) and ($page['content_type']) != 'page') {
            $get_layout_from_parent = false;
            $par = app()->content_manager->get_by_id($page['parent']);

            if (isset($par['layout_file']) and $par['layout_file'] != '' and $par['layout_file'] != 'inherit') {
                $get_layout_from_parent = $par;

            } elseif (isset($par['is_home']) and isset($par['active_site_template']) and (!isset($par['layout_file']) or $par['layout_file'] == '') and $par['is_home'] == 'y') {
                $par['layout_file'] = 'index.php';
                $get_layout_from_parent = $par;

            } else {
                $inh = app()->content_manager->get_inherited_parent($page['parent']);

                if ($inh != false) {
                    $par = app()->content_manager->get_by_id($inh);
                    if (isset($par['active_site_template']) and isset($par['layout_file']) and $par['layout_file'] != '') {
                        $get_layout_from_parent = $par;
                    } elseif (isset($par['active_site_template']) and isset($par['is_home']) and $par['is_home'] == 'y' and (!isset($par['layout_file']) or $par['layout_file'] == '')) {
                        $par['layout_file'] = 'index.php';
                        $get_layout_from_parent = $par;
                    }
                }
            }

            if (isset($get_layout_from_parent['layout_file'])) {
                if (!isset($get_layout_from_parent['active_site_template'])) {
                    $get_layout_from_parent['active_site_template'] = 'default';
                }
                if ($get_layout_from_parent['active_site_template'] == 'default') {
                    $get_layout_from_parent['active_site_template'] = $site_template_settings;
                }
                if ($get_layout_from_parent['active_site_template'] == 'mw_default') {
                    $get_layout_from_parent['active_site_template'] = 'default';
                }
                $get_layout_from_parent['layout_file'] = str_replace('___', DS, $get_layout_from_parent['layout_file']);
                $get_layout_from_parent['layout_file'] = sanitize_path($get_layout_from_parent['layout_file']);
                $render_file_temp = templates_dir() . $get_layout_from_parent['active_site_template'] . DS . $get_layout_from_parent['layout_file'];
                $render_file_temp2 = templates_dir() . $get_layout_from_parent['active_site_template'] . DS . 'layouts' . DS . $get_layout_from_parent['layout_file'];
                $render_use_default = templates_dir() . $get_layout_from_parent['active_site_template'] . DS . 'use_default_layouts.php';
                $render_file_temp = normalize_path($render_file_temp, false);
                $render_use_default = normalize_path($render_use_default, false);

                $render_file_module_temp = modules_path() . DS . $get_layout_from_parent['layout_file'];
                $render_file_module_temp = normalize_path($render_file_module_temp, false);


                //if (!isset($page['content_type']) or $page['content_type'] == 'page') {
                if (is_file($render_file_temp)) {
                    $render_file = $render_file_temp;
                } else if (is_file($render_file_temp2)) {
                    $render_file = $render_file_temp2;
                } elseif (is_file($render_use_default)) {
                    $render_file_temp = $this->getFallbackTemplateDir() . $get_layout_from_parent['layout_file'];
                    if (is_file($render_file_temp)) {
                        $render_file = $render_file_temp;
                    }
                } elseif (is_file($render_file_module_temp)) {
                    $render_file = $render_file_module_temp;
                }
            }

        }

        if ($render_file == false and !isset($page['active_site_template']) and isset($page['layout_file'])) {
            $test_file = str_replace('___', DS, $page['layout_file']);
            $test_file = sanitize_path($test_file);

            $render_file_temp = $test_file;
            $render_file_temp = normalize_path($render_file_temp, false);

            if (is_file($render_file_temp)) {
                $render_file = $render_file_temp;
            }
        }

        if ($render_file == false and isset($page['active_site_template']) and isset($page['active_site_template']) and isset($page['layout_file']) and $page['layout_file'] != 'inherit' and $page['layout_file'] != '') {
            $test_file = str_replace('___', DS, $page['layout_file']);
            $test_file = sanitize_path($test_file);

            $render_file_temp = templates_dir() . $page['active_site_template'] . DS . $test_file;
            $render_file_module_temp = modules_path() . DS . $test_file;
            $render_file_module_temp = normalize_path($render_file_module_temp, false);

            if (is_file($render_file_temp)) {
                $render_file = $render_file_temp;
            } elseif (is_file($render_file_module_temp)) {
                $render_file = $render_file_module_temp;
            }
        }


        if (($render_file == false)
            and isset($page['id']) and $page['id'] == 0) {
            $url_file = app()->url_manager->string(1, 1);
            $test_file = str_replace('___', DS, $url_file);
            $test_file = sanitize_path($test_file);
            $render_file_temp = $this->getActiveTemplateDir() . $test_file . '.php';
            $render_file_temp2 = $this->getActiveTemplateDir() . $test_file . '.php';
            $render_file_temp3 = $this->getActiveTemplateDir() . 'layouts' . DS . $test_file . '.php';

            if (is_file($render_file_temp)) {
                $render_file = $render_file_temp;
            } elseif (is_file($render_file_temp2)) {
                $render_file = $render_file_temp2;
            } elseif (is_file($render_file_temp3)) {

                $render_file = $render_file_temp3;
            }
        }

        if (isset($page['active_site_template']) and $page['active_site_template'] == 'default') {
            $page['active_site_template'] = $site_template_settings;
        }

        if (isset($page['active_site_template']) and $page['active_site_template'] != 'default' and $page['active_site_template'] == 'mw_default') {
            $page['active_site_template'] = 'default';
        }

        if ($render_file == false and isset($page['id']) and isset($page['active_site_template']) and isset($page['layout_file']) and ($page['layout_file'] != 'inherit')) {
            $render_file_temp = templates_dir() . $page['active_site_template'] . DS . $page['layout_file'];
            $render_file_temp = normalize_path($render_file_temp, false);
            if (is_file($render_file_temp)) {
                $render_file = $render_file_temp;
            } else {
                $render_file_temp = $this->getFallbackTemplateDir() . $page['layout_file'];
                if (is_file($render_file_temp)) {
                    $render_file = $render_file_temp;
                }
            }
        }

        if ($render_file == false and isset($page['id']) and isset($page['active_site_template']) and
            (!isset($page['layout_file']) or (isset($page['layout_file']) and ($page['layout_file'] == 'inherit')) or $page['layout_file'] == false)) {


            $inherit_from = app()->content_manager->get_parents($page['id']);
            $found = 0;
            if ($inherit_from == false) {
                if (isset($page['parent']) and $page['parent'] != false) {
                    $par_test = app()->content_manager->get_by_id($page['parent']);

                    if (is_array($par_test)) {
                        $inherit_from = array();
                        if (isset($page['layout_file']) and ($page['layout_file'] != 'inherit')) {
                            $inherit_from[] = $page['parent'];
                        } else {
                            $inh = app()->content_manager->get_inherited_parent($page['parent']);
                            $inherit_from[] = $inh;
                        }
                    }
                }
            }


            if (!empty($inherit_from)) {
                foreach ($inherit_from as $value) {
                    if ($found == 0 and $value != $page['id']) {

                        $par_c = app()->content_manager->get_by_id($value);
                        if (isset($par_c['id']) and isset($par_c['layout_file']) and $par_c['layout_file'] != 'inherit') {

                            if (!isset($par_c['active_site_template'])) {
                                if (isset($get_layout_from_parent) and isset($get_layout_from_parent['active_site_template'])) {
                                    $par_c['active_site_template'] = $get_layout_from_parent['active_site_template'];
                                }
                            }
                            if (!isset($par_c['active_site_template'])) {
                                continue;
                                //$par_c['active_site_template'] = ACTIVE_TEMPLATE_DIR;
                            }
                            $page['layout_file'] = $par_c['layout_file'];
                            $page['layout_file'] = str_replace('__', DS, $page['layout_file']);
                            $page['active_site_template'] = $par_c['active_site_template'];

                            $page['active_site_template'] = sanitize_path($page['active_site_template']);
                            if ($page['active_site_template'] == 'default') {
                                $page['active_site_template'] = $site_template_settings;
                            }

                            if ($page['active_site_template'] != 'default' and $page['active_site_template'] == 'mw_default') {
                                $page['active_site_template'] = 'default';
                            }


                            $render_file_temp = templates_dir() . $page['active_site_template'] . DS . $page['layout_file'];
                            $render_file_temp = normalize_path($render_file_temp, false);
                            $render_file_module_temp = modules_path() . DS . $page['layout_file'];
                            $render_file_module_temp = normalize_path($render_file_module_temp, false);

                            if (is_file($render_file_temp)) {
                                $render_file = $render_file_temp;
                            } elseif (is_file($render_file_module_temp)) {
                                $render_file = $render_file_module_temp;
                            } else {
                                $render_file_temp = $this->getFallbackTemplateDir() . $page['layout_file'];
                                if (is_file($render_file_temp)) {
                                    $fallback_render_internal_file = $render_file_temp;
                                }
                            }

                            $found = 1;
                        }
                    }
                }
            }
        }
        if ($render_file != false and (isset($page['content_type']) and ($page['content_type']) != 'page')) {
            $f1 = $render_file;
            $f2 = $render_file;

            $stringA = $f1;
            $stringB = '_inner';
            $length = strlen($stringA);
            $temp1 = substr($stringA, 0, $length - 4);
            $temp2 = substr($stringA, $length - 4, $length);
            $f1 = $temp1 . $stringB . $temp2;
            $f1 = normalize_path($f1, false);

            if (is_file($f1)) {
                $render_file = $f1;
            } else {
                $stringA = $f2;
                $stringB = '_' . $page['content_type'];
                $length = strlen($stringA);
                $temp1 = substr($stringA, 0, $length - 4);
                $temp2 = substr($stringA, $length - 4, $length);
                $f3 = $temp1 . $stringB . $temp2;
                $f3 = normalize_path($f3, false);

                if (is_file($f3)) {
                    $render_file = $f3;
                } else {
                    $found_subtype_layout = false;
                    if (isset($page['subtype'])) {
                        $stringA = $f2;
                        $stringB = '_' . $page['subtype'];
                        $length = strlen($stringA);
                        $temp1 = substr($stringA, 0, $length - 4);
                        $temp2 = substr($stringA, $length - 4, $length);
                        $f3 = $temp1 . $stringB . $temp2;
                        $f3 = normalize_path($f3, false);
                        if (is_file($f3)) {
                            $found_subtype_layout = true;
                            $render_file = $f3;
                        }
                    }

                    $check_inner = dirname($render_file);
                    if ($found_subtype_layout == false and is_dir($check_inner)) {
                        if (isset($page['subtype'])) {
                            $stringA = $check_inner;
                            $stringB = $page['subtype'] . '.php';
                            $length = strlen($stringA);
                            $f3 = $stringA . DS . $stringB;
                            $f3 = normalize_path($f3, false);
                            if (is_file($f3)) {
                                $found_subtype_layout = true;
                                $render_file = $f3;
                            }
                        }

                        if ($found_subtype_layout == false) {
                            $in_file = $check_inner . DS . 'inner.php';
                            $in_file = normalize_path($in_file, false);
                            $in_file2 = $check_inner . DS . $page['content_type'] . '.php';
                            $in_file2 = normalize_path($in_file2, false);
                            if (is_file($in_file2)) {
                                $render_file = $in_file2;
                            } elseif (is_file($in_file)) {
                                $render_file = $in_file;
                            }
                        }
                    }
                }
            }
        }

        if ($render_file == false and isset($page['content_type']) and $page['content_type'] != false and $page['content_type'] != '') {
            $look_for_post = $page;

            if (isset($page['parent'])) {
                $par_page = false;
                $inh_par_page = app()->content_manager->get_inherited_parent($page['parent']);
                if ($inh_par_page != false) {
                    $par_page = app()->content_manager->get_by_id($inh_par_page);
                } else {
                    $par_page = app()->content_manager->get_by_id($page['parent']);
                }
                if (is_array($par_page)) {
                    if (isset($par_page['active_site_template']) and $par_page['active_site_template'] != false) {
                        $page['active_site_template'] = $par_page['active_site_template'];
                    }
                    if (isset($par_page['layout_file']) and $par_page['layout_file'] != false) {
                        //$page['layout_file'] = $par_page['layout_file'];
                    }
                } else {
                    $template_view_set_inner = $this->getActiveTemplateDir() . 'inner.php';
                    $template_view_set_inner2 = $this->getActiveTemplateDir() . 'layouts/inner.php';
                }
            } else {
                $template_view_set_inner = $this->getActiveTemplateDir() . 'inner.php';
                $template_view_set_inner2 = $this->getActiveTemplateDir() . 'layouts/inner.php';
            }
        }

        if ($render_file == false and isset($page['simply_a_file'])) {
            $simply_a_file2 = $this->getActiveTemplateDir() . $page['simply_a_file'];
            $simply_a_file3 = $this->getActiveTemplateDir() . 'layouts' . DS . $page['simply_a_file'];
            if ($render_file == false and is_file($simply_a_file3) == true) {
                $render_file = $simply_a_file3;
            }

            if ($render_file == false and is_file($simply_a_file2) == true) {
                $render_file = $simply_a_file2;
            }

            if ($render_file == false and is_file($page['simply_a_file']) == true) {
                $render_file = $page['simply_a_file'];
            }
        }
        if (!isset($page['active_site_template'])) {
            $page['active_site_template'] = $this->getTemplateFolderName();
        }
        if ($render_file == false and isset($page['active_site_template']) and trim($page['active_site_template']) != 'default') {
            $use_default_layouts = templates_dir() . $page['active_site_template'] . DS . 'use_default_layouts.php';
            if (is_file($use_default_layouts)) {
                $page['active_site_template'] = 'default';
            }
        }

        if ($render_file == false and isset($page['content_type']) and ($page['content_type'] == 'page') and isset($page['layout_file']) and trim($page['layout_file']) == 'inherit') {
            $use_index = $this->getActiveTemplateDir() . 'clean.php';
            $use_index2 = $this->getActiveTemplateDir() . 'layouts/clean.php';
            $use_index = normalize_path($use_index, false);
            $use_index2 = normalize_path($use_index2, false);

            if (is_file($use_index)) {
                $render_file = $use_index;
            } elseif (is_file($use_index2)) {
                $render_file = $use_index2;
            }
        }

//        if ($render_file == false and isset($page['active_site_template']) and isset($page['layout_file']) and trim($page['layout_file']) == '') {
//            $use_index = templates_dir() . $page['active_site_template'] . DS . 'index.php';
//            if (is_file($use_index)) {
//                $render_file = $use_index;
//            }
//        }


        if ($render_file == false and isset($page['active_site_template']) and ($page['active_site_template']) == 'default') {
            $page['active_site_template'] = $this->getTemplateFolderName();
        }

        if ($render_file == false and isset($page['active_site_template']) and isset($page['content_type']) and isset($page['layout_file'])) {
            $page['active_site_template'] = trim(sanitize_path($page['active_site_template']));
            $page['layout_file'] = str_replace('__', DS, $page['layout_file']);

            $page['layout_file'] = trim(urldecode(sanitize_path($page['layout_file'])));
            $page['layout_file'] = (str_replace('\\', '/', $page['layout_file']));

            $render_file_test = templates_dir() . $page['active_site_template'] . DS . $page['layout_file'];
            $render_file_test = normalize_path($render_file_test, false);

            $render_file_test2 = templates_dir() . $page['active_site_template'] . DS . 'layouts' . DS . $page['layout_file'];
            $render_file_test2 = normalize_path($render_file_test2, false);

            if (is_file($render_file_test)) {
                $render_file = $render_file_test;
            } elseif (is_file($render_file_test2)) {
                $render_file = $render_file_test2;
            }
        }


        if ($render_file == false and isset($page['active_site_template']) and isset($page['layout_file'])) {
            if (isset($page['content_type']) and $page['content_type'] == 'page') {
                $look_for_post = false;
            }


            $page['layout_file'] = str_replace('__', DS, $page['layout_file']);

            if ($look_for_post != false) {
                $f1 = $page['layout_file'];
                $stringA = $f1;
                $stringB = '_inner';
                $length = strlen($stringA);
                $temp1 = substr($stringA, 0, $length - 4);
                $temp2 = substr($stringA, $length - 4, $length);
                $f1 = $temp1 . $stringB . $temp2;
                if (strtolower($page['active_site_template']) == 'default') {
                    $template_view = $this->getActiveTemplateDir() . $f1;
                } else {
                    $template_view = templates_dir() . $page['active_site_template'] . DS . $f1;
                }

                if (is_file($template_view) == true) {
                    $render_file = $template_view;
                } else {
                    $dn = dirname($template_view);
                    $dn1 = $dn . DS;
                    $f1 = $dn1 . 'inner.php';
                    if (is_file($f1) == true) {
                        $render_file = $f1;
                    } else {
                        $dn = dirname($dn);
                        $dn1 = $dn . DS;
                        $f1 = $dn1 . 'inner.php';
                        if (is_file($f1) == true) {
                            $render_file = $f1;
                        } else {
                            $dn = dirname($dn);
                            $dn1 = $dn . DS;
                            $f1 = $dn1 . 'inner.php';
                            if (is_file($f1) == true) {
                                $render_file = $f1;
                            }
                        }
                    }
                }
            }

            if ($render_file == false) {
                if (strtolower($page['active_site_template']) == 'default') {
                    $template_view = $this->getActiveTemplateDir() . $page['layout_file'];
                } else {
                    $template_view = templates_dir() . $page['active_site_template'] . DS . $page['layout_file'];
                }
                if (is_file($template_view) == true) {
                    $render_file = $template_view;
                } else {
                    if (trim($page['active_site_template']) != 'default') {
                        $use_default_layouts = templates_dir() . $page['active_site_template'] . DS . 'use_default_layouts.php';
                        if (is_file($use_default_layouts)) {
                            $page['active_site_template'] = 'default';
                        }
                    }
                }
            }
        }

        if ($render_file == false and ((!isset($page['layout_file'])) or $page['layout_file'] == false) and isset($page['url']) and $page['url'] != '') {
            $page['url'] = trim(sanitize_path($page['url']));
            $template_view = $this->getActiveTemplateDir() . strtolower($page['url']) . '.php';
            if (is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }

        if ($render_file == false and isset($page['subtype']) and $page['subtype'] != '') {
            $page['subtype'] = trim(sanitize_path($page['subtype']));
            $template_view = $this->getActiveTemplateDir() . strtolower($page['subtype']) . '.php';
            if (is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }

        if ($render_file == false and isset($page['content_type']) and $page['content_type'] != '') {
            $page['content_type'] = trim(sanitize_path($page['content_type']));
            $template_view = $this->getActiveTemplateDir() . strtolower($page['content_type']) . '.php';
            if (is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }


        if (isset($page['active_site_template']) and $render_file == false and (strtolower($page['active_site_template']) == 'default' or $page['active_site_template'] == $site_template_settings)) {
            if ($render_file == false and isset($page['active_site_template']) and isset($page['id'])) {
                if (isset($look_for_post) and $look_for_post != false) {
                    if (isset($look_for_post['content_type'])) {
                        $ct = sanitize_path($look_for_post['content_type']);
                        $template_view = $this->getActiveTemplateDir() . $ct . '.php';
                        if ($render_file == false and is_file($template_view) == true) {
                            $render_file = $template_view;
                        }
                    }
                    $template_view = $this->getActiveTemplateDir() . 'index_inner.php';
                    if ($render_file == false and is_file($template_view) == true) {
                        $render_file = $template_view;
                    }
                    if (isset($look_for_post['content_type']) and $look_for_post['content_type'] != 'page') {
                        $template_view = $this->getActiveTemplateDir() . 'inner.php';
                        if ($render_file == false and is_file($template_view) == true) {
                            $render_file = $template_view;
                        }
                    }
                }
            }
            //
            if ($render_file == false and isset($page['parent']) and $page['parent'] == 0) {
                if ($render_file == false and isset($page['layout_file']) and $page['layout_file'] == 'inherit') {
                    $t_dir = $this->getActiveTemplateDir();
                    if (isset($page['active_site_template'])) {
                        $t_dir = templates_dir() . DS . $page['active_site_template'] . DS;
                        $t_dir = normalize_path($t_dir, 1);
                    }

                    $template_view_cl = $t_dir . 'clean.php';
                    $template_view_cl2 = $t_dir . 'layouts/clean.php';
                    if ($render_file == false and is_file($template_view_cl) == true) {
                        $render_file = $template_view_cl;
                    }
                    if ($render_file == false and is_file($template_view_cl2) == true) {
                        $render_file = $template_view_cl2;
                    }
                }
            }


            if ($render_file == false and isset($page['layout_file']) and ($page['layout_file']) != false and ($page['layout_file']) != 'index.php' and ($page['layout_file']) != 'inherit') {
                if ($render_file == false and isset($page['layout_file']) and ($page['layout_file']) != false) {
                    $page['layout_file'] = str_replace('__', DS, $page['layout_file']);

                    $template_view = $this->getActiveTemplateDir() . $page['layout_file'];

                    $template_view = normalize_path($template_view, false);
                    if (is_file($template_view) == true) {
                        $render_file = $template_view;
                    } else {
                        if (!isset($page['is_home']) or $page['is_home'] != 1) {
                            $template_view = $this->getActiveTemplateDir() . 'clean.php';
                            if (is_file($template_view) == true) {
                                $render_file = $template_view;
                            }
                        }
                    }
                }
            }

            $template_view = $this->getActiveTemplateDir() . 'index.php';
            if ($render_file == false and is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }

        if ($render_file == false and isset($page['active_site_template'])) {
            $url_file = app()->url_manager->string(1, 1);
            $test_file = str_replace('___', DS, $url_file);
            $template_view = $this->getActiveTemplateDir() . $test_file . '.php';
            $template_view = normalize_path($template_view, false);
            if (is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }

        if ($render_file == false and $fallback_render_internal_file != false) {
            if (is_file($fallback_render_internal_file)) {
                $render_file = $fallback_render_internal_file;
            }
        }

        if ($render_file == false and isset($page['active_site_template']) and strtolower($page['active_site_template']) != 'default') {
            $template_view = $this->getActiveTemplateDir() . 'index.php';
            if (is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }

        if ($render_file == false and isset($page['active_site_template']) and strtolower($page['active_site_template']) != 'default') {
            $template_view = $this->getActiveTemplateDir() . 'index.html';
            if (is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }

        if (isset($page['active_site_template']) and $render_file == false and strtolower($page['active_site_template']) != 'default') {
            $template_view = $this->getActiveTemplateDir() . 'index.htm';
            if (is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }


        if ($render_file == false and $template_view_set_inner != false) {
            if (isset($template_view_set_inner2)) {
                $template_view_set_inner2 = normalize_path($template_view_set_inner2, false);
                if (is_file($template_view_set_inner2) == true) {
                    $render_file = $template_view_set_inner2;
                }
            }
            $template_view_set_inner = normalize_path($template_view_set_inner, false);
            if ($render_file == false and is_file($template_view_set_inner) == true) {
                $render_file = $template_view_set_inner;
            }
        }

        if ($render_file != false and isset($page['custom_view'])) {
            $check_custom = dirname($render_file) . DS;
            $check_custom_parent = dirname($render_file) . DS;
            $cv = trim($page['custom_view']);
            $cv = sanitize_path($cv);
            $cv = str_ireplace('.php', '', $cv);
            $check_custom_f = $check_custom . $cv . '.php';

            if (is_file($check_custom_f)) {
                $render_file = $check_custom_f;
            }
        }

        if ($render_file == false and isset($page['layout_file']) and ($page['layout_file']) != false) {
            $page['layout_file'] = str_replace('__', DS, $page['layout_file']);

            $template_view = $this->getActiveTemplateDir() . $page['layout_file'];
            $template_view = normalize_path($template_view, false);
            if (is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }


        return $render_file;
    }


    public function getStyleSettings($templateDir = false)
    {
        if (!$templateDir) {
            $templateDir = $this->getActiveTemplateDir();
        }

        $styleSettingReader = new TemplateStylesSettingsReader($templateDir);
        $getStyleSettings = $styleSettingReader->getStyleSettings();

        return $getStyleSettings;
    }


    /**
     * Defines all constants that are needed to parse the page layout.
     *
     * It accepts array or $content that must have  $content['id'] set
     *
     * @param array|bool $content
     *
     * @option     integer  "id"   [description]
     * @option     string "content_type" [description]
     * @example
     * <code>
     *  Define constants for some page
     *
     *  $this->defineConstants(['id'=>1]);
     *  print PAGE_ID;
     *  print POST_ID;
     *  print CATEGORY_ID;
     *  print MAIN_PAGE_ID;
     *  print DEFAULT_TEMPLATE_DIR;
     *  print DEFAULT_TEMPLATE_URL;
     * </code>
     *
     * @const      PAGE_ID Defines the current page id
     * @const      POST_ID Defines the current post id
     * @const      CATEGORY_ID Defines the current category id if any
     * @const      ACTIVE_PAGE_ID Same as PAGE_ID
     * @const      CONTENT_ID current post or page id
     * @const      MAIN_PAGE_ID the parent page id
     * @const      DEFAULT_TEMPLATE_DIR the directory of the site's default template
     * @const      DEFAULT_TEMPLATE_URL the url of the site's default template
     *
     */
    public function defineConstants($content = false)
    {


        $ref_page = false;
        if ($content == false) {
            if (isset($_REQUEST['from_url'])) {
                $ref_page = $_REQUEST['from_url'];
            } else if (!defined('MW_BACKEND') and (is_ajax() or defined('MW_API_CALL')) && isset($_SERVER['HTTP_REFERER'])) {
                $ref_page = $_SERVER['HTTP_REFERER'];
            } else {
                $ref_page = url_current(true);
            }
        }


        if (!$content and isset($ref_page) and is_string($ref_page)) {
            if ($ref_page != '') {
                $ref_page = strtok($ref_page, '?');
                $ref_page_data = [];
                if ($ref_page == site_url()) {
                    $ref_page_data = app()->content_manager->homepage();
                } else {
                    $ref_page_data = app()->content_manager->get_by_url($ref_page);
                }

                if ($ref_page_data != false and !empty($ref_page_data)) {
                    $content = $ref_page_data;
                }
            }
        }

        $this->setVariablesFromContent($content);


        // this gets categoryId from url
        if (!$this->categoryId and !defined('CATEGORY_ID')) {

            $cat_id = app()->category_manager->get_category_id_from_url();

            if ($cat_id != false) {
                define('CATEGORY_ID', intval($cat_id));
                $this->categoryId = intval($cat_id);

            }
        }
        // this gets pageId from url
        if (!$this->pageId and !defined('PAGE_ID')) {


            $getPageSlug = app()->permalink_manager->slug($ref_page, 'page');

            $pageFromSlug = app()->content_manager->get_by_url($getPageSlug);
            if ($pageFromSlug) {
                $page = $pageFromSlug;
                $this->pageId = intval($page['id']);
            }
        }

        $this->defineContentConstants();
        $this->defineTemplateConstants();

    }

    function defineContentConstants()
    {
        if (!defined('CATEGORY_ID')) {
            define('CATEGORY_ID', $this->categoryId);
        }

        if (!defined('PAGE_ID')) {
            define('PAGE_ID', $this->pageId);
        }

        if (!defined('POST_ID')) {
            define('POST_ID', $this->postId);
        }
        if (!defined('CONTENT_ID')) {
            define('CONTENT_ID', $this->contentId);
        }

        if (!defined('MAIN_PAGE_ID')) {
            define('MAIN_PAGE_ID', $this->mainPageId);
        }
        if (!defined('ROOT_PAGE_ID')) {
            define('ROOT_PAGE_ID', $this->rootPageId);
        }


        if (!defined('PARENT_PAGE_ID')) {
            define('PARENT_PAGE_ID', $this->parentPageId);
        }
    }

    function defineTemplateConstants()
    {
        // template folders constants
        if (!defined('TEMPLATES_DIR')) {
            define('TEMPLATES_DIR', templates_dir());
        }


        if (!defined('TEMPLATE_DIR')) {
            define('TEMPLATE_DIR', $this->getActiveTemplateDir());
        }


        if (!defined('TEMPLATE_NAME')) {
            define('TEMPLATE_NAME', $this->getTemplateFolderName());
        }

        if (!defined('THIS_TEMPLATE_DIR')) {
            $active_dir = $this->getActiveTemplateDir();
            if (!is_dir($active_dir)) {
                $active_dir = $this->getFallbackTemplateDir();
            }
            define('THIS_TEMPLATE_DIR', $active_dir);
        }
        if (!defined('TEMPLATE_URL')) {
            define('TEMPLATE_URL', $this->getActiveTemplateUrl());
        }

        if (!defined('THIS_TEMPLATE_URL')) {
            define('THIS_TEMPLATE_URL', $this->getActiveTemplateUrl());
        }
        if (!defined('TEMPLATE_URL')) {
            define('TEMPLATE_URL', $this->getActiveTemplateUrl());
        }

        if (!defined('THIS_TEMPLATE_FOLDER_NAME')) {
            define('THIS_TEMPLATE_FOLDER_NAME', $this->getTemplateFolderName());
        }
        if (!defined('ACTIVE_SITE_TEMPLATE')) {
            define('ACTIVE_SITE_TEMPLATE', $this->getTemplateFolderName());
        }


        if (!defined('DEFAULT_TEMPLATE_DIR')) {
            define('DEFAULT_TEMPLATE_DIR', $this->getFallbackTemplateDir());
        }
        if (!defined('DEFAULT_TEMPLATE_URL')) {
            define('DEFAULT_TEMPLATE_URL', templates_url() . $this->fallbackTempleteFolderName . '/');
        }
        if (!defined('ACTIVE_TEMPLATE_DIR')) {
            define('ACTIVE_TEMPLATE_DIR', $this->getActiveTemplateDir());
        }

        if (!defined('THIS_TEMPLATE_DIR')) {
            define('THIS_TEMPLATE_DIR', $this->getActiveTemplateDir());
        }


        if (defined('THIS_TEMPLATE_DIR') == false) {
            define('THIS_TEMPLATE_DIR', $this->getTemplateFolderName());
        }

    }

}
