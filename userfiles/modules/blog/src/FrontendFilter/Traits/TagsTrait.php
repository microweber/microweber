<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;

trait TagsTrait {

    public function applyQueryTags()
    {
        // Tags
        $this->query->with('tagged');
        $tags = $this->request->get('tags', false);

        if (!empty($tags)) {
            $this->queryParams['tags'] = $tags;
            $this->query->withAllTags($tags);
        }
    }

    public function tags($template = 'blog::partials.tags')
    {
        $show = get_option('filtering_by_tags', $this->params['moduleId']);
        if (!$show) {
            return false;
        }

        $tags = [];

        $fullUrl = URL::current();
        $category = $this->request->get('category');

        foreach ($this->allTagsForResults as $tag) {
            $buildLink = [];
            if (!empty($category)) {
                $buildLink['category'] = $category;
            }
            $buildLink['tags'] = $tag->slug;
            $buildLink = http_build_query($buildLink);

            $active = false;
            if ($this->request->get('tags', false) == $tag->slug) {
                $active = true;
            }

            $tag->active = $active;

            $tag->url = $fullUrl .'?'. $buildLink;
            $tags[$tag->slug] = $tag;
        }

        return view($template, compact('tags'));
    }
}
