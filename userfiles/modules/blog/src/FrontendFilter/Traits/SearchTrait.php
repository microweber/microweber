<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;

trait SearchTrait {

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
        $fullUrl = URL::current();

        $searchUri = $this->queryParams;
        $searchUri['search'] = '';
        $searchUri = $fullUrl . '?'. http_build_query($searchUri);

        $search = $this->request->get('search', false);

        $moduleId = $this->params['moduleId'];

        return view($template, compact('searchUri', 'search', 'moduleId'));
    }
}
