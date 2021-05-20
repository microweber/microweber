<?php


namespace MicroweberPackages\Blog;

use Illuminate\Support\Facades\URL;

class FrontendFilter
{
    public $queryParams = array();
    protected $pagination;
    protected $query;
    protected $model;

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function pagination($theme = false)
    {
        return $this->pagination->links($theme);
    }

    public function total()
    {
        return $this->pagination->total();
    }

    public function count()
    {
        return $this->pagination->count();
    }

    public function items()
    {
        return $this->pagination->items();
    }

    public function sort()
    {

        if (!isset($this->model->sortable)) {
            return false;
        }

        $options = [];

        $fullUrl = URL::current();

        $directions = [
          'desc'=>'NEWEST',
          'asc'=>'OLDEST',
        ];

        foreach($this->model->sortable as $field) {
            foreach($directions as $direction=>$directionName) {

                $buildLink = $this->queryParams;
                $buildLink['sort'] = $field;
                $buildLink['order'] = $direction;
                $buildLink = http_build_query($buildLink);

                $pageSort = new \stdClass;
                $pageSort->active = 0;
                $pageSort->link = $fullUrl . '?' . $buildLink;
                $pageSort->name = '' . $field .' '. $directionName;

                $options[] = $pageSort;
            }
        }

        return view('blog::sort',compact('options'));
    }

    public function limit()
    {
        $options =[];

        $pageLimits = [
            1,
            2,
            3,
            4,
            5,
        ];

        $fullUrl = URL::current();

        foreach ($pageLimits as $limit) {

            $buildLink = $this->queryParams;
            $buildLink['limit'] = $limit;
            $buildLink = http_build_query($buildLink);

            $isActive = 0;
            if (\Request::get('limit') == $limit) {
                $isActive = 1;
            }

            $pageLimit = new \stdClass;
            $pageLimit->active = $isActive;
            $pageLimit->link = $fullUrl .'?'. $buildLink;
            $pageLimit->name = $limit;

            $options[] = $pageLimit;
        }

        return view('blog::limit',compact('options'));
    }

    public function search($template = false)
    {
        $fullUrl = URL::current();

        $searchUri = $this->queryParams;
        $searchUri['search'] = '';
        $searchUri = $fullUrl . '?'. http_build_query($searchUri);

        $search = \Request::get('search', false);

        return view('blog::search', compact('searchUri', 'search'));
    }

    public function results()
    {
        return $this->pagination->items();
    }

    public function apply()
    {
        $limit = \Request::get('limit', false);
        if ($limit) {
            $this->queryParams['limit'] = $limit;
        }

        $page = \Request::get('page', false);
        if ($page) {
            $this->queryParams['page'] = $page;
        }


        // Search
        $search = \Request::get('search');
        if (!empty($search)) {
            $this->query->where('title','LIKE','%'.$search.'%');
        }

        // Sort & Order
        $sort = \Request::get('sort', false);
        $order = \Request::get('order', false);
        if ($sort && $order) {
            $this->query->orderBy($sort, $order);
        }

        $this->pagination = $this->query->paginate($limit)->withQueryString();

        return $this;
    }
}
