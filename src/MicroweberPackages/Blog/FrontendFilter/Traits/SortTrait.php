<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;

trait SortTrait {

    public function appendFiltersActiveSort()
    {
        $sort = $this->request->get('sort', false);
        if ($sort) {

            $sortName = $sort;

            if (isset($this->query->getModel()->sortable)) {
                $sortable = $this->query->getModel()->sortable;
                if (isset($sortable[$sort])) {
                    if (isset($sortable[$sort]['title'])) {
                        $sortName = $sortable[$sort]['title'];
                    }
                }
            }

            $order = $this->request->get('order', false);
            if ($order) {
                if ($order == 'desc') {
                    $sortName .= ' New';
                }
                if ($order == 'asc') {
                    $sortName .= ' Old';
                }
            }

            $filter = new \stdClass();
            $filter->name = _e('Sort', true) .': '. $sortName;
            $filter->link = '';
            $filter->value = $sort;
            $filter->key = 'order, sort';
            $this->filtersActive[] = $filter;
        }
    }

    public function applyQuerySort()
    {
        // Sort & Order
        $sort = $this->request->get('sort', false);
        $order = $this->request->get('order', false);

        if ($sort && $order) {

            $this->queryParams['sort'] = $sort;
            $this->queryParams['order'] = $order;

            $this->query->orderBy($sort, $order);
        }
    }

    public function sort($template = 'blog::partials.sort')
    {
        $disableSort = get_option('disable_sort', $this->params['moduleId']);
        if ($disableSort) {
            return false;
        }

        if (!isset($this->model->sortable)) {
            return false;
        }

        $options = [];

        $fullUrl = URL::current();

        $directions = [
            'desc'=>'New',
            'asc'=>'Old',
        ];

        foreach($this->model->sortable as $field=>$fieldSettings) {

            foreach($directions as $direction=>$directionName) {

                $isActive = 0;
                if (($this->request->get('order') == $direction) && $this->request->get('sort') == $field) {
                    $isActive = 1;
                }

                $buildLink = $this->queryParams;
                $buildLink['sort'] = $field;
                $buildLink['order'] = $direction;
                $buildLink = http_build_query($buildLink);

                $pageSort = new \stdClass;
                $pageSort->active = $isActive;
                $pageSort->link = $fullUrl . '?' . $buildLink;
                $pageSort->name = '' . $fieldSettings['title'] .' '. $directionName;
                $pageSort->sort = $field;
                $pageSort->order = $direction;

                $options[] = $pageSort;
            }
        }

        $moduleId = $this->params['moduleId'];

        return view($template,compact('options','moduleId'));
    }
}
