<?php

namespace MicroweberPackages\Module\Http\Controllers\Api;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\Controller;

class ModulesApiLiveEdit extends Controller
{
    public function index(Request $request)
    {

        $lockedLayouts = true;
        if (have_license('modules/white_label')) {
            $lockedLayouts = false;
        }

        $modules_by_categories = array();
        $show_grouped_by_cats = false;
        $is_elements = false;
        $hide_dynamic_layouts = false;
        $disable_elements = false;
        $template_config = app()->template_manager->get_config();
        $params = $request->all();
        if (isset($template_config['elements_mode']) and $template_config['elements_mode'] == 'disabled') {
            $disable_elements = true;
        }

        $template_composer = [];
        $template_composer_file = template_dir() . '/composer.json';
        if (is_file($template_composer_file)) {
            $template_composer = json_decode(file_get_contents($template_composer_file), true);
        }

        if (isset($params['elements_mode']) and $params['elements_mode']) {
            $is_elements = true;
        }
        if (isset($params['hide-dynamic']) and $params['hide-dynamic']) {
            $hide_dynamic_layouts = true;
        }

        if (isset($params['group_modules_by_category']) and $params['group_modules_by_category']) {
            $show_grouped_by_cats = true;
        }

        if (isset($params['group_layouts_by_category']) and $params['group_layouts_by_category']) {
            $show_grouped_by_cats = true;
        }

        if (isset($template_config['use_dynamic_layouts_for_posts']) and $template_config['use_dynamic_layouts_for_posts']) {
            $hide_dynamic_layouts = false;
        }

        if (isset($is_elements) and $is_elements == true) {

            $el_params = array();
            if (isset($params['layout_type'])) {
                $el_params['layout_type'] = $params['layout_type'];
            }


            if (isset($template_config['group_layouts_by_category']) and $template_config['group_layouts_by_category']) {
                $show_grouped_by_cats = true;
            }

//            $modules = mw()->layouts_manager->get($el_params);
//
//            if ($modules == false) {
//                $el_params['no_cache'] = true;
//                mw()->module_manager->scan_for_elements($el_params);
//                $modules = mw()->layouts_manager->get($el_params);
//            }

            // if ($modules == false) {
            $modules = array();
            //   }

//            $elements_from_template = mw()->layouts_manager->get_elements_from_current_site_template();
//            if (!empty($elements_from_template)) {
//                $modules = array_merge($elements_from_template, $modules);
//            }

            if ($disable_elements) {
                $modules = array();
            }

            $template_dir = template_dir();
            $active_site_template = false;
            if (isset($params['active_site_template'])) {
                $filter_template_dir = templates_dir() . $params['active_site_template'] . DS;
                $filter_template_dir = normalize_path($filter_template_dir, true);
                if (is_dir($filter_template_dir)) {
                    $template_dir = $filter_template_dir;
                }
                $active_site_template = $params['active_site_template'];
            }

            $module_layouts_skins = app()->microweber->getTemplates('layouts', $active_site_template);

            //    $dynamic_layouts = mw()->layouts_manager->get_all('no_cache=1&get_dynamic_layouts=1');
            //$module_layouts_skins = mw()->module_manager->templates('layouts', false, false, $template_dir);
            $dynamic_layouts = false;

            if ($hide_dynamic_layouts) {
                $dynamic_layouts = false;
                $module_layouts_skins = false;
            }


        } else {


            $modulesFromRegistry = app()->microweber->getModulesDetails();

            $modules = mw()->module_manager->get('installed=1&ui=1');
            $module_layouts = mw()->module_manager->get('installed=1&module=layouts');
            $hide_from_display_list = array('layouts', 'template_settings');
            $sortout_el = array();
            $sortout_mod = array();
            if ($modules) {
                foreach ($modules as $mkey => $mod) {
                    $mod['icon'] = '';  // old modules do not have icons
                    $modules[$mkey] = $mod;
                }
            }
            if ($modulesFromRegistry) {


                foreach ($modulesFromRegistry as $modkey => $mod) {
                    if (isset($mod['icon']) and ($mod['icon'])) {
                        try {

                            //  $mod['icon'] = 'data:image/svg+xml;utf8,'.$this->encodeURIComponent(svg($mod['icon'])->toHtml());
                            $mod['icon'] = 'data:image/svg+xml;base64,' . base64_encode(svg($mod['icon'])->toHtml());
                            //  $mod['icon'] = svg($mod['icon'])->toHtml();
                        } catch (\Exception $e) {

                            $mod['icon'] = '';
                        }

                    } else {

                    }
                    $modulesFromRegistry[$modkey] = $mod;
                }


                $modules = array_merge($modulesFromRegistry, $modules);
            }


            if (!empty($modules)) {
                foreach ($modules as $mod) {
                    if (isset($mod['as_element']) and $mod['as_element']) {
                        $sortout_el[] = $mod;
                    } else {
                        $sortout_mod[] = $mod;
                    }
                }
                $modules = array_merge($sortout_el, $sortout_mod);
                if ($modules and !empty($module_layouts)) {
                    $modules = array_merge($modules, $module_layouts);
                }
            }

            $modules_from_template = mw()->module_manager->get_modules_from_current_site_template();
            if (!empty($modules_from_template)) {
                if (!is_array($modules)) {
                    $modules = array();
                }
                foreach ($modules as $module) {
                    foreach ($modules_from_template as $k => $module_from_template) {
                        if (isset($module['name']) and isset($module_from_template['name'])) {
                            if ($module['name'] == $module_from_template['name']) {
                                unset($modules_from_template[$k]);
                            }
                        }
                    }
                }
                $modules = array_merge($modules, $modules_from_template);
            }

            if ($modules) {
                foreach ($modules as $modk => $module) {
                    if (isset($module['name']) and
                        (in_array($module['name'], $hide_from_display_list)
                            or in_array(strtolower($module['name']), $hide_from_display_list))
                    ) {
                        //  unset($modules[$modk]);
                    }
                }
            }

            $is_shop_disabled = get_option('shop_disabled', 'website') == "y";

            if ($modules) {
                foreach ($modules as $mkey => $module) {
                    if (!isset($module['categories']) or !($module['categories'])) {
                        $module['categories'] = 'other';
                    }
                    if (isset($module['categories']) and ($module['categories'])) {
                        $mod_cats = explode(',', $module['categories']);

                        if ($mod_cats) {
                            $skip_m = false;
                            if ($is_shop_disabled and in_array('online shop', $mod_cats)) {
                                $skip_m = true;
                            }

                            if (!$skip_m) {
                                foreach ($mod_cats as $mod_cat) {
                                    $mod_cat = trim($mod_cat);
                                    if (!isset($modules_by_categories[$mod_cat])) {
                                        $modules_by_categories[$mod_cat] = array();
                                    }
                                    $modules_by_categories[$mod_cat][] = $module;
                                }

                            } else {
                                unset($modules[$mkey]);
                            }
                        }
                    }
                }
            }
        }

        if ($modules_by_categories and is_array($modules_by_categories) and count($modules_by_categories) > 1) {
            $sort_first = array();

            $first_keys = array('recommended', 'media', 'content', 'navigation');
            foreach ($first_keys as $first_key) {
                if (isset($modules_by_categories[$first_key])) {
                    $sort_first[$first_key] = $modules_by_categories[$first_key];
                    unset($modules_by_categories[$first_key]);
                }
            }
            $modules_by_categories_new = array_merge($sort_first, $modules_by_categories);
            $modules_by_categories = $modules_by_categories_new;
        }
        if (($modules and !$modules_by_categories) or ($modules and !$show_grouped_by_cats)) {
            $modules_by_categories = array('Modules' => $modules);
        }

        if (isset($_COOKIE['recommend']) and is_string($_COOKIE['recommend']) and isset($modules) and is_array($modules)) {
            $recommended = json_decode($_COOKIE['recommend'], true);

            if (is_array($recommended) and !empty($recommended)) {
                $position = 9;
                $sorted_modules = array();
                arsort($recommended);
                foreach ($recommended as $key => $value) {
                    foreach ($modules as $mod_key => $item) {
                        if (isset($item['module']) and isset($item['position']) and $item['position'] > $position) {
                            if ($key == $item['module']) {
                                $sorted_modules[] = $item;
                            }
                        }
                    }
                }

                if (!empty($sorted_modules)) {
                    //arsort( $sorted_modules);
                    if (!empty($modules)) {
                        $re_sorted_modules = array();
                        $temp = array();
                        $modules_copy = $modules;
                        foreach ($modules_copy as $key => $item) {
                            if (is_array($sorted_modules) and !empty($sorted_modules)) {
                                foreach ($sorted_modules as $key2 => $sorted_module) {
                                    if ($sorted_module['module'] == $item['module']) {
                                        unset($modules_copy[$key]);
                                    }
                                }
                            }
                        }
                        foreach ($modules_copy as $key => $item) {
                            $re_sorted_modules[] = $item;

                            if (!isset($item['position'])) {
                                $item['position'] = 999;
                            }

                            if ($item['position'] > $position) {
                                if (is_array($sorted_modules) and !empty($sorted_modules)) {
                                    foreach ($sorted_modules as $key2 => $sorted_module) {
                                        $re_sorted_modules[] = $sorted_module;
                                        unset($sorted_modules[$key2]);
                                    }
                                }

                            }

                        }
                        if (!empty($re_sorted_modules)) {
                            $modules = $re_sorted_modules;
                        }
                    }
                }
            }

        }

        $moduleListJson = [];

        if (isset($dynamic_layouts) and is_array($dynamic_layouts)) {


            $i = 0;

            foreach ($dynamic_layouts as $dynamic_layout) {

                if (isset($dynamic_layout['template_dir']) and isset($dynamic_layout['layout_file'])) {

                    $dynamic_layout['locked'] = false;
                    if (isset($template_composer['extra']['premium_layouts'])
                        && !empty($template_composer['extra']['premium_layouts'])) {
                        foreach ($template_composer['extra']['premium_layouts'] as $composerPremiumLayout) {
                            if (strpos($composerPremiumLayout, $dynamic_layout['layout_file']) !== false) {
                                $dynamic_layout['icon'] = 'fa fa-lock';
                                $dynamic_layout['locked'] = $lockedLayouts;
                            }
                        }
                    }

                    $moduleListJson['layouts'][] = [
                        //'group' => 'layouts',
                        'locked' => $dynamic_layout['locked'],
                        'template' => $dynamic_layout['layout_file'],
                        'name' => $dynamic_layout['name'],
                        'icon' => $dynamic_layout['icon'],
                        'screenshot' => isset($dynamic_layout['screenshot']) ? $dynamic_layout['screenshot'] : '',
                        'description_raw' => $dynamic_layout['description'],
                        'description' => addslashes($dynamic_layout['description']),
                        'title' => titlelize(_e($dynamic_layout['name'], true)),
                    ];
                }

            }

        }

        if (isset($module_layouts_skins) and is_array($module_layouts_skins)) {

            $i = 0;
            $module_layouts_skins_grouped = [];
            foreach ($module_layouts_skins as $module_layouts_skin) {
                if (!$show_grouped_by_cats) {
                    $expCategories = ['Other'];

                } else {
                    if (isset($module_layouts_skin['categories'])) {
                        $expCategories = explode(',', $module_layouts_skin['categories']);
                        array_walk($expCategories, 'trim');
                    } else {
                        $expCategories = ['Other'];

                    }

                }
                if (!empty($expCategories)) {
                    foreach ($expCategories as $category) {
                        $category = strtolower($category);
                        $category = trim($category);
                        $module_layouts_skins_grouped[$category][] = $module_layouts_skin;
                    }
                }
            }

            $module_layouts_skins_grouped_ordered_positions = [
                'content',
                'header',
                'design',
                'features',
                'gallery',
                'testimonials',
                'ecommerce',
                'contact us',
                'call to action',
                'blog',
                'team',
                'grids',
                'misc',
                'price lists',
                'titles',
                'text block',
                'video',
                'menu',
                'footers',
                'other',
            ];
            if (isset($template_config['order_layouts_by_category']) && !empty($template_config['order_layouts_by_category'])) {
                $module_layouts_skins_grouped_ordered_positions = $template_config['order_layouts_by_category'];
            }

            $module_layouts_skins_grouped_ordered = [];
            foreach ($module_layouts_skins_grouped_ordered_positions as $ordered_position) {
                foreach ($module_layouts_skins_grouped as $dynamic_layouts_group_name => $dynamic_layouts_grouped) {
                    if ($ordered_position == $dynamic_layouts_group_name) {
                        $module_layouts_skins_grouped_ordered[$dynamic_layouts_group_name] = $dynamic_layouts_grouped;
                        unset($module_layouts_skins_grouped[$dynamic_layouts_group_name]);
                    }
                }
            }
            $module_layouts_skins_grouped_ordered = array_merge($module_layouts_skins_grouped_ordered, $module_layouts_skins_grouped);
            $module_layouts_skins_grouped = $module_layouts_skins_grouped_ordered;


            foreach ($module_layouts_skins_grouped as $dynamic_layouts_group_name => $dynamic_layouts_grouped) {
                $dynamic_layouts_group_name_orig = $dynamic_layouts_group_name;

                $dynamic_layouts_group_name = str_slug($dynamic_layouts_group_name);


                foreach ($dynamic_layouts_grouped as $dynamic_layout) {
                    $randId = uniqid();

                    if (isset($dynamic_layout['layout_file'])) {

                        if (!isset($dynamic_layout['icon'])) {
                            $dynamic_layout['icon'] = '';
                        }
                        if (!isset($dynamic_layout['description'])) {
                            $dynamic_layout['description'] = '';
                        }

                        if (isset($dynamic_layout['categories'])) {
                            $moduleListJson['categories'][$dynamic_layout['categories']] = true;
                        }

                        $dynamic_layout['locked'] = false;

                        if (isset($template_composer['extra']['premium_layouts'])
                            && !empty($template_composer['extra']['premium_layouts'])) {
                            foreach ($template_composer['extra']['premium_layouts'] as $composerPremiumLayout) {
                                if (strpos($composerPremiumLayout, $dynamic_layout['layout_file']) !== false) {
                                    $dynamic_layout['icon'] = 'fa fa-lock';
                                    $dynamic_layout['locked'] = $lockedLayouts;
                                }
                            }
                        }

                        if (isset($dynamic_layout['hidden'])) {
                            if ($dynamic_layout['hidden'] == true || $dynamic_layout['hidden'] == 1) {
                                continue;
                            }
                        }
                        if (!isset($dynamic_layout['screenshot'])) {
                            if (isset($dynamic_layout['screenshot_file']) and ($dynamic_layout['screenshot_file']) and is_file($dynamic_layout['screenshot_file'])) {
//                                $dynamic_layout['screenshot'] = thumbnail($dynamic_layout['screenshot_file'], 1024);
                                $dynamic_layout['screenshot'] = $dynamic_layout['screenshot_public_url'];
                            }
                        }
                        $moduleListJson['layouts'][] = [
                            // 'group' => 'layouts',
                            'template' => $dynamic_layout['layout_file'],
                            'locked' => $dynamic_layout['locked'],
                            'name' => $dynamic_layout['name'],
                            'icon' => $dynamic_layout['icon'],
                            'categories' => isset($dynamic_layout['categories']) ? $dynamic_layout['categories'] : '',
                            'screenshot' => isset($dynamic_layout['screenshot']) ? $dynamic_layout['screenshot'] : '',
                            'description_raw' => $dynamic_layout['description'],
                            'description' => addslashes($dynamic_layout['description']),
                            'title' => titlelize($dynamic_layout['name']),
                        ];
                    }
                }
            }
        }

        if (isset($modules) and !empty($modules)) {
            foreach ($modules_by_categories as $mod_cat => $modules) {
                $i = 0;
                foreach ($modules as $module_item) {
                    $i++;
                    if (isset($module_item['module'])) {


                        $module_group2 = explode(DIRECTORY_SEPARATOR, $module_item['module']);
                        $module_group2 = $module_group2[0];


                        $module_item['module'] = str_replace('\\', '/', $module_item['module']);

                        $module_item['module'] = rtrim($module_item['module'], '/');
                        $module_item['module'] = rtrim($module_item['module'], '\\');
                        $temp = array();
                        if (isset($module_item['categories']) and is_array($module_item['categories']) and !empty($module_item['categories'])) {
                            foreach ($module_item['categories'] as $it) {
                                $temp[] = $it['parent_id'];
                            }
                            $module_item['categories'] = implode(',', $temp);
                        }


                        $module_item['module_clean'] = str_replace('/', '__', $module_item['module']);
                        $module_item['name_clean'] = str_replace('/', '-', $module_item['module']);
                        $module_item['name_clean'] = str_replace(' ', '-', $module_item['name_clean']);
                        if (isset($module_item['categories']) and is_array($module_item['categories'])) {
                            $module_item['categories'] = implode(',', $module_item['categories']);
                        }

                        if (isset($module_item['categories']) && !empty($module_item['categories'])) {
                            $moduleListJson['categories'][$module_item['categories']] = true;
                        }

                        if (!isset($module_item['description'])) {
                            $module_item['description'] = $module_item['name'];
                        }


                        $module_id = $module_item['name_clean'] . '_' . uniqid($i);

                        if (!isset($module_item['icon'])) {
                            $module_item['icon'] = '';
                        }
                        if (!isset($module_item['description'])) {
                            $module_item['description'] = '';
                        }
                        if (!isset($module_item['template'])) {
                            $module_item['template'] = '';
                        }

                        if (!isset($module_item['categories'])) {
                            $module_item['categories'] = [];
                        }

                        if (!isset($module_item['settings'])) {
                            $module_item['settings'] = [];
                        }

                        if (!isset($module_item['as_element'])) {
                            $module_item['as_element'] = '';
                        }
                        if (!isset($module_item['icon'])) {
                            $module_item['icon'] = '';
                        }

                        $title = $module_item["name"];
                        $title = _e($title, true);


                        $moduleDataItem = [
                            'module_id' => $module_id,
                            'hidden' => false,
                            'mod_cat' => $mod_cat,
                            'name' => $module_item['name'],
                            'title' => $title,
                            'name_clean' => $module_item['name_clean'],
                            'position' => $module_item['position'],
                            'icon' => $module_item['icon'],
                            'module' => $module_item['module'],
                            'module_clean' => $module_item['module_clean'],
                            'categories' => $module_item['categories'],
                            'template' => $module_item['template'],
                            'description' => $module_item['description'],
                            'settings' => $module_item['settings'],
                            'as_element' => $module_item['as_element'] ?? false,
                            'keywords' => $module_item['keywords'] ?? $module_item['name'],
                        ];

                        if (isset($hide_from_display_list)) {
                            $moduleDataItem['hidden'] = $hide_from_display_list;
                        }

                        $moduleListJson['modules'][] = $moduleDataItem;
                    }
                }
            }
        }

        if (isset($moduleListJson['categories'])
            && !empty($moduleListJson['categories'])
            && is_array($moduleListJson['categories'])) {

            $moduleListJson['categories'] = array_keys($moduleListJson['categories']);
        } else {
            $moduleListJson['categories'] = [];
        }

        return $moduleListJson;
    }


    public function getSkins(Request $request)
    {
        $module = $request->get('module');

        $module = str_replace('__', '/', $module);
        $module_templates = module_templates($module);

        $ready = [];

        if ($module_templates) {
            foreach ($module_templates as $item) {
                $ready[] = [
                    'name' => $item['name'] ?? '',
                    'description' => $item['description'] ?? '',
                    'template' => $item['layout_file'] ?? '',
                    'category' => $item['category'] ?? '',
                    'position' => $item['position'] ?? '',
                ];
            }
        }

        return $ready;

    }

    function encodeURIComponent($str)
    {
        $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
        return strtr(rawurlencode($str), $revert);
    }
}
