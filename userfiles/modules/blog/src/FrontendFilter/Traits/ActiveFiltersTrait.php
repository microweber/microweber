<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

trait ActiveFiltersTrait {

    public function activeFilters($template = 'blog::partials.active_filters') {

        $activeFilters = [];

        $this->filters();

        foreach($this->filters as $filter) {
            foreach($filter->options as $option) {
                if (!isset($option->value) && !empty($option->value)) {
                    continue;
                }
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
            $filter->value= $search;
            $activeFilters[] = $filter;
        }

        $limit = $this->request->get('limit', false);
        if ($limit) {
            $filter = new \stdClass();
            $filter->name = 'Limit: '. $limit;
            $filter->link = '';
            $filter->key = 'limit';
            $filter->value = $limit;
            $activeFilters[] = $filter;
        }

        $sort = $this->request->get('sort', false);
        if ($sort) {
            $filter = new \stdClass();
            $filter->name = 'Sort: '. $sort;
            $filter->link = '';
            $filter->value = $sort;
            $filter->key = 'order, sort';
            $activeFilters[] = $filter;
        }

        $minPrice = $this->request->get('min_price', 0.00);
        $maxPrice = $this->request->get('max_price', false);
        if ($maxPrice) {
            $filter = new \stdClass();
            $filter->name = 'Price: '. $minPrice . ' - ' . $maxPrice;
            $filter->link = '';
            $filter->value = $maxPrice;
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
