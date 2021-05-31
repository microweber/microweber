<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

trait ActiveFiltersTrait {

    public function activeFilters($template = false) {

        $activeFilters = [];

        $this->filters();

        foreach($this->filters as $filter) {
            foreach($filter->options as $option) {
                if ($option->active) {
                    $urlForRemoving = 'filters['.$filter->nameKey.'][]';
                    $activeFilter = new \stdClass();
                    $activeFilter->name = $filter->name . ': '. $option->value;
                    $activeFilter->link = '';
                    $activeFilter->key = $urlForRemoving;
                    $activeFilter->value = $option->value;
                    $activeFilters[] = $activeFilter;
                }
            }
        }

        $search = $this->request->get('search', false);
        if ($search) {
            $filter = new \stdClass();
            $filter->name = 'Search: '. $search;
            $filter->link = '';
            $filter->key= 'search';
            $activeFilters[] = $filter;
        }

        $limit = $this->request->get('limit', false);
        if ($limit) {
            $filter = new \stdClass();
            $filter->name = 'Limit: '. $limit;
            $filter->link = '';
            $filter->key = 'limit';
            $activeFilters[] = $filter;
        }

        $sort = $this->request->get('sort', false);
        if ($sort) {
            $filter = new \stdClass();
            $filter->name = 'Sort: '. $sort;
            $filter->link = '';
            $filter->key = 'order, sort';
            $activeFilters[] = $filter;
        }

        $minPrice = $this->request->get('min_price', 0.00);
        $maxPrice = $this->request->get('max_price', false);
        if ($maxPrice) {
            $filter = new \stdClass();
            $filter->name = 'Price: '. $minPrice . ' - ' . $maxPrice;
            $filter->link = '';
            $filter->key = 'min_price, max_price';
            $activeFilters[] = $filter;
        }

        if (empty($activeFilters)) {
            return false;
        }

        $moduleId = $this->params['moduleId'];

        return view($template, compact('activeFilters','moduleId'));
    }

}
