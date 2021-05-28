<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;

trait SortTrait {

    public function applyQuerySort($request)
    {
        // Sort & Order
        $sort = $request->get('sort', false);
        $order = $request->get('order', false);

        if ($sort && $order) {

            $this->queryParams['sort'] = $sort;
            $this->queryParams['order'] = $order;

            $this->query->orderBy($sort, $order);
        }
    }

    public function sort($template = false)
    {
        $sortTheResults = get_option('sort_the_results', $this->params['moduleId']);
        if (!$sortTheResults) {
            return false;
        }

        if (!isset($this->model->sortable)) {
            return false;
        }

        $options = [];

        $fullUrl = URL::current();
        $request = $this->getRequest();

        $directions = [
            'desc'=>'NEWEST',
            'asc'=>'OLDEST',
        ];

        foreach($this->model->sortable as $field) {
            foreach($directions as $direction=>$directionName) {

                $isActive = 0;
                if (($request->get('order') == $direction) && $request->get('sort') == $field) {
                    $isActive = 1;
                }

                $buildLink = $this->queryParams;
                $buildLink['sort'] = $field;
                $buildLink['order'] = $direction;
                $buildLink = http_build_query($buildLink);

                $pageSort = new \stdClass;
                $pageSort->active = $isActive;
                $pageSort->link = $fullUrl . '?' . $buildLink;
                $pageSort->name = '' . $field .' '. $directionName;
                $pageSort->sort = $field;
                $pageSort->order = $direction;

                $options[] = $pageSort;
            }
        }

        $moduleId = $this->params['moduleId'];

        return view($template,compact('options','moduleId'));
    }
}
