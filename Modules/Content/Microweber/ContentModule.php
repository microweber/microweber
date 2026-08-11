<?php

namespace Modules\Content\Microweber;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
use Modules\Content\Filament\ContentModuleSettings;
use Modules\Content\Models\Content;

class ContentModule extends BaseModule
{
    public static string $name = 'Content';
    public static string $module = 'content';
    public static string $icon = 'modules.content-icon';
    public static string $categories = 'content';
    public static int $position = 30;
    public static string $settingsComponent = ContentModuleSettings::class;
    public static string $templatesNamespace = 'modules.content::templates';

    /**
     * AI-62 / TICKET-JJ (cycle-81 2026-05-08): default per-page when
     * `data-limit` is unset. Skins that render a posts/products list
     * via this module pre-cycle-81 had pagination wired up in the
     * Blade (`@if (isset($pages_count) && $pages_count > 1)`) but
     * the controller never computed `pages_count` — so pagination
     * was always silently off, even with thousands of posts.
     *
     * 6 is the legacy default that's been stamped into Posts skin
     * options forever (skin-3, skin-7, skin-9 sample fixtures).
     * Picked to match user expectations from existing sites.
     */
    public const DEFAULT_ITEMS_PER_PAGE = 6;

    public function render()
    {
        $viewData = $this->getViewData();
        $viewData['data'] = [];

        $options = $viewData['options'] ?? [];
        $moduleId = $this->params['id'] ?? ($options['id'] ?? 'content');

        // AI-62 / TICKET-JJ (cycle-81 2026-05-08): default-on
        // pagination. The page-size limit comes from
        // options['data-limit'] (admin-configurable) and falls back
        // to DEFAULT_ITEMS_PER_PAGE. The current_page is read from
        // the URL via a per-module paging_param (md5 of the module
        // id) so multiple Posts modules on one page paginate
        // independently.
        $pageSize = isset($options['data-limit']) && (int) $options['data-limit'] > 0
            ? (int) $options['data-limit']
            : static::DEFAULT_ITEMS_PER_PAGE;

        // Per-module paging_param keeps URL state isolated when
        // multiple Post/Content modules render on the same page.
        $pagingParam = 'page-' . substr(md5((string) $moduleId), 0, 8);
        $currentPage = max(1, (int) request()->get($pagingParam, 1));

        // Total count is computed via a SEPARATE query so the LIMIT
        // applied below doesn't truncate it. Reuse
        // applyQueryBuilderFiltersFromOptions() to ensure the count
        // query carries the same filters minus the limit.
        $countOptions = $options;
        unset($countOptions['data-limit']);
        $totalCount = static::getQueryBuilderFromOptions($countOptions)->count();
        $pagesCount = (int) ceil($totalCount / max(1, $pageSize));

        $query = static::getQueryBuilderFromOptions($options);
        // Apply pagination offset so page 2+ actually shifts.
        if ($currentPage > 1) {
            $query->offset(($currentPage - 1) * $pageSize)->limit($pageSize);
        }
        $data = $query->get();

        if ($data and $data->count()) {
            $viewData['data'] = $data;
        }

        // AI-62: expose paging variables so skins'
        // `@if (isset($pages_count) && $pages_count > 1)` blocks
        // actually render the pagination control. Hidden when
        // total count fits in one page (pages_count <= 1).
        $viewData['pages_count'] = $pagesCount;
        $viewData['paging_param'] = $pagingParam;
        $viewData['current_page'] = $currentPage;
        $viewData['total_count'] = $totalCount;
        $viewData['page_size'] = $pageSize;

        // Populate schema_org_item_type_tag and other attributes
        $viewData['schema_org_item_type_tag'] = $this->getSchemaOrgItemTypeTag($viewData['options']);
        $viewData['show_fields'] = $this->getShowFields($viewData['options']);
        $viewData['character_limit'] = $this->getCharacterLimit($viewData['options']);
        $viewData['title_character_limit'] = $this->getTitleCharacterLimit($viewData['options']);
      //  $viewData['tn'] = $this->getThumbnailSize($viewData['options']);
        $viewData['tn_size'] = $this->getThumbnailSize($viewData['options']);
        $viewData['read_more_text'] = $this->getReadMoreText($viewData['options']);
        $viewData['add_to_cart_text'] = $this->getAddToCartText($viewData['options']);

        $viewName = $this->getViewName($viewData['template'] ?? 'default');

        return view($viewName, $viewData);
    }


    /**
     * Apply content filtering options to a query builder
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $optionsArray
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function applyQueryBuilderFiltersFromOptions($query, $optionsArray = [])
    {
        if (!empty($optionsArray)) {
            $filterOptions = [];
            
            // Handle page filtering
            if (isset($optionsArray['data-page-id']) && $optionsArray['data-page-id']) {
                $filterOptions['page'] = $optionsArray['data-page-id'];
            }
            
            // Handle category filtering  
            if (isset($optionsArray['data-category-id']) && $optionsArray['data-category-id']) {
                $filterOptions['category'] = $optionsArray['data-category-id'];
            }
            
            // Handle tags filtering
            if (isset($optionsArray['data-tags']) && $optionsArray['data-tags']) {
                $filterOptions['tags'] = $optionsArray['data-tags'];
            }
            
            // Handle ordering
            if (isset($optionsArray['data-order-by']) && $optionsArray['data-order-by']) {
                // Convert format from "column+direction" to "column,direction"
                $orderBy = str_replace('+', ',', $optionsArray['data-order-by']);
                $filterOptions['orderBy'] = $orderBy;
            }
            
            // Handle limit
            if (isset($optionsArray['data-limit']) && $optionsArray['data-limit']) {
                $query->limit(intval($optionsArray['data-limit']));
            }
            
            // Apply filters if any
            if (!empty($filterOptions)) {
                $query = $query->filter($filterOptions);
            }
        }
        
        return $query;
    }

    public static function getQueryBuilderFromOptions($optionsArray = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = Content::query()->where('is_active', 1);
        return static::applyQueryBuilderFiltersFromOptions($query, $optionsArray);
    }


    public function getSchemaOrgItemTypeTag($options)
    {
        $schema_org_item_type = 'Product';
        if (isset($options['content_type']) && $options['content_type'] == 'page') {
            $schema_org_item_type = 'WebPage';
        } elseif (isset($options['content_type']) && $options['content_type'] == 'post') {
            $schema_org_item_type = 'Article';
        }
        return 'http://schema.org/' . ucfirst($schema_org_item_type);
    }

    public function getShowFields($options)
    {
        $show_fields = [];
        if (isset($options['data-show-thumbnail']) && $options['data-show-thumbnail']) {
            $show_fields[] = 'thumbnail';
        }
        if (isset($options['data-show-title']) && $options['data-show-title']) {
            $show_fields[] = 'title';
        }
        if (isset($options['data-show-description']) && $options['data-show-description']) {
            $show_fields[] = 'description';
        }
        if (isset($options['data-show-read-more']) && $options['data-show-read-more']) {
            $show_fields[] = 'read_more';
        }
        if (isset($options['data-show-date']) && $options['data-show-date']) {
            $show_fields[] = 'date';
        }
        if (isset($options['data-show-author']) && $options['data-show-author']) {
            $show_fields[] = 'author';
        }
        return $show_fields;
    }

    public function getCharacterLimit($options)
    {
        return isset($options['data-character-limit']) ? intval($options['data-character-limit']) : 0;
    }

    public function getTitleCharacterLimit($options)
    {
        return isset($options['data-title-limit']) ? intval($options['data-title-limit']) : 0;
    }

    public function getThumbnailSize($options)
    {
        $tn_size = [150];
        if (isset($options['data-thumbnail-size'])) {
            $temp = explode('x', strtolower($options['data-thumbnail-size']));
            if (!empty($temp)) {
                $tn_size = $temp;
            }
        }
        if (!isset($tn_size[0]) || $tn_size[0] == 150) {
            $tn_size[0] = 350;
        }
        if (!isset($tn_size[1])) {
            $tn_size[1] = $tn_size[0];
        }
        return $tn_size;
    }

    public function getReadMoreText($options)
    {
        return isset($options['data-read-more-text']) ? $options['data-read-more-text'] : 'Read More';
    }

    public function getAddToCartText($options)
    {
        return isset($options['data-add-to-cart-text']) ? $options['data-add-to-cart-text'] : 'Add to cart';
    }
}
