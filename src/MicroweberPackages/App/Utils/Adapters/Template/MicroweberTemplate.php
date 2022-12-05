<?php


// moved to src/MicroweberPackages/Template/Adapters/MicroweberTemplate.php

//namespace MicroweberPackages\Utils\Adapters\Template;
//
//use Microweber\Utils\Adapters\Template\RenderHelpers\TemplateMetaTagsRenderer;
//use MicroweberPackages\View\View;
//
//class MicroweberTemplate
//{
//    /** @var \MicroweberPackages\App\LaravelApplication */
//    public $app;
//
//    public function __construct($app = null)
//    {
//        $this->app = $app;
//    }
//
//    public function render($params = array())
//    {
//        $render_file = $params['render_file'];
//        $l = new View($render_file);
//        $l->page_id = $params['page_id'];
//        $l->content_id = $params['content_id'];
//        $l->post_id = $params['post_id'];
//       // $l->category_id = $params['category_id'];
//
//        if (isset($params['content'])) {
//            $l->content = $params['content'];
//        }
//        if (isset($params['category_id'])) {
//            $l->category_id = $params['category_id'];
//        }
//
//        if (isset($params['category'])) {
//            $l->category = $params['category'];
//        }
//       // $l->category = $params['category'];
//        $l->page = $params['page'];
//        $l->application = $this->app;
//
//        if (!empty($params)) {
//            foreach ($params as $k => $v) {
//                $l->assign($k, $v);
//            }
//        }
//
//        $l = $l->__toString();
//
//        if(isset($params['meta_tags']) and $params['meta_tags']){
//            $params['layout'] = $l;
//            $meta_tags_render = new TemplateMetaTagsRenderer($this->app);
//            $l = $meta_tags_render->render($params);
//        }
//
//        return $l;
//    }
//
//    /**
//     * Return the path to the layout file that will render the page.
//     */
//    public function get_layout($page = array())
//    {
//        $function_cache_id = '';
//        if (is_array($page)) {
//            ksort($page);
//        }
//        $function_cache_id = $function_cache_id.serialize($page);
//        $cache_id = __FUNCTION__.crc32($function_cache_id);
//        $cache_group = 'content/global';
//        if (!defined('ACTIVE_TEMPLATE_DIR')) {
//            if (isset($page['id'])) {
//                $this->app->content_manager->define_constants($page);
//            }
//        }
//
//        $cache_content = false;
//        //  $cache_content = $this->app->cache_manager->get($cache_id, $cache_group);
//        if (($cache_content) != false) {
//            // return $cache_content;
//        }
//
//        $override = $this->app->event_manager->trigger('mw.front.get_layout', $page);
//
//        $render_file = false;
//        $look_for_post = false;
//        $template_view_set_inner = false;
//        $fallback_render_internal_file = false;
//        $site_template_settings = $this->app->option_manager->get('current_template', 'template');
//        if (!isset($page['active_site_template'])) {
//            $page['active_site_template'] = 'default';
//        } elseif (isset($page['active_site_template']) and $page['active_site_template'] == '') {
//            $page['active_site_template'] = $site_template_settings;
//        }
//
//        if ($page['active_site_template'] and ($page['active_site_template'] == 'default' or $page['active_site_template'] == 'mw_default')) {
//            if ($site_template_settings != 'default' and $page['active_site_template'] == 'mw_default') {
//                $page['active_site_template'] = 'default';
//                $site_template_settings = 'default';
//            }
//            if ($site_template_settings != false) {
//                $site_template_settings = sanitize_path($site_template_settings);
//                $site_template_settings_dir = TEMPLATES_DIR.$site_template_settings.DS;
//                if (is_dir($site_template_settings_dir) != false) {
//                    $page['active_site_template'] = $site_template_settings;
//                }
//            }
//        }
//        if (isset($page['content_type'])) {
//            $page['content_type'] = sanitize_path($page['content_type']);
//        }
//
//        if (isset($page['subtype'])) {
//            $page['subtype'] = sanitize_path($page['subtype']);
//        }
//        if (isset($page['layout_file'])) {
//            $page['layout_file'] = sanitize_path($page['layout_file']);
//
//
//        }
//        if (isset($page['active_site_template'])) {
//            $page['active_site_template'] = sanitize_path($page['active_site_template']);
//        }
//        if (is_array($override)) {
//            foreach ($override as $resp) {
//                if (isset($resp['render_file']) and ($resp['render_file']) != false) {
//                    $render_file = $resp['render_file'];
//                } elseif (is_array($resp) and !empty($resp)) {
//                    $page = array_merge($page, $resp);
//                }
//            }
//        }
//
//        if ($render_file == false) {
//            if (isset($page['active_site_template']) and isset($page['layout_file'])) {
//                $page['layout_file'] = str_replace('___', DS, $page['layout_file']);
//                $page['layout_file'] = str_replace('__', DS, $page['layout_file']);
//                $page['layout_file'] = sanitize_path($page['layout_file']);
//
//                $template_d = $page['active_site_template'];
//                if ($template_d == 'mw_default') {
//                    $template_d = 'default';
//                }
//
//
//
//
//
//                $render_file_temp_from_url = false;
//                $render_file_temp = normalize_path(TEMPLATES_DIR.$template_d.DS.$page['layout_file'], false);
//                $render_use_default = normalize_path(TEMPLATES_DIR.$template_d.DS.'use_default_layouts.php', false);
//
//
//                if(isset($page['content_type']) and ($page['content_type']) == 'page' and $page['layout_file'] and $page['layout_file'] == 'inherit'){
//                    if (isset($page['url']) and isset($page['active_site_template'])) {
//                        $render_file_temp_from_url = normalize_path(TEMPLATES_DIR.$template_d.DS.$page['url'].'.php', false);
//
//                    }
//                }
//
//                $render_file_module_temp = modules_path().DS.$page['layout_file'];
//                $render_file_module_temp = normalize_path($render_file_module_temp, false);
//                if ($render_file_temp_from_url and is_file($render_file_temp_from_url)) {
//                    $render_file = $render_file_temp_from_url;
//                }  else if (is_file($render_file_temp)) {
//                    $render_file = $render_file_temp;
//                } elseif (is_file($render_file_module_temp)) {
//                    $render_file = $render_file_module_temp;
//                } elseif (is_file($render_use_default)) {
//                    $render_file_temp = DEFAULT_TEMPLATE_DIR.$page['layout_file'];
//                    if (is_file($render_file_temp)) {
//                        $render_file = $render_file_temp;
//                    }
//                }
//            }
//        }
//        if ($render_file == false and isset($page['content_type']) and isset($page['parent']) and ($page['content_type']) != 'page') {
//            $get_layout_from_parent = false;
//            $par = $this->app->content_manager->get_by_id($page['parent']);
//
//            if (isset($par['layout_file']) and $par['layout_file'] != '' and $par['layout_file'] != 'inherit') {
//                $get_layout_from_parent = $par;
//            } elseif (isset($par['is_home']) and isset($par['active_site_template']) and (!isset($par['layout_file']) or $par['layout_file'] == '') and $par['is_home'] == 'y') {
//                $par['layout_file'] = 'index.php';
//                $get_layout_from_parent = $par;
//            } else {
//                $inh = $this->app->content_manager->get_inherited_parent($page['parent']);
//
//                if ($inh != false) {
//                    $par = $this->app->content_manager->get_by_id($inh);
//                    if (isset($par['active_site_template']) and isset($par['layout_file']) and $par['layout_file'] != '') {
//                        $get_layout_from_parent = $par;
//                    } elseif (isset($par['active_site_template']) and isset($par['is_home']) and $par['is_home'] == 'y' and (!isset($par['layout_file']) or $par['layout_file'] == '')) {
//                        $par['layout_file'] = 'index.php';
//                        $get_layout_from_parent = $par;
//                    }
//                }
//            }
//
//            if (isset($get_layout_from_parent['layout_file'])) {
//                if (!isset($get_layout_from_parent['active_site_template'])) {
//                    $get_layout_from_parent['active_site_template'] = 'default';
//                }
//                if ($get_layout_from_parent['active_site_template'] == 'default') {
//                    $get_layout_from_parent['active_site_template'] = $site_template_settings;
//                }
//                if ($get_layout_from_parent['active_site_template'] == 'mw_default') {
//                    $get_layout_from_parent['active_site_template'] = 'default';
//                }
//                $get_layout_from_parent['layout_file'] = str_replace('___', DS, $get_layout_from_parent['layout_file']);
//                $get_layout_from_parent['layout_file'] = sanitize_path($get_layout_from_parent['layout_file']);
//                $render_file_temp = TEMPLATES_DIR.$get_layout_from_parent['active_site_template'].DS.$get_layout_from_parent['layout_file'];
//                $render_use_default = TEMPLATES_DIR.$get_layout_from_parent['active_site_template'].DS.'use_default_layouts.php';
//                $render_file_temp = normalize_path($render_file_temp, false);
//                $render_use_default = normalize_path($render_use_default, false);
//
//                $render_file_module_temp = modules_path().DS.$get_layout_from_parent['layout_file'];
//                $render_file_module_temp = normalize_path($render_file_module_temp, false);
//
//                //if (!isset($page['content_type']) or $page['content_type'] == 'page') {
//                if (is_file($render_file_temp)) {
//                    $render_file = $render_file_temp;
//                } elseif (is_file($render_use_default)) {
//                    $render_file_temp = DEFAULT_TEMPLATE_DIR.$get_layout_from_parent['layout_file'];
//                    if (is_file($render_file_temp)) {
//                        $render_file = $render_file_temp;
//                    }
//                } elseif (is_file($render_file_module_temp)) {
//                    $render_file = $render_file_module_temp;
//                }
//            }
//        }
//
//        if ($render_file == false and !isset($page['active_site_template']) and isset($page['layout_file'])) {
//            $test_file = str_replace('___', DS, $page['layout_file']);
//            $test_file = sanitize_path($test_file);
//            $render_file_temp = $test_file;
//            $render_file_temp = normalize_path($render_file_temp, false);
//
//            if (is_file($render_file_temp)) {
//                $render_file = $render_file_temp;
//            }
//        }
//
//        if ($render_file == false and isset($page['active_site_template']) and isset($page['active_site_template']) and isset($page['layout_file']) and $page['layout_file'] != 'inherit' and $page['layout_file'] != '') {
//            $test_file = str_replace('___', DS, $page['layout_file']);
//            $test_file = sanitize_path($test_file);
//
//            $render_file_temp = TEMPLATES_DIR.$page['active_site_template'].DS.$test_file;
//            $render_file_module_temp = modules_path().DS.$test_file;
//            $render_file_module_temp = normalize_path($render_file_module_temp, false);
//
//            if (is_file($render_file_temp)) {
//                $render_file = $render_file_temp;
//            } elseif (is_file($render_file_module_temp)) {
//                $render_file = $render_file_module_temp;
//            }
//        }
//
//
//
//        if (($render_file == false)
//             and isset($page['id'])  and $page['id'] == 0 ) {
//            $url_file = $this->app->url_manager->string(1, 1);
//            $test_file = str_replace('___', DS, $url_file);
//            $test_file = sanitize_path($test_file);
//            $render_file_temp = ACTIVE_TEMPLATE_DIR.DS.$test_file.'.php';
//            $render_file_temp2 = ACTIVE_TEMPLATE_DIR.DS.$test_file.'.php';
//            $render_file_temp3 = ACTIVE_TEMPLATE_DIR.DS.'layouts'.DS.$test_file.'.php';
//
//            if (is_file($render_file_temp)) {
//                $render_file = $render_file_temp;
//            } elseif (is_file($render_file_temp2)) {
//                $render_file = $render_file_temp2;
//            }elseif (is_file($render_file_temp3)) {
//
//                $render_file = $render_file_temp3;
//            }
//        }
//
//        if (isset($page['active_site_template']) and $page['active_site_template'] == 'default') {
//            $page['active_site_template'] = $site_template_settings;
//        }
//
//        if (isset($page['active_site_template']) and $page['active_site_template'] != 'default' and $page['active_site_template'] == 'mw_default') {
//            $page['active_site_template'] = 'default';
//        }
//
//        if ($render_file == false and isset($page['id']) and isset($page['active_site_template']) and isset($page['layout_file']) and ($page['layout_file'] != 'inherit')) {
//            $render_file_temp = TEMPLATES_DIR.$page['active_site_template'].DS.$page['layout_file'];
//            $render_file_temp = normalize_path($render_file_temp, false);
//            if (is_file($render_file_temp)) {
//                $render_file = $render_file_temp;
//            } else {
//                $render_file_temp = DEFAULT_TEMPLATE_DIR.$page['layout_file'];
//                if (is_file($render_file_temp)) {
//                    $render_file = $render_file_temp;
//                }
//            }
//        }
//
//        if ($render_file == false and isset($page['id']) and isset($page['active_site_template']) and (!isset($page['layout_file']) or (isset($page['layout_file']) and ($page['layout_file'] == 'inherit')) or $page['layout_file'] == false)) {
//            $inherit_from = $this->app->content_manager->get_parents($page['id']);
//            $found = 0;
//            if ($inherit_from == false) {
//                if (isset($page['parent']) and $page['parent'] != false) {
//                    $par_test = $this->app->content_manager->get_by_id($page['parent']);
//
//                    if (is_array($par_test)) {
//                        $inherit_from = array();
//                        if (isset($page['layout_file']) and ($page['layout_file'] != 'inherit')) {
//                            $inherit_from[] = $page['parent'];
//                        } else {
//                            $inh = $this->app->content_manager->get_inherited_parent($page['parent']);
//                            $inherit_from[] = $inh;
//                        }
//                    }
//                }
//            }
//
//            if (!empty($inherit_from)) {
//                foreach ($inherit_from as $value) {
//                    if ($found == 0 and $value != $page['id']) {
//                        $par_c = $this->app->content_manager->get_by_id($value);
//                        if (isset($par_c['id']) and isset($par_c['active_site_template']) and isset($par_c['layout_file']) and $par_c['layout_file'] != 'inherit') {
//                            $page['layout_file'] = $par_c['layout_file'];
//                            $page['layout_file'] = str_replace('__', DS, $page['layout_file']);
//                            $page['active_site_template'] = $par_c['active_site_template'];
//
//                            $page['active_site_template'] = sanitize_path($page['active_site_template']);
//                            if ($page['active_site_template'] == 'default') {
//                                $page['active_site_template'] = $site_template_settings;
//                            }
//
//                            if ($page['active_site_template'] != 'default' and $page['active_site_template'] == 'mw_default') {
//                                $page['active_site_template'] = 'default';
//                            }
//
//                            $render_file_temp = TEMPLATES_DIR.$page['active_site_template'].DS.$page['layout_file'];
//                            $render_file_temp = normalize_path($render_file_temp, false);
//                            $render_file_module_temp = modules_path().DS.$page['layout_file'];
//                            $render_file_module_temp = normalize_path($render_file_module_temp, false);
//
//                            if (is_file($render_file_temp)) {
//                                $render_file = $render_file_temp;
//                            } elseif (is_file($render_file_module_temp)) {
//                                $render_file = $render_file_module_temp;
//                            } else {
//                                $render_file_temp = DEFAULT_TEMPLATE_DIR.$page['layout_file'];
//                                if (is_file($render_file_temp)) {
//                                    $fallback_render_internal_file = $render_file_temp;
//                                }
//                            }
//
//                            $found = 1;
//                        }
//                    }
//                }
//            }
//        }
//
//        if ($render_file != false and (isset($page['content_type']) and ($page['content_type']) != 'page')) {
//            $f1 = $render_file;
//            $f2 = $render_file;
//
//            $stringA = $f1;
//            $stringB = '_inner';
//            $length = strlen($stringA);
//            $temp1 = substr($stringA, 0, $length - 4);
//            $temp2 = substr($stringA, $length - 4, $length);
//            $f1 = $temp1.$stringB.$temp2;
//            $f1 = normalize_path($f1, false);
//
//            if (is_file($f1)) {
//                $render_file = $f1;
//            } else {
//                $stringA = $f2;
//                $stringB = '_'.$page['content_type'];
//                $length = strlen($stringA);
//                $temp1 = substr($stringA, 0, $length - 4);
//                $temp2 = substr($stringA, $length - 4, $length);
//                $f3 = $temp1.$stringB.$temp2;
//                $f3 = normalize_path($f3, false);
//
//                if (is_file($f3)) {
//                    $render_file = $f3;
//                } else {
//                    $found_subtype_layout = false;
//                    if (isset($page['subtype'])) {
//                        $stringA = $f2;
//                        $stringB = '_'.$page['subtype'];
//                        $length = strlen($stringA);
//                        $temp1 = substr($stringA, 0, $length - 4);
//                        $temp2 = substr($stringA, $length - 4, $length);
//                        $f3 = $temp1.$stringB.$temp2;
//                        $f3 = normalize_path($f3, false);
//                        if (is_file($f3)) {
//                            $found_subtype_layout = true;
//                            $render_file = $f3;
//                        }
//                    }
//
//                    $check_inner = dirname($render_file);
//                    if ($found_subtype_layout == false and is_dir($check_inner)) {
//                        if (isset($page['subtype'])) {
//                            $stringA = $check_inner;
//                            $stringB = $page['subtype'].'.php';
//                            $length = strlen($stringA);
//                            $f3 = $stringA.DS.$stringB;
//                            $f3 = normalize_path($f3, false);
//                            if (is_file($f3)) {
//                                $found_subtype_layout = true;
//                                $render_file = $f3;
//                            }
//                        }
//                        if ($found_subtype_layout == false) {
//                            $in_file = $check_inner.DS.'inner.php';
//                            $in_file = normalize_path($in_file, false);
//                            $in_file2 = $check_inner.DS.$page['content_type'].'.php';
//                            $in_file2 = normalize_path($in_file2, false);
//                            if (is_file($in_file2)) {
//                                $render_file = $in_file2;
//                            } elseif (is_file($in_file)) {
//                                $render_file = $in_file;
//                            }
//                        }
//                    }
//                }
//            }
//        }
//
//        if ($render_file == false and isset($page['content_type']) and $page['content_type'] != false and $page['content_type'] != '') {
//            $look_for_post = $page;
//
//            if (isset($page['parent'])) {
//                $par_page = false;
//                $inh_par_page = $this->app->content_manager->get_inherited_parent($page['parent']);
//                if ($inh_par_page != false) {
//                    $par_page = $this->app->content_manager->get_by_id($inh_par_page);
//                } else {
//                    $par_page = $this->app->content_manager->get_by_id($page['parent']);
//                }
//                if (is_array($par_page)) {
//                    if (isset($par_page['active_site_template']) and $par_page['active_site_template'] != false) {
//                        $page['active_site_template'] = $par_page['active_site_template'];
//                    }
//                    if (isset($par_page['layout_file']) and $par_page['layout_file'] != false) {
//                        //$page['layout_file'] = $par_page['layout_file'];
//                    }
//                } else {
//                    $template_view_set_inner = ACTIVE_TEMPLATE_DIR.DS.'inner.php';
//                    $template_view_set_inner2 = ACTIVE_TEMPLATE_DIR.DS.'layouts/inner.php';
//                }
//            } else {
//                $template_view_set_inner = ACTIVE_TEMPLATE_DIR.DS.'inner.php';
//                $template_view_set_inner2 = ACTIVE_TEMPLATE_DIR.DS.'layouts/inner.php';
//            }
//        }
//
//        if ($render_file == false and isset($page['simply_a_file'])) {
//            $simply_a_file2 = ACTIVE_TEMPLATE_DIR.$page['simply_a_file'];
//            $simply_a_file3 = ACTIVE_TEMPLATE_DIR.'layouts'.DS.$page['simply_a_file'];
//            if ($render_file == false and is_file($simply_a_file3) == true) {
//                $render_file = $simply_a_file3;
//            }
//
//            if ($render_file == false and is_file($simply_a_file2) == true) {
//                $render_file = $simply_a_file2;
//            }
//
//            if ($render_file == false and is_file($page['simply_a_file']) == true) {
//                $render_file = $page['simply_a_file'];
//            }
//        }
//        if (!isset($page['active_site_template'])) {
//            $page['active_site_template'] = ACTIVE_SITE_TEMPLATE;
//        }
//        if ($render_file == false and isset($page['active_site_template']) and trim($page['active_site_template']) != 'default') {
//            $use_default_layouts = TEMPLATES_DIR.$page['active_site_template'].DS.'use_default_layouts.php';
//            if (is_file($use_default_layouts)) {
//                $page['active_site_template'] = 'default';
//            }
//        }
//
//        if ($render_file == false and isset($page['content_type']) and ($page['content_type'] == 'page') and isset($page['layout_file']) and trim($page['layout_file']) == 'inherit') {
//            $use_index = TEMPLATE_DIR.DS.'clean.php';
//            $use_index2 = TEMPLATE_DIR.DS.'layouts/clean.php';
//            $use_index = normalize_path($use_index, false);
//            $use_index2 = normalize_path($use_index2, false);
//
//            if (is_file($use_index)) {
//                $render_file = $use_index;
//            } elseif (is_file($use_index2)) {
//                $render_file = $use_index2;
//            }
//        }
//
////        if ($render_file == false and isset($page['active_site_template']) and isset($page['layout_file']) and trim($page['layout_file']) == '') {
////            $use_index = TEMPLATES_DIR . $page['active_site_template'] . DS . 'index.php';
////            if (is_file($use_index)) {
////                $render_file = $use_index;
////            }
////        }
//
//
//
//
//        if ($render_file == false and isset($page['active_site_template']) and ($page['active_site_template']) == 'default') {
//            $page['active_site_template'] = ACTIVE_SITE_TEMPLATE;
//        }
//
//        if ($render_file == false and isset($page['active_site_template']) and isset($page['content_type']) and isset($page['layout_file'])) {
//            $page['active_site_template'] = trim(sanitize_path($page['active_site_template']));
//            $page['layout_file'] = str_replace('__', DS, $page['layout_file']);
//
//            $page['layout_file'] = trim(urldecode(sanitize_path($page['layout_file'])));
//            $page['layout_file'] = (str_replace('\\', '/', $page['layout_file']));
//
//            $render_file_test = TEMPLATES_DIR.$page['active_site_template'].DS.$page['layout_file'];
//            $render_file_test = normalize_path($render_file_test, false);
//
//            $render_file_test2 = TEMPLATES_DIR.$page['active_site_template'].DS.'layouts'.DS.$page['layout_file'];
//            $render_file_test2 = normalize_path($render_file_test2, false);
//
//            if (is_file($render_file_test)) {
//                $render_file = $render_file_test;
//            } elseif (is_file($render_file_test2)) {
//                $render_file = $render_file_test2;
//            }
//        }
//
//
//        if ($render_file == false and isset($page['active_site_template']) and isset($page['layout_file'])) {
//            if (isset($page['content_type']) and $page['content_type'] == 'page') {
//                $look_for_post = false;
//            }
//
//
//
//            $page['layout_file'] = str_replace('__', DS, $page['layout_file']);
//
//            if ($look_for_post != false) {
//                $f1 = $page['layout_file'];
//                $stringA = $f1;
//                $stringB = '_inner';
//                $length = strlen($stringA);
//                $temp1 = substr($stringA, 0, $length - 4);
//                $temp2 = substr($stringA, $length - 4, $length);
//                $f1 = $temp1.$stringB.$temp2;
//                if (strtolower($page['active_site_template']) == 'default') {
//                    $template_view = ACTIVE_TEMPLATE_DIR.DS.$f1;
//                } else {
//                    $template_view = TEMPLATES_DIR.$page['active_site_template'].DS.$f1;
//                }
//
//                if (is_file($template_view) == true) {
//                    $render_file = $template_view;
//                } else {
//                    $dn = dirname($template_view);
//                    $dn1 = $dn.DS;
//                    $f1 = $dn1.'inner.php';
//                    if (is_file($f1) == true) {
//                        $render_file = $f1;
//                    } else {
//                        $dn = dirname($dn);
//                        $dn1 = $dn.DS;
//                        $f1 = $dn1.'inner.php';
//                        if (is_file($f1) == true) {
//                            $render_file = $f1;
//                        } else {
//                            $dn = dirname($dn);
//                            $dn1 = $dn.DS;
//                            $f1 = $dn1.'inner.php';
//                            if (is_file($f1) == true) {
//                                $render_file = $f1;
//                            }
//                        }
//                    }
//                }
//            }
//
//            if ($render_file == false) {
//                if (strtolower($page['active_site_template']) == 'default') {
//                    $template_view = ACTIVE_TEMPLATE_DIR.DS.$page['layout_file'];
//                } else {
//                    $template_view = TEMPLATES_DIR.$page['active_site_template'].DS.$page['layout_file'];
//                }
//                if (is_file($template_view) == true) {
//                    $render_file = $template_view;
//                } else {
//                    if (trim($page['active_site_template']) != 'default') {
//                        $use_default_layouts = TEMPLATES_DIR.$page['active_site_template'].DS.'use_default_layouts.php';
//                        if (is_file($use_default_layouts)) {
//                            $page['active_site_template'] = 'default';
//                        }
//                    }
//                }
//            }
//        }
//
//        if ($render_file == false and ((!isset($page['layout_file'])) or $page['layout_file'] == false) and isset($page['url']) and $page['url'] != '') {
//            $page['url'] = trim(sanitize_path($page['url']));
//            $template_view = ACTIVE_TEMPLATE_DIR.strtolower($page['url']).'.php';
//            if (is_file($template_view) == true) {
//                $render_file = $template_view;
//            }
//        }
//
//        if ($render_file == false and isset($page['subtype']) and $page['subtype'] != '') {
//            $page['subtype'] = trim(sanitize_path($page['subtype']));
//            $template_view = ACTIVE_TEMPLATE_DIR.strtolower($page['subtype']).'.php';
//            if (is_file($template_view) == true) {
//                $render_file = $template_view;
//            }
//        }
//
//        if ($render_file == false and isset($page['content_type']) and $page['content_type'] != '') {
//            $page['content_type'] = trim(sanitize_path($page['content_type']));
//            $template_view = ACTIVE_TEMPLATE_DIR.strtolower($page['content_type']).'.php';
//            if (is_file($template_view) == true) {
//                $render_file = $template_view;
//            }
//        }
//
//
//        if (isset($page['active_site_template']) and $render_file == false and (strtolower($page['active_site_template']) == 'default' or $page['active_site_template'] == $site_template_settings)) {
//            if ($render_file == false and isset($page['active_site_template']) and isset($page['id'])) {
//                if (isset($look_for_post) and $look_for_post != false) {
//                    if (isset($look_for_post['content_type'])) {
//                        $ct = sanitize_path($look_for_post['content_type']);
//                        $template_view = ACTIVE_TEMPLATE_DIR.$ct.'.php';
//                        if ($render_file == false and is_file($template_view) == true) {
//                            $render_file = $template_view;
//                        }
//                    }
//                    $template_view = ACTIVE_TEMPLATE_DIR.'index_inner.php';
//                    if ($render_file == false and is_file($template_view) == true) {
//                        $render_file = $template_view;
//                    }
//                    if (isset($look_for_post['content_type']) and $look_for_post['content_type'] != 'page') {
//                        $template_view = ACTIVE_TEMPLATE_DIR.'inner.php';
//                        if ($render_file == false and is_file($template_view) == true) {
//                            $render_file = $template_view;
//                        }
//                    }
//                }
//            }
//            //
//            if ($render_file == false and isset($page['parent']) and $page['parent'] == 0) {
//                if ($render_file == false and isset($page['layout_file']) and $page['layout_file'] == 'inherit') {
//                    $t_dir = ACTIVE_TEMPLATE_DIR;
//                    if (isset($page['active_site_template'])) {
//                        $t_dir = templates_path().DS.$page['active_site_template'].DS;
//                        $t_dir = normalize_path($t_dir, 1);
//                    }
//
//                    $template_view_cl = $t_dir.'clean.php';
//                    $template_view_cl2 = $t_dir.'layouts/clean.php';
//                    if ($render_file == false and is_file($template_view_cl) == true) {
//                        $render_file = $template_view_cl;
//                    }
//                    if ($render_file == false and is_file($template_view_cl2) == true) {
//                        $render_file = $template_view_cl2;
//                    }
//                }
//            }
//
//
//
//
//            if ($render_file == false and isset($page['layout_file']) and ($page['layout_file']) != false and ($page['layout_file']) != 'index.php' and ($page['layout_file']) != 'inherit') {
//                if ($render_file == false and isset($page['layout_file']) and ($page['layout_file']) != false) {
//                    $page['layout_file'] = str_replace('__', DS, $page['layout_file']);
//
//                    $template_view = ACTIVE_TEMPLATE_DIR.DS.$page['layout_file'];
//
//                    $template_view = normalize_path($template_view, false);
//                    if (is_file($template_view) == true) {
//                        $render_file = $template_view;
//                    } else {
//                        if (!isset($page['is_home']) or $page['is_home'] != 1) {
//                            $template_view = ACTIVE_TEMPLATE_DIR.DS.'clean.php';
//                            if (is_file($template_view) == true) {
//                                $render_file = $template_view;
//                            }
//                        }
//                    }
//                }
//            }
//
//            $template_view = ACTIVE_TEMPLATE_DIR.'index.php';
//            if ($render_file == false and is_file($template_view) == true) {
//                $render_file = $template_view;
//            }
//        }
//
//        if ($render_file == false and isset($page['active_site_template'])) {
//            $url_file = $this->app->url_manager->string(1, 1);
//            $test_file = str_replace('___', DS, $url_file);
//            $template_view = ACTIVE_TEMPLATE_DIR.$test_file.'.php';
//            $template_view = normalize_path($template_view, false);
//            if (is_file($template_view) == true) {
//                $render_file = $template_view;
//            }
//        }
//
//        if ($render_file == false and $fallback_render_internal_file != false) {
//            if (is_file($fallback_render_internal_file)) {
//                $render_file = $fallback_render_internal_file;
//            }
//        }
//
//        if ($render_file == false and isset($page['active_site_template']) and strtolower($page['active_site_template']) != 'default') {
//            $template_view = ACTIVE_TEMPLATE_DIR.'index.php';
//            if (is_file($template_view) == true) {
//                $render_file = $template_view;
//            }
//        }
//
//        if ($render_file == false and isset($page['active_site_template']) and strtolower($page['active_site_template']) != 'default') {
//            $template_view = ACTIVE_TEMPLATE_DIR.'index.html';
//            if (is_file($template_view) == true) {
//                $render_file = $template_view;
//            }
//        }
//
//        if (isset($page['active_site_template']) and $render_file == false and strtolower($page['active_site_template']) != 'default') {
//            $template_view = ACTIVE_TEMPLATE_DIR.'index.htm';
//            if (is_file($template_view) == true) {
//                $render_file = $template_view;
//            }
//        }
//
//
//
//        if ($render_file == false and $template_view_set_inner != false) {
//            if (isset($template_view_set_inner2)) {
//                $template_view_set_inner2 = normalize_path($template_view_set_inner2, false);
//                if (is_file($template_view_set_inner2) == true) {
//                    $render_file = $template_view_set_inner2;
//                }
//            }
//            $template_view_set_inner = normalize_path($template_view_set_inner, false);
//            if ($render_file == false and is_file($template_view_set_inner) == true) {
//                $render_file = $template_view_set_inner;
//            }
//        }
//
//        if ($render_file != false and isset($page['custom_view'])) {
//            $check_custom = dirname($render_file).DS;
//            $check_custom_parent = dirname($render_file).DS;
//            $cv = trim($page['custom_view']);
//            $cv = sanitize_path($cv);
//            $cv = str_ireplace('.php', '', $cv);
//            $check_custom_f = $check_custom.$cv.'.php';
//
//            if (is_file($check_custom_f)) {
//                $render_file = $check_custom_f;
//            }
//        }
//
//        if ($render_file == false and isset($page['layout_file']) and ($page['layout_file']) != false) {
//            $page['layout_file'] = str_replace('__', DS, $page['layout_file']);
//
//            $template_view = ACTIVE_TEMPLATE_DIR.DS.$page['layout_file'];
//            $template_view = normalize_path($template_view, false);
//            if (is_file($template_view) == true) {
//                $render_file = $template_view;
//            }
//        }
//
//
//        return $render_file;
//    }
//
//
//}
