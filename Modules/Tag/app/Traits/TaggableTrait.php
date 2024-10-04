<?php

namespace Modules\Tag\Traits;

use Conner\Tagging\Model\Tag;
use Conner\Tagging\Model\Tagged;
use Conner\Tagging\Taggable as _Taggable;
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
                }
            }
        });

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
