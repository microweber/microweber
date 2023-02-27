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

use Illuminate\Support\Facades\Cache;
use Microweber\App\Providers\Illuminate\Support\Facades\Paginator;
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


    public function element_display($element_filename, $attr = array())
    {
        $element_file = false;
        $file = $element_filename;
        if (!str_ends_with($element_filename, '.php')) {
            $file = $file . '.php';
        }


        $file = sanitize_path($file);
        $file_from_default = normalize_path(elements_path() . $file, false);
        $file_from_template = normalize_path(template_dir() . 'elements' . DS . $file, false);
        if (is_dir(template_dir() . 'elements') and is_file($file_from_template)) {
            $element_file = $file_from_template;
        } elseif (is_dir(elements_path()) and is_file($file_from_default)) {
            $element_file = $file_from_default;
        }


        if ($element_file) {
            $view = new View($element_file);
            if(is_array($attr) and !empty($attr)){
                $view->set($attr);
            }
            return $view->display();
        }
    }

    public function scan($options = false)
    {
        $options = parse_params($options);
        if (!isset($options['path'])) {
            if (isset($options['site_template']) and (strtolower($options['site_template']) != 'default') and (trim($options['site_template']) != '')) {
                $tmpl = trim($options['site_template']);
                $check_dir = templates_path() . '' . $tmpl;

                if (is_dir($check_dir)) {
                    $the_active_site_template = $tmpl;
                } else {
                    $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
                }
            } elseif (isset($options['site_template']) and (strtolower($options['site_template']) == 'mw_default')) {
                $options['site_template'] = 'default';
                $tmpl = trim($options['site_template']);
                $check_dir = templates_path() . '' . $tmpl;
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

            $path = normalize_path(templates_path() . $the_active_site_template);
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
                        if (strstr($here_dir, templates_path())) {
                            $templ_dir = str_replace(templates_path(), '', $here_dir);
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

                            $layout_file = str_replace(DS, '/', $layout_file);
                            $to_return_temp['layout_file'] = $layout_file;
                            $to_return_temp['filename'] = $filename;
                            $screen = str_ireplace('.php', '.png', $filename);
                            $screen_jpg = str_ireplace('.php', '.jpg', $filename);
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

    public function get_link($options = false)
    {
        $args = func_get_args();
        $function_cache_id = '';
        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $cache_id = __FUNCTION__ . crc32($function_cache_id);
        //get cache from memory
        $mem = mw_var($cache_id);
        if ($mem != false) {
            return $mem;
        }

        $options = parse_params($options);
        $fn = false;

        if (isset($options[0])) {
            $fn = $options[0];
        } elseif (is_array($options)) {
            $val = current($options);
            $fn = key($options);
        }

        $page_url_segment_1 = $this->app->url_manager->segment(0);
        $td = templates_path() . $page_url_segment_1;
        $td_base = $td;

        $page_url_segment_2 = $this->app->url_manager->segment(1);
        $directly_to_file = false;
        $page_url_segment_3 = $this->app->url_manager->segment();

        if (!is_dir($td_base)) {
            array_shift($page_url_segment_3);
            //$page_url_segment_1 =	$the_active_site_template = $this->app->option_manager->get('current_template');
            //$td_base = templates_path() .   $the_active_site_template.DS;
        } else {
        }
        if (empty($page_url_segment_3)) {
            $page_url_segment_str = '';
        } else {
            $page_url_segment_str = $page_url_segment_3[0];
        }
        //$page_url_segment_str = implode('/', $page_url_segment_3);
        $fn = $this->app->url_manager->site($page_url_segment_str . '/' . $fn);
        //d($page_url_segment_3);

        //set cache in memory
        mw_var($cache_id, $fn);

        return $fn;
    }

    public function save($data_to_save)
    {
        if (is_admin() == false) {
            return false;
        }
        if (isset($data_to_save['is_element']) and $data_to_save['is_element'] == true) {
            exit(__FILE__ . __LINE__ . d($data_to_save));
        }

        $table = 'elements';
        $save = false;

        if (!empty($data_to_save)) {
            $s = $data_to_save;

            if (!isset($s['parent_id'])) {
                $s['parent_id'] = 0;
            }
            if (!isset($s['id']) and isset($s['module'])) {
                $s['module'] = $data_to_save['module'];
                if (!isset($s['module_id'])) {
                    $save = $this->get('limit=1&module=' . $s['module']);
                    if ($save != false and isset($save[0]) and is_array($save[0])) {
                        $s['id'] = $save[0]['id'];
                        $save = $this->app->database_manager->save($table, $s);
                    } else {
                        $save = $this->app->database_manager->save($table, $s);
                    }
                }
            } else {
                $save = $this->app->database_manager->save($table, $s);
            }
        }

        if ($save != false) {
            $this->app->cache_manager->delete('elements' . DIRECTORY_SEPARATOR . '');
            $this->app->cache_manager->delete('elements' . DIRECTORY_SEPARATOR . 'global');
        }

        return $save;
    }

    public function get($params = false)
    {
        $table = 'elements';
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $options = $params2;
        }
        $params['table'] = $table;
        $params['group_by'] = 'module';
        $params['orderby'] = 'position asc';

        $params['cache_group'] = 'elements/global';
        if (isset($params['id'])) {
            $params['limit'] = 1;
        } else {
            $params['limit'] = 1000;
        }

        if (!isset($params['ui'])) {
            //   $params['ui'] = 1;
        }

        $s = $this->app->database_manager->get($params);

        return $s;
    }

    public function delete_all()
    {
        if (is_admin() == false) {
            return false;
        } else {
            $table = get_table_prefix() . 'elements';

            $db_categories = get_table_prefix() . 'categories';
            $db_categories_items = get_table_prefix() . 'categories_items';

            $q = "DELETE FROM $table ";
            //   d($q);
            $this->app->database_manager->q($q);

            $q = "DELETE FROM $db_categories WHERE rel_type='elements' AND data_type='category' ";
            // d($q);
            $this->app->database_manager->q($q);

            $q = "DELETE FROM $db_categories_items WHERE rel_type='elements' ";
            // d($q);
            $this->app->database_manager->q($q);
            $this->app->cache_manager->delete('categories' . DIRECTORY_SEPARATOR . '');
            $this->app->cache_manager->delete('categories_items' . DIRECTORY_SEPARATOR . '');

            $this->app->cache_manager->delete('elements' . DIRECTORY_SEPARATOR . '');
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

                $template_folder = templates_path() . $template . DS;
                $template_url = templates_url() . $template . '/';
                $this_template_url = THIS_TEMPLATE_URL;

                $template_folder = userfiles_path() . 'css' . DS . $template . DS;
                if (!is_dir($template_folder)) {
                    mkdir_recursive($template_folder);
                }

                $live_edit_css = $template_folder . 'live_edit.css';

                $css_cont = false;
                if (isset($params['css_file_content'])) {
                    $css_cont_new = $params['css_file_content'];

                } else {


                    $fcont = '';
                    if (is_file($live_edit_css)) {
                        $fcont = file_get_contents($live_edit_css);
                    }

                    $css_cont = $fcont;
                    $css_cont_new = $css_cont;

                    //@import on top
                    $sort_params = array();
                    $sort_params2 = array();
                    foreach ($params as $item) {
                        if (isset($item['selector']) and trim($item['selector']) == '@import' and isset($item['value'])) {
                            if ($item['value'] != 'reset') {
                                $sort_params[] = $item;
                            }
                        } else {
                            $sort_params2[] = $item;
                        }
                    }


                    $params = array_merge($sort_params, $sort_params2);


                    foreach ($params as $item) {
                        $curr = '';

                        if (isset($item['css']) and isset($item['selector']) and !isset($item['property'])) {
                            //  $item['property'] =  $item['css'];
                        }


                        if (!isset($item['css']) and isset($item['property']) and isset($item['value'])) {
                            if ($item['value'] == 'reset') {
                                $item['css'] = 'reset';
                            } else {

                                if (isset($item['selector']) and trim($item['selector']) == '@import' and isset($item['value'])) {
                                    $props = explode(',', $item['property']);

                                    foreach ($props as $prop) {
                                        $curr .= $prop . ' ' . $item['value'] . ';' . "\n";
                                    }
                                } else {
                                    $props = explode(',', $item['property']);
                                    $curr = '';
                                    foreach ($props as $prop) {
                                        if (isset($item['value']) and trim($item['value']) != '') {
                                            $curr .= $prop . ':' . $item['value'] . ';' . "\n";
                                        }
                                    }
                                }
                                if ($curr != '') {
                                    $item['css'] = $curr;
                                }
                            }
                        } else {

                            if (!isset($item['css']) and !isset($item['selector'])) {
                                $find_css = $this->__array_search_key('css', $item);
                                $find_selector = $this->__array_search_key('selector', $item);
                                if ($find_css and $find_selector) {
                                    $item['css'] = $find_css;
                                    $item['selector'] = $find_selector;
                                }

                            }

                            if (isset($item['css'])) {

                                $props = explode(';', $item['css']);
                                $curr = '';
                                $css_props = array();
                                foreach ($props as $prop) {

                                    $prop_key = substr($prop, 0, strpos($prop, ':'));
                                    $prop_val = substr($prop, strpos($prop, ':') + 1, 9999);
                                    $prop_key = trim($prop_key);
                                    $prop_val = trim($prop_val);
                                    if ($prop_key and $prop_val) {
                                        $css_props[$prop_key] = $prop_val;
                                    }

                                }
                                $curr = '';
                                if ($css_props) {
                                    foreach ($css_props as $prop_k => $prop_v) {
                                        $curr .= $prop_k . ':' . $prop_v . '; ' . "\n";
                                    }
                                }
                                if ($curr != '') {
                                    $item['css'] = $curr;
                                }
                            }
                        }

                        if (isset($item['selector']) and trim($item['selector']) != '' and isset($item['css'])) {
                            $item['selector'] = str_ireplace('.element-current', '', $item['selector']);
                            $item['selector'] = str_ireplace('.mwfx', '', $item['selector']);
                            $item['selector'] = str_ireplace('.mw_image_resizer', '', $item['selector']);
                            $item['selector'] = str_ireplace('.ui-resizable', '', $item['selector']);
                            $item['selector'] = str_ireplace('.ui-draggable', '', $item['selector']);
                            $item['css'] = str_ireplace('background:url(;', '', $item['css']);
                            $item['css'] = str_ireplace('background:;', '', $item['css']);
                            $item['css'] = str_ireplace('background-image:url(;', '', $item['css']);
                            $item['css'] = str_ireplace('background-image: url("");', 'background-image: none;', $item['css']);

                            $sel = trim($item['selector']);
                            $css = trim($item['css']);

                            if (trim($sel) != '' and strlen($sel) > 2 and strlen($css) > 2) {
                                $delim = "\n /* $sel */ \n";

                                //$item["css"] = str_ireplace($this_template_url, '', $item["css"]);
                                //$item["css"] = str_ireplace($template_url, '', $item["css"]);

                                $item['css'] = str_ireplace('http://', '//', $item['css']);
                                $item['css'] = str_ireplace('https://', '//', $item['css']);

                                $is_existing = explode($delim, $css_cont_new);

                                if (!empty($is_existing)) {
                                    $srings = $this->app->format->string_between($css_cont_new, $delim, $delim);

                                    if ($srings != false) {
                                        $css_cont_new = str_ireplace($srings, '', $css_cont_new);
                                        $css_cont_new = str_ireplace($delim, '', $css_cont_new);
                                    }
                                }
                                if (trim($item['css']) != 'reset' and trim($item['css']) != 'reset;') {
                                    $css_cont_new .= $delim;
                                    if (isset($sel) and trim($sel) == '@import') {
                                        $css_cont_new .= $sel . ' ' . $item['css'] . ' ';
                                    } else {
                                        $css_cont_new .= $sel . ' { ' . $item['css'] . ' }';
                                    }
                                    $css_cont_new .= $delim;
                                }
                            }
                        }
                    }
                }
                $resp = array();
                $resp['url'] = $this->app->url_manager->link_to_file($live_edit_css);
                if ($css_cont_new != '' and $css_cont != $css_cont_new) {
                    file_put_contents($live_edit_css, $css_cont_new);
                    //  print $css_cont_new;
                } else if ($css_cont_new == '' and isset($params['css_file_content'])) {
                    file_put_contents($live_edit_css, '');
                }


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


    private function __array_search_key($needle_key, $array)
    {
        foreach ($array AS $key => $value) {
            if ($key == $needle_key) return $value;
            if (is_array($value)) {
                if (($result = $this->__array_search_key($needle_key, $value)) !== false)
                    return $result;
            }
        }
        return false;
    }
}
