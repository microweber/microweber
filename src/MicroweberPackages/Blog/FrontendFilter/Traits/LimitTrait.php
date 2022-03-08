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
        $limit = 10;
        $itemsPerPage = get_option('items_per_page', $this->params['moduleId']);
        if ($itemsPerPage > 0) {
            $limit = $itemsPerPage;
        }

        $this->queryParams['limit'] = $limit;

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

        $options = [];

        $templateConfig = app()->template->get_config();
        if (isset($templateConfig['template_settings']['items_limit_options']['default'])) {
            $pageLimits = $templateConfig['template_settings']['items_limit_options']['default'];
        } else {
            $pageLimits = [
                6,
                12,
                24,
                48,
            ];
        }

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
