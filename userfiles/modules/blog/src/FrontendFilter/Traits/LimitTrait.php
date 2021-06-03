<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;

trait LimitTrait {

    public function appendFiltersActiveLimit()
    {
        $limit = $this->request->get('limit', false);
        if ($limit) {
            $filter = new \stdClass();
            $filter->name = _e('Limit', true) . ': '. $limit;
            $filter->link = '';
            $filter->key = 'limit';
            $filter->value = $limit;
            $this->filtersActive[] = $filter;
        }
    }

    public function applyQueryLimit()
    {
        $this->queryParams['limit'] = 10;

        $limit = $this->request->get('limit', false);
        if ($limit) {
            $this->queryParams['limit'] = $limit;
        }
    }

    public function limit($template = 'blog::partials.limit')
    {
        $disableLimit = get_option('disable_limit', $this->params['moduleId']);
        if ($disableLimit) {
            return false;
        }

        $options =[];

        $pageLimits = [
            4,
            16,
            22,
            24,
            32,
        ];

        $fullUrl = URL::current();

        foreach ($pageLimits as $limit) {

            $buildLink = $this->queryParams;
            $buildLink['limit'] = $limit;
            $buildLink = http_build_query($buildLink);

            $isActive = 0;
            if ($this->request->get('limit') == $limit) {
                $isActive = 1;
            }

            $pageLimit = new \stdClass;
            $pageLimit->active = $isActive;
            $pageLimit->link = $fullUrl .'?'. $buildLink;
            $pageLimit->name = $limit;
            $pageLimit->value = $limit;

            $options[] = $pageLimit;
        }

        $moduleId = $this->params['moduleId'];

        return view($template, compact('options', 'moduleId'));
    }

}
