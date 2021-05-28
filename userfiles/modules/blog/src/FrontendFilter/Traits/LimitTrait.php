<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;

trait LimitTrait {

    public function limit($template = false)
    {

        $limitTheResults = get_option('limit_the_results', $this->params['moduleId']);
        if (!$limitTheResults) {
            return false;
        }

        $options =[];

        $pageLimits = [
            1,
            2,
            3,
            4,
            5,
        ];

        $fullUrl = URL::current();
        $request = $this->getRequest();

        foreach ($pageLimits as $limit) {

            $buildLink = $this->queryParams;
            $buildLink['limit'] = $limit;
            $buildLink = http_build_query($buildLink);

            $isActive = 0;
            if ($request->get('limit') == $limit) {
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
