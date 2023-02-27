<?php

namespace MicroweberPackages\Category\HelperRenders;

use Knp\Menu\MenuFactory;
use MicroweberPackages\Category\HelperRenders\KnpCustomListRenderer as ListRenderer;


class KnpCategoryTreeRenderer
{


    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    /** @var \Knp\Menu\MenuItem */
    private $menu_instance;

    /** @var \Knp\Menu\MenuFactory */
    private $menu_factory;

    private $active_item_id = false;
    private $use_cache = false;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
        $this->menu_factory = new MenuFactory();


        $active_cat = false;
        if (defined('CATEGORY_ID')) {
            $active_cat = CATEGORY_ID;
        }
        if (!$active_cat) {
            $cat_url = $this->app->category_manager->get_category_id_from_url();
            if ($cat_url != false) {
                $active_cat = $cat_url;
            }
        }
        $this->active_item_id = $active_cat;

    }


    //   $params['content_link_class'] = "mw-tree-renderer-admin-content-link-item";
    //   $params['title_slashes'] = "mw-tree-renderer-admin-content-link-item";
    //   $params['exteded_classes'] = "mw-tree-renderer-admin-content-link-item";
    //   $params['link']
    //   $params['url']
    //   $params['title'] = "";
    //   $params['items_count'] = "";
    //   $params['exteded_classes'] = "";


    public function render($params)
    {

        $return_instead_of_print  = false;
        if (isset($params['return_data']) and $params['return_data']) {
            $return_instead_of_print  = true;
        }

        $permalinkStructure = get_option('permalink_structure', 'website');
        $cache_id = __CLASS__ . __FUNCTION__ . crc32(json_encode($params) . $permalinkStructure.current_lang());
        $cache_group = 'categories';
        if ($this->use_cache) {
            $results = cache_get($cache_id, $cache_group, 600);
            if ($results) {
                if ($return_instead_of_print) {
                    return $results;
                } else {
                    print $results;
                    return;
                }
            }
        }


        $tree_data = false;
        $list_tag = 'ul';
        if (isset($params['list_tag'])) {
            $list_tag = $params['list_tag'];
        }
        $list_item_tag = 'li';
        if (isset($params['list_item_tag'])) {
            $list_item_tag = $params['list_item_tag'];
        }
        if (isset($params['li_class_name'])) {
            $li_class_name = $params['li_class_name'];
        }

        if (isset($params['tree_data'])) {
            $tree_data = $params['tree_data'];
        }


        if (!isset($li_class_name)) {
            $li_class_name = false;
        }
        if (isset($params['removed_ids_code'])) {
            $removed_ids_code = $params['removed_ids_code'];
        } else {
            $removed_ids_code = false;
        }
        $ul_class_name = '';
        $ul_class_name_deep = '';
        $li_class_name_deep = '';
        $li_hidden_class= '';
        $link_class = '';
        $extra_attributes = array();
        if (isset($params['link_class'])) {
            $link_class = $params['link_class'];
        }
        if (isset($params['a_class'])) {
            $link_class = $params['a_class'];

        }
        if (isset($params['li_hidden_class'])) {
            $li_hidden_class = $params['li_hidden_class'];
        }
        if (isset($params['extra_attributes'])) {
            $extra_attributes = $params['extra_attributes'];
        }


        if (isset($params['class'])) {
            $ul_class_name = $params['class'];
        }
        if (isset($params['ul_class'])) {
            $ul_class_name = $params['ul_class'];
        }

        if (isset($params['ul_class_name'])) {
            $ul_class_name = $params['ul_class_name'];
        }
        if (isset($params['ul_class_deep'])) {
            $ul_class_name_deep = $params['ul_class_deep'];
        }
        if (isset($params['ul_class_name_deep'])) {
            $ul_class_name_deep = $params['ul_class_name_deep'];
        }
        if (isset($params['li_class'])) {
            $li_class_name = $params['li_class'];
        }
        if (isset($params['li_class_name_deep'])) {
            $li_class_name_deep = $params['li_class_name_deep'];
        }
        if (isset($params['li_class_deep'])) {
            $li_class_name_deep = $params['li_class_deep'];
        }
        if (isset($params['categories_active_ids'])) {
            $params['active_ids'] = $params['categories_active_ids'];
        }

        $active_class = 'active';
        $active_parent_class = 'active-parent';


        if (isset($params['active_class'])) {
            $active_class = $params['active_class'];
        }
        if (isset($params['active_parent_class'])) {
            $active_parent_class = $params['active_parent_class'];

        }
        $extra_attributes['set_active_class'] = $active_class;
        $extra_attributes['set_active_parent_class'] = $active_parent_class;


        $link = isset($params['link']) ? $params['link'] : false;

        $custom_link_html_replaces = array();
//        if ($link != false) {
//            if (!is_array($extra_attributes)) {
//                $extra_attributes = array();
//            }
//            if (isset($params['active_code'])) {
//                $extra_attributes['active_code'] = $params['active_code'];
//            }
//            if (isset($params['active_ids'])) {
//                $extra_attributes['active_ids'] = $params['active_ids'];
//            }
//
//            //  $link = "<a href='{categories_url}' data-category-id='{id}'  {active_code} class='{active_class} {nest_level}'>{title}</a>";
//        }
        if (!$tree_data) {
            $data_provider = new \MicroweberPackages\Category\HelperRenders\CategoryTreeData($this->app);
            $tree_data = $data_provider->get($params);

        }
        if (!$tree_data) {

            if ($this->use_cache) {
                cache_save('', $cache_id, $cache_group);
            }

            return '';
        }

        $options = $options_children = array(
            'depth' => null,
            // 'matchingDepth' => null,
            'currentAsLink' => true,
            'currentClass' => $active_class,
            'ancestorClass' => $active_parent_class,
            'firstClass' => 'first',
            'lastClass' => 'last',
            'compressed' => false,
            'allow_safe_labels' => false,

            'branch_class' => $ul_class_name,
            'leaf_class' => $li_class_name,

            'branch_class_deep' => $ul_class_name_deep,
            'leaf_class_deep' => $li_class_name_deep,


            'link_class' => $link_class,
            'li_hidden_class' => $li_hidden_class,


            'branch_tag' => $list_tag,
            'leaf_tag' => $list_item_tag,

            'custom_link_html' => $link,
            'extra_attributes' => $extra_attributes
            //   'linkAttributes' => ['target' => '_blank'],
        );

        $menu_attrs = array();


        $classes = array();
        $classes[] = $ul_class_name;
        if (isset($params['nest_level'])) {
            $this->start_level = intval($params['nest_level']);
            $classes[] = 'depth-' . $this->start_level;
        }


        $menu_attrs['class'] = implode(' ', $classes);


        //  $classes[] = 'depth-' . $level;


        $main_menu = $this->menu_factory->createItem('Menu', $options)
            ->setAttributes($menu_attrs)
            ->setChildrenAttributes($menu_attrs);
        // $menu = $this->menu_factory->createItem('Menu', $options);


        //$this->menu_instance = $menu;

        $main_menu = $this->__process_nodes($main_menu, $tree_data, $options, $params);


        $renderer = new ListRenderer(new \Knp\Menu\Matcher\Matcher(), $options);
        $tree = $renderer->render($main_menu);

        if ($tree) {
            if ($this->use_cache) {
                cache_save($tree, $cache_id, $cache_group);
            }
        }

        if ($return_instead_of_print) {
            return $tree;
        } else {
            print $tree;
        }

    }

    private $level = 0;
    private $start_level = 0;

    private function __process_nodes($menu = false, $tree_data = [], $options = [], $params = [])
    {
        /** @var $menu \Knp\Menu\MenuItem */

        if (!isset($options['__process_nodes_level'])) {
            $options['__process_nodes_level'] = 0;
        }

        array_map(function ($data) use ($menu, $tree_data, $options, $params) {

            //  $menu = $this->menu_instance;

            $has_children = false;


            if (isset($data['children']) and !empty($data['children'])) {
                $has_children = true;
            }


            $nest_level = 0;
            if (isset($params['nest_level'])) {
                $params['nest_level']++;
                $nest_level = intval($params['nest_level']);
            }
            // $level = $this->level + $nest_level;
            // $level = $this->level + $nest_level;
            // $options['__process_nodes_level']++;
            if ($has_children) {


                $this->level++;
            } else {
                //  $options['__process_nodes_level'] = 0;
            }


         //   $level = $options['__process_nodes_level'] + $nest_level;
            $level = $options['__process_nodes_level'] + $nest_level;
            if ($has_children) {
                // $level++;
            }

            if (isset($params['max_level'])) {
                 if ($level > 0 and (intval($params['max_level']) == $level)) {
                    return;
                }
            }

            $url = $this->app->category_manager->link($data['id']);

            $child_opts = $options;
            $ul_class = $options['branch_class_deep'];
            $li_class = $options['leaf_class'];
            $link_class = $options['link_class'];

            $li_hidden_class = '';
            if (isset($options['li_hidden_class'])) {
                $li_hidden_class = $options['li_hidden_class'];
            }

            $active_ids = array($this->active_item_id);
            if (isset($params['active_ids']) and $params['active_ids']) {
                if (!is_array($params['active_ids']) and $params['active_ids']) {
                    $a = explode(',', $params['active_ids']);
                    $params['active_ids'] = $a;
                }
                $active_ids = array_merge($active_ids, $params['active_ids']);
                $active_ids = array_map('intval', $active_ids);

            }

            $active_code = false;
            if (isset($params['active_code'])) {
                $active_code = $params['active_code'];
            }


            $extra_attributes = isset($options['extra_attributes']) ? $options['extra_attributes'] : array();
            if (!is_array($extra_attributes)) {
                $extra_attributes = array();
            }


            $custom_html = $options['custom_link_html'];


            if ($custom_html) {

                if (!$extra_attributes) {
                    $extra_attributes = array();
                }
                $extra_attributes = array_merge($extra_attributes, array(
                    'link' => true, 'page-id' => true, 'nest_level' => true, 'title' => true, 'id' => true
                ));
            }


            if ($extra_attributes and !empty($extra_attributes)) {

                foreach ($extra_attributes as $extra_attribute_key => $extra_attribute_value) {
                    switch ($extra_attribute_key) {
                        case 'link':
                        case 'url':
                            $extra_attribute_value = $url;
                            break;
                        case 'data-category-id':
                        case 'category-id':
                        case 'id':
                            $extra_attribute_value = $data['id'];
                            break;
                        case 'data-page-id':
                        case 'page-id':
                        case 'page_id':
                            $extra_attribute_related_item = $this->app->category_manager->get_page($data['id']);
                            if ($extra_attribute_related_item and isset($extra_attribute_related_item['id'])) {
                                $extra_attribute_value = $extra_attribute_related_item['id'];
                            }
                            break;

                        case 'data-count':
                        case 'data-items-count':
                        case 'items-count':
                        case 'count':
                            $extra_attribute_value = $this->app->category_manager->get_items_count($data['id']);

                            break;

                        case 'data-category-type':
                        case 'data-categories-type':

                            $extra_attribute_value = $data['data_type'];
                            break;
                        case 'nest_level':

                            $extra_attribute_value = 'depth-' . $level;
                            break;
                        default :
                            if (isset($data[$extra_attribute_key])) {
                                $extra_attribute_value = $data[$extra_attribute_key];

                            }
                            break;


                    }

                    foreach ($data as $item_k => $item_v) {
                        if (is_string($item_k) and (is_string($item_v) or is_numeric($item_v))) {
                            $extra_attribute_value = str_replace('{' . $item_k . '}', $item_v, $extra_attribute_value);
                        }
                    }
                    $menu_attrs[$extra_attribute_key] = $extra_attribute_value;
                    $extra_attributes[$extra_attribute_key] = $extra_attribute_value;
                }
            }


            if ($custom_html) {
                $child_opts['custom_link_html'] = $custom_html;
            }


            $menu->addChild($data['id'],
                $child_opts
            );
            //$level = $menu[$data['id']]->getLevel();


            //    $level = $menu->getLevel();


            $active_class = $options['currentClass'];
            $active_parent_class = $options['ancestorClass'];
            $leaf_tag = $options['leaf_tag'];



            if ($level > 0) {
                $ul_class = $options['branch_class_deep'];
                $li_class = $options['leaf_class_deep'];
            }


            $link_attrs = array();
            $link_attrs['data-category-id'] = $data['id'];
            if ($data['parent_id']) {
                $link_attrs['data-category-parent-id'] = $data['parent_id'];
            }
            $link_attrs['title'] = $data['title'];

            $classes = array();
            $classes[] = $link_class;
            $classes[] = 'depth-' . $level;
            if ($data['parent_id']) {
                $classes[] = 'have-parent';
            }

            $classes = array_unique($classes);
            $link_attrs['class'] = implode(' ', $classes);


            $children_attrs = array();

            $children_attrs['data-category-id'] = $data['id'];
            $classes = array();
            $classes[] = $ul_class;
            $classes[] = 'depth-' . $level;
            $classes[] = 'category-item-' . $data['id'];
            //  $classes[] = 'category_element';




            $children_attrs['class'] = implode(' ', $classes);


            $menu_attrs = array();
            $menu_attrs['title'] = $data['title'];

            $menu_attrs['data-category-id'] = $data['id'];
            if ($leaf_tag == 'option'
                or $leaf_tag == 'radio'
                or $leaf_tag == 'checkbox'
            ) {
                $menu_attrs['value'] = $data['id'];

            }
            if ($data['parent_id']) {
                $menu_attrs['data-category-parent-id'] = $data['parent_id'];
            }


            $classes = array();
            $classes[] = $li_class;
            $classes[] = 'depth-' . $level;

            if(isset($data['is_hidden']) and $data['is_hidden'] and  $li_hidden_class){
                $classes[] = $li_hidden_class;
            }

            if ($active_ids and !empty($active_ids)) {
                foreach ($active_ids as $active_id) {
                    if ($active_id == $data['id']) {
                        if ($leaf_tag == 'option') {
                            $menu_attrs['selected'] = 'selected';
                        }
                        if ($leaf_tag == 'radio' or $leaf_tag == 'checkbox') {
                            $menu_attrs['checked'] = 'checked';
                        }
//
                        if (isset($extra_attributes['set_active_class'])) {
                            $classes[] = $extra_attributes['set_active_class'];
                        } else {
                            $extra_attributes['active_class'] = '';
                        }
                        if (isset($extra_attributes['set_active_parent_class'])) {
                            $classes[] = $extra_attributes['active_parent_class'] = $extra_attributes['set_active_parent_class'];
                        }


                        if ($active_code) {
                            $extra_attributes['active_code'] = $active_code;
                        }

                        // if ($this->active_item_id == $data['id']) {
                        $menu[$data['id']]->setCurrent(true);
                        $classes[] = $active_class;




                        $extra_attributes['set_active_class'] = $active_class;
                        $extra_attributes['set_active_parent_class'] = $active_parent_class;


                        if ($has_children) {
                            $classes[] = $active_parent_class;
                        }

                    }
                }
            }

            $classes = array_unique($classes);

            $menu_attrs['class'] = trim(implode(' ', $classes));
            //if (!$has_children) {
            $menu[$data['id']]->setChildrenAttributes($children_attrs)
                ->setLabel($data['title'])
                ->setLinkAttributes($link_attrs)
                ->setAttributes($menu_attrs)
                ->setExtras($extra_attributes)
                ->setUri($url);
            // }


            if ($has_children) {
                // $this->level++;

                $child_opts['__process_nodes_level']++;

                $menu2 = $this->menu_factory->createItem($data['id'], $child_opts)
                    ->setChildrenAttributes($children_attrs)
                    ->setLabel($data['title'])
                    ->setLinkAttributes($link_attrs)
                    ->setAttributes($menu_attrs)
                    ->setExtras($extra_attributes)
                    ->setUri($url);

                $menu2 = $this->__process_nodes($menu2, $data['children'], $child_opts, $params);

//
//                $menu->addChild($menu2,
//                    $child_opts
//                );

                $menu->addChild($menu2,
                    $child_opts
                );
            } else {
                //$this->level = 0;
            }

        }, $tree_data);


        return $menu;
    }


    public function setUseCache($should_use_cache)
    {

        $this->use_cache = $should_use_cache;
    }

}


