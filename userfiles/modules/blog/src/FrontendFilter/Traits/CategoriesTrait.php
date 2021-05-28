<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;
use MicroweberPackages\Category\Models\Category;

trait CategoriesTrait {

    public function applyQueryCategories()
    {
        // Categories
        $category = $this->request->get('category');
        if (!empty($category)) {
            $this->queryParams['category'] = $category;
            $this->query->whereHas('categoryItems', function ($query) use($category) {
                $query->where('parent_id', '=', $category);
            });
        }
    }

    public function categories($template = false)
    {
        $show = get_option('filtering_by_categories', $this->params['moduleId']);
        if (!$show) {
            return false;
        }

        $categoryQuery = Category::query();
        $categoryQuery->where('rel_id', $this->getMainPageId());

        $categories = $categoryQuery->where('parent_id',0)->get();

        return view($template, compact('categories'));
    }

}
