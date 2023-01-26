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
        $getPagesQuery = Page::query();
        $getPagesQuery->where('is_active', 1);
        $getPagesQuery->where('is_deleted', 0);

        $getPagesQuery->orderBy('position', 'ASC');

        $getPages = $getPagesQuery->get();

        if ($getPages) {
            $this->pages = $getPages->toArray();
        }
    }

    public function getCategoriesDatabase()
    {
        $getCategoriesQuery = Category::query();
        $getCategoriesQuery->orderBy('position', 'ASC');
        $getCategoriesQuery->where('is_active', 1);
        $getCategoriesQuery->where('is_deleted', 0);

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
        $filterOnlyCategories = false;

        $hideShop = false;
        if (get_option('shop_disabled', 'website') == 'y') {
            $hideShop = true;
        }

        if (!empty($this->filters)) {
            if (isset($this->filters['from_content_id'])) {
                $filterByPageId = (int)$this->filters['from_content_id'];
            }
            if (isset($this->filters['only_categories'])) {
                $filterOnlyCategories = (int)$this->filters['only_categories'];
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

                if ($hideShop) {
                    if ($page['is_shop'] == 1) {
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
                    $getAndAppendPageChildren = $this->getAndAppendPageChildren($page['parent']);
                    if (!empty($getAndAppendPageChildren)) {
                        continue;
                    }
                }

                // Add only main pages
                if ($filterOnlyCategories) {
                    $foundedCategories = $this->getCategoryByRelId($page['id']);
                    if ($filterOnlyCategories && empty($foundedCategories)) {
                        continue;
                    }
                    $this->appendPage($page);
                } else {
                    $this->appendPage($page);
                    $this->getCategoryByRelId($page['id']);
                }

            }
        }
    }

    public function getCategoryByRelId($parentId)
    {
        $foundedCategories = [];
        foreach ($this->categories as $category) {
            if ($category['rel_type'] == 'content' && $category['rel_id'] == $parentId) {
                $this->appendCategory($category);
                $getAndAppendCategoryChildren = $this->getAndAppendCategoryChildren($category['id']);
                $foundedCategories[] = $category;
            }
        }
        return $foundedCategories;
    }

    public function getAndAppendPageChildren($pageId) {

        $children = [];
        foreach ($this->pages as $page) {
            if ($page['parent'] == $pageId) {

                $this->appendPage($page);
                $this->getCategoryByRelId($page['id']);

                $newPage = $page;
                $newPage['children'] = $this->getAndAppendPageChildren($newPage['id']);
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
            $appendPage['icon'] = 'dynamic';
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


        if(isset($category['is_hidden']) and $category['is_hidden'] == 1){
            $appendCategory['is_active'] = 0;
        }
        if(isset($category['is_deleted']) and $category['is_deleted'] == 1){
            $appendCategory['is_deleted'] = 0;
        }


        if ($category['parent_id'] == 0) {
            if ($category['rel_type'] == 'content') {
                $appendCategory['parent_type'] = 'page';
                $appendCategory['parent_id'] = $category['rel_id'];
            }
        }

        $this->output[] = $appendCategory;
    }

    public function getAndAppendCategoryChildren($categoryId) {

        $children = [];
        foreach ($this->categories as $category) {
            if ($category['parent_id'] == $categoryId) {

                $this->appendCategory($category);

                $newCategory = $category;
                $newCategory['children'] = $this->getAndAppendCategoryChildren($newCategory['id']);
                $children[] = $newCategory;
            }
        }
        return $children;
    }

}
