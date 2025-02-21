@php
    // Get options from module settings
    $moduleId = $params['id'] ?? null;
    $options = \MicroweberPackages\Option\Models\Option::where('option_group', $moduleId)->get();

    // Get selected categories and pages
    $selectedCategory = \MicroweberPackages\Option\Models\Option::fetchFromCollection($options, 'fromcategory');
    $selectedPage = \MicroweberPackages\Option\Models\Option::fetchFromCollection($options, 'frompage');
    $showOnlyForParent = \MicroweberPackages\Option\Models\Option::fetchFromCollection($options, 'single-only');
    $showSubcats = \MicroweberPackages\Option\Models\Option::fetchFromCollection($options, 'show-subcats');
    $hidePages = \MicroweberPackages\Option\Models\Option::fetchFromCollection($options, 'hide-pages');
    $filterInStock = isset($params['filter-only-in-stock']) 
        ? $params['filter-only-in-stock'] 
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

    $data = $categories;
@endphp
