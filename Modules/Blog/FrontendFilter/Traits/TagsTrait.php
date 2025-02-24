<?php
namespace Modules\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;
use Modules\Tag\Models\Tag;

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

    public function appendFiltersActiveTags()
    {
        $tags = $this->request->get('tags', false);
        if ($tags && is_array($tags)) {
            foreach ($tags as $tag) {
                $urlForRemoving = 'tags[]';
                $activeFilter = new \stdClass();
                $activeFilter->name = _e('Tag', true) . ': ' . $tag;
                $activeFilter->link = '';
                $activeFilter->key = $urlForRemoving;
                $activeFilter->value = $tag;
                $this->filtersActive[] = $activeFilter;
            }
        }
    }

    public function tags($template = 'blog::partials.tags')
    {
        $show = get_option('filtering_by_tags', $this->params['moduleId']);
        if (!$show) {
            return false;
        }

        $contentFromId = get_option('content_from_id', $this->params['moduleId']);
        if ($contentFromId) {

        }

        $fullUrl = URL::current();
        $category = $this->request->get('category');
        $tagsRequest = $this->request->get('tags', []);

        if (!is_array($tagsRequest)) {
            return false;
        }

        $getTags = Tag::get();

        $tags = [];
        foreach ($getTags as $tag) {

            if (empty($tag)) {
                continue;
            }

            $buildLink = [];
            if (!empty($category)) {
                $buildLink['category'] = $category;
            }
            $buildLink['tags'] = $tag->slug;
            $buildLink = http_build_query($buildLink);

            $active = false;
            if (in_array($tag->slug, $tagsRequest)) {
                $active = true;
            }

            $tag->active = $active;
            $tag->url = $fullUrl .'?'. $buildLink;

            $tags[$tag->slug] = $tag;
        }

        return view($template, compact('tags'));
    }
}
