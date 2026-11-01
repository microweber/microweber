<?php

namespace Modules\Tag\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use Modules\Tag\Database\Factories\TaggedFactory;

/**
 * @package Conner\Tagging\Models
 *
 * @property integer id
 * @property string taggable_id
 * @property string taggable_type
 * @property string tag_name
 * @property string tag_slug
 * @property Tag tag
 */
class Tagged extends \Conner\Tagging\Model\Tagged
{
    use CacheableQueryBuilderTrait;
    use HasFactory;

    protected $fillable = ['tag_name', 'tag_slug', 'taggable_id', 'taggable_type'];

    protected static function boot()
    {
        parent::boot();

        // tag_slug is NOT NULL, but callers (e.g. the Filament TaggedResource
        // form) legitimately supply only tag_name. Derive the slug from the
        // name when it is missing so a tagged row is never saved slug-less.
        static::saving(function (self $tagged) {
            if (empty($tagged->tag_slug) && !empty($tagged->tag_name)) {
                $tagged->tag_slug = \Conner\Tagging\TaggingUtility::slug($tagged->tag_name);
            }
        });
    }

    protected static function newFactory()
    {
        return TaggedFactory::new();
    }
}
