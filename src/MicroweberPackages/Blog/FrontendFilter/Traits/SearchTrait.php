<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;

trait SearchTrait {

    public function appendFiltersActiveSearch()
    {
        $search = $this->request->get('search', false);
        if ($search) {
            $filter = new \stdClass();
            $filter->name = _e('Search', true) .': '. $search;
            $filter->link = '';
            $filter->key= 'search';
            $filter->value= $search;
            $this->filtersActive[] = $filter;
        }
    }

    public function applyQuerySearch()
    {
        // Search
        $search = $this->request->get('search');
        if (!empty($search)) {
            $this->query->where('title','LIKE','%'.$search.'%');
        }
    }

    public function search($template = 'blog::partials.search')
    {

        $disableSearch = get_option('disable_search', $this->params['moduleId']);
        if ($disableSearch == '1') {
            return false;
        }

        $fullUrl = URL::current();

        $searchUri = $this->queryParams;
        $searchUri['search'] = '';
        $searchUri = $fullUrl . '?'. http_build_query($searchUri);

        $search = $this->request->get('search', false);

        $moduleId = $this->params['moduleId'];

        return view($template, compact('searchUri', 'search', 'moduleId'));
    }
}
