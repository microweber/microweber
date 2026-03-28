<?php
namespace Modules\Tag\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use Modules\Tag\Database\Factories\TagGroupFactory;

/**
 * @package Conner\Tagging\Models
 *
 * @property string id
 * @property string slug
 * @property string name
 * @property-read Collection|Tag[] tags
 */
class TagGroup extends \Conner\Tagging\Model\TagGroup
{
    use CacheableQueryBuilderTrait;
    use HasFactory;

    protected static function newFactory()
    {
        return TagGroupFactory::new();
    }
}
