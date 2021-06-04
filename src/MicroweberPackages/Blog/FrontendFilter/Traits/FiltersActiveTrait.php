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

        $moduleId = $this->params['moduleId'];
        $filtersActive = $this->filtersActive;

        if (empty($filtersActive)) {
            return false;
        }

        return view($template, compact('filtersActive','moduleId'));
    }

}
