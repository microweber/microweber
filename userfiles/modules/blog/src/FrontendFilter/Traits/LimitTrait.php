<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;

trait LimitTrait {

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
        $limitTheResults = get_option('limit_the_results', $this->params['moduleId']);
        if (!$limitTheResults) {
            return false;
        }

        $options =[];

        $pageLimits = [
            5,
            15,
            20,
            35,
            50,
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
