<?php

namespace MicroweberPackages\Page\Http\Livewire\Admin;

use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Http\Livewire\Admin\ProductsList;
use MicroweberPackages\Product\Models\Product;

class PagesList extends ProductsList
{
    public function render()
    {
        return view('page::admin.page.livewire.table', [
            'pages' => $this->pages,
            'appliedFilters' => $this->appliedFilters
        ]);
    }

    public function getPagesProperty()
    {
        return $this->pagesQuery->paginate($this->paginate);
    }

    public function getPagesQueryProperty()
    {
        $query = Page::query();
        $query->disableCache(true);

        $this->appliedFilters = [];

        foreach ($this->filters as $filterKey => $filterValue) {
            $this->appliedFilters[$filterKey] = $filterValue;
        }

        $applyFiltersToQuery = $this->appliedFilters;
        if (!isset($applyFiltersToQuery['orderBy'])) {
            $applyFiltersToQuery['orderBy'] = 'position,desc';
        }
        if (!isset($applyFiltersToQuery['trashed'])) {
            $applyFiltersToQuery['trashed'] = 0;
        }

        $query->filter($applyFiltersToQuery);

        return $query;
    }
}
