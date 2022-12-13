<?php

namespace MicroweberPackages\Category;

use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Page\Models\Page;

class AdminJsCategoryTree
{
    public $output = [];
    public $pages;
    public $categories;
    public $filters = [];

    public function filters($filters)
    {
        $this->filters = $filters;
    }

    public function getPagesDatabase()
    {
        $getPagesQuery =  Page::query();

        if (!empty($this->filters)) {
            if (isset($this->filters['from_content_id'])) {
                $pageId = (int) $this->filters['from_content_id'];
                $getPagesQuery->where('id', $pageId);
            }
            if (isset($this->filters['is_shop']) && $this->filters['is_shop']) {
                $getPagesQuery->where('is_shop', 1);
            }
            if (isset($this->filters['is_blog']) && $this->filters['is_blog']) {
                $getPagesQuery->where('is_shop', '=', 0);
                $getPagesQuery->where('content_type','=', 'page');
                $getPagesQuery->where('subtype','=', 'dynamic');
            }
        }

        $getPagesQuery->orderBy('position', 'ASC');

        $getPages = $getPagesQuery->get();

        if ($getPages) {
            $this->pages = $getPages->toArray();
        }
    }

    public function getCategoriesDatabase()
    {
        $getCategoriesQuery =  Category::query();
        $getCategoriesQuery->orderBy('position', 'ASC');

        $getCategories = $getCategoriesQuery->get();

        if ($getCategories) {
            $this->categories = $getCategories->toArray();
        }
    }

    public function get()
    {
        $this->getPagesDatabase();
        $this->getCategoriesDatabase();

        return $this->build();
    }

    public function build() {

        $keyword = false;
        if (!empty($this->filters)) {
            if (isset($this->filters['keyword'])) {
                $keyword = $this->filters['keyword'];
            }
        }

        $response = [];
        if (!empty($this->pages)) {
            foreach ($this->pages as $page) {

                if ($keyword) {
                    if (!str_contains($page['title'], $keyword) !== false) {
                        continue;
                    }
                }

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
               // $response[] = $appendPage;
            }
        }

        if (!empty($this->categories)) {
            foreach ($this->categories as $category) {

                if ($keyword) {
                    if (!str_contains($category['title'], $keyword) !== false) {
                        continue;
                    }
                }

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
                        $appendCategory['main_category'] = true;
                        $appendCategory['parent_type'] = 'page';
                        $appendCategory['parent_id'] = $category['rel_id'];

                        $appendCategory['children'] = $this->getChildren($category['id']);
                    }
                }

                $response[] = $appendCategory;
            }
        }

        return $response;
    }

    public function getChildren($categoryId) {
        $children = [];
        foreach ($this->categories as $category) {
            if ($category['parent_id'] == $categoryId) {
                $newCategory = $category;
                $newCategory['children'] = $this->getChildren($newCategory['id']);
                $children[] = $newCategory;
            }
        }
        return $children;
    }

}
