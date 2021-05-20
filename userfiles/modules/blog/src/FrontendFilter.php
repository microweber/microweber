<?php


namespace MicroweberPackages\Blog;

use Illuminate\Support\Facades\URL;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Page\Models\Page;

class FrontendFilter
{
    public $params = array();
    public $queryParams = array();
    protected $pagination;
    protected $query;
    protected $model;

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function pagination($theme = false)
    {
        //$filteringTheResults = get_option('filtering_the_results', $this->params['moduleId']);

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

        $directions = [
          'desc'=>'NEWEST',
          'asc'=>'OLDEST',
        ];

        foreach($this->model->sortable as $field) {
            foreach($directions as $direction=>$directionName) {

                $isActive = 0;
                if ((\Request::get('order') == $direction) && \Request::get('sort') == $field) {
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

                $options[] = $pageSort;
            }
        }

        return view($template,compact('options'));
    }

    public function categories($template = false)
    {
        $categoryQuery = Category::query();
        $categoryQuery->where('rel_id', $this->getMainPageId());

        $categories = $categoryQuery->where('parent_id',0)->get();

        return view($template, compact('categories'));
    }

    public function tags($template = false)
    {
        $tags = [];

        $fullUrl = URL::current();
        $category = \Request::get('category');

        $query = $this->model::query();
        $query->with('tagged');
        $results = $query->get();
        if (!empty($results)) {
            foreach($results as $result) {
                foreach($result->tags as $tag) {

                    if (!isset($tag->slug)) {
                        continue;
                    }

                    $buildLink = [];
                    if (!empty($category)) {
                        $buildLink['category'] = $category;
                    }
                    $buildLink['tags'] = $tag->slug;
                    $buildLink = http_build_query($buildLink);

                    $tag->url = $fullUrl .'?'. $buildLink;
                    $tags[$tag->slug] = $tag;
                }
            }
        }

        return view($template, compact('tags'));
    }

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

        return view($template, compact('options'));
    }

    public function search($template = false)
    {
        $fullUrl = URL::current();

        $searchUri = $this->queryParams;
        $searchUri['search'] = '';
        $searchUri = $fullUrl . '?'. http_build_query($searchUri);

        $search = \Request::get('search', false);

        return view($template, compact('searchUri', 'search'));
    }

    public function results()
    {
        return $this->pagination->items();
    }

    public function getMainPageId()
    {
        $contentFromId = get_option('content_from_id', $this->params['moduleId']);
        if ($contentFromId) {
            return $contentFromId;
        }

        $findFirtBlog = Page::where('content_type', 'page')
            ->where('subtype','dynamic')
            ->where('is_shop', 0)
            ->first();

        if ($findFirtBlog) {
            return $findFirtBlog->id;
        }
        
        return 0;
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

        $this->query->where('parent', $this->getMainPageId());

        // Search
        $search = \Request::get('search');
        if (!empty($search)) {
            $this->query->where('title','LIKE','%'.$search.'%');
        }

        // Sort & Order
        $sort = \Request::get('sort', false);
        $order = \Request::get('order', false);

        if ($sort && $order) {

            $this->queryParams['sort'] = $sort;
            $this->queryParams['order'] = $order;

            $this->query->orderBy($sort, $order);
        }

        // Tags
        $this->query->with('tagged');
        $tags = \Request::get('tags', false);

        if (!empty($tags)) {
            $this->queryParams['tags'] = $tags;
            $this->query->withAllTags($tags);
        }

        // Categories
        $category = \Request::get('category');
        if (!empty($category)) {
            $this->queryParams['category'] = $category;
            $this->query->whereHas('categoryItems', function ($query) use($category) {
                $query->where('parent_id', '=', $category);
            });
        }

        $this->pagination = $this->query->paginate($limit)->withQueryString();

        return $this;
    }
}
