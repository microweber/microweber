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
        $getPagesQuery = app()->make(Page::class);

     //   $getPagesQuery->withoutMultilanguageRetrieving();
        $getPagesQuery->orderBy('position', 'DESC');


        $getPages = $getPagesQuery->get();

        if ($getPages) {
            $this->pages = $getPages->toArray();
        }
    }

    public function getCategoriesDatabase()
    {
         $getCategoriesQuery = app()->make(Category::class);

    //    $getCategoriesQuery->withoutMultilanguageRetrieving();
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
        $this->buildPages();

        return $this->output;
    }

    public function buildPages() {

        $filterByPageId = false;
        $filterByShop = false;
        $filterByBlog = false;
        $filterByKeyword = false;

        if (!empty($this->filters)) {
            if (isset($this->filters['from_content_id'])) {
                $filterByPageId = (int)$this->filters['from_content_id'];
            }
            if (isset($this->filters['is_shop'])) {
                $filterByShop = true;
            }
            if (isset($this->filters['is_blog'])) {
                $filterByBlog = true;
            }
            if (isset($this->filters['keyword'])) {
                $filterByKeyword = $this->filters['keyword'];
            }
        }

        if (!empty($this->pages)) {
            foreach ($this->pages as $page) {

                if ($filterByKeyword) {
                    if (!str_contains($page['title'], $filterByKeyword) !== false) {
                        continue;
                    }
                }

                if ($filterByBlog) {
                    if ($page['subtype'] != 'dynamic' || $page['is_shop'] == 1) {
                        continue;
                    }
                }

                if ($filterByShop) {
                    if ($page['is_shop'] != 1) {
                        continue;
                    }
                }

                if ($filterByPageId) {
                    if ($page['id'] !== $filterByPageId) {
                        continue;
                    }
                }

                if (isset($page['parent']) && $page['parent'] > 0) {
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
;
        $appendPage['icon'] = 'page';

        if ($page['subtype'] == 'dynamic') {
            $appendPage['icon'] = 'blog';
        }

        if ($page['is_shop'] == 1) {
            $appendPage['icon'] = 'shop';
        }

        if ($page['is_home'] == 1) {
            $appendPage['icon'] = 'home';
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
