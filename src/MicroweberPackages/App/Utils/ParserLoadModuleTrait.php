<?php


namespace MicroweberPackages\App\Utils;

use MicroweberPackages\View\View;


trait ParserLoadModuleTrait
{


    public $module_registry = array();
    public $module_load_registry = array();
    public $module_load_registry_is_loaded = array();

    public $_current_parser_module_of_type = array();
    public $prev_module_data = array();
    public $_existing_module_ids_grouped = array();
    public $_existing_module_ids = array();


    public function load($module_name, $attrs = array())
    {


        //  $mod_id_value = 'load' . crc32($module_name . json_encode($attrs['id']));

        //   $mod_id_value = 'load'.crc32($module_name . json_encode($attrs));
        //   $mod_id_value = $attrs['id'];


        if (isset($attrs['id'])) {
            $mod_id_value = 'load_module_' . ($module_name . $attrs['id']);
            // $this->registry->registerParsedModule($module_name,$attrs['id']);

        } else {
            $mod_id_value = 'load' . crc32($module_name . json_encode($attrs['id']));

        }


        if (isset($this->module_load_registry[$mod_id_value])) {

            return $this->module_load_registry[$mod_id_value];
        }

        if (isset($that->module_load_registry_is_loaded[$mod_id_value])) {
            return $this->module_load_registry[$mod_id_value];
        }

        if ($this->debugbarEnabled) {
            \Debugbar::startMeasure('render_module_' . $module_name, 'Rendering ' . $module_name);
        }


        $this->module_load_registry[$mod_id_value] = $this->load_module_callback($module_name, $attrs);
        $this->module_load_registry_is_loaded[$mod_id_value] = 1;

        if ($this->debugbarEnabled) {
            \Debugbar::stopMeasure('render_module_' . $module_name, $attrs);
        }


        return $this->module_load_registry[$mod_id_value];


    }

    private function load_module_callback($module_name, $attrs = array())
    {
        $is_element = false;
        $custom_view = false;
        if (isset($attrs['view'])) {
            $custom_view = $attrs['view'];
            $custom_view = trim($custom_view);
            $custom_view = str_replace('\\', '/', $custom_view);
            $attrs['view'] = $custom_view = sanitize_path($custom_view);
        }

        /*   if ($custom_view != false and strtolower($custom_view) == 'admin') {
               if (app()->user_manager->is_admin() == false) {
                   mw_error($custom_view. 'Not logged in as admin');
               }
           }*/

        $module_name = trim($module_name);
        $module_name = str_replace('\\', '/', $module_name);
        $module_name = sanitize_path($module_name);
        // prevent hack of the directory
        $module_name = reduce_double_slashes($module_name);

        $module_namei = $module_name;

        if (strstr($module_name, 'admin')) {
            $module_namei = str_ireplace('\\admin', '', $module_namei);
            $module_namei = str_ireplace('/admin', '', $module_namei);
        }

        //$module_namei = str_ireplace($search, $replace, $subject)


        if (!defined('ACTIVE_TEMPLATE_DIR')) {
            app()->content_manager->define_constants();
        }


        if (isset($attrs) and is_array($attrs) and !empty($attrs)) {
            $attrs2 = array();
            foreach ($attrs as $attrs_k => $attrs_v) {
                $attrs_k2 = substr($attrs_k, 0, 5);
                if (strtolower($attrs_k2) == 'data-') {
                    $attrs_k21 = substr($attrs_k, 5);
                    $attrs2[$attrs_k21] = $attrs_v;
                } elseif (!isset($attrs['data-' . $attrs_k])) {
                    $attrs2['data-' . $attrs_k] = $attrs_v;
                }

                $attrs2[$attrs_k] = $attrs_v;
            }
            $attrs = $attrs2;
        }


        if (isset($attrs['module-id']) and $attrs['module-id'] != false) {
            $attrs['id'] = $attrs['module-id'];
        }

        if (!isset($attrs['id'])) {
            global $mw_mod_counter;
            ++$mw_mod_counter;
            //  $seg_clean = app()->url_manager->segment(0);
            $seg_clean = app()->url_manager->segment(0, url_current());


            if (defined('IS_HOME')) {
                $seg_clean = '';
            }
            $seg_clean = str_replace('%20', '-', $seg_clean);
            $seg_clean = str_replace(' ', '-', $seg_clean);
            $seg_clean = str_replace('.', '', $seg_clean);
            $attrs1 = crc32(serialize($attrs) . $seg_clean . $mw_mod_counter);
            $attrs1 = str_replace('%20', '-', $attrs1);
            $attrs1 = str_replace(' ', '-', $attrs1);
            $attrs['id'] = ($this->module_css_class($module_name) . '-' . $attrs1);
        }
        if (isset($attrs['id']) and strstr($attrs['id'], '__MODULE_CLASS_NAME__')) {
            $attrs['id'] = str_replace('__MODULE_CLASS_NAME__', $this->module_css_class($module_name), $attrs['id']);
            //$attrs['id'] = ('__MODULE_CLASS__' . '-' . $attrs1);
        }


        if (isset($this->module_registry[$module_name]) and $this->module_registry[$module_name]) {
            return \App::call($this->module_registry[$module_name], ["params" => $attrs]);
        } else if (isset($this->module_registry[$module_name . '/index']) and $this->module_registry[$module_name . '/index']) {
            return \App::call($this->module_registry[$module_name . '/index'], ["params" => $attrs]);
        }

        $module_in_template_dir = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '';
        $module_in_template_dir = normalize_path($module_in_template_dir, 1);
        $module_in_template_file = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '.php';
        $module_in_template_file = normalize_path($module_in_template_file, false);

        $try_file1 = false;

        $mod_d = $module_in_template_dir;
        $mod_d1 = normalize_path($mod_d, 1);
        $try_file1zz = $mod_d1 . 'index.php';
        $in_dir = false;

        if ($custom_view == true) {
            $try_file1zz = $mod_d1 . trim($custom_view) . '.php';
        } else {
            $try_file1zz = $mod_d1 . 'index.php';
        }

        if (is_dir($module_in_template_dir) and is_file($try_file1zz)) {
            $try_file1 = $try_file1zz;

            $in_dir = true;
        } elseif (is_file($module_in_template_file)) {
            $try_file1 = $module_in_template_file;
            $in_dir = false;
        } else {
            $module_in_default_dir = modules_path() . $module_name . '';
            $module_in_default_dir = normalize_path($module_in_default_dir, 1);
            // d($module_in_default_dir);
            $module_in_default_file = modules_path() . $module_name . '.php';
            $module_in_default_file_custom_view = modules_path() . $module_name . '_' . $custom_view . '.php';

            $element_in_default_file = elements_path() . $module_name . '.php';
            $element_in_default_file = normalize_path($element_in_default_file, false);

            //

            $module_in_default_file = normalize_path($module_in_default_file, false);

            if (is_file($module_in_default_file)) {
                $in_dir = false;
                if ($custom_view == true and is_file($module_in_default_file_custom_view)) {
                    $try_file1 = $module_in_default_file_custom_view;
                } else {
                    $try_file1 = $module_in_default_file;
                }
            } else {
                if (is_dir($module_in_default_dir)) {
                    $in_dir = true;
                    $mod_d1 = normalize_path($module_in_default_dir, 1);

                    if ($custom_view == true) {
                        $try_file1 = $mod_d1 . trim($custom_view) . '.php';
                    } else {
                        $try_file1 = $mod_d1 . 'index.php';
                    }
                } elseif (is_file($element_in_default_file)) {
                    $in_dir = false;
                    $is_element = true;

                    $try_file1 = $element_in_default_file;
                }
            }
        }
        //
        if (isset($try_file1) != false and $try_file1 != false and is_file($try_file1)) {
            if (isset($attrs) and is_array($attrs) and !empty($attrs)) {
                $attrs2 = array();
                foreach ($attrs as $attrs_k => $attrs_v) {
                    $attrs_k2 = substr($attrs_k, 0, 5);
                    if (strtolower($attrs_k2) == 'data-') {
                        $attrs_k21 = substr($attrs_k, 5);
                        $attrs2[$attrs_k21] = $attrs_v;
                    } elseif (!isset($attrs['data-' . $attrs_k])) {
                        $attrs2['data-' . $attrs_k] = $attrs_v;
                    }

                    $attrs2[$attrs_k] = $attrs_v;
                }
                $attrs = $attrs2;
            }
            $config = array();
            $config['path_to_module'] = $config['mp'] = $config['path'] = normalize_path((dirname($try_file1)) . '/', true);
            $config['the_module'] = $module_name;
            $config['module'] = $module_name;
            $module_name_dir = dirname($module_name);
            $config['module_name'] = $module_name_dir;

            $config['module_name_url_safe'] = $this->module_name_encode($module_name);

            $find_base_url = app()->url_manager->current(1);
            if ($pos = strpos($find_base_url, ':' . $module_name) or $pos = strpos($find_base_url, ':' . $config['module_name_url_safe'])) {
                $find_base_url = substr($find_base_url, 0, $pos) . ':' . $config['module_name_url_safe'];
            }
            $config['url'] = $find_base_url;

            $config['url_main'] = $config['url_base'] = strtok($find_base_url, '?');

            if ($in_dir != false) {
                $mod_api = str_replace('/admin', '', $module_name);
            } else {
                $mod_api = str_replace('/admin', '', $module_name_dir);
            }

            $config['module_api'] = app()->url_manager->site('api/' . $mod_api);
            $config['module_view'] = app()->url_manager->site('module/' . $module_name);
            $config['ns'] = str_replace('/', '\\', $module_name);
            $config['module_class'] = $this->module_css_class($module_name);

            $config['url_to_module'] = app()->url_manager->link_to_file($config['path_to_module']);

            if (isset($attrs['id'])) {
                $attrs['id'] = str_replace('__MODULE_CLASS_NAME__', $config['module_class'], $attrs['id']);

                $template = false;
            }


//            if($module_name == '.'){
//                return;
//            }


            //  $installed_module = app()->module_manager->get('single=1&ui=any&module=' . $module_name);
            $installed_module = app()->module_repository->getModule($module_name);


            if ($installed_module and isset($installed_module['settings'])) {
                $config['settings'] = $installed_module['settings'];
            }

//            $is_installed = app()->module_manager->is_installed($module_name);
//
//            if(!$is_installed){
//                d($module_name);
//                return '';
//            }


            $modules_dir_default = modules_path() . $module_name;
            $modules_dir_default = normalize_path($modules_dir_default, true);
            $module_name_root = mw()->module_manager->locate_root_module($module_name);
            $modules_dir_default_root = modules_path() . $module_name_root;
            $modules_dir_default_root = normalize_path($modules_dir_default_root, true);


            if ($module_name_root and is_dir($modules_dir_default_root) and is_file($modules_dir_default_root . 'config.php')) {
                $is_installed = app()->module_manager->is_installed($module_name_root);
                if (!$is_installed) {
                    return '';

                }
            }


            if (isset($installed_module['installed']) and $installed_module['installed'] != '' and intval($installed_module['installed']) != 1) {
                return '';
            }
            if (isset($installed_module['type']) and !isset($config['module_type'])) {
                $config['module_type'] = $installed_module['type'];
            } else {
                $config['module_type'] = null;
            }

            //$config['url_to_module'] = rtrim($config['url_to_module'], '///');
            $lic = app()->module_manager->license($module_name);
            //  $lic = 'valid';
            if ($lic != false) {
                $config['license'] = $lic;
            }

            if (isset($attrs['module-id']) and $attrs['module-id'] != false) {
                $attrs['id'] = $attrs['module-id'];
            }

            if (!isset($attrs['id'])) {
                global $mw_mod_counter;
                ++$mw_mod_counter;
                //  $seg_clean = app()->url_manager->segment(0);
                $seg_clean = app()->url_manager->segment(0, url_current());


                if (defined('IS_HOME')) {
                    $seg_clean = '';
                }
                $seg_clean = str_replace('%20', '-', $seg_clean);
                $seg_clean = str_replace(' ', '-', $seg_clean);
                $seg_clean = str_replace('.', '', $seg_clean);
                $attrs1 = crc32(serialize($attrs) . $seg_clean . $mw_mod_counter);
                $attrs1 = str_replace('%20', '-', $attrs1);
                $attrs1 = str_replace(' ', '-', $attrs1);
                $attrs['id'] = ($config['module_class'] . '-' . $attrs1);
            }
            if (isset($attrs['id']) and strstr($attrs['id'], '__MODULE_CLASS_NAME__')) {
                $attrs['id'] = str_replace('__MODULE_CLASS_NAME__', $config['module_class'], $attrs['id']);
                //$attrs['id'] = ('__MODULE_CLASS__' . '-' . $attrs1);
            }

            //load scripts and css
            $module_css = '';
            $module_css_file = dirname($try_file1) . DS . 'module.css';
            if (is_file($module_css_file)) {
                $module_css = @file_get_contents($module_css_file);

                if ($module_css) {
                    $module_css = str_replace('#module', '#' . url_title($attrs['id']), $module_css);
                }
            }


            $l1 = new View($try_file1);
            $l1->config = $config;
            $l1->app = app();

            if (!isset($attrs['module'])) {
                $attrs['module'] = $module_name;
            }
            if (!isset($attrs['type'])) {
                $attrs['type'] = $module_name;
            }

//            if (!isset($attrs['parent-module'])) {
//                $attrs['parent-module'] = $module_name;
//            }
//
//            if (!isset($attrs['parent-module-id'])) {
//                $attrs['parent-module-id'] = $attrs['id'];
//            }
//            $mw_restore_get = mw_var('mw_restore_get');
//            if ($mw_restore_get != false and is_array($mw_restore_get)) {
//                $l1->_GET = $mw_restore_get;
//                $_GET = $mw_restore_get;
//            }
            if (defined('MW_MODULE_ONDROP')) {
                if (!isset($attrs['ondrop'])) {
                    $attrs['ondrop'] = true;
                }
            }
            $l1->params = $attrs;

            //   $this->registry->registerParsedModule($module_name,$attrs['id']);


            if ($config) {
                $this->current_module = ($config);
            }
            if ($attrs) {
                $this->current_module_params = ($attrs);
            }
            if (isset($attrs['view']) && (trim($attrs['view']) == 'empty')) {
                $module_file = EMPTY_MOD_STR;
            } elseif (isset($attrs['view']) && (trim($attrs['view']) == 'admin')) {
                $module_file = $l1->__toString();
            } else {
                if (isset($attrs['display']) && (trim($attrs['display']) == 'custom')) {
                    $module_file = $l1->__get_vars();

                    return $module_file;
                } elseif (isset($attrs['format']) && (trim($attrs['format']) == 'json')) {
                    $module_file = $l1->__get_vars();
                    header('Content-type: application/json');
                    exit(json_encode($module_file));
                } else {
                    $module_file = $l1->__toString();
                }
            }
            //	$l1 = null;
            unset($l1);
            if ($lic != false and isset($lic['error']) and ($lic['error'] == 'no_license_found')) {
                $lic_l1_try_file1 = MW_ADMIN_VIEWS_DIR . 'activate_license.php';
                $lic_l1 = new \MicroweberPackages\View\View($lic_l1_try_file1);

                $lic_l1->config = $config;
                $lic_l1->params = $attrs;

                $lic_l1e_file = $lic_l1->__toString();
                unset($lic_l1);
                $module_file = $lic_l1e_file . $module_file;
            }

            if ($module_css and $module_file and is_string($module_file)) {
                $module_file .= "
                <style>"

                    . $module_css .


                    "</style>

                ";
            }


            // $mw_loaded_mod_memory[$function_cache_id] = $module_file;
            return $module_file;
        } else {
            //define($cache_content, FALSE);
            // $mw_loaded_mod_memory[$function_cache_id] = false;
            return false;
        }
    }

    public function replace_non_cached_modules_with_placeholders($layout)
    {
        //   $non_cached
        $non_cached = app()->module_manager->get('allow_caching=0&ui=any');
        $has_changes = false;


        if (!$non_cached or $layout == '') {
            return $layout;
        }
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';

        $pq = \phpQuery::newDocument($layout);


        $remove_clases = ['changed', 'inaccessibleModule', 'module-over', 'currentDragMouseOver', 'mw-webkit-drag-hover-binded'];
        $found_mods = array();
        $found_mods_non_cached = array();
        //  foreach ($pq ['.module'] as $elem) {
        foreach ($pq->find('.module') as $elem) {
            $attrs = $elem->attributes;
            $tag = $elem->tagName;


            $module_html = '<' . $tag . ' ';
            if (!empty($attrs)) {
                $mod_name = false;
                $mod_name_is_cached = true;
                foreach ($attrs as $attribute_name => $attribute_node) {
                    $v = $attribute_node->nodeValue;

                    if ($attribute_name == 'type'
                        or $attribute_name == 'data-type'
                        or $attribute_name == 'type'
                    ) {
                        $mod_name = $v;
                        $found_mods[] = $mod_name;
                    }
                }
                foreach ($non_cached as $mod) {
                    if (isset($mod['module'])
                        and $mod_name
                        and $mod_name == $mod['module']
                    ) {
                        $has_changes = true;
                        $mod_name_is_cached = false;

                        $found_mods_non_cached[] = $mod_name;
                    }
                }

                if (!$mod_name_is_cached and $mod_name and $has_changes) {


                    foreach ($attrs as $attribute_name => $attribute_node) {

                        $v = $attribute_node->nodeValue;


                        if ($attribute_name == 'class') {
                            $v = str_replace('module ', 'mw-lazy-load-module module ', $v);
                        }


                        $module_html .= " {$attribute_name}='{$v}'  ";
                        $has_changes = true;


                    }

                    if ($has_changes) {
                        $module_html .= '><!-- Loading module ' . $mod_name . ' --><' . $tag . '/>';


                        $elem = pq($elem);

                        $elem->replaceWith($module_html);


                    }


                }

            }


        }


        if ($has_changes) {
            $layout = $pq->htmlOuter();
        }
        return $layout;

    }

    private function _process_additional_module_parsers($layout, $module, $params)
    {
        $type = 'module';
        if (isset($this->_additional_parsers[$type]) and $this->_additional_parsers[$type]) {
            $parsers_callbacks = $this->_additional_parsers[$type];
            foreach ($parsers_callbacks as $parser_callback) {
                if (is_callable($parser_callback)) {
                    $res = call_user_func($parser_callback, $layout, $module, $params);
                    if ($res) {
                        $layout = $res;
                    }
                }
            }
        }
        return $layout;
    }



}
