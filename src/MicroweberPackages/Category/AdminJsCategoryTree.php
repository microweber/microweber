<?php

namespace MicroweberPackages\Category;

use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Page\Models\Page;

class AdminJsCategoryTree
{
    public $pages;
    public $categories;

    public function __construct()
    {
        $getPages =  Page::orderBy('position', 'ASC')->get();
        $getCategories =  Category::orderBy('position', 'ASC')->get();
        if ($getPages) {
            $this->pages = $getPages->toArray();
        }
        if ($getCategories) {
            $this->categories = $getCategories->toArray();
        }
    }

    public function get()
    {
        $response = [];
        if (!empty($this->pages)) {
            foreach ($this->pages as $page) {

                $appendPage = [];
                $appendPage['id'] = $page['id'];
                $appendPage['type'] = 'page';
                $appendPage['parent_id'] = $page['parent'];
                $appendPage['parent_type'] = 'page';
                $appendPage['title'] = $page['title'];
                $appendPage['url'] = $page['url'];
                $appendPage['is_active'] = $page['is_active'];
                $appendPage['subtype'] = $page['subtype'];
                $appendPage['position'] = (int) $page['position'];

                $appendPage['icon'] = 'page';
                if ($page['is_shop'] == 1) {
                    $appendPage['icon'] = 'shop';
                }

                $response[] = $appendPage;
            }
        }

        if (!empty($this->categories)) {
            foreach ($this->categories as $category) {

                $appendCategory = [];
                $appendCategory['id'] = $category['id'];
                $appendCategory['type'] = 'category';
                $appendCategory['parent_id'] = $category['parent_id'];
                $appendCategory['parent_type'] = 'category';
                $appendCategory['title'] = $category['title'];
                $appendCategory['subtype'] = 'category';
                $appendCategory['position'] = $category['position'];
                $appendCategory['url'] = $category['url'];
                $appendCategory['is_active'] = 1;

                if ($category['parent_id'] == 0) {
                    if ($category['rel_type'] == 'content') {
                        $appendCategory['parent_type'] = 'page';
                        $appendCategory['parent_id'] = $category['rel_id'];
                    }
                }

                $response[] = $appendCategory;
            }
        }

        return $response;
    }
}
