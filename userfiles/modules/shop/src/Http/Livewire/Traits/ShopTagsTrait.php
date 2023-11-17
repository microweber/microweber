<?php
namespace MicroweberPackages\Modules\Shop\Http\Livewire\Traits;

trait ShopTagsTrait {

    public $tags = '';

    public function filterTag($tagSlug)
    {
        if (!empty($this->tags)) {
            $currentTags = explode(',', $this->tags);
        } else {
            $currentTags = [];
        }

        $currentTags[] = $tagSlug;
        $currentTags = array_unique($currentTags);
        $this->tags = implode(',', $currentTags);
    }

    public function getTags()
    {
        if (!empty($this->tags)) {
            $currentTags = explode(',', $this->tags);
        } else {
            $currentTags = [];
        }

        return $currentTags;
    }

    public function filterClearTags()
    {
        $this->tags = '';
    }

    public function filterRemoveTag($tagSlug)
    {
        $tags = $this->getTags();
        $tags = array_diff($tags, [$tagSlug]);
        $this->tags = implode(',', $tags);
    }

}

