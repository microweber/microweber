<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

trait CustomFieldsTrait {

    public function appendFiltersActiveCustomFields()
    {
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
                    $this->filtersActive[] = $activeFilter;
                }
            }
        }
    }

    public function applyQueryCustomFields()
    {
        $filters = $this->request->get('filters');

        if (!empty($filters)) {
            $this->queryParams['filters'] = $filters;
            $this->query->whereCustomField($filters);
        }
    }
}
