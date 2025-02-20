<?php

namespace Modules\Category\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Category\Filament\CategoryModuleSettings;

class CategoryModule extends BaseModule
{
    public static string $name = 'Category';
    public static string $module = 'categories';
    public static string $icon = 'modules.category-icon';
    public static string $categories = 'navigation';
    public static int $position = 1;
    public static string $settingsComponent = CategoryModuleSettings::class;
    public static string $templatesNamespace = 'modules.category::templates';


    public function render()
    {
        $viewData = $this->getViewData();

        // Build category parameters
        $category_params = [];

        // Get content/page ID
        $content_id = get_option('data-content-id', $this->params['id'] ?? $this->params['content_id'] ?? $this->params['content-id'] ?? null);
        if ($content_id) {
            $category_params['content_id'] = $content_id;
        }

        // Get category ID
        $category_id = get_option('data-category-id', $this->params['id'] ?? $this->params['category_id'] ?? $this->params['category-id'] ?? null);
        if ($category_id) {
            $category_params['category_id'] = $category_id;
        }

        // Get max depth
        $max_depth = get_option('data-max-depth', $this->params['id'] ?? $this->params['max_depth'] ?? $this->params['max-depth'] ?? null);
        if ($max_depth) {
            $category_params['max_depth'] = $max_depth;
        }

        // Get other options
        $single_only = get_option('single_only', $this->params['id'] ?? $this->params['single_only'] ?? null);
        if ($single_only) {
            $category_params['single_only'] = $single_only;
        }

        $show_subcats = get_option('show_subcats', $this->params['id'] ?? $this->params['show_subcats'] ?? null);
        if ($show_subcats) {
            $category_params['show_subcats'] = $show_subcats;
        }

        $hide_pages = get_option('hide_pages', $this->params['id'] ?? $this->params['hide_pages'] ?? null);
        if ($hide_pages) {
            $category_params['hide_pages'] = $hide_pages;
        }

        $filter_only_in_stock = get_option('filter_only_in_stock', $this->params['id'] ?? $this->params['filter_only_in_stock'] ?? null);
        if ($filter_only_in_stock) {
            $category_params['filter_only_in_stock'] = $filter_only_in_stock;
        }

        // Get options from module settings
        $moduleId = $this->params['id'] ?? null;
        $options = \MicroweberPackages\Option\Models\Option::where('option_group', $moduleId)->get();

        // Get selected categories and pages
        $selectedCategory = \MicroweberPackages\Option\Models\Option::fetchFromCollection($options, 'fromcategory');
        $selectedPage = \MicroweberPackages\Option\Models\Option::fetchFromCollection($options, 'frompage');
        $showOnlyForParent = \MicroweberPackages\Option\Models\Option::fetchFromCollection($options, 'single-only');
        $showSubcats = \MicroweberPackages\Option\Models\Option::fetchFromCollection($options, 'show-subcats');
        $hidePages = \MicroweberPackages\Option\Models\Option::fetchFromCollection($options, 'hide-pages');
        $filterInStock = isset($this->params['filter-only-in-stock']) 
            ? $this->params['filter-only-in-stock'] 
            : \MicroweberPackages\Option\Models\Option::fetchFromCollection($options, 'filter-only-in-stock') == '1';

        // Initialize categories array
        $categories = [];
        $categoryIds = [];

        // Process selected pages
        if ($selectedPage) {
            $selectedPages = explode(',', $selectedPage);
            foreach ($selectedPages as $pageId) {
                $page = get_content_by_id($pageId);
                if ($page) {
                    $page['is_page'] = true;
                    $page['picture'] = get_picture($page['id'], 'content');
                    $page['url'] = content_link($page['id']);
                    if (!$hidePages) {
                        $categories[] = $page;
                    }
                }

                // Get categories for this page
                if ($selectedCategory) {
                    $selectedCats = explode(',', $selectedCategory);
                    foreach ($selectedCats as $catId) {
                        $categoryPageCheck = get_page_for_category($catId);
                        if (isset($categoryPageCheck['id']) && $categoryPageCheck['id'] == $pageId) {
                            $catData = get_category_by_id($catId);
                            if ($catData && !in_array($catData['id'], $categoryIds)) {
                                $categories[] = $catData;
                                $categoryIds[] = $catData['id'];

                                // Get subcategories if enabled
                                if ($showSubcats && !$showOnlyForParent) {
                                    $subCategories = app()->category_manager->get_children($catData['id']);
                                    if ($subCategories) {
                                        foreach ($subCategories as $subCatId) {
                                            $subCatData = get_category_by_id($subCatId);
                                            if ($subCatData && !in_array($subCatData['id'], $categoryIds)) {
                                                $categories[] = $subCatData;
                                                $categoryIds[] = $subCatData['id'];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // Process selected categories
        if ($selectedCategory) {
            $selectedCatIds = explode(',', $selectedCategory);
            $selectedCategories = \Modules\Category\Models\Category::whereIn('id', $selectedCatIds)
                ->with('children')
                ->get();

            foreach ($selectedCategories as $category) {
                if (!in_array($category->id, $categoryIds)) {
                    $catArray = $category->toArray();
                    $catArray['picture'] = get_picture($category->id, 'category');
                    $catArray['url'] = category_link($category->id);
                    
                    // Get content items count
                    if (isset($category->rel_type) && $category->rel_type == morph_name(\Modules\Content\Models\Content::class)) {
                        $itemsCount = $filterInStock 
                            ? app()->category_repository->getProductsInStockCount($category->id)
                            : app()->category_repository->getItemsCount($category->id);
                        $catArray['content_items_count'] = $itemsCount;
                    }
                    
                    $categories[] = $catArray;
                    $categoryIds[] = $category->id;

                    // Get subcategories if enabled
                    if ($showSubcats && !$showOnlyForParent && $category->children) {
                        foreach ($category->children as $childCategory) {
                            if (!in_array($childCategory->id, $categoryIds)) {
                                $childArray = $childCategory->toArray();
                                $childArray['picture'] = get_picture($childCategory->id, 'category');
                                $childArray['url'] = category_link($childCategory->id);
                                
                                if (isset($childCategory->rel_type) && $childCategory->rel_type == morph_name(\Modules\Content\Models\Content::class)) {
                                    $itemsCount = $filterInStock 
                                        ? app()->category_repository->getProductsInStockCount($childCategory->id)
                                        : app()->category_repository->getItemsCount($childCategory->id);
                                    $childArray['content_items_count'] = $itemsCount;
                                }
                                
                                $categories[] = $childArray;
                                $categoryIds[] = $childCategory->id;
                            }
                        }
                    }
                }
            }
        }

        // Sort categories by position
        usort($categories, function($a, $b) {
            if (isset($a['position']) && isset($b['position'])) {
                return $a['position'] - $b['position'];
            }
            return 0;
        });

        // Get template
        $template = isset($viewData['template']) ? $viewData['template'] : 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        // Pass both category parameters and data to the view
        $viewData['params'] = $category_params;
        $viewData['data'] = $categories;

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
