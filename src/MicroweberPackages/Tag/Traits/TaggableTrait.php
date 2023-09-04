<?php

namespace MicroweberPackages\Tag\Traits;

use Conner\Tagging\Taggable as _Taggable;


use Conner\Tagging\Events\TagAdded;
use Conner\Tagging\Events\TagRemoved;
use Conner\Tagging\Model\Tag;
use Conner\Tagging\Model\Tagged;
use Conner\Tagging\TaggingUtility;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @package Conner\Tagging
 * @method static Builder withAllTags(array $tags)
 * @method static Builder withAnyTag(array $tags)
 * @method static Builder withoutTags(array $tags)
 * @property Collection|Tagged[] tagged
 * @property Collection|Tag[] tags
 * @property string[] tag_names
 */
trait TaggableTrait
{
    use _Taggable;


    private $_addTagsToContent = [];
    private $_toSaveTags = false;

    public function initializeTaggableTrait()
    {
      //  $this->appends[] = 'tags';
        $this->fillable[] = 'tag_names';
    }

    /**
     * Delete tags that are not used anymore
     */
    public static function shouldDeleteUnused(): bool
    {
        return true;
    }

    /**
     * Should untag on delete
     */
    public static function untagOnDelete()
    {
        return true;
    }

    public static function bootTaggableTrait()
    {
        static::deleting(function($model) {
            $model->untag();
        });

        static::saving(function ($model) {
            $model->untag();
            // append tags to content
            if (isset($model->tag_names)) {
                $model->_toSaveTags = true;
                $model->_addTagsToContent = $model->tag_names;
                unset($model->tag_names);
            }
        });

        static::saved(function ($model) {
            if ($model->_toSaveTags) {
                if (!empty($model->_addTagsToContent)) {
                    foreach ($model->_addTagsToContent as $tag) {
                        $model->tag($tag); // attach the tag
                    }
                } else {
                    $tags = $model->existingTags();
                    if ($tags) {
                        foreach ($tags as $tag) {
                            $tag->delete();
                        }
                    }
                    $model->untag();
                }
            }
        });

    }


    /**
     * Removes a single tag
     *
     * @param $tagName string
     */
    private function removeSingleTag($tagName)
    {
        $tagName = trim($tagName);
        $tagSlug = TaggingUtility::normalize($tagName);

        if($count = $this->tagged()->where('tag_slug', '=', $tagSlug)->delete()) {
            // TODO
            //TaggingUtility::decrementCount($tagSlug, $count);
        }

        unset($this->relations['tagged']); // clear the "cache"

        event(new TagRemoved($this, $tagSlug));
    }


    /**
     * Return collection of tags related to the tagged model
     * TODO : I'm sure there is a faster way to build this, but
     * If anyone knows how to do that, me love you long time.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Tagged[]
     */
    public function getTagsAttribute()
    {
        return $this->tagged->map(function(Tagged $item){
            if (isset($item->tag_slug)) {
                return $item;
            }
        });
    }
}
