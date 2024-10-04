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
 * @method void addTags(string|array $tagNames) Add tags to the model
 * @method void tag(string|array $tagNames) Alias for addTags
 * @method array tagNames() Get the tag names related to the current model
 * @method array tagSlugs() Get the tag slugs related to the current model
 * @method void untag(string|array|null $tagNames = null) Remove tags from the model
 * @method void retag(string|array $tagNames) Replace the tags from the model
 * @method static Collection|Tagged[] existingTags() Get all tags in use by this model
 * @method static Collection|Tagged[] existingTagsInGroups(array $groups) Get all tags in use by this model in specific groups
 * /
 *
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
