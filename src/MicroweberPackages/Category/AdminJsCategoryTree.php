<?php

namespace MicroweberPackages\Category;

use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Page\Models\Page;

class AdminJsCategoryTree
{
    public $output = [];
    public $outputPageIds = [];
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

      /*  if (!empty($this->filters)) {
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
        }*/

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

       /* $this->keyword = false;
        if (!empty($this->filters)) {
            if (isset($this->filters['keyword'])) {
                $this->keyword = $this->filters['keyword'];
            }
        }*/

        $this->buildPages();

        return $this->output;
    }

    public function buildPages() {

        if (!empty($this->pages)) {
            foreach ($this->pages as $page) {

              /*  if ($keyword) {
                    if (!str_contains($page['title'], $keyword) !== false) {
                        continue;
                    }
                }*/

                if ($page['parent'] > 0) {
                    $getPageChildren = $this->getPageChildren($page['parent']);
                    if (!empty($getPageChildren)) {
                        continue;
                    }
                }

                // Add only main pages
                $this->appendPage($page);
                $this->getCategoryByRelId($page['id']);

            }
        }
    }

    public function getCategoryByRelId($parentId)
    {
        foreach ($this->categories as $category) {
            if ($category['rel_type'] == 'content' && $category['rel_id'] == $parentId) {
                $this->appendCategory($category);
                $getCategoryChildren = $this->getCategoryChildren($category['id']);
            }
        }
    }

    public function getPageChildren($pageId) {

        $children = [];
        foreach ($this->pages as $page) {
            if ($page['parent'] == $pageId) {

                $this->appendPage($page);
                $this->getCategoryByRelId($page['id']);

                $newPage = $page;
                $newPage['children'] = $this->getPageChildren($newPage['id']);
                $children[] = $newPage;

            }
        }
        return $children;
    }

    public function appendPage($page)
    {
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

            // todo alex
            $appendPage['subtype'] = 'shop';
        }

        $this->output[] = $appendPage;
    }

    public function buildCategories() {

        if (!empty($this->categories)) {
            foreach ($this->categories as $category) {

               /* if ($keyword) {
                    if (!str_contains($category['title'], $keyword) !== false) {
                        continue;
                    }
                }*/

                $children = $this->getChildren($category['id']);
                if (!empty($children)) {
                    $this->appendCategory($category);
                    continue;
                }

                if ($category['parent_id'] == 0) {
                    if ($category['rel_type'] == 'content') {
                        if (!in_array($category['rel_id'], $this->outputPageIds)) {
                            continue;
                        }
                        $this->appendCategory($category);
                        continue;
                    }
                }

            }
        }
    }

    public function appendCategory($category) {

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

        $this->output[] = $appendCategory;
    }

    public function getCategoryChildren($categoryId) {

        $children = [];
        foreach ($this->categories as $category) {
            if ($category['parent_id'] == $categoryId) {

                $this->appendCategory($category);

                $newCategory = $category;
                $newCategory['children'] = $this->getCategoryChildren($newCategory['id']);
                $children[] = $newCategory;
            }
        }
        return $children;
    }

}
