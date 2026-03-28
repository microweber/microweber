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

    protected static function newFactory()
    {
        return TaggedFactory::new();
    }
}
