<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

trait ActiveFiltersTrait {

    public function activeFilters($template = false) {

        $activeFilters = [];

        $this->filters();

        foreach($this->filters as $filter) {
            foreach($filter->options as $option) {
                if ($option->active) {

                    $filter = new \stdClass();
                    $filter->name = 'Filter: '. $option->value;
                    $filter->link = '';
                    $filter->keys[] = 'filters';
                    $activeFilters[] = $filter;
                }
            }
        }

        $search = $this->request->get('search', false);
        if ($search) {
            $filter = new \stdClass();
            $filter->name = 'Search: '. $search;
            $filter->link = '';
            $filter->keys[] = 'search';
            $activeFilters[] = $filter;
        }

        $limit = $this->request->get('limit', false);
        if ($limit) {
            $filter = new \stdClass();
            $filter->name = 'Limit: '. $limit;
            $filter->link = '';
            $filter->keys[] = 'limit';
            $activeFilters[] = $filter;
        }

        $sort = $this->request->get('sort', false);
        if ($sort) {
            $filter = new \stdClass();
            $filter->name = 'Sort: '. $sort;
            $filter->link = '';
            $filter->keys[] = 'order';
            $filter->keys[] = 'sort';
            $activeFilters[] = $filter;
        }

        $minPrice = $this->request->get('min_price', 0.00);
        $maxPrice = $this->request->get('max_price', false);
        if ($maxPrice) {
            $filter = new \stdClass();
            $filter->name = 'Price: '. $minPrice . ' - ' . $maxPrice;
            $filter->link = '';
            $filter->keys[] = 'min_price';
            $filter->keys[] = 'max_price';
            $activeFilters[] = $filter;
        }

        if (empty($activeFilters)) {
            return false;
        }

        $moduleId = $this->params['moduleId'];

        return view($template, compact('activeFilters','moduleId'));
    }

}
