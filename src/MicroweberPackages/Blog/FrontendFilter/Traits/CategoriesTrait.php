<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;
use MicroweberPackages\Category\Models\Category;

trait CategoriesTrait {

    public function appendFiltersActiveCategories()
    {

        $categories = $this->request->get('categories', false);
        if (is_array($categories) && !empty($categories)) {
            foreach($categories as $categoryId) {

                $category = Category::where('id', $categoryId)->first();
                if ($category == null) {
                    continue;
                }

                $filter = new \stdClass();
                $filter->name = _e('Category', true) . ': ' . $category->title;
                $filter->link = '';
                $filter->value = $categoryId;
                $filter->key = 'categories[]';
                $this->filtersActive[] = $filter;
            }
        }

        $categoryId = $this->request->get('category', false);
        if (empty($categoryId)) {
            $categoryId = category_id();
        }

        if ($categoryId) {
            $category = Category::where('id', $categoryId)->first();
            if ($category != null) {
                $filter = new \stdClass();
                $filter->name = _e('Category', true) . ': ' . $category->title;
                $filter->link = '';
                $filter->value = $categoryId;
                $filter->key = 'category';
                $this->filtersActive[] = $filter;
            }
        }
    }

    public function applyQueryCategories()
    {
        // Categories
        $categoryId = $this->request->get('category');
        if (empty($categoryId)) {
            $categoryId = category_id();
        }

        if ($categoryId) {
            $this->queryParams['category'] = $categoryId;
            $this->query->whereHas('categoryItems', function ($query) use($categoryId) {
                $query->where('parent_id', '=', $categoryId);
            });
        }

        $categories = $this->request->get('categories', false);
        if (is_array($categories)) {
            $this->queryParams['categories'] = $categories;
            $this->query->whereHas('categoryItems', function ($query) use($categories) {
                $query->whereIn('parent_id', $categories);
            });
        }
    }

    public function categories($template = 'blog::partials.categories')
    {
        $show = get_option('filtering_by_categories', $this->params['moduleId']);
        if (!$show) {
            return false;
        }

        $categoriesActiveIds = $this->request->get('categories', []);
        if (!is_array($categoriesActiveIds)) {
            $categoriesActiveIds = [];
        }

        $categoryId = $this->request->get('category', false);
        if (empty($categoryId)) {
            $categoryId = category_id();
        }
        if ($categoryId) {
            $categoriesActiveIds[] = $categoryId;
        }

        $categoryQuery = Category::query();
        $categoryQuery->where('rel_id', $this->getMainPageId());
        $categoryQuery->orderBy('position');

        $categories = $categoryQuery->where('parent_id',0)->get();

        $request = $this->request;

        return view($template, compact('categories','categoriesActiveIds','request'));
    }

}
