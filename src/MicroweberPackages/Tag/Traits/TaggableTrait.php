<?php

namespace MicroweberPackages\Tag\Traits;

use Conner\Tagging\Taggable as _Taggable;


use Conner\Tagging\Events\TagAdded;
use Conner\Tagging\Events\TagRemoved;
use Conner\Tagging\Model\Tag;
use Conner\Tagging\Model\Tagged;
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

    public static function bootTaggableTrait()
    {

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


}
