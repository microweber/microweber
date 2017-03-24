<?php

namespace Microweber\Providers\Categories\Helpers;

use Knp\Menu\MenuFactory;
use Microweber\Providers\Categories\Helpers\CategoryTreeData;
use Microweber\Providers\Categories\Helpers\KnpCustomListRenderer as ListRenderer;


class KnpCategoryTreeRenderer
{


    /** @var \Microweber\Application */
    public $app;


    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }


    public function render($params, $tree_data = false)
    {

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
        if (isset($params['class'])) {
            $ul_class_name = $params['class'];
        }
        if (isset($params['ul_class'])) {
            $ul_class_name = $params['ul_class'];
        }

        if (isset($params['ul_class_name'])) {
            $ul_class_name = $params['ul_class_name'];
        }
        if (isset($params['ul_class_name_deep'])) {
            $ul_class_name_deep = $params['ul_class_name_deep'];
        }
        if (isset($params['li_class'])) {
            $li_class_name = $params['li_class'];
        }

        $link = isset($params['link']) ? $params['link'] : false;
        if ($link == false) {
            $link = "<a href='{categories_url}' data-category-id='{id}'  {active_code} class='{active_class} {nest_level}'>{title}</a>";
        }
        $link = str_replace('data-page-id', 'data-category-id', $link);

        $active_ids = isset($params['active_ids']) ? $params['active_ids'] : array($active_cat);
        if (isset($params['active_code'])) {
            $active_code = $params['active_code'];
        } else {
            $active_code = ' active ';
        }


        if (!$tree_data) {
            $data_provider = new CategoryTreeData($this->app);
            $tree_data = $data_provider->get($params);
        }

        if (!$tree_data) {
            return;
        }

        $factory = new MenuFactory();
        $menu = $factory->createItem('Menu');

        $this->__process_nodes($tree_data, $menu);

        $options = array(
            'depth' => null,
            // 'matchingDepth' => null,
            'currentAsLink' => true,
            'currentClass' => 'current',
            'ancestorClass' => 'current_ancestor',
            'firstClass' => 'first',
            'lastClass' => 'last',
            'compressed' => false,
            'allow_safe_labels' => false,
            'branch_class' => null,
            'leaf_class' => null,
            'branch_tag' => $list_tag,
            'leaf_tag' => $list_item_tag,
            //   'linkAttributes' => ['target' => '_blank'],
        );


        $renderer = new ListRenderer(new \Knp\Menu\Matcher\Matcher(), $options);
        print $renderer->render($menu);

    }


    private function __process_nodes($tree_data, $menu)
    {
        array_map(function ($data) use ($tree_data, $menu) {

            $ul = $menu->addChild($data['id'], array('uri' => $this->app->category_manager->link($data['id'])))->setLabel($data['title']);
            if (isset($data['children']) and !empty($data['children'])) {
                $this->__process_nodes($data['children'], $ul);
            }

        }, $tree_data);
    }




}


