<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;

trait TagsTrait {

    public function applyQueryTags($request)
    {
        // Tags
        $this->query->with('tagged');
        $tags = $request->get('tags', false);

        if (!empty($tags)) {
            $this->queryParams['tags'] = $tags;
            $this->query->withAllTags($tags);
        }
    }

    public function tags($template = false)
    {
        $show = get_option('filtering_by_tags', $this->params['moduleId']);
        if (!$show) {
            return false;
        }

        $tags = [];

        $fullUrl = URL::current();
        $request = $this->getRequest();
        $category = $request->get('category');

        foreach ($this->allTagsForResults as $tag) {
            $buildLink = [];
            if (!empty($category)) {
                $buildLink['category'] = $category;
            }
            $buildLink['tags'] = $tag->slug;
            $buildLink = http_build_query($buildLink);

            $tag->url = $fullUrl .'?'. $buildLink;
            $tags[$tag->slug] = $tag;
        }

        return view($template, compact('tags'));
    }
}
