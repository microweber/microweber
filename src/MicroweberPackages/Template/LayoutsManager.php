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

use MicroweberPackages\Template\Adapters\ElementsConfigReader;
use MicroweberPackages\View\View;

class LayoutsManager
{
    public $app;
    private $external_layouts = array();

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
    }

    /**
     * Lists the layout files from a given directory.
     *
     * You can use this function to get layouts from various folders in your web server.
     * It returns array of layouts with desctption, icon, etc
     *
     * This function caches the result in the 'templates' cache group
     *
     * @param bool|array|string $options
     *
     * @return array|mixed
     *
     * @params $options['path'] if set i will look for layouts in this folder
     * @params $options['get_dynamic_layouts'] if set this function will scan for templates for the 'layout' module in all templates folders
     */
    public function get_all($options = false)
    {
        $layouts_from_template = $this->scan($options);
        $external_layouts = $this->external_layouts;
        if (is_array($layouts_from_template)) {
            $res = array_merge($layouts_from_template, $external_layouts);
        } else {
            $res = $external_layouts;
        }

        return $res;
    }

    public function get_layout_details($params)
    {
        $layout_options = array();

        $layout_options['site_template'] = $params['active_site_template'];
        $layout_options['no_cache'] = true;
        $layout_options['no_folder_sort'] = true;

        $layouts = $this->get_all($layout_options);

        if (isset($params["layout_file"]) and trim($params["layout_file"]) != '') {
            $params['layout_file'] = sanitize_path($params['layout_file']);
            $params['layout_file'] = str_replace('____', '/', $params['layout_file']);
            $params['layout_file'] = str_replace('__', '/', $params['layout_file']);
            $params['layout_file'] = normalize_path($params['layout_file'], false);
            $params['layout_file'] = str_replace('\\', '/', $params['layout_file']);

        }


        if ($layouts) {
            foreach ($layouts as $layout) {
                if (isset($layout['layout_file']) and $layout['layout_file']) {
                    $layout['layout_file'] = str_replace('____', '/', $layout['layout_file']);
                    $layout['layout_file'] = str_replace('\\', '/', $layout['layout_file']);

                    if ($layout['layout_file'] == $params['layout_file']) {
                        return $layout;
                    }
                }
            }
        }

    }

    public function get_elements_from_current_site_template()
    {
        if (!defined('ACTIVE_TEMPLATE_DIR')) {
            $this->app->content_manager->define_constants();
        }

        $dir_name = ACTIVE_TEMPLATE_DIR . 'elements' . DS;

        $elements_config_reader = new ElementsConfigReader();
        if (is_dir($dir_name)) {
            $opts = array();
            $opts['path'] = $dir_name;
            $elements = $elements_config_reader->scan($dir_name);
            return $elements;
        }
    }



    public function scan($options = false)
    {
        $options = parse_params($options);
        if (!isset($options['path'])) {

            if (isset($options['site_template']) and (strtolower($options['site_template']) != 'default') and (trim($options['site_template']) != '')) {
                $tmpl = trim($options['site_template']);
                $check_dir = templates_dir() . '' . $tmpl;

                if (is_dir($check_dir)) {
                    $the_active_site_template = $tmpl;
                    $path = $check_dir;
                } else {
                    $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
                }
            } elseif (isset($options['site_template']) and (strtolower($options['site_template']) == 'mw_default')) {
                $options['site_template'] = 'default';
                $tmpl = trim($options['site_template']);
                $check_dir = templates_dir() . '' . $tmpl;
                if (is_dir($check_dir)) {
                    $the_active_site_template = $tmpl;
                } else {
                    $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
                }
            } else {
                $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
            }
            if ($the_active_site_template == '' or $the_active_site_template == 'mw_default') {
                $the_active_site_template = 'default';
            }

            $path = normalize_path(templates_dir() . $the_active_site_template);
        } else {
            $path = $options['path'];
        }
        if (isset($the_active_site_template) and trim($the_active_site_template) != 'default') {
            if (defined('DEFAULT_TEMPLATE_DIR') and (!isset($path) or $path == false or (!strstr($path, DEFAULT_TEMPLATE_DIR)))) {
                $use_default_layouts = $path . 'use_default_layouts.php';
                if (is_file($use_default_layouts)) {
                    $path = DEFAULT_TEMPLATE_DIR;
                }
            }
        }

        if(!isset($the_active_site_template)){
            $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
        }




        $is_laravel_template = false;
        //check if template is laravel type
        if(app()->bound('templates')){
            $laravelTemplate = app()->templates->find($the_active_site_template);

            if($laravelTemplate){
                $is_laravel_template = true;
            }
        }

        if($is_laravel_template){
            $pathViews = $laravelTemplate->getPath() .'/resources/views';
            $pathViews = normalize_path($pathViews, true);
            if(is_dir($pathViews)){
                $path = $pathViews;
            }

         }


        if (!isset($path) or $path == false) {
            return;
        }

        if (!isset($options['no_cache'])) {
            $args = func_get_args();
            $function_cache_id = '';
            foreach ($args as $k => $v) {
                $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
            }

            $cache_id = $function_cache_id = __FUNCTION__ . crc32($path . $function_cache_id);

            $cache_group = 'templates';

            $cache_content = $this->app->cache_manager->get($cache_id, $cache_group);

            if (($cache_content) != false) {
                return $cache_content;
            }
        }

        $glob_patern = '*.php';

        if($is_laravel_template){
            $glob_patern = '*.blade.php';
        }

        $template_dirs = array();
        if (isset($options['get_dynamic_layouts'])) {
            // $possible_dir = TEMPLATE_DIR.'modules'.DS.'layout'.DS;
            $possible_dir = TEMPLATE_DIR . 'modules' . DS . 'layout' . DS;
            $possible_dir2 = TEMPLATE_DIR;

            if (is_dir($possible_dir)) {
                $template_dirs[] = $possible_dir2;
                $dir2 = rglob($possible_dir . '*.php', 0);
                if (!empty($dir2)) {
                    foreach ($dir2 as $dir_glob) {
                        $dir[] = $dir_glob;
                    }
                }
            }

        }

        if (!isset($options['get_dynamic_layouts'])) {
            if (!isset($options['filename'])) {
                $dir = rglob($glob_patern, 0, $path);
            } else {
                $dir = array();
                $dir[] = $options['filename'];
            }
        } else {
        }


        $configs = array();
        if (!empty($dir)) {
            foreach ($dir as $filename) {
                $skip = false;
                if (!isset($options['get_dynamic_layouts'])) {
                    if (!isset($options['for_modules'])) {
                        if (strstr($filename, 'modules' . DS)) {
                            $skip = true;
                        }
                    } else {
                        if (!strstr($filename, 'modules' . DS)) {
                            $skip = true;
                        }
                    }
                }
                if ($skip == false and is_file($filename)) {

                    $fin = file_get_contents($filename);
                    $fin = preg_replace('/\r\n?/', "\n", $fin);

                    $here_dir = dirname($filename) . DS;
                    $to_return_temp = array();
                    if (preg_match('/type:.+/', $fin, $regs)) {
                        $result = $regs[0];
                        $result = str_ireplace('type:', '', $result);
                        $to_return_temp['type'] = trim($result);
                        $to_return_temp['directory'] = $here_dir;
                        if (strstr($here_dir, templates_dir())) {
                            $templ_dir = str_replace(templates_dir(), '', $here_dir);
                            if ($templ_dir != '') {
                                $templ_dir = explode(DS, $templ_dir);
                                if (isset($templ_dir[0])) {
                                    $to_return_temp['template_dir'] = $templ_dir[0];
                                }
                            }
                        }
                        if (strstr($here_dir, modules_path())) {
                            $templ_dir1 = str_replace(modules_path(), '', $here_dir);

                            $templ_dir1 = str_ireplace('templates', '', $templ_dir1);
                            $templ_dir1 = str_ireplace('\\', '/', $templ_dir1);
                            $templ_dir1 = str_ireplace('//', '/', $templ_dir1);
                            $templ_dir1 = rtrim($templ_dir1, '/\\');

                            $to_return_temp['module_directory'] = $templ_dir1;
                        }

                        if (strtolower($to_return_temp['type']) == 'layout') {
                            $to_return_temp['directory'] = $here_dir;

                            if (preg_match('/is_shop:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('is_shop:', '', $result);
                                $to_return_temp['is_shop'] = trim($result);
                            }

                            if (preg_match('/hidden:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('hidden:', '', $result);
                                $to_return_temp['hidden'] = trim($result);
                            }

                            if (preg_match('/name:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('name:', '', $result);
                                $to_return_temp['name'] = trim($result);
                            }

                            $to_return_temp['category'] = 'All';
                            if (preg_match('/category:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('category:', '', $result);
                                $result = trim($result);
                                $to_return_temp['category'] = $result;
                            }

                            if (preg_match('/is_default:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('is_default:', '', $result);
                                $to_return_temp['is_default'] = trim($result);
                            }
                            if (preg_match('/categories:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('categories:', '', $result);
                                $to_return_temp['categories'] = trim($result);
                            }

                            if (preg_match('/position:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('position:', '', $result);
                                $to_return_temp['position'] = intval($result);
                            } else {
                                $to_return_temp['position'] = 99999;
                            }

                            if (preg_match('/version:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('version:', '', $result);
                                $to_return_temp['version'] = trim($result);
                            }
                            if (preg_match('/visible:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('visible:', '', $result);
                                $to_return_temp['visible'] = trim($result);
                            }

                            if (preg_match('/icon:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('icon:', '', $result);
                                $to_return_temp['icon'] = trim($result);

                                $possible = $here_dir . $to_return_temp['icon'];
                                if (is_file($possible)) {
                                    $to_return_temp['icon'] = $this->app->url_manager->link_to_file($possible);
                                } else {
                                    unset($to_return_temp['icon']);
                                }
                            }

                            if (preg_match('/image:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('image:', '', $result);
                                $to_return_temp['image'] = trim($result);
                                $possible = $here_dir . $to_return_temp['image'];

                                if (is_file($possible)) {
                                    $to_return_temp['image'] = $this->app->url_manager->link_to_file($possible);
                                } else {
                                    unset($to_return_temp['image']);
                                }
                            }

                            if (preg_match('/description:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('description:', '', $result);
                                $to_return_temp['description'] = trim($result);
                            }

                            if (preg_match('/content_type:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('content_type:', '', $result);
                                $to_return_temp['content_type'] = trim($result);
                            }

                            if (preg_match('/tag:.+/', $fin, $regs)) {
                                $result = $regs[0];
                                $result = str_ireplace('tag:', '', $result);
                                $to_return_temp['tag'] = trim($result);
                            }

                            $layout_file = str_replace($path, '', $filename);
                            if (isset($options['get_dynamic_layouts'])) {
                                //   dd($template_dirs);
                                $layout_file = str_replace($possible_dir2, '', $layout_file);

                            }
                            if (isset($template_dirs) and !empty($template_dirs)) {
                                foreach ($template_dirs as $template_dir) {
                                    $layout_file = str_replace($template_dir, '', $layout_file);
                                }
                            }

                            if (isset($options['content_type']) && $options['content_type'] == 'post') {
                                if (isset($to_return_temp['content_type']) && $to_return_temp['content_type'] !== 'post') {
                                    continue;
                                }
                            } else {
                                if (isset($to_return_temp['content_type']) && $to_return_temp['content_type'] == 'post') {
                                    continue;
                                }
                            }

                            $layout_file = str_replace(DS, '/', $layout_file);
                            $layout_file_preview = str_replace('/', '__', $layout_file);

                            $skipLayoutFiles = [
                                '404.php',
                                'forgot_password.php',
                                'login.php',
                                'register.php',
                                'reset_password.php',
                                'layouts/sign-up.php',
                            ];


                            if (in_array($layout_file, $skipLayoutFiles)) {
                                continue;
                            }

                            if(!isset($the_active_site_template)){
                                $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
                            }
                            $to_return_temp['layout_file'] = $layout_file;
                            $to_return_temp['layout_file_preview'] = $layout_file_preview;
                            $to_return_temp['layout_file_preview_url'] = site_url('new-content-preview-' . uniqid()) . '?content_id=0&no_editmode=true&preview_layout=' . $layout_file_preview.'&preview_template='.$the_active_site_template;

                            $to_return_temp['filename'] = $filename;
                            $screen = str_ireplace('.php', '.png', $filename);
                            $screen_jpg = str_ireplace('.php', '.jpg', $filename);
                            $skin_settings_json = str_ireplace('.php', '.json', $filename);

                            if (is_file($skin_settings_json)) {
                                $to_return_temp['skin_settings_json_file'] = $skin_settings_json;
                            }


                            if (is_file($screen_jpg)) {
                                $to_return_temp['screenshot_file'] = $screen_jpg;
                            } elseif (is_file($screen)) {
                                $to_return_temp['screenshot_file'] = $screen;
                            }
                            if (isset($to_return_temp['screenshot_file'])) {
                                $to_return_temp['screenshot'] = $this->app->url_manager->link_to_file($to_return_temp['screenshot_file']);
                            }

                            $configs[] = $to_return_temp;
                        }
                    }
                }
            }

            if (!empty($configs)) {
                $sorted_by_pos = array();
                $sorted_by_pos_items = array();
                $sorted_by_pos_in_folder_items = array();
                $pos = 9999;
                foreach ($configs as $item) {
                    if (isset($item['position'])) {
                        $sorted_by_pos_items[$item['position']][] = $item;
                    } else {
                        $sorted_by_pos[$pos] = $item;
                    }
                    ++$pos;
                }


                $pos = 9999;
                foreach ($configs as $item) {
                    $item_folder_name = false;
                    if (isset($item['layout_file'])) {
                        $item_folder_name = dirname($item['layout_file']);

                    }
                    if (!$item_folder_name or $item_folder_name == '.') {
                        $item_folder_name = 'default';
                    }

                    if (!isset($sorted_by_pos_in_folder_items[$item_folder_name])) {
                        $sorted_by_pos_in_folder_items[$item_folder_name] = array();
                    }

                    if (isset($item['position'])) {
                        $sorted_by_pos_in_folder_items[$item_folder_name][$item['position']][] = $item;
                    } else {
                        $sorted_by_pos_in_folder_items[$item_folder_name][$pos] = $item;
                    }


                    ++$pos;
                }

                if ($sorted_by_pos_in_folder_items) {
                    foreach ($sorted_by_pos_in_folder_items as $k => $v) {
                        if (is_array($v)) {
                            ksort($v);
                            $sorted_by_pos_in_folder_items[$k] = $v;
                        }
                    }
                }


                if (!empty($sorted_by_pos_items)) {
                    ksort($sorted_by_pos_items);
                    foreach ($sorted_by_pos_items as $configs) {
                        $pos = 0;
                        foreach ($configs as $item) {
                            $sorted_by_pos[] = $item;
                            ++$pos;
                        }
                    }
                }


                if (!empty($sorted_by_pos)) {
                    $configs = $sorted_by_pos;
                }
                if (!isset($options['no_folder_sort'])) {
                    if ($sorted_by_pos_in_folder_items and !empty($sorted_by_pos_in_folder_items)) {
                        // sort by inner folders position
                        $configs = array();
                        foreach ($sorted_by_pos_in_folder_items as $sort) {
                            foreach ($sort as $item) {
                                foreach ($item as $item1) {
                                    $configs[] = $item1;
                                }
                            }
                        }
                    }
                }
                if (!isset($options['no_cache'])) {
                    $this->app->cache_manager->save($configs, $function_cache_id, $cache_group);
                }

                return $configs;
            }
        }
    }



    public function template_remove_custom_css($params)
    {
        $is_admin = $this->app->user_manager->is_admin();
        if ($is_admin == false) {
            return false;
        }
        if (is_string($params)) {
            $params = parse_params($params);
        }
        $template = false;
        $return_styles = false;
        if (isset($params['template']) and $params['template'] != '' and $params['template'] != false) {
            $template = $params['template'];
        } else {
            $template = $this->app->option_manager->get('current_template', 'template');
        }

        if (isset($params['return_styles'])) {
            $return_styles = $params['return_styles'];
        }

        if ($template != false) {
            if ($return_styles == true) {
                $tf = $this->template_check_for_custom_css($template, true);
                $tf2 = str_ireplace('.bak', '', $tf);

                if (rename($tf, $tf2)) {
                    return array('success' => 'Custom css is returned');
                } else {
                    return array('error' => 'File could not be returned');
                }
            } else {
                $tf = $this->template_check_for_custom_css($template);
                $tf2 = $tf . '.bak';

                $option = array();
                $option['option_value'] = '';
                $option['option_key'] = 'template_settings';
                $option['option_group'] = 'template_' . $template;

                $o = save_option($option);

                if (is_file($tf) and rename($tf, $tf2)) {
                    return array('success' => 'Custom css is removed');
                } else {
                    return array('error' => 'File could not be removed');
                }
            }
        }

        return $params;
    }

    public function template_check_for_custom_css($template_name, $check_for_backup = false)
    {
        $template = $template_name;
        if (trim($template) == '') {
            $template = 'default';
        }
        $final_file_blocks = array();

        if ($template != false) {
            $template_folder = userfiles_path() . 'css' . DS . $template . DS;

            $live_edit_css = $template_folder . 'live_edit.css';

            // $live_edit_css = $template_folder . 'live_edit.css';
            if ($check_for_backup == true) {
                $live_edit_css = $live_edit_css . '.bak';
            }
            $fcont = '';
            if (is_file($live_edit_css)) {
                return $live_edit_css;
            }
        }
    }

    public function template_save_css($params)
    {
        $is_admin = $this->app->user_manager->is_admin();
        if ($is_admin == false) {
            return false;
        }

        if (is_string($params)) {
            $params = parse_params($params);
        }

        $ref_page = false;

        if (!isset($params['active_site_template'])) {
            if (!isset($params['content_id'])) {
                if (isset($_SERVER['HTTP_REFERER'])) {
                    $ref_page_url = $_SERVER['HTTP_REFERER'];

                    if ($ref_page_url != '') {
                        $ref_page_url_rel = str_ireplace(site_url(), '', $ref_page_url);

                        if ($ref_page_url_rel == '') {
                            $ref_page1 = $this->app->content_manager->homepage();
                        } else {
                            $ref_page1 = $this->app->content_manager->get_by_url($ref_page_url, true);
                        }
                        if (isset($ref_page1['id'])) {
                            $ref_page = $this->app->content_manager->get_by_id(intval($ref_page1['id']));
                        }
                    }
                }
            } else {
                $ref_page = $this->app->content_manager->get_by_id(intval($params['content_id']));
            }

            if (isset($ref_page['id']) and isset($ref_page['content_type']) and $ref_page['content_type'] != 'page') {
                $ref_page_parent = $this->app->content_manager->get_by_id(intval($ref_page['id']));
                if (isset($ref_page_partent['parent']) and intval($ref_page_parent['parent']) != 0) {
                    $ref_page = $this->app->content_manager->get_by_id(intval($ref_page_parent['id']));
                } else {
                    $ref_page_parents = $this->app->content_manager->get_parents(intval($ref_page['id']));
                    if (!empty($ref_page_parents)) {
                        $ref_page_parent = array_pop($ref_page_parents);
                        $ref_page = $this->app->content_manager->get_by_id($ref_page_parent);
                    }
                }
            }
        } else {
            $ref_page = $params;
        }

        if (!is_array($ref_page) or empty($ref_page)) {
            return false;
        }
        $pd = $ref_page;

        if ($is_admin == true and is_array($pd)) {
            $save_page = $pd;

            if (isset($save_page['layout_file']) and $save_page['layout_file'] == 'inherit') {
                $inherit_from_id = $this->app->content_manager->get_inherited_parent($save_page['id']);
                $inherit_from = $this->app->content_manager->get_by_id($inherit_from_id);
                if (is_array($inherit_from) and isset($inherit_from['active_site_template'])) {
                    $save_page['active_site_template'] = $inherit_from['active_site_template'];
                    $save_page['layout_file'] = $inherit_from['layout_file'];
                }
            }
            $template = false;
            if (!isset($save_page['active_site_template']) or $save_page['active_site_template'] == '') {
                $template = 'default';
            } elseif (isset($save_page['active_site_template'])) {
                $template = $save_page['active_site_template'];
            }

            if ($template == 'default') {
                $site_template_settings = $this->app->option_manager->get('current_template', 'template');
                if ($site_template_settings != false and $site_template_settings != 'default') {
                    $template = $site_template_settings;
                }
            }

            $final_file_blocks = array();

            if ($template != false) {
                if (isset($_POST['save_template_settings'])) {
                    $json = json_encode($_POST);
                    $option = array();
                    $option['option_value'] = $json;
                    $option['option_key'] = 'template_settings';
                    $option['option_group'] = 'template_' . $template;
                    save_option($option);
                }

                $template_folder = templates_dir() . $template . DS;
                $template_url = templates_url() . $template . '/';
                $this_template_url = THIS_TEMPLATE_URL;

                $template_folder = userfiles_path() . 'css' . DS . $template . DS;
                $template_folder_url = userfiles_url() . 'css' . '/' . $template . '/';
                if (!is_dir($template_folder)) {
                    mkdir_recursive($template_folder);
                }

                $live_edit_css = $template_folder . 'live_edit.css';

                $css_cont = false;
                $css_cont_new = false;
                if (isset($params['css_file_content'])) {
                    $css_cont_new = $params['css_file_content'];

                }

                app()->template_manager->liveEditCssAdapter->saveLiveEditCssContent($css_cont_new, $template);
                $saveCustomCssPath = app()->template_manager->liveEditCssAdapter->getLiveEditCssPath($template);
                $saveCustomCssUrl = app()->template_manager->liveEditCssAdapter->getLiveEditCssUrl($template);

                $resp = array();
                $resp['url'] =$saveCustomCssUrl;


                $resp['content'] = $css_cont_new;

                return $resp;
            }
        }
    }

    public function add_external($arr)
    {
        $this->external_layouts[] = ($arr);

        return $this->external_layouts;
    }


}
