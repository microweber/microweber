<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

trait FiltersActiveTrait {

    public function filtersActive($template = 'blog::partials.filters_active') {

        $filtersActive = [];

        $tags = $this->request->get('tags', false);
        if ($tags) {
            foreach ($tags as $tag) {
                $urlForRemoving = 'tags[]';
                $activeFilter = new \stdClass();
                $activeFilter->name = _e('Tag', true) . ': ' . $tag;
                $activeFilter->link = '';
                $activeFilter->key = $urlForRemoving;
                $activeFilter->value = $tag;
                $filtersActive[] = $activeFilter;
            }
        }

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
                    $filtersActive[] = $activeFilter;
                }
            }
        }

        $search = $this->request->get('search', false);
        if ($search) {
            $filter = new \stdClass();
            $filter->name = _e('Search', true) .': '. $search;
            $filter->link = '';
            $filter->key= 'search';
            $filter->value= $search;
            $filtersActive[] = $filter;
        }

        $limit = $this->request->get('limit', false);
        if ($limit) {
            $filter = new \stdClass();
            $filter->name = _e('Limit', true) . ': '. $limit;
            $filter->link = '';
            $filter->key = 'limit';
            $filter->value = $limit;
            $filtersActive[] = $filter;
        }

        $sort = $this->request->get('sort', false);
        if ($sort) {
            $filter = new \stdClass();
            $filter->name = _e('Sort', true) .': '. $sort;
            $filter->link = '';
            $filter->value = $sort;
            $filter->key = 'order, sort';
            $filtersActive[] = $filter;
        }

        $minPrice = $this->request->get('min_price', 0.00);
        $maxPrice = $this->request->get('max_price', false);
        if ($maxPrice) {
            $filter = new \stdClass();
            $filter->name = _e('Price', true) .': '. $minPrice . ' - ' . $maxPrice;
            $filter->link = '';
            $filter->value = $maxPrice;
            $filter->key = 'min_price, max_price';
            $filtersActive[] = $filter;
        }

        if (empty($filtersActive)) {
            return false;
        }

        $moduleId = $this->params['moduleId'];

        return view($template, compact('filtersActive','moduleId'));
    }

}
