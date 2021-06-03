<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

trait FiltersActiveTrait {

    public $filtersActive = [];

    public function filtersActive($template = 'blog::partials.filters_active') {
        
        $reflection = new \ReflectionClass(get_class($this));
        $traitMethods = $reflection->getMethods();
        foreach($traitMethods as $method) {
            if (strpos($method->name, 'appendFiltersActive') !== false) {
                $this->{$method->name}();
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
                    $this->filtersActive[] = $activeFilter;
                }
            }
        }

        $moduleId = $this->params['moduleId'];
        $filtersActive = $this->filtersActive;

        if (empty($filtersActive)) {
            return false;
        }

        return view($template, compact('filtersActive','moduleId'));
    }

}
