<?php


namespace MicroweberPackages\Blog;

use Illuminate\Support\Facades\URL;

class FrontendFilter
{
    public $queryParams = array();
    protected $pagination;
    protected $model;

    public function setModel($model)
    {
        $this->model = $model;
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
        return view('blog::sort');
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

        return view('blog::search', compact('searchUri'));
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

        $this->pagination = $this->model->paginate($limit)->withQueryString();

        return $this;
    }
}
